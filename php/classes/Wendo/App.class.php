<?php

/**
 * Description of App
 *
 * @author Sammy N Ukavi Jr
 */

namespace Wendo;

use Intervention\Image\ImageManager as ImageManager;
use Zikiza\Sema as Sema;

class App extends Utilities {

    public $event = null;
    public $user;
    private $tempKeys = array();
    public $isAuthenticated = false;

    /**
     * This id is used to save properties as drafts when a user uploads files. 
     * ToDO avoid overwrite of the ID when may users are online adding a property
     */
    public $propertyId;

    public function __construct() {
        parent::__construct();
        $this->event = new events();
        $this->con->rawQuery('SET SESSION group_concat_max_len = 10000000;');
    }

    public function emailExists($email) {
        if (empty($email)) {
            return false;
        }
        $keys = $this->con->rawQuery('SELECT email FROM ' . DB_TABLE_PREFIX . 'users WHERE email=?', array($email));
        if ($this->con->count > 0) {
            return true;
        }
        return false;
    }

    public function saveUserPasswordsData(array $params, $deleteSessionData = true) {
        if ($deleteSessionData) {
            $this->deleteUserSessionPasswordsData($params['uid']);
        }

        if (isset($params['password']) && !empty($params['password'])) {
            $params['password'] = $this->encryptPassword($params['password']);
        }
        $updateColumns = array_keys($params);
        $uid = "uid";
        $this->con->onDuplicate($updateColumns, $uid);
        $this->con->insert('user_passwords', $params);

        return $this->con->getInsertId();
    }

    public function saveUserData(array $params) {
        $updateColumns = array_keys($params);
        $idu = "idu";
        $this->con->onDuplicate($updateColumns, $idu);
        $this->con->insert('users', $params);
        return $this->con->getInsertId();
    }

    public function getUsers(array $columns = array('*')) {
        $this->con->orderBy("fname", "asc");
        $this->con->join("files files", "files.id_file=users.avatar_id", "LEFT");
        return $this->con->get("users users", null, $columns);
    }

    public function getUser($uid, array $columns = array('*')) {
        $this->con->where("idu", $uid);
        $this->con->join("files files", "files.id_file=users.avatar_id", "LEFT");
        return $this->con->getOne("users users", null, $columns);
    }

    public function deleteUser($uid) {
        $this->deleteUserOrdersFromDB($uid);
        $this->removeProfilePhoto($uid);
        $this->deleteUserSessionPasswordsData($uid);
        $this->con->where("idu", $uid);
        return $this->con->delete('users');
    }

    private function deleteUserSessionPasswordsData($uid) {
        $this->con->where("uid", $uid);
        $this->con->delete('app_user_sessions');
        $this->con->where("uid", $uid);
        $this->con->delete('user_sessions');
        $this->con->where("uid", $uid);
        return $this->con->delete('user_passwords');
    }

    public function removeProfilePhoto($account_id, $isUser = true) {
        global $CONFIG, $imageSizes;
        if ($CONFIG['delete_photo_on_region_remove']) {
            $cols = Array('filename', 'file_path', 'id_file');
            if ($isUser) {
                $cols[] = 'avatar_id';
                $this->con->join("files", "avatar_id=id_file", "LEFT");
                $this->con->where("idu", $account_id);
                $fileInfo = $this->con->getOne("users", $cols);
            } else {
                $cols[] = 'logo_id';
                $this->con->join("files", "logo_id=id_file", "LEFT");
                $this->con->where("id_station", $account_id);
                $fileInfo = $this->con->getOne("stations", $cols);
            }

            if ($this->con->count > 0 && !empty($fileInfo['filename'])) {
                foreach ($imageSizes as $imageSize) {
                    $cropped_dest_path = UPLOADS_PATH . DS . $imageSize['width'] . "x" . $imageSize['height'] . DS . $fileInfo['filename'];
                    @unlink($cropped_dest_path);
                }
                @unlink($fileInfo['file_path']);
                $this->con->where("id_file", $fileInfo['id_file']);
                $this->con->delete('files');
            }
        }
        if ($isUser) {
            $this->saveUserData(array('avatar_id' => 0, 'idu' => $account_id));
        } else {
            $this->saveStation(array('logo_id' => 0, 'id_station' => $account_id));
        }
        return true;
    }

    public function saveUserLoginKeys(array $params) {
        $updateColumns = Array('auth_code', 'auth_key', 'expires', 'last_updated');
        $lastInsertId = "id_pass";
        $this->con->onDuplicate($updateColumns, $lastInsertId);
        return $this->con->insert('user_sessions', $params);
    }

    public function saveAppUserLoginKeys(array $params) {
        $updateColumns = Array('auth_code', 'auth_key', 'expires', 'last_updated');
        $lastInsertId = "id_pass";
        $this->con->onDuplicate($updateColumns, $lastInsertId);
        return $this->con->insert('app_user_sessions', $params);
    }

