<?php

define('API_VER', '1.2');
$request_status = ERROR_STATUS_CODE; // '0' for failed '1' for successful '2' for pending 
$server_info = array(
    'system_name' => $App->getSiteName(),
    'server_time' => $App->getCurrentDateTime(), //date("Y-m-d H:i:s", strtotime("-2 hour", strtotime($App->getCurrentDateTime()))),
    'api_ver' => API_VER,
    'server_status' => $CONFIG['system_status']// '0' for off '1' for on '2' for upgrades
);

$action = $App->getValue('a');

/*$App->postValues = array(
    'username' => 'rbnct5',
    'password' => 'rudaka',
        //'auth_code' => 'fdD2tZ',
        //'auth_key' => '1e119144d5366febb3bfac2c09563a7266e3ad0d',
        //'msg_content' => 'Hello world',
        //'id_user' => 21,
        //'id_water_source' => 25
);*/


switch ($action) {
    case 'login':
        $data = array(
            'msgs' => array('')
        );
        $email = $App->postValue('username');
        $password = $App->encryptPassword(trim($App->postValue('password')));

        $sql = "SELECT  idu, group_id, username, pnumber, email, fname, lname, app_preferred_language, active, last_login AS last_sync FROM " . DB_TABLE_PREFIX . "users  LEFT JOIN " . DB_TABLE_PREFIX . "user_passwords on idu=uid WHERE  (username = '$email'  OR pnumber = '$email'  OR email = '$email')  AND password='$password'  LIMIT 1";

        $account = $App->con->rawQuery($sql);
        if (isset($account[0])) {
            $account = $account[0];
        }

        if (!empty($account) && isset($account['idu'])) {
            foreach ($account as $column => $value) {
                $App->$column = $value;
            }
            if ($account['active'] == 1) {
                $App->con->where('id_group', $account['group_id']);
                $user_group = $App->con->getOne('user_groups');
                if (!empty($user_group)) {
                    if ($user_group['group_is_enabled'] == 1) {
                        if ($user_group['can_access_app'] == 1) {
                            //Generate the session keys for the app

                            $auth_code = $App->generateAlphaNumCode();
                            $auth_key = sha1($App->encrypt_decrypt($App->generateAlphaNumCode()) . time() . uniqid("", true));
                            while ($App->auth_keysAreTaken($auth_code, $auth_key)) {
                                $auth_code = $App->generateAlphaNumCode();
                                $auth_key = sha1($App->encrypt_decrypt($App->generateAlphaNumCode()) . time() . uniqid("", true));
                            }
                            $params = array(
                                'uid' => $account['idu'],
                                'auth_code' => $auth_code,
                                'auth_key' => $auth_key,
                                'expires' => $App->getCurrentDateTime(time() + $CONFIG['offline_cookie_duration']),
                                'last_updated' => $App->getCurrentDateTime()
                            );

                            $App->saveAppUserLoginKeys($params);

                            $session_account = array(
                                'idu' => $account['idu'],
                                'group_id' => $account['group_id'],
                                'username' => $account['username'],
                                'pnumber' => $account['pnumber'],
                                'email' => $account['email'],
                                'fname' => $account['fname'],
                                'lname' => $account['lname'],
                                'auth_code' => $auth_code,
                                'auth_key' => $auth_key,
                                'app_preferred_language' => $account['app_preferred_language']
                            );

                            $data['user_account'] = $session_account;

                            //get user group and persmissions
                            $skipKeys = array(
                                'id_group',
                                'group_name',
                                'date_created',
                                'last_updated'
                            );

                            foreach ($user_group as $key => $value) {
                                if (!in_array($key, $skipKeys)) {
                                    $permissions[$key] = $value == 1 ? true : false;
                                } else {
                                    $permissions[$key] = $value;
                                }
                                unset($user_group[$key]);
                            }

                            $data['user_permissions'] = $permissions;

                            //get all water sources the user is a caretaker           
                            $cols = array(
                                'water_sources.id_water_source',
                                'water_sources.water_source_id',
                                'water_sources.water_source_name',
                                'water_sources.water_source_location',
                                'water_sources.water_source_coordinates',
                                'water_sources.monthly_charges',
                                'water_sources.percentage_saved',
                                'attending_to.date_created',
                                'attending_to.last_updated'
                            );
                            $App->con->where('uid', $account['idu']);
                            $App->con->join('water_sources', 'id_water_source=attending_to.water_source_id', 'LEFT');
                            $attending_to = $App->con->get('water_source_caretakers attending_to', null, $cols);

                            if (!is_array($attending_to)) {
                                $attending_to = array();
                            }

                            $data['attending_to'] = $attending_to;

                            //get all water sources the the user is a treasurer          
                            $cols = array(
                                'water_sources.id_water_source',
                                'water_sources.water_source_id',
                                'water_sources.water_source_name',
                                'water_sources.water_source_location',
                                'water_sources.water_source_coordinates',
                                'water_sources.monthly_charges',
                                'water_sources.percentage_saved',
                                'collecting_from.date_created',
                                'collecting_from.last_updated'
                            );

                            $App->con->where('uid', $account['idu']);
                            $App->con->join('water_sources', 'id_water_source=collecting_from.water_source_id', 'LEFT');
                            $collecting_from = $App->con->get('water_source_treasurers collecting_from', null, $cols);

                            if (!is_array($collecting_from)) {
                                $collecting_from = array();
                            }

                            $data['collecting_from'] = $collecting_from;

                            $water_source_ids = array();

                            if (!empty($collecting_from)) {
                                foreach ($collecting_from as $water_source) {
                                    $water_source_ids[] = $water_source['id_water_source'];
                                }
                            }

                            if (!empty($water_source_ids)) {
                                $water_source_ids = array_unique($water_source_ids);
                            }

                            $cols = array(
                                'id_expenditure',
                                'water_source_id',
                                'repair_type_id',
                                'expenditure_date',
                                'expenditure_cost',
                                'benefactor',
                                'description',
                                'logged_by',
                                'marked_for_delete',
                                'date_created',
                                'last_updated'
                            );

                            if (!empty($water_source_ids)) {
                                $App->con->where('marked_for_delete', 0);
                                $App->con->where('water_source_id', $water_source_ids, "IN");
                                $expenditures = $App->con->get('expenditures', null, $cols);
                            } else {
                                $expenditures = array();
                            }

                            if (!is_array($expenditures)) {
                                $expenditures = array();
                            }

                            //var_dump($expenditures);

                            $data['expenditures'] = $expenditures;

                            $cols = array(
                                'id_repair_type',
                                'repair_type',
                                'active',
                                'date_created',
                                'last_updated'
                            );

                            $App->con->where('active', 1);
                            $repair_types = $App->con->get('repair_types', null, $cols);

                            if (!is_array($repair_types)) {
                                $repair_types = array();
                            }

                            $repair_types[] = array(
                                'id_repair_type' => 0,
                                'repair_type' => 'Other',
                                'active' => 1,
                                'date_created' => '0000-00-00 00:00:00',
                                'last_updated' => '0000-00-00 00:00:00'
                            );

                            //var_dump($repair_types);

                            $data['repair_types'] = $repair_types;


                            //get all water sources the the user is a caretaker or treasurer
                            //get all sales where the user is a caretaker or treasurer

                            $water_source_ids = array();

                            if (!empty($attending_to)) {
                                foreach ($attending_to as $water_source) {
                                    $water_source_ids[] = $water_source['id_water_source'];
                                }
                            }
                            if (!empty($collecting_from)) {
                                foreach ($collecting_from as $water_source) {
                                    $water_source_ids[] = $water_source['id_water_source'];
                                }
                            }

                            if (!empty($water_source_ids)) {
                                $water_source_ids = array_unique($water_source_ids);
                            }


                            $cols = array();

                            if (!empty($water_source_ids)) {
                                $App->con->where('water_source_id', $water_source_ids, "IN");
                                $App->con->where('marked_for_delete', 0);
                                $sales = $App->con->get('sales', null, $cols);
                            }else{
                                $sales = array();
                            }

                            if (!is_array($sales)) {
                                $sales = array();
                            }

                            $data['sales'] = $sales;


                            //Fetch all system users for accountablility
                            $cols = array(
                                'idu',
                                'fname',
                                'lname'
                            );

                            $users = $App->con->get('users', null, $cols);

                            if (!is_array($users)) {
                                $users = array();
                            }

                            $data['users'] = $users;


                            //get all users where the user is a caretaker
                            $water_source_ids = array();

                            if (!empty($attending_to)) {
                                foreach ($attending_to as $water_source) {
                                    $water_source_ids[] = $water_source['id_water_source'];
                                }
                            }

                            $cols = array();

                            if (!empty($water_source_ids)) {
                                $App->con->where('water_source_id', $water_source_ids, "IN");
                                $App->con->where('marked_for_delete', 0);
                                $water_users = $App->con->get('water_users', null, $cols);
                            }

                            if (!is_array($water_users)) {
                                $water_users = array();
                            }

                            $data['water_users'] = $water_users;

                            // var_dump($water_users);                            

                            $request_status = SUCCESS_STATUS_CODE;

                            $data['msgs'] = array('Login successful.');

                            $app_version_in_use = $App->postValue('app_version');
                            if (empty($app_version_in_use)) {
                                $app_version_in_use = "Unknown";
                            }
                            $app_preferred_language = $App->postValue("app_preferred_language");
                            $device_imei = $App->postValue('device_imei');
                            $last_known_location = $App->postValue('last_known_location');

                            $updateParams = array(
                                'last_login' => $App->getCurrentDateTime(),
                                'app_version_in_use' => $app_version_in_use,
                                'app_preferred_language' => $app_preferred_language,
                                'device_imei' => $device_imei,
                                'last_known_location' => $last_known_location,
                                'idu' => $account['idu']
                            );
                            $App->LogEevent($account["idu"], $App->event->EVENT_LOGGED_IN, $App->getCurrentDateTime(), "", 0, MOBILE_APP_ANDROID);
                            $App->saveUserData($updateParams);
                        } else {
                            $data['msgs'] = array("You cannot be logged in by the use of the app because your user group is not allowed to use the app. Please consult your administrator for further advice.");
                        }
                    } else {
                        $data['msgs'] = array("You cannot be logged in because your user group has been deactivated. Please consult your administrator for further advice.");
                    }
                } else {
                    $data['msgs'] = array("You cannot be logged in because you belong to no user group. Please consult your administrator for further advice.");
                }
            } else {
                $data['msgs'] = array("Your account is inactive hence you cannot log in. Please consult your administrator for further advice.");
            }
            $App->LogEevent($account["idu"], $App->event->EVENT_ATTEMPTED_LOGIN, $App->getCurrentDateTime(), $data['msgs'][0], 0, MOBILE_APP_ANDROID);
        } else {
            $data['msgs'] = array('Wrong username or password.');
            $App->LogEevent(0, $App->event->EVENT_ATTEMPTED_LOGIN, $App->getCurrentDateTime(), $data['msgs'][0], 0, MOBILE_APP_ANDROID);
        }
        break;
    case 'recover-password':
        $email = $App->postValue('username');
        $query = "SELECT idu,pnumber,email FROM " . DB_TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')";
        $columns = array(
            'idu', 'pnumber', 'email'
        );
        $App->con->where('username', $email, "=", "OR");
        $App->con->where('pnumber', $email, "=", "OR");
        $App->con->where('email', $email, "=", "OR");
        $account = $App->con->getOne('users ', $columns);

        if (!empty($account) && isset($account['idu'])) {
            if ($App->appUserIsLoggedIn()) {
                $data['msgs'] = array('You need to log out first');
            } else {
                if (!empty($account['email']) || !empty($account['pnumber'])) {
                    $password = $App->generateAlphaNumCode(6);
                    $params['uid'] = $account['idu'];
                    $params['password'] = $password;
                    if ($App->saveUserPasswordsData($params)) {
                        $request_status = SUCCESS_STATUS_CODE;
                        if (isset($account['email'])) {
                            $template = 'recovery_email_template';
                            $App->setEmailTemplateParams(array('password' => $password));
                            $App->sendEmail($account['email'], $App->getEmailTemplate($App->getLocale(), $template, "title"), $App->getEmailTemplate($App->getLocale(), $template, "body"));
                        }

                        if (!empty($account['pnumber'])) {
                            $template = 'recovery_sms_template';
                            $App->setEmailTemplateParams(array('password' => $password));
                            $App->sendSMS($account['pnumber'], $App->getEmailTemplate($App->getLocale(), $template, "body"));
                        }

                        $data['msgs'] = array("Your password has been reset. Please check your Email and\or SMS inbox for instructions.");
                    } else {
                        $data['msgs'] = array("An error occured resetting your password. Please try agin later. If this persists please consult your administrator.");
                    }
                } else {
                    $data['msgs'] = array("Your password could not be reset. The account does not have an email address or phone number to send the new logins. Please consult your administrator");
                }
            }
        } else {
            $data['msgs'] = array("Your password could not be reset. No account exists with those details");
        }

        break;
    case "fetch-account-balance":
        if (!$App->appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please log out of the application then login again.');
        } else {

            $id_water_source = $App->postValue("id_water_source");

            $account_balance = 0;
            $inflow = 0;
            $outflow = 0;

            $query = "SELECT CASE WHEN " . DB_TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . DB_TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS inflow FROM " . DB_TABLE_PREFIX . "sales WHERE water_source_id=$id_water_source AND sold_by=" . $App->user->idu . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
            $squery = "SELECT SUM(inflow) AS inflow FROM ($query) AS derived";
            //echo $squery;            
            $result = $App->con->rawQuery($squery);
            $inflow = floatval($result[0]['inflow']);

            $query = "SELECT SUM(expenditure_cost) AS outflow FROM " . DB_TABLE_PREFIX . "expenditures WHERE " . DB_TABLE_PREFIX . "expenditures.water_source_id=$id_water_source AND logged_by=" . $App->user->idu . "";

            $result = $App->con->rawQuery($query);
            $outflow = floatval($result[0]['outflow']);
            $account_balance = $inflow - $outflow;

            $query = "SELECT * FROM " . DB_TABLE_PREFIX . "water_sources WHERE " . DB_TABLE_PREFIX . "id_water_source=$id_water_source";

            $result = $App->con->rawQuery($query);

            $request_status = SUCCESS_STATUS_CODE;
            $data['water_source_name'] = $result[0]['water_source_name'];
            $data['account_name'] = $App->user->fname . " " . $App->user->lname;
            $data['account_balance'] = number_format($account_balance, 2, '.', ',');
            $data['msgs'] = array('Transaction completed.');
        }
        break;
    case "fetch-mini-statement":
        if (!$App->appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please log out of the application then login again.');
        } else {
            $id_water_source = $App->postValue("id_water_source");
            $transactions = array();
            $sales = array();

            $query = "SELECT * FROM " . DB_TABLE_PREFIX . "water_sources WHERE " . DB_TABLE_PREFIX . "id_water_source=$id_water_source";
            $water_source = $App->con->rawQuery($query);

            $query = "SELECT *,CASE WHEN " . DB_TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . DB_TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS savings FROM " . DB_TABLE_PREFIX . "sales WHERE water_source_id=$id_water_source AND submitted_to_treasurer=1 AND treasurerer_approval_status=1 ORDER BY id_sale DESC";
            $squery = "SELECT date_reviewed, SUM(savings) AS savings FROM ($query) AS derived GROUP BY DATE(date_reviewed) LIMIT 5";

            $result = $App->con->rawQuery($squery);
            foreach ($result as $sale) {
                $sales[] = array(
                    'type' => 'Deposit',
                    'amount' => number_format($sale['savings'], 2, '.', ','),
                    'date' => date("M-d-Y h:i:s A", strtotime($sale['date_reviewed'])),
                );
            }
            $expenditures = array();
            $query = "SELECT * FROM " . DB_TABLE_PREFIX . "expenditures WHERE water_source_id=$id_water_source ORDER BY id_expenditure DESC LIMIT 5 ";
            $result = $App->con->rawQuery($query);
            foreach ($result as $expenditure) {
                $expenditures[] = array(
                    'type' => 'Expenditure',
                    'amount' => number_format($expenditure['expenditure_cost'], 2, '.', ','),
                    'date' => date("M-d-Y h:i:s A", strtotime($expenditure['expenditure_date'])),
                );
            }
            $transactions = array_merge($expenditures, $sales);
            $transactions = $App->sortArray($transactions, "date", SORT_DESC);
            $transactions = array_values($App->sortArray(array_slice($transactions, 0, 5), "date"));

            $data['water_source'] = $water_source[0]['water_source_name'];
            $data['transactions'] = $transactions;


            $data['msgs'] = array('Transaction completed.');
            $request_status = SUCCESS_STATUS_CODE;
        }
        break;
    case 'check-sync-auth':
        if (!$App->appUserIsLoggedIn()) {
            $data['msgs'] = array('Cannot send data to the server. Login is required. Please log out of the application then login again.');
        } else {
            $data['msgs'] = array('User is authenticated');
            $request_status = SUCCESS_STATUS_CODE;
        }
        break;
    case"perform-sync":
        if (!$App->appUserIsLoggedIn()) {
            $data['msgs'] = array('Cannot send data to the server. Login is required. Please log out of the application then login again.');
        } else {

            $posted_data = json_decode($App->postValue('data'));

            if (property_exists($posted_data, 'user_permissions')) {
                $App->con->where('id_group', $App->user->group_id);
                $user_group = $App->con->getOne('user_groups');
                if (!empty($user_group)) {
                    //get user group and persmissions
                    $skipKeys = array(
                        'id_group',
                        'group_name',
                        'date_created',
                        'last_updated'
                    );

                    foreach ($user_group as $key => $value) {
                        if (!in_array($key, $skipKeys)) {
                            $permissions[$key] = $value == 1 ? true : false;
                        } else {
                            $permissions[$key] = $value;
                        }
                        unset($user_group[$key]);
                    }
                    $data['data_type'] = "user_permissions";
                    $data['user_permissions'] = $permissions;
                }
            }

            //get all water sources the user is a caretaker           
            $cols = array(
                'water_sources.id_water_source',
                'water_sources.water_source_id',
                'water_sources.water_source_name',
                'water_sources.water_source_location',
                'water_sources.water_source_coordinates',
                'water_sources.monthly_charges',
                'water_sources.percentage_saved',
                'attending_to.date_created',
                'attending_to.last_updated'
            );
            $App->con->where('uid', $App->user->idu);
            $App->con->join('water_sources', 'id_water_source=attending_to.water_source_id', 'LEFT');
            $attending_to = $App->con->get('water_source_caretakers attending_to', null, $cols);

            if (!is_array($attending_to)) {
                $attending_to = array();
            }

            if (property_exists($posted_data, 'attending_to')) {
                $data['data_type'] = "attending_to";
                $data['attending_to'] = $attending_to;
            }

            //get all water sources the the user is a treasurer          
            $cols = array(
                'water_sources.id_water_source',
                'water_sources.water_source_id',
                'water_sources.water_source_name',
                'water_sources.water_source_location',
                'water_sources.water_source_coordinates',
                'water_sources.monthly_charges',
                'water_sources.percentage_saved',
                'collecting_from.date_created',
                'collecting_from.last_updated'
            );
            $App->con->where('uid', $App->user->idu);
            $App->con->join('water_sources', 'id_water_source=collecting_from.water_source_id', 'LEFT');
            $collecting_from = $App->con->get('water_source_treasurers collecting_from', null, $cols);

            if (!is_array($collecting_from)) {
                $collecting_from = array();
            }

            if (property_exists($posted_data, 'collecting_from')) {
                $data['data_type'] = "collecting_from";
                $data['collecting_from'] = $collecting_from;
            }

            if (property_exists($posted_data, 'event_logs')) {
                foreach ($posted_data->event_logs as $event) {
                    $event = (array) $event;
                    $App->LogEevent($event['uid'], $event['event'], $event['event_time'], $event['event_description'], $event['affected_object_id'], MOBILE_APP_ANDROID);
                }
                $data['data_type'] = "event_logs";
                $data['event_logs'] = array();
            }

            if (property_exists($posted_data, 'expenditures')) {
                foreach ($posted_data->expenditures as $expenditure) {
                    $sql = "INSERT INTO " . DB_TABLE_PREFIX . "expenditures (id_expenditure, water_source_id, repair_type_id, expenditure_date, expenditure_cost, benefactor, description, logged_by, marked_for_delete, date_created, last_updated) 
                                VALUES ($expenditure->id_expenditure, $expenditure->water_source_id, $expenditure->repair_type_id, '$expenditure->expenditure_date', $expenditure->expenditure_cost, '$expenditure->benefactor', '$expenditure->description', $expenditure->logged_by, $expenditure->marked_for_delete, '$expenditure->date_created', '$expenditure->last_updated') 
                                    ON DUPLICATE KEY UPDATE water_source_id=$expenditure->water_source_id,repair_type_id=$expenditure->repair_type_id,expenditure_date='$expenditure->expenditure_date',"
                            . "expenditure_cost=$expenditure->expenditure_cost,benefactor='$expenditure->benefactor',description='$expenditure->description',logged_by=$expenditure->logged_by,marked_for_delete=$expenditure->marked_for_delete,last_updated='$expenditure->last_updated',id_expenditure = IF(last_updated < VALUES(last_updated) AND water_source_id=VALUES(water_source_id), VALUES(id_expenditure), id_expenditure) ";
                    $result = $App->con->rawQuery($sql);
                }

                $water_source_ids = array();

                if (!empty($collecting_from)) {
                    foreach ($collecting_from as $water_source) {
                        $water_source_ids[] = $water_source['id_water_source'];
                    }
                }

                if (!empty($water_source_ids)) {
                    $water_source_ids = array_unique($water_source_ids);
                }

                $cols = array(
                    'id_expenditure',
                    'water_source_id',
                    'repair_type_id',
                    'expenditure_date',
                    'expenditure_cost',
                    'benefactor',
                    'description',
                    'logged_by',
                    'marked_for_delete',
                    'date_created',
                    'last_updated'
                );

                $App->con->where('marked_for_delete', 0);
                $App->con->where('water_source_id', $water_source_ids, "IN");
                $expenditures = $App->con->get('expenditures', null, $cols);

                if (!is_array($expenditures)) {
                    $expenditures = array();
                }

                $data['data_type'] = "expenditures";
                $data['expenditures'] = $expenditures;
            }

            if (property_exists($posted_data, 'repair_types')) {
                $cols = array(
                    'id_repair_type',
                    'repair_type',
                    'active',
                    'date_created',
                    'last_updated'
                );
                $App->con->where('active', 1);
                $repair_types = $App->con->get('repair_types', null, $cols);
                if (!is_array($repair_types)) {
                    $repair_types = array();
                }
                $repair_types[] = array(
                    'id_repair_type' => 0,
                    'repair_type' => 'Other',
                    'active' => 1,
                    'date_created' => '0000-00-00 00:00:00',
                    'last_updated' => '0000-00-00 00:00:00'
                );
                $data['data_type'] = "repair_types";
                $data['repair_types'] = $repair_types;
            }

            if (property_exists($posted_data, 'sales')) {
                foreach ($posted_data->sales as $sale) {
                    $sql = "INSERT INTO " . DB_TABLE_PREFIX . "sales (id_sale, water_source_id, sold_by, sold_to, sale_ugx, sale_date, percentage_saved, submitted_to_treasurer, submitted_by, submittion_to_treasurer_date, treasurerer_approval_status, reviewed_by, date_reviewed, marked_for_delete, date_created, last_updated) 
                                                               VALUES ($sale->id_sale, $sale->water_source_id, $sale->sold_by, $sale->sold_to, $sale->sale_ugx, '$sale->sale_date', $sale->percentage_saved, $sale->submitted_to_treasurer, $sale->submitted_by, '$sale->submittion_to_treasurer_date', $sale->treasurerer_approval_status,
                                                                   $sale->reviewed_by, '$sale->date_reviewed', $sale->marked_for_delete, '$sale->date_created', '$sale->last_updated') 
                                    ON DUPLICATE KEY UPDATE water_source_id=$sale->water_source_id,sold_by=$sale->sold_by, sold_to=$sale->sold_to, sale_ugx=$sale->sale_ugx, sale_date='$sale->sale_date', percentage_saved=$sale->percentage_saved, submitted_to_treasurer=$sale->submitted_to_treasurer, submitted_by=$sale->submitted_by, submittion_to_treasurer_date='$sale->submittion_to_treasurer_date', treasurerer_approval_status=$sale->treasurerer_approval_status,
                                                                   reviewed_by=$sale->reviewed_by, date_reviewed='$sale->date_reviewed', marked_for_delete=$sale->marked_for_delete, last_updated='$sale->last_updated',id_sale = IF(last_updated < VALUES(last_updated) AND water_source_id=VALUES(water_source_id), VALUES(id_sale), id_sale) ";
                    $result = $App->con->rawQuery($sql);
                }

                //get all water sources the the user is a caretaker or treasurer
                //get all sales where the user is a caretaker or treasurer

                $water_source_ids = array();

                if (!empty($attending_to)) {
                    foreach ($attending_to as $water_source) {
                        $water_source_ids[] = $water_source['id_water_source'];
                    }
                }
                if (!empty($collecting_from)) {
                    foreach ($collecting_from as $water_source) {
                        $water_source_ids[] = $water_source['id_water_source'];
                    }
                }

                if (!empty($water_source_ids)) {
                    $water_source_ids = array_unique($water_source_ids);
                }

                $cols = array();

                $App->con->where('water_source_id', $water_source_ids, "IN");
                $App->con->where('marked_for_delete', 0);
                $sales = $App->con->get('sales', null, $cols);

                if (!is_array($sales)) {
                    $sales = array();
                }
                $data['data_type'] = "sales";
                $data['sales'] = $sales;
            }

            if (property_exists($posted_data, 'users')) {
                $cols = array(
                    'idu',
                    'fname',
                    'lname'
                );
                $users = $App->con->get('users', null, $cols);
                if (!is_array($users)) {
                    $users = array();
                }
                $data['data_type'] = "users";
                $data['users'] = $users;
            }

            if (property_exists($posted_data, 'water_users')) {
                foreach ($posted_data->water_users as $water_user) {
                    $sql = "INSERT INTO " . DB_TABLE_PREFIX . "water_users (id_user, fname, lname, pnumber, water_source_id, date_added, added_by, reported_defaulter, marked_for_delete, last_updated) 
                                VALUES ($water_user->id_user, '$water_user->fname', '$water_user->lname', '$water_user->pnumber', $water_user->water_source_id, '$water_user->date_added', $water_user->added_by, $water_user->reported_defaulter, $water_user->marked_for_delete, '$water_user->last_updated') 
                                    ON DUPLICATE KEY UPDATE id_user=$water_user->id_user, fname='$water_user->fname', lname='$water_user->lname', pnumber='$water_user->pnumber', water_source_id=$water_user->water_source_id, reported_defaulter=$water_user->reported_defaulter, marked_for_delete=$water_user->marked_for_delete, last_updated='$water_user->last_updated',id_user = IF(last_updated < VALUES(last_updated) AND water_source_id=VALUES(water_source_id), VALUES(id_user), id_user) ";
                    $result = $App->con->rawQuery($sql);
                }

                //get all users where the user is a caretaker
                $water_source_ids = array();

                if (!empty($attending_to)) {
                    foreach ($attending_to as $water_source) {
                        $water_source_ids[] = $water_source['id_water_source'];
                    }
                }

                $cols = array();

                $App->con->where('water_source_id', $water_source_ids, "IN");
                $App->con->where('marked_for_delete', 0);
                $water_users = $App->con->get('water_users', null, $cols);

                if (!is_array($water_users)) {
                    $water_users = array();
                }

                $data['data_type'] = "water_users";
                $data['water_users'] = $water_users;
            }
        }
        break;
    case 'send-sms-message':
        if (!$App->appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please log out of the application then login again.');
        } else {
            $errors = array();
            $event = $App->event->EVENT_ATTEMPTED_TO_QUEUE_SMS_MESSAGE_FOR_SENDING;
            $params = $pnumbers = array();
            $params['type'] = 'sms';
            $params['label'] = 'outbox';
            $params['created_by'] = $App->user->idu;
            $params['can_be_sent'] = 1;
            $params['last_updated'] = $App->getCurrentDateTime();

            $system_users = $App->postValue('system_users');
            $water_users = $App->postValue('water_users');
            $scheduled = $App->postValue('scheduled');

            if (empty($system_users) && empty($water_users)) {
                $errors[] = "Please select a recepient or recepients";
            } else {
                if (!empty($system_users)) {
                    if (is_array($system_users)) {

                        $App->con->where('pnumber', '', '<>');
                        $App->con->where('idu', $system_users, 'IN');
                        $results = $App->con->get('users', null, 'idu,pnumber');
                        foreach ($results as $row) {

                            $pnumbers[] = array(
                                $row['idu'],
                                0,
                                'user',
                                $row['pnumber']
                            );
                        }
                    } else {
                        $errors[] = "Invalid system users";
                    }
                }

                if (!empty($water_users)) {
                    if (is_array($water_users)) {
                        $App->con->where('pnumber', '', '<>');
                        $App->con->where('id_user', $water_users, 'IN');
                        $results = $App->con->get('water_users', null, 'id_user,pnumber');
                        foreach ($results as $row) {
                            $pnumbers[] = array(
                                0,
                                $row['id_user'],
                                'water_user',
                                $row['pnumber'],
                            );
                        }
                    } else {
                        $errors[] = "Invalid water users";
                    }
                }
            }


            switch ($scheduled) {
                case 'now':
                    $params['scheduled_send_date'] = $_POST['scheduledDate'] = $App->getCurrentDateTime();
                    break;
                case 'setDate':
                    $params['scheduled_send_date'] = $App->getCurrentDateTime($_POST['scheduledDate']);
                    break;
                case 'noSend':
                    $params['scheduled_send_date'] = '0000-00-00 00:00:00';
                    $params['can_be_sent'] = 0;
                    $_POST['scheduledDate'] = $App->getCurrentDateTime();
                    break;
                default:
                    $_POST['scheduledDate'] = $App->getCurrentDateTime();
                    $errors[] = "Please select the schedule method";
                    break;
            }

            if (!$App->isValid('date', $params['scheduled_send_date']) && $params['can_be_sent'] == 1) {
                $errors[] = "Please enter a valid date";
            } elseif (strtotime($params['scheduled_send_date']) < strtotime($App->getCurrentDateTime()) && $params['can_be_sent']) {
                $errors[] = "Please use a valid date. You cannot schedule a message for time that has already passed";
            }

            $params['message_content'] = $App->postValue('msg_content');

            if (empty($params['message_content'])) {
                $errors[] = "SMS messages cost money. You cannot send a blank sms message.";
            }

            if (empty($errors)) {
                $id_msg = $App->con->insert('sms_messages', $params);
                if (is_int($id_msg)) {
                    $params = array();
                    foreach ($pnumbers as $pnumber) {
                        $params[] = array(
                            'msg_id' => $id_msg,
                            'idu' => $pnumber[0],
                            'id_user' => $pnumber[1],
                            'account_type' => $pnumber[2],
                            'pnumber' => $pnumber[3]
                        );
                    }
                    $App->MultiInsert("sms_messages_recipients", $params);
                    $event = $App->event->EVENT_QUEUED_SMS_MESSAGE_FOR_SENDING;
                    $data['msgs'] = array("SMS will be sent");
                    $request_status = SUCCESS_STATUS_CODE;
                } else {
                    $data['msgs'] = array("An error occured queueing the SMS message for sending.");
                }
            }

            foreach ($errors as $error) {
                $data['msgs'][] = $error;
            }
            $App->LogEevent($App->user->idu, $event, $App->getCurrentDateTime());
        }
        break;
    case 'check-update':

        $App->con->where('published', 1);
        $App->con->where('preferred', 1);
        $App->con->join('files', 'id_file=file_id', 'LEFT');
        $build = $App->con->getOne('app_builds');
        if (!empty($build)) {
            $request_status = SUCCESS_STATUS_CODE;
            $update = array(
                'version' => $build['build_version'],
                'url' => $App->siteURL() . '/attachment/' . $build['file_name'],
                'name' => $build['build_name'] . '-' . $build['build_version'] . '-' . ($build['is_stable'] == 1 ? 'stable' : 'nightly') . ".apk", //always change the value, app does not overwrite instead deletes
                'size' => $build['file_size_bytes']
            );
            $data['msgs'] = array('Update available');
        } else {
            $data['msgs'] = array('No update is available at this time');
            $update = array();
        }
        $data['update'] = $update;
        break;
    default:
        $data['msgs'] = array("Undefined request '" . $action . "'");
        break;
}
$data['request'] = $action;
$data['request_status'] = $request_status;
$server_reply = array(
    'server_info' => $server_info,
    'data' => $data
);

header('Content-Type: application/json');
//echo preg_replace('/\s+/', ' ', json_encode($server_reply));
echo json_encode($server_reply);
exit();