    public function userIsLoggedIn() {
        global $CONFIG;
        $sessionUid = $this->getSessionVariable(SESSION_KEYS_IDU);
        $sessionAuthCode = $this->getSessionVariable(SESSION_KEYS_AUTHCODE);
        $sessionAuthKey = $this->getSessionVariable(SESSION_KEYS_AUTH_KEY);

        if ($CONFIG['enable_cookies'] == 1) {
            $cookieUid = $this->getCookieVariable(SESSION_KEYS_IDU);
            $cookieAuthCode = $this->getCookieVariable(SESSION_KEYS_AUTHCODE);
            $cookieAuthKey = $this->getCookieVariable(SESSION_KEYS_AUTH_KEY);

            if ($cookieUid !== null) {
                $sessionUid = $cookieUid;
            }

            if ($cookieAuthCode !== null) {
                $sessionAuthCode = $cookieAuthCode;
            }

            if ($cookieAuthKey !== null) {
                $sessionAuthKey = $cookieAuthKey;
            }
        } else {
            $this->killCookies(SESSION_KEYS_IDU);
            $this->killCookies(SESSION_KEYS_AUTHCODE);
            $this->killCookies(SESSION_KEYS_AUTH_KEY);
        }

        $keys = $this->con->rawQuery('SELECT uid,auth_code,auth_key FROM ' . DB_TABLE_PREFIX . 'user_sessions USE INDEX(quickLoginIndex) WHERE uid=? AND auth_code=? AND auth_key=? AND expires>=?', array($sessionUid, $sessionAuthCode, $sessionAuthKey, $this->getCurrentDateTime()));

        if ($this->con->count > 0) {
            $cols = array("idu AS uid", "fname", "lname", "email", "username", "pnumber", "group_id", "active", "last_login");
//$this->con->join("files f", "u.avatar_id=f.id_file", "LEFT");
            $this->con->where("idu", $sessionUid);
            $user = $this->con->getOne("users u", $cols);
            if ($this->con->count > 0) {
                if ($user['active'] == 0) {
                    $this->logOut(false);
                    $this->setSessionMessage($this->lang("account_deactivated_error"));
                } else {
                    $this->user = (object) $user;
                    $this->saveUserData(array('idu' => $sessionUid, 'last_login' => $this->getCurrentDateTime()));

                    $this->con->where('id_group', $user['group_id']);
                    $this->con->where('group_is_enabled', 1);
                    $user_group = $this->con->getOne("user_groups");

                    if (!empty($user_group)) {
                        foreach ($user_group as $key => $value) {
                            if ($key !== 'id_group' && $key !== 'group_name') {
                                if (!property_exists(__CLASS__, $key)) {
                                    $this->$key = (intval($value) === 1 ? true : false);
                                }
                            }
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function appUserIsLoggedIn() {
        $username = $this->postValue('username');
        $auth_code = $this->postValue('auth_code');
        $auth_key = $this->postValue('auth_key');

        if (empty($username) || empty($auth_key) || empty($auth_code)) {
            return false;
        }
        $sql = "SELECT  idu,group_id,username,pnumber,email,fname,lname,app_preferred_language,active,last_login AS last_sync FROM " . DB_TABLE_PREFIX . "users  LEFT JOIN " . DB_TABLE_PREFIX . "app_user_sessions on idu=uid WHERE (username = '$username'  OR pnumber = '$username'  OR email = '$username')  AND auth_code='$auth_code' AND auth_key='$auth_key'  LIMIT 1";

        $account = $this->con->rawQuery($sql);

        if (isset($account[0])) {
            $account = $account[0];
        }

        if (!empty($account) && $account['active'] == 1) {
            $this->con->where('id_group', $account['group_id']);
            $user_group = $this->con->getOne('user_groups');
            if (!empty($user_group)) {
                $this->user = (object) $account;
                $app_version_in_use = $this->postValue('app_version');
                if (empty($app_version_in_use)) {
                    $app_version_in_use = "Unknown";
                }
                $app_preferred_language = $this->postValue("app_preferred_language");
                $device_imei = $this->postValue('device_imei');
                $last_known_location = $this->postValue('last_known_location');

                $updateParams = array(
                    'last_login' => $this->getCurrentDateTime(),
                    'app_version_in_use' => $app_version_in_use,
                    'app_preferred_language' => $app_preferred_language,
                    'device_imei' => $device_imei,
                    'last_known_location' => $last_known_location
                );
//$pm4w->Update('users', $updateParams, array('idu' => $account['idu']));
                $this->saveUserData(array('idu' => $account['idu'], 'last_login' => $this->getCurrentDateTime()));
                return true;
            }
        }
        return false;
    }

    public function logOut($destroySession = true) {
        global $CONFIG;
        $sessionUid = $this->getSessionVariable(SESSION_KEYS_IDU);
        $sessionAuthCode = $this->getSessionVariable(SESSION_KEYS_AUTHCODE);
        $sessionAuthKey = $this->getSessionVariable(SESSION_KEYS_AUTH_KEY);

        if ($CONFIG['enable_cookies'] == 1) {
            $cookieUid = $this->getCookieVariable(SESSION_KEYS_IDU);
            $cookieAuthCode = $this->getCookieVariable(SESSION_KEYS_AUTHCODE);
            $cookieAuthKey = $this->getCookieVariable(SESSION_KEYS_AUTH_KEY);

            if ($cookieUid !== null) {
                $sessionUid = $cookieUid;
            }

            if ($cookieAuthCode !== null) {
                $sessionAuthCode = $cookieAuthCode;
            }

            if ($cookieAuthKey !== null) {
                $sessionAuthKey = $cookieAuthKey;
            }
        } else {
            $this->killCookies(SESSION_KEYS_IDU);
            $this->killCookies(SESSION_KEYS_AUTHCODE);
            $this->killCookies(SESSION_KEYS_AUTH_KEY);
        }

        $this->con->rawQuery('DELETE FROM ' . DB_TABLE_PREFIX . 'user_sessions WHERE uid=?', array($sessionUid));

        $this->killCookies(SESSION_KEYS_IDU);
        $this->killCookies(SESSION_KEYS_AUTHCODE);
        $this->killCookies(SESSION_KEYS_AUTH_KEY);

        $this->deleteSessionVariable(SESSION_KEYS_IDU);
        $this->deleteSessionVariable(SESSION_KEYS_AUTHCODE);
        $this->deleteSessionVariable(SESSION_KEYS_AUTH_KEY);
        $this->deleteSessionVariable(SESSION_KEYS_IPINFO);

        if ($destroySession) {
            session_destroy();
        }

        return $this;
    }

    public function getFile($fileId, $cols = array()) {
        if (empty($cols)) {
            $cols = array("*");
        }
        $this->con->where("id_file", $fileId);
        $fileData = $this->con->getOne("files", $cols);
        if ($this->con->count > 0) {
            return $fileData;
        }
        return null;
    }

    public function auth_keysAreTaken($auth_code, $auth_key) {
        $this->con->rawQuery('SELECT auth_code,auth_key FROM ' . DB_TABLE_PREFIX . 'user_sessions USE INDEX(auth_keyauth_code) WHERE auth_code=? AND auth_key=?', array($auth_code, $auth_key));
        if ($this->con->count > 0) {
            return true;
        }
        return false;
    }

    public function getSlug($text, $tablename, $column = 'slug', $idColumnKey = null, $idColumnValue = null) {
        $slug = $this->slugify($text);
        $this->con->where($column, $slug);
        if (!empty($idColumnKey) && !empty($idColumnValue)) {
            $this->con->where($idColumnKey, Array('<>' => $idColumnValue));
        }
        while ($this->con->has($tablename)) {
            $parts = explode('-', $slug);
            $last_count = end($parts);
            if (is_numeric($last_count)) {
                $last_count+=$last_count;
            } else {
                $last_count.='-1';
            }

            $parts[count($parts) - 1] = $last_count;
            $slug = implode('-', $parts);
            $this->con->where($column, $slug);
        }
        return $slug;
    }

    public function saveImageFile($path, $added_by, $owner = 0, $returnName = false, array $params = array()) {
        global $imageSizes;
        if (file_exists($path)) {
            $filename = basename($path);
            $extention = pathinfo($path, PATHINFO_EXTENSION);
            while (file_exists(UPLOADS_PATH . DS . 'defaults' . DS . $filename)) {
                $filename = uniqid("", true) . "-" . time() . "." . $extention;
            }

            if (!is_dir(UPLOADS_PATH . DS . 'defaults')) {
                @mkdir(UPLOADS_PATH . DS . 'defaults', 0777);
            }
            $default_dest_pathname = UPLOADS_PATH . DS . 'defaults' . DS . $filename;

            if (rename($path, $default_dest_pathname)) {
                foreach ($imageSizes as $imageSize) {
                    $width = empty($imageSize['width']) ? '' : $imageSize['width'];
                    $height = empty($imageSize['height']) ? '' : $imageSize['height'];
                    $cropped_dest_path = UPLOADS_PATH . DS . $width . "x" . $height;
                    if (!is_dir($cropped_dest_path)) {
                        @mkdir($cropped_dest_path, 0777);
                    }
                    if (copy($default_dest_pathname, $cropped_dest_path . DS . $filename)) {
                        $manager = new ImageManager();
                        $img = $manager->make($cropped_dest_path . DS . $filename)->resize((empty($imageSize['width']) ? null : $imageSize['width']), (empty($imageSize['height']) ? null : $imageSize['height']), function($constraint) {
                            $constraint->aspectRatio();
//$constraint->upsize();
                        });
                        $img->save($cropped_dest_path . DS . $filename);
                    }
                }
                if (!isset($params['file_name'])) {
                    $params['file_name'] = $filename;
                }
                if (!isset($params['file_mime'])) {
                    $params['file_mime'] = $this->getFileMimeType($default_dest_pathname);
                }
                if (!isset($params['file_size_bytes'])) {
                    $params['file_size_bytes'] = filesize($default_dest_pathname);
                }
                if (!isset($params['file_path'])) {
                    $params['file_path'] = $default_dest_pathname;
                }
                if (!isset($params['date_added'])) {
                    $params['date_added'] = $this->getCurrentDateTime();
                }
                if (!isset($params['privacy'])) {
                    $params['privacy'] = FILE_PRIVACY_PUBLIC;
                }
                if (!isset($params['owner'])) {
//$params['owner'] = 'property';
                }
                if (!isset($params['owner'])) {
                    $params['owner'] = $owner;
                }
                if (!isset($params['added_by'])) {
                    $params['added_by'] = $added_by;
                }

                $updateColumns = array_keys($params);
                $id_file = "id_file";
                $this->con->onDuplicate($updateColumns, $id_file);
                $fileId = $this->con->insert('files', $params);
                if ($returnName) {
                    return array(
                        'id' => $fileId,
                        'name' => $filename
                    );
                }
                return $fileId;
            }
        }
        return false;
    }

    public function saveFile($path, $added_by, $owner = 0, $returnName = false, array $params = array()) {
        if (file_exists($path)) {
            $filename = basename($path);
            $extention = pathinfo($path, PATHINFO_EXTENSION);
            while (file_exists(UPLOADS_PATH . DS . 'defaults' . DS . $filename)) {
                $filename = uniqid("", true) . "-" . time() . "." . $extention;
            }

            if (!is_dir(UPLOADS_PATH . DS . 'defaults')) {
                @mkdir(UPLOADS_PATH . DS . 'defaults', 0777);
            }
            $default_dest_pathname = UPLOADS_PATH . DS . 'defaults' . DS . $filename;
            if (rename($path, $default_dest_pathname)) {
                if (!isset($params['file_name'])) {
                    $params['file_name'] = $filename;
                }
                if (!isset($params['file_mime'])) {
                    $params['file_mime'] = $this->getFileMimeType($default_dest_pathname);
                }
                if (!isset($params['file_size_bytes'])) {
                    $params['file_size_bytes'] = filesize($default_dest_pathname);
                }
                if (!isset($params['file_path'])) {
                    $params['file_path'] = $default_dest_pathname;
                }
                if (!isset($params['date_added'])) {
                    $params['date_added'] = $this->getCurrentDateTime();
                }
                if (!isset($params['privacy'])) {
                    $params['privacy'] = FILE_PRIVACY_PUBLIC;
                }
                if (!isset($params['owner'])) {
//$params['owner'] = 'property';
                }
                if (!isset($params['owner'])) {
                    $params['owner'] = $owner;
                }
                if (!isset($params['added_by'])) {
                    $params['added_by'] = $added_by;
                }

                $updateColumns = array_keys($params);
                $id_file = "id_file";
                $this->con->onDuplicate($updateColumns, $id_file);
                $fileId = $this->con->insert('files', $params);
                if ($returnName) {
                    return array(
                        'id' => $fileId,
                        'name' => $filename
                    );
                }
                return $fileId;
            }
        }
        return false;
    }

    public function LogEevent($uid, $event, $event_time, $event_description = "", $affected_object_id = "", $system_used = "web_app") {
        $params = array(
            'uid' => $uid,
            'event' => $event,
            'event_time' => $event_time,
            'affected_object_id' => $affected_object_id,
            'event_description' => $event_description,
            'system_used' => $system_used
        );

        return $this->con->insert("event_logs", $params);
    }

    /*public function LogAppEevent($id_client_event, $uid, $event, $event_time, $event_description = "", $affected_object_id = "", $system_used = "web_app") {
        $params = array(
            'id_client_event' => $id_client_event,
            'uid' => $uid,
            'event' => $event,
            'event_time' => $event_time,
            'affected_object_id' => $affected_object_id,
            'event_description' => $event_description,
            'system_used' => $system_used
        );

        return $this->con->insert("event_logs", $params);
    }*/

    public function dbDateIsNewer($table, $ColumnToCheck, $valueToCheck, array $primary_key_column) {
        $row_cnt = 0;
        $lastIndexOfArray = (count($primary_key_column) - 1);
        $counter = 0;
        $sql = "SELECT * FROM " . DB_TABLE_PREFIX . "$table WHERE $ColumnToCheck >= '" . $valueToCheck . "' ";

        if (count($primary_key_column) > 0) {
            $sql .= " AND ";
        }

        foreach ($primary_key_column as $arrayKey => $value) {
            if ($counter != $lastIndexOfArray) {
                $sql.= $this->con->mysqli()->escape_string($arrayKey) . "='" . $this->con->mysqli()->escape_string($value) . "' AND ";
            } else {
                $sql.= $this->con->mysqli()->escape_string($arrayKey) . "='" . $this->con->mysqli()->escape_string($value) . "' LIMIT 1";
            }
            $counter++;
        }

        if ($result = $this->con->query($sql)) {
            $row_cnt = $result->num_rows;
            $result->free();
        } else {
            logError("Error in Function", "An error occured when running the query <br/> <br/> <strong>" . $sql . "</strong><br/><br/>This occured in the class " . __CLASS__ . ' in the function ' . __FUNCTION__ . '<br/>
          The error is as shown below. <br/> <br/> <div style="background:#FFFF00; color:#FF0000;padding:20px;">' . $this->con->error . '</div><br/> <br/>');
            return false;
        }
        return $row_cnt > 0 ? true : false;
    }

    public function RunSMSCron() {
        $SYSTEM_CONFIG = $this->FetchSiteSettings();
        $enable_acountablility_sms = intval(getArrayVal($SYSTEM_CONFIG, 'enable_acountablility_sms'));
        $acountablility_cycle = intval(getArrayVal($SYSTEM_CONFIG, 'acountablility_cycle'));
        $batch_schedule_date = strtotime(getArrayVal($SYSTEM_CONFIG, 'batch_schedule_date'));
        $last_day_acountability_sms_was_sent = $SYSTEM_CONFIG['last_day_acountability_sms_was_sent'];
        $next_acountability_date_sent = strtotime($last_day_acountability_sms_was_sent . " + $acountablility_cycle days");
        if (defined("ENABLE_SMS") && ENABLE_SMS == 1) {
            $hasErrors = false;

            if ($enable_acountablility_sms == 1 && $batch_schedule_date <= strtotime(getCurrentDate()) && $batch_schedule_date > $next_acountability_date_sent || $enable_acountablility_sms == 1 && $next_acountability_date_sent < strtotime(getCurrentDate())) {
                $water_sources = array();
                $funds_accountability_sms_template = getArrayVal($SYSTEM_CONFIG, 'funds_accountability_sms_template');
                $query = "SELECT * FROM " . DB_TABLE_PREFIX . "water_sources";
                $result = $this->RunQueryForResults($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $water_sources[] = $row;
                    }
                }

                foreach ($water_sources as $water_source) {
//var_dump($water_source);
                    $uids = $pnumbers = $params = array();
                    $sql = "SELECT " . DB_TABLE_PREFIX . "water_source_caretakers.uid FROM " . DB_TABLE_PREFIX . "water_source_caretakers WHERE water_source_id=" . $water_source['id_water_source'];
                    $result2 = $this->RunQueryForResults($sql);
                    while ($row2 = $result2->fetch_assoc()) {
                        $uids[] = $row2['uid'];
                    }

                    $sql = "SELECT " . DB_TABLE_PREFIX . "water_source_treasurers.uid FROM " . DB_TABLE_PREFIX . "water_source_treasurers WHERE water_source_id=" . $water_source['id_water_source'];
                    $result2 = $this->RunQueryForResults($sql);
                    while ($row2 = $result2->fetch_assoc()) {
                        $uids[] = $row2['uid'];
                    }
                    $uids = array_unique($uids);
                    if (!empty($uids)) {
                        $sql = "SELECT idu,pnumber FROM " . DB_TABLE_PREFIX . "users WHERE pnumber<> '' AND idu IN(" . implode(",", $uids) . ")";
                        $results2 = $this->RunQueryForResults($sql);
                        while ($row2 = $results2->fetch_assoc()) {
                            $pnumbers[] = array(
                                $row2['idu'],
                                0,
                                $row2['pnumber']
                            );
                        }
                    }

                    $sql = "SELECT id_user,pnumber FROM " . DB_TABLE_PREFIX . "water_users WHERE pnumber<> '' AND marked_for_delete=0 AND water_source_id=" . $water_source['id_water_source'];
                    $results2 = $this->RunQueryForResults($sql);
                    while ($row2 = $results2->fetch_assoc()) {
                        $pnumbers[] = array(
                            0,
                            $row2['id_user'],
                            $row2['pnumber']
                        );
                    }

// $total_sales = $transactions = $total_savings = $total_expenses = 0.0;


                    $TEMPLATE_PARAMS = array(
                        'system_name' => $SYSTEM_CONFIG['system_name'],
                        'site_url' => SITE_URL,
                        'water_source_name' => $water_source['water_source_name'],
                        'water_source_location' => $water_source['water_source_location'],
                        'monthly_charges' => $water_source['monthly_charges'],
                        'percentage_saved' => $water_source['percentage_saved'],
                        'total_sales' => number_format(calculateWaterSourceTotalSales($water_source['id_water_source']), 2, ".", ','),
                        'transactions' => number_format(calculateWaterSourceTotalTransactions($water_source['id_water_source']), 2, ".", ','),
                        'total_expenditures' => number_format(calculateWaterSourceTotalExpenditures($water_source['id_water_source']), 2, ".", ','),
                        'total_savings' => number_format(calculateWaterSourceTotalSavings($water_source['id_water_source']), 2, ".", ','),
                        'acountablility_cycle' => $acountablility_cycle
                    );

                    $params['message_content'] = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($TEMPLATE_PARAMS) {
                        return (isset($TEMPLATE_PARAMS[$matches[1]]) ? $TEMPLATE_PARAMS[$matches[1]] : "");
                    }, $funds_accountability_sms_template);

                    $params['type'] = MESSAGE_TYPE_TAG_SMS;
                    $params['created_by'] = 0;
                    $params['can_be_sent'] = 1;
                    $params['scheduled_send_date'] = $_POST['scheduledDate'] = getCurrentDate();
                    $params['last_updated'] = getCurrentDate();

                    $id_msg = $this->con->insert('sms_messages', $params);
                    if (is_int($id_msg)) {
                        $params = array();
                        foreach ($pnumbers as $pnumber) {
                            $params[] = array(
                                'msg_id' => $id_msg,
                                'pnumber' => $pnumber[2],
                                'sender' => MESSAGE_TAG_SYSTEM,
                                'idu' => $pnumber[0],
                                'id_user' => $pnumber[1]
                            );
                        }
                        $this->MultiInsert("sms_messages_recipients", $params);
                    } else {
                        logError("Error executing cron job", "An error occured queueing the SMS message for sending.");
                    }
                }
            }
            if (!$hasErrors) {
                $this->Update('settings', array('last_day_acountability_sms_was_sent' => getCurrentDate()), array('id_system' => 1));
            }
        }
    }

    /**
     * Function to build SQL /Importing SQL DATA
     *
     * @param string $args as the queries of sql data , yopu could use file get contents to read data args
     *
     * @return string complete if complete
     */
    public function importDb($args) {
// check mysqli extension installed
        if (!function_exists('mysqli_connect')) {
            die(' This scripts need mysql extension to be running properly ! please resolve!!');
        }

//$this = @new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);        

        $querycount = 11;
        $queryerrors = '';
        $lines = (array) $args;
        if (is_string($args)) {
            $lines = array($args);
        }

        if (!$lines) {
            return '' . 'cannot execute ' . $args;
        }

        $scriptfile = false;
        foreach ($lines as $line) {
            $line = trim($line);
// if have -- comments add enters
            if (substr($line, 0, 2) == '--') {
                $line = "\n" . $line;
            }
            if (substr($line, 0, 2) != '--') {
                $scriptfile .= ' ' . $line;
                continue;
            }
        }

        $queries = explode(';', $scriptfile);
        foreach ($queries as $query) {
            $query = trim($query);
            ++$querycount;

            if ($query == '') {
                continue;
            }

//var_dump($query);

            $sqlComments = '@(([\'"]).*?[^\\\]\2)|((?:\#|--).*?$|/\*(?:[^/*]|/(?!\*)|\*(?!/)|(?R))*\*\/)\s*|(?<=;)\s+@ms';
            /* Commented version
              $sqlComments = '@
              (([\'"]).*?[^\\\]\2) # $1 : Skip single & double quoted expressions
              |(                   # $3 : Match comments
              (?:\#|--).*?$    # - Single line comments
              |                # - Multi line (nested) comments
              /\*             #   . comment open marker
              (?: [^/*]    #   . non comment-marker characters
              |/(?!\*) #   . ! not a comment open
              |\*(?!/) #   . ! not a comment close
              |(?R)    #   . recursive case
              )*           #   . repeat eventually
              \*\/             #   . comment close marker
              )\s*                 # Trim after comments
              |(?<=;)\s+           # Trim after semi-colon
              @msx';
             */
            $query = trim(preg_replace($sqlComments, '$1', $query));
            preg_match_all($sqlComments, $query, $comments);
            $extractedComments = array_filter($comments[3]);
            if (!empty($query)) {
                if (!$this->con->query($query)) {
                    $queryerrors .= '' . 'Line ' . $querycount . ' - ' . $this->con->error . '<br>';
                    continue;
                }
            }
        }

        if ($queryerrors) {
//  return '' . 'There was an error on File: ' . $filename . '<br>' . $queryerrors;
        }

        if ($this && !$this->con->error) {
            @$this->close();
        }
        return 'complete dumping database !';
    }

    /**
     * MYSQL EXPORT TO GZIP 
     * exporting database to sql gzip compression data.
     * if directory writable will be make directory inside of directory if not exist, else wil be die
     *
     * @param string directory , as the directory to put file
     * @param $outname as file name just the name !, if file exist will be overide as numeric next ++ as name_1.sql , name_2.sql next ++
     *
     * @param string $dbhost database host
     * @param string $dbuser database user
     * @param string $dbpass database password
     * @param string $dbname database name
     *
     */
    function exportDb($outname = "") {

        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 0); //Forever

        if (empty($outname)) {
            $outname = $this->slugify($this->mainSiteURL() . "-" . $this->getCurrentDateTime());
        } else {
            $this->slugify($outname);
        }
        $tables = array();
        $show = $this->con->query("SHOW TABLES");

        foreach ($show as $key => $value) {
            foreach ($value as $column => $column_name) {
                $tables[] = $column_name;
            }
        }

        /**
         * Ucomment the array below to test with just one table
         */
        /* $tables = array(
          'beta_users'
          ); */

        $query = '';

        foreach ($tables as $table) {
            $data = $this->con->rawQuery('SHOW CREATE TABLE ' . $table);
//$data = $results->fetch_array();

            $query .= "-- --------------------------------------------------------
-- Table structure for table : `$table`
-- ---------------------------------------------------------\n";

            $query.= str_replace("CREATE TABLE", "CREATE TABLE IF NOT EXISTS", $data[0]["Create Table"]) . ";\n\n";
            $rows = $this->con->rawQuery('SELECT * FROM ' . $table);
            $total_rows = $this->con->count;
            $max_rows = 300;
            $row_count = $position = 1;
            $con = $this->con;
            $query .= "
-- ---------------------------------------------------------
-- Dumping data for table `$table`
-- ---------------------------------------------------------\n\n";

            foreach ($rows as $row) {

                if ($row_count == 1) {
                    $query.=" INSERT IGNORE INTO `$table` (`" . implode("`,`", array_keys($row)) . "`) VALUES ";
                }
//$query.="('" . implode("','", array_values($row)) . "')";
                $query.="('" . implode("','", array_values(array_map(function($value) use ($con) {
                                            return $con->mysqli()->real_escape_string($value);
                                        }, $row))) . "')";
                if ($row_count == $max_rows || $position == $total_rows) {
                    $query.=";\n";
                } else {
                    $query.=",";
                }
                if ($row_count == $max_rows) {
                    $row_count = 1;
                } else {
                    $row_count++;
                }
                $position++;
            }
        }


        $query = "-- ---------------------------------------------------------
--
-- " . $this->getSiteName() . " database dump
-- sammyukavi@gmail.com
--
-- Host Connection Info: " . $this->con->mysqli()->host_info . "
-- Generation Time: " . date('F d, Y \a\t H:i A ( e )') . "
-- Server version: " . $this->con->mysqli()->server_info . "
-- PHP Version: " . PHP_VERSION . "
--
-- ---------------------------------------------------------\n\n

SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
" . $query . "
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
";

# end values result
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0');
        header('Pragma: no-cache');
        header('Connection: Keep-Alive');
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Content-Length: ' . mb_strlen($query)); #
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $outname . '.sql' . '"');
        header('Content-type: text/plain');
        echo $query;
        exit();
        if ($this->con && !$this->con->error) {
            @$this->con->close();
        }
    }

    public function deleteFromDb($table_name, $columns) {
        foreach ($columns as $column => $value) {
            $this->con->where($column, $value);
        }
        $this->con->delete($table_name);
        return true;
    }

    public function encryptPassword($password) {
        return sha1($password);
    }

    public function sendSMS($semi_collon_separated_recepients, $message) {
        global $CONFIG, $PUBLIC_VARS;
        if ($CONFIG['enable_sms'] != 1) {
            return true;
        }
        $this->emailTemplateParams['site_url'] = $this->siteURL();
        $this->emailTemplateParams['main_site_url'] = $this->mainSiteURL();
        $this->emailTemplateParams = array_merge($this->emailTemplateParams, $PUBLIC_VARS);
        $pass_this = $this;
        $message = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use ($pass_this) {
            return (isset($pass_this->emailTemplateParams[$matches[1]]) ? $pass_this->emailTemplateParams[$matches[1]] : "");
        }, $message);
//Sema::sendSMS($semi_collon_separated_recepients, $message);
        return true;
    }

    public function checkIFExists($table_name, $columns) {
        foreach ($columns as $column => $value) {
            $this->con->where($column, $value);
        }
        $this->con->getOne($table_name);
        return $this->con->count > 0 ? true : false;
    }

    public function saveWaterUser(array $params) {
        $updateColumns = array_keys($params);
        $id_user = "id_user";
        $this->con->onDuplicate($updateColumns, $id_user);
        $this->con->insert('water_users', $params);
        return $this->con->getInsertId();
    }

    public function saveWaterSale(array $params) {
        $updateColumns = array_keys($params);
        $id_sale = "id_sale";
        $this->con->onDuplicate($updateColumns, $id_sale);
        $this->con->insert('sales', $params);
        return $this->con->getInsertId();
    }

    public function calculateWaterSourceTotalWaterUsers($water_source_id) {
        $water_users = 0;
        $query = "SELECT COUNT(id_user) water_users FROM " . DB_TABLE_PREFIX . "water_users WHERE water_source_id=$water_source_id";
        $results = $this->con->rawQuery($query);
        if ($this->con->count > 0) {
            if (!empty($results[0]['water_users'])) {
                $water_users = $results[0]['water_users'];
            }
        }
        return $water_users;
    }

    public function calculateWaterSourceTotalTransactions($water_source_id) {
        $transactions = 0;
        $query = "SELECT COUNT(id_sale) AS transactions FROM " . DB_TABLE_PREFIX . "sales WHERE " . DB_TABLE_PREFIX . "sales.water_source_id=$water_source_id ";
        $results = $this->con->rawQuery($query);
        if ($this->con->count > 0) {
            if (!empty($results[0]['transactions'])) {
                $transactions = $results[0]['transactions'];
            }
        }
        return $transactions;
    }

    public function calculateWaterSourceTotalApprovedTransactions($water_source_id) {
        $transactions = 0;
        $query = "SELECT COUNT(id_sale) AS transactions FROM " . DB_TABLE_PREFIX . "sales WHERE " . DB_TABLE_PREFIX . "sales.water_source_id=$water_source_id AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
        $results = $this->con->rawQuery($query);
        if ($this->con->count > 0) {
            if (!empty($results[0]['transactions'])) {
                $transactions = $results[0]['transactions'];
            }
        }
        return $transactions;
    }

    public function calculateWaterSourceTotalSales($src_id) {
        $total_sales = 0;
        $query = "SELECT SUM(sale_ugx) total_sales FROM " . DB_TABLE_PREFIX . "sales WHERE " . DB_TABLE_PREFIX . "sales.water_source_id=$src_id AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
        $result = $this->RunQueryForResults($query);
        while ($sale = $result->fetch_assoc()) {
            $total_sales = $sale['total_sales'];
        }
        return $total_sales;
    }

    public function calculateWaterSourceTotalExpenditures($src_id) {
        $total_expenses = 0.0;
        $query = "SELECT SUM(expenditure_cost) AS total_expenses FROM " . DB_TABLE_PREFIX . "expenditures WHERE water_source_id=$src_id";
        $result = $this->RunQueryForResults($query);
        while ($sale = $result->fetch_assoc()) {
            if (!empty($sale['total_expenses'])) {
                $total_expenses = $sale['total_expenses'];
            }
        }
        return $total_expenses;
    }

    public function calculateWaterSourceTotalSavings($src_id) {
        $query = "SELECT CASE WHEN " . DB_TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . DB_TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS savings FROM " . DB_TABLE_PREFIX . "sales WHERE " . DB_TABLE_PREFIX . "sales.water_source_id=$src_id AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
        $squery = "SELECT SUM(savings) AS savings FROM ($query) AS derived";
        $result = $this->con->rawQuery($squery);
        $total_savings = $result[0]['savings'];
        $query = "SELECT SUM(expenditure_cost) AS total_expenses FROM " . DB_TABLE_PREFIX . "expenditures WHERE marked_for_delete=0 AND water_source_id=$src_id";
        $result = $this->con->rawQuery($query);
        $total_expenses = $result[0]['total_expenses'];
        return $total_savings - $total_expenses;
    }

    public function saveExpenditure(array $params) {
        $updateColumns = array_keys($params);
        $id_expenditure = "id_expenditure";
        $this->con->onDuplicate($updateColumns, $id_expenditure);
        $this->con->insert('expenditures', $params);
        return $this->con->getInsertId();
    }

    public function saveWaterSource(array $params) {
        $updateColumns = array_keys($params);
        $id_water_source = "id_water_source";
        $this->con->onDuplicate($updateColumns, $id_water_source);
        $this->con->insert('water_sources', $params);
        return $this->con->getInsertId();
    }

    public function saveWaterSourceCaretaker(array $params) {
        $updateColumns = array_keys($params);
        $id_attendant = "id_attendant";
        $this->con->onDuplicate($updateColumns, $id_attendant);
        $this->con->insert('water_source_caretakers', $params);
        return $this->con->getInsertId();
    }

    public function saveWaterSourceTreasurer(array $params) {
        $updateColumns = array_keys($params);
        $id_treasurer = "id_treasurer";
        $this->con->onDuplicate($updateColumns, $id_treasurer);
        $this->con->insert('water_source_treasurers', $params);
        return $this->con->getInsertId();
    }

    public function saveWaterRepairTypes(array $params) {
        $updateColumns = array_keys($params);
        $id_repair_type = "id_repair_type";
        $this->con->onDuplicate($updateColumns, $id_repair_type);
        $this->con->insert('repair_types', $params);
        return $this->con->getInsertId();
    }

    public function saveUserGroup(array $params) {
        $updateColumns = array_keys($params);
        $id_group = "id_group";
        $this->con->onDuplicate($updateColumns, $id_group);
        $this->con->insert('user_groups', $params);
        return $this->con->getInsertId();
    }

    public function saveSettings(array $params) {
        $params['id_system'] = 1;
        $updateColumns = array_keys($params);
        $id_system = "id_system";
        $this->con->onDuplicate($updateColumns, $id_system);
        $this->con->insert('settings', $params);
        return $this->con->getInsertId();
    }

    public function postValue($key, $trim = true) {
        if ($trim) {
            $var = parent::postValue($key);
            if (is_array($var)) {
                return $var;
            }
            return trim(parent::postValue($key));
        }
        return parent::postValue($key);
    }

    function file_is_apk($path_to_file) {
        $allowed = array(
            'application/octet-stream',
            'application/java-archive',
            'application/vnd.android.package-archive',
            'application/zip'
        );
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $path_to_file);
//var_dump($type);
        finfo_close($finfo);

        if (in_array($type, $allowed)) {
            return true;
        } else {
            return false;
        }
    }

    public function saveBuild(array $params) {
        $updateColumns = array_keys($params);
        $id_build = "id_build";
        $this->con->onDuplicate($updateColumns, $id_build);
        $this->con->insert('app_builds', $params);
        return $this->con->getInsertId();
    }

    function sortArray($array, $on, $order = SORT_ASC) {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        return $new_array;
    }

}
