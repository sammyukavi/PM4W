<?php

function appUserIsLoggedIn() {
    global $dbhandle;
    $email = getArrayVal($_POST, 'username');
    $password = getArrayVal($_POST, 'request_hash');

    if (empty($email) || empty($password)) {
        return false;
    }
    $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')"
            . " AND password='$password'";
    $result = $dbhandle->RunQueryForResults($query);
    $account = $result->fetch_assoc();

    if (!empty($account) && isset($account['idu'])) {

        $user_group = $dbhandle->Fetch("user_groups", "*", array('id_group' => $account['group_id'], 'group_is_enabled' => 1));

        if (!empty($user_group)) {

            foreach ($user_group as $key => $value) {
                if ($key !== 'id_group' && $key !== 'group_name') {
                    $user_group[$key] = $value === '1' ? true : false;
                }
            }

            $USER = (object) array_merge($account, $user_group);

            $_SESSION['idu'] = $USER->idu;
            $_SESSION['username'] = $USER->username;
            $_SESSION['fname'] = $USER->fname;
            $_SESSION['lname'] = $USER->lname;
            $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));
            return true;
        }
    }
    return false;
}

function strip_only_tags($str, $tags, $stripContent = false) {
    $content = '';
    if (!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if (end($tags) == '')
            array_pop($tags);
    }
    foreach ($tags as $tag) {
        if ($stripContent)
            $content = '(.+</' . $tag . '(>|\s[^>]*>)|)';
        $str = preg_replace('#</?' . $tag . '(>|\s[^>]*>)' . $content . '#is', '', $str);
    }
    return $str;
}

function getArrayVal(array $array, $name) {
    if (array_key_exists($name, $array)) {
        return trim(strip_only_tags($array[$name], "script"));
    }
}

function getCurrentDate($date = "", $humanreadable = false, $simple = false) {
    if (!empty($date)) {
        if ($humanreadable === true) {
            if ($simple === true) {
//return date("d-m-Y H:i:s", strtotime($date));
                return is_numeric($date) ? date("d-m-Y H:i:s", $date) : date("d-m-Y H:i:s", strtotime($date));
            } else {
                return is_numeric($date) ? date("D d-M-Y h:i:s A", $date) : date("D d-M-Y h:i:s A", strtotime($date));
            }
        } else {
            if ($simple === true) {
                return is_numeric($date) ? date("d-m-Y H:i:s", $date) : date("d-m-Y H:i:s", strtotime($date));
            } else {
                return is_numeric($date) ? date("Y-m-d H:i:s", $date) : date("Y-m-d H:i:s", strtotime($date));
            }
        }
    } else {
        if ($humanreadable === true) {
            if ($simple === true) {
                return date("d-m-Y H:i:s");
            } else {
                return date("D d-M-Y h:i:s A");
            }
        } else {
            if ($simple === true) {
                return date("d-m-Y H:i:s");
            } else {
                return date("Y-m-d H:i:s");
            }
        }
    }
}

function isAllowedWord($string) {
//NOTE: DICTIONARY WORDS MUST ALL BE IN LOWER CASE 
    $dictionaryWords = array(
        strtolower(SYSTEM_NAME)
    );
    $isValid = true;
    $wordsArray = explode(' ', $string);
    if (isset($wordsArray) && is_array($wordsArray) && !empty($wordsArray)) {
        foreach ($wordsArray as $singleWord) {
            if (in_array(strtolower($singleWord), $dictionaryWords)) {
                $isValid = $isValid === true ? $isValid = false : $isValid = true;
            }
        }
    } else {
        if (in_array(strtolower($string), $dictionaryWords)) {
            $isValid = $isValid === true ? $isValid = false : $isValid = true;
        }
    }
    return $isValid;
}

function isValid($what, $string) {
    global $GLOBAL_SETTINGS;
    $allowedCharsInUsername = explode(",", $GLOBAL_SETTINGS['allowed_chars_in_username']);
    switch ($what) {
        case 'email':
            if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                return false;
            } else {
                return true;
            }
            break;
        case 'username':
            if (empty($string)) {
                return false;
            }
            $start_char = $string[0];
            if (in_array($start_char, $allowedCharsInUsername)) {
                return false;
            }
            $start_char = $string[strlen($string) - 1];
            if (in_array($start_char, $allowedCharsInUsername)) {
                return false;
            }

            if (ctype_alnum(str_replace($allowedCharsInUsername, '', $string))) {
                if (strlen($string) >= $GLOBAL_SETTINGS['min_username_length'] && strlen($string) <= $GLOBAL_SETTINGS['max_username_length'] && isAllowedWord($string)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
            break;
        case 'pnumber':
            $formats = array('############', '##########');
//$format = trim(preg_replace("/[0-9]/", "#", $string)); //outputs same as below            
            $format = trim(preg_replace("/\d/", "#", $string));
            return in_array($format, $formats) ? true : false;
            break;
        case 'password':
            return true;
            break;
        case 'name':
            return isAllowedWord($string);
            break;
        default:
            return false;
            break;
    }
}

function autoCorrectPnumber($pnumber) {
    $formats = array('##########');
    $format = trim(preg_replace("/\d/", "#", $pnumber));
    if (in_array($format, $formats)) {
        return "256" . ltrim($pnumber, '0');
    } else {
        return $pnumber;
    }
}

function logMessage($msg, $severity = 1) {
//0=error
//1=warning
//2=success
//3=notification
    $_SESSION['msgs'][$severity][] = $msg;
}

function printMessage() {
    $str = '';
    if (isset($_SESSION['msgs'][3])) {
        $str = '<div class="alert alert-success">';
        foreach ($_SESSION['msgs'][3] as $msg) {
            $str.=$msg . "<br/>";
        }
        $str .= '</div>';
    }
    if (isset($_SESSION['msgs'][2])) {
        $str = '<div class="alert alert-info">';
        foreach ($_SESSION['msgs'][2] as $msg) {
            $str.=$msg . "<br/>";
        }
        $str .= '</div>';
    }
    if (isset($_SESSION['msgs'][1])) {
        $str = '<div class="alert alert-warning">';
        foreach ($_SESSION['msgs'][1] as $msg) {
            $str.=$msg . "<br/>";
        }
        $str .= '</div>';
    }
    if (isset($_SESSION['msgs'][0])) {
        $str = '<div class="alert alert-danger">';
        foreach ($_SESSION['msgs'][0] as $msg) {
            $str.=$msg . "<br/>";
        }
        $str .= '</div>';
    }
    echo $str;
    $_SESSION['msgs'] = '';
}

function is_logged_in() {
    global $dbhandle, $USER;
    $query = "SELECT idu,username,gcm_regid,group_id,username,pnumber,email,fname,lname,date_added,last_login,active FROM " . TABLE_PREFIX . "users WHERE username='" . getArrayVal($_SESSION, 'username') . "' AND idu=" . getArrayVal($_SESSION, 'idu') . " AND active=1";
    $result = $dbhandle->RunQueryForResults($query);
    if (empty($result)) {
        return false;
    }

    $account = $result->fetch_assoc();

    if (!empty($account) && isset($account['idu'])) {

        $user_group = $dbhandle->Fetch("user_groups", "*", array('id_group' => $account['group_id'], 'group_is_enabled' => 1));

        if (!empty($user_group)) {

            foreach ($user_group as $key => $value) {
                if ($key !== 'id_group' && $key !== 'group_name') {
                    $user_group[$key] = $value === '1' ? true : false;
                }
            }

//var_dump($USER);
// die();

            $USER = (object) array_merge($account, $user_group);
//$GLOBALS['USER'] = $USER;

            $_SESSION['idu'] = $USER->idu;
            $_SESSION['username'] = $USER->username;

            $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));
            return true;
        }
    }
    return false;
}

function isTaken($what, $value) {
    global $dbhandle;
    switch ($what) {
        case 'email':
            return $dbhandle->CheckIFExists('users', array('email' => $value));
            break;
        case 'username':
            return $dbhandle->CheckIFExists('users', array('username' => $value));
            break;
        case 'pnumber':
            return $dbhandle->CheckIFExists('users', array('pnumber' => $value));
            break;
        case 'user_pnumber':
            return $dbhandle->CheckIFExists('water_users', array('pnumber' => $value));
            break;
        default:
            break;
    }
}

function sortArray($array, $subkey, $reverse = false) {
    if (is_array($array) && !empty($array)) {
        foreach ($array as $k => $v) {
            $b[$k] = strtolower($v[$subkey]);
        }

        if ($reverse === true) {
            rsort($b);
        } else {
            arsort($b);
        }
        //var_dump($b);

        foreach ($b as $key => $val) {
            $c[] = $array[$key];
        }
        return $c;
    } else {
        return $array;
    }
}

function generateAlphaNumCode($length = 4) {
    $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
    $code = "";
    for ($index = 0; $index < $length; $index++) {
        $code.=$chars[rand(0, (strlen($chars) - 1))];
    }
    return $code;
}

function send_sms_message($comma_separated_recepients, $message) {
    //return true;
    if (ENABLE_SMS !== 1) {
        return true;
    }

    if (!defined("SMS_API_USERNAME") || strlen(SMS_API_USERNAME) <= 0) {
        return true;
    }

    if (!defined("SMS_API_KEY") || strlen(SMS_API_KEY) <= 0) {
        return true;
    }
    
    //pm4w-1.102

    global $TEMPLATE_PARAMS;

    if (!is_array($TEMPLATE_PARAMS)) {
        $TEMPLATE_PARAMS = array();
    }

    $message = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($TEMPLATE_PARAMS) {
        return (isset($TEMPLATE_PARAMS[$matches[1]]) ? $TEMPLATE_PARAMS[$matches[1]] : "");
    }, $message);
      
    if (function_exists('curl_version')) {
        $smsObject = new AfricasTalkingGateway(SMS_API_USERNAME, SMS_API_KEY);
        try {
            $smsObject->sendMessage($comma_separated_recepients, $message);
        } catch (AfricasTalkingGatewayException $e) {
            send_email(DEFAULT_SITE_EMAIL, "Encountered an error while sending SMS", $e->getMessage());
            //echo "Encountered an error while sending: " . $e->getMessage();            
            return false;
        }
    } else {
        $url = 'http://api.africastalking.com/version1/messaging';
        $data = array('username' => SMS_API_USERNAME, 'message' => $message, 'to' => $comma_separated_recepients);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Accept: application/json\r\n"
                . "apikey: " . SMS_API_KEY,
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);

        set_error_handler(
                create_function(
                        '$severity, $message, $file, $line', 'throw new ErrorException($message, $severity, $severity, $file, $line);'
                )
        );

        try {
            $result = file_get_contents($url, false, $context);
        } catch (Exception $e) {
            send_email(DEFAULT_SITE_EMAIL, "Encountered an error while sending SMS", $e->getMessage());
            //echo "Encountered an error while sending: " . $e->getMessage();            
            return false;
        }
        restore_error_handler();
        return true;
    }
    return true;
}

function send_push_notification($semi_collon_separated_ids, $message) {

    if (ENABLE_PUSH_NOTIFICATIONS !== 1) {
        return true;
    }


    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => explode("|", $semi_collon_separated_ids),
        'data' => array("data" => $message),
    );

    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );
    //print_r($headers);
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        send_email(DEFAULT_SITE_EMAIL, "Encountered an error while sending push notifications", curl_error($ch));
        return FALSE;
    }
    // Close connection
    curl_close($ch);

    return true;
}

function send_email($semi_collon_separated_recepients, $subject, $message, $sender = array()) {

    if (ENABLE_EMAILS !== 1) {
        return true;
    }

    global $TEMPLATE_PARAMS;

    if (!is_array($TEMPLATE_PARAMS)) {
        $TEMPLATE_PARAMS = array();
    }

    $message = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($TEMPLATE_PARAMS) {
        return (isset($TEMPLATE_PARAMS[$matches[1]]) ? $TEMPLATE_PARAMS[$matches[1]] : "");
    }, $message);


    if (isset($sender) && !empty($sender)) {
        if (!isset($sender['name']) || empty($sender['name'])) {
            $sender['name'] = 'do-not-reply';
        }
        if (!isset($sender['email']) || empty($sender['email'])) {
            $sender['email'] = 'do-not-reply@' . getArrayVal($_SERVER, 'SERVER_NAME');
        }
        $headers = "From: " . $sender['name'] . " <" . $sender['email'] . ">\n";
        $headers .= "Return-Path: " . $sender['name'] . " <" . $sender['email'] . ">\n";
    } else {
        $headers = "From: " . DEFAULT_SITE_EMAIL . "<" . DEFAULT_SITE_EMAIL . ">\n";
        $headers .= "Return-Path: " . DEFAULT_SITE_EMAIL . "<" . DEFAULT_SITE_EMAIL . ">\n";
    }
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: text/html; charset=\"utf-8\"\n";
    $headers .= "X-Priority: 3 (Normal)\n";
    $headers .= "X-MSMail-Priority: Normal\n";
    $headers .= "Importance: Normal\n";

    $recepients_array = explode(';', $semi_collon_separated_recepients);
    foreach ($recepients_array as $single_recepient) {
        if (isValid("email", $single_recepient)) {
            mail($single_recepient, $subject, $message, $headers);
        }
    }
    return true;
}

function calculatePendingApprovals() {
    global $dbhandle;
    $sales = array();
    $query = "SELECT COUNT(id_sale) AS transactions, SUM(sale_ugx) AS inflow,"
            . TABLE_PREFIX . "sales.id_sale,"
            . TABLE_PREFIX . "sales.sale_date,"
            . TABLE_PREFIX . "sales.percentage_saved,"
            . TABLE_PREFIX . "users.idu,"
            . TABLE_PREFIX . "users.fname AS attendant_fname,"
            . TABLE_PREFIX . "users.lname AS attendant_lname,"
            . TABLE_PREFIX . "water_sources.water_source_id"
            . " FROM " . TABLE_PREFIX . "sales "
            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
// . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by WHERE submitted_to_treasurer=1 AND status=0 GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date DESC"
            . "";

//var_dump($query);
    $result = $dbhandle->RunQueryForResults($query);
    if (isset($result->num_rows) && $result->num_rows > 0) {
        while ($sale = $result->fetch_assoc()) {
            if (!empty($sale['idu'])) {
                $sales[] = $sale;
            }
        }
    }
    return count($sales);
}

function calculateTotalWaterSourceTransactions($water_source_id) {
    global $dbhandle;
    $transactions = 0;
    $query = "SELECT COUNT(id_sale) AS transactions FROM " . TABLE_PREFIX . "sales WHERE " . TABLE_PREFIX . "sales.water_source_id=$water_source_id AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";

    $result = $dbhandle->RunQueryForResults($query);
    if (isset($result->num_rows) && $result->num_rows > 0) {
        while ($sale = $result->fetch_assoc()) {
            if (!empty($sale['transactions'])) {
                $transactions = $sale['transactions'];
            }
        }
    }
    return $transactions;
}

function calculateTotalWaterUsersFromWaterSource($water_source_id) {
    global $dbhandle;
    $water_users = 0;
    $query = "SELECT COUNT(id_user) AS water_users FROM " . TABLE_PREFIX . "water_source_caretakers "
            . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.added_by=" . TABLE_PREFIX . "water_source_caretakers.uid WHERE " . TABLE_PREFIX . "water_source_caretakers.water_source_id=$water_source_id AND " . TABLE_PREFIX . "water_users.marked_for_delete=0";
    $result = $dbhandle->RunQueryForResults($query);
    if (isset($result->num_rows) && $result->num_rows > 0) {
        while ($sale = $result->fetch_assoc()) {
            if (!empty($sale['water_users'])) {
                $water_users = $sale['water_users'];
            }
        }
    }
    return $water_users;
}

function calculateTotalSavingsFromWaterSource($src_id) {
    global $dbhandle;
    $total_savings = 0;
    $total_expenses = 0;
    /* $query = "SELECT id_sale,sale_date,time_stamp,SUM(inflow) AS total_savings FROM (SELECT *,EXTRACT(MONTH FROM sale_date) as month, 
      EXTRACT(YEAR FROM sale_date) as year, sale_date AS time_stamp, (floor((sale_ugx) * (percentage_saved / 100) / 100) * 100) AS inflow FROM " . TABLE_PREFIX . "sales WHERE submitted_to_treasurer=1 AND status=1) AS T";
     */

    $query = "SELECT CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "sales WHERE " . TABLE_PREFIX . "sales.water_source_id=$src_id AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
    $squery = "SELECT SUM(savings) AS savings FROM ($query) AS derived";
    //echo $squery;
    $result = $dbhandle->RunQueryForResults($squery);
    while ($sale = $result->fetch_assoc()) {
        $total_savings = $sale['savings'];
    }



    $query = "SELECT SUM(expenditure_cost) AS total_expenses FROM " . TABLE_PREFIX . "expenditures WHERE water_source_id=$src_id";
    $result = $dbhandle->RunQueryForResults($query);

    while ($sale = $result->fetch_assoc()) {
        if (!empty($sale['total_expenses'])) {
            $total_expenses = $sale['total_expenses'];
        }
    }

    return $total_savings - $total_expenses;
}

function the_full_size_map() {
//var_dump($_SESSION);
    ?>
    <div class="row">
        <div class="col-md-12">
            <h3>All Water Source locations</h3>
        </div>
    </div>   
    <div class="row">     
        <div class="col-md-12">
            <div class="graph-container">
                <div id="map-canvas"></div>
            </div> 
        </div>
    </div>
    <?php
}

function the_dashboard() {
    global $USER, $dbhandle;
//var_dump($USER);

    $water_sources = array();
    $water_source_names = array();
    $water_source_locations = array();
    $total_sales = 0;
    $inflow = 0;
    $outflow = 0;
    $account_balance = 0;
    $total_users = 0;

    if ($USER->can_approve_treasurers_submissions) {
        //This is District Water Officer
        //Query to summarise from water sources
    } elseif ($USER->can_approve_attendants_submissions) {
        //This is the Water Board Treasurer
        //Query to summarise from attendatnts
    } elseif ($USER->can_submit_attendant_daily_sales) {
        //This is the Water User Committee Treasurer        
    } else {
        //This is the attendant

        $query = "SELECT " . TABLE_PREFIX . "water_sources.water_source_name,water_source_location FROM water_source_caretakers,water_sources WHERE uid=" . $USER->idu . " AND id_water_source=water_source_caretakers.water_source_id";
        //echo $query;
        $results = $dbhandle->RunQueryForResults($query);
        while ($row = $results->fetch_assoc()) {
            $water_sources[] = $row;
            $water_source_names[] = $row['water_source_name'];
            $water_source_locations[] = $row['water_source_location'];
        }

        //CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings
        $query = "SELECT COUNT(" . TABLE_PREFIX . "sales.id_sale) AS total_sales FROM sales WHERE sold_by=" . $USER->idu . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
        //echo $query;
        $results = $dbhandle->RunQueryForResults($query);
        while ($row = $results->fetch_assoc()) {
            $total_sales = $row['total_sales'];
        }

        $query = "SELECT CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS inflow FROM sales WHERE sold_by=" . $USER->idu . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
        $squery = "SELECT SUM(inflow) AS inflow FROM ($query) AS derived";
        //echo $squery;
        $result = $dbhandle->RunQueryForResults($squery);
        while ($sale = $result->fetch_assoc()) {
            $inflow = $sale['inflow'];
        }

        $query = "SELECT SUM(expenditure_cost) AS outflow FROM expenditures WHERE logged_by=" . $USER->idu . "";

        $result = $dbhandle->RunQueryForResults($query);
        while ($expenditure = $result->fetch_assoc()) {
            $outflow = $expenditure['outflow'];
        }
        $account_balance = $inflow - $outflow;

        $query = "SELECT COUNT(" . TABLE_PREFIX . "water_users.id_user) AS total_users FROM water_users WHERE added_by=" . $USER->idu;
        //echo $query;
        $results = $dbhandle->RunQueryForResults($query);
        while ($row = $results->fetch_assoc()) {
            $total_users = $row['total_users'];
        }
    }


    //var_dump($water_sources);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="row-title">Dashboard</h3>
                </div>
                <div class="panel-body">
                    <div class="row">               
                        <div class="col-md-12">
                            <h4>Account Name: <?php echo $USER->fname . " " . $USER->lname; ?></h4>
                            <h4>User Role: <?php echo $USER->group_name; ?></h4>
                            <?php if (!empty($water_source_names)) { ?>
                                <h4>Water Source: <?php echo implode(', ', $water_source_names); ?></h4>
                            <?php } ?>
                            <?php if (!empty($water_source_locations)) { ?>
                                <h4>Water Source Location: <?php echo implode(', ', $water_source_locations); ?></h4>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (!empty($water_source_names)) { ?>
                        <div class="row">     
                            <?php if ($USER->can_view_water_users) { ?>
                                <div class="col-md-4">
                                    <div class="dashboard-stat purple">
                                        <div class="visual">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </div>
                                        <div class="details">
                                            <div class="number">
                                                <?php echo number_format($total_users, 0, '.', ','); ?>
                                            </div>
                                            <div class="desc">
                                                Monthly Billed Water Users
                                            </div>
                                        </div>
                                        <a class="more" href="?a=water-users">
                                            View more <i class="m-icon-swapright m-icon-white"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php } if ($USER->can_view_sales) { ?>
                                <div class="col-md-4">
                                    <div class="dashboard-stat blue">
                                        <div class="visual">
                                            <i class="glyphicon glyphicon-transfer"></i>
                                        </div>
                                        <div class="details">
                                            <div class="number">
                                                <?php echo number_format($total_sales, 0, '.', ','); ?>
                                            </div>
                                            <div class="desc">
                                                Verified transactions
                                            </div>
                                        </div>
                                        <a class="more" href="?a=sales">
                                            View more <i class="m-icon-swapright m-icon-white"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php } if ($USER->can_view_personal_savings) { ?>
                                <div class="col-md-4">
                                    <div class="dashboard-stat yellow">
                                        <div class="visual">
                                            <i class="glyphicon glyphicon-usd"></i>
                                        </div>
                                        <div class="details">
                                            <div class="number">
                                                <?php echo number_format($account_balance, 0, '.', ',');
                                                ?>
                                            </div>
                                            <div class="desc">
                                                Total Savings
                                            </div>
                                        </div>
                                        <a class="more" href="?a=user-statement">
                                            View more <i class="m-icon-swapright m-icon-white"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


    <!--div class="row">      
        <div class="col-md-6">
            <div class="graph-container">
                <div id="placeholder" class="map-placeholder"></div>
            </div>  
            <div class="row">
                <div class="col-md-12">
                    <a href="?a=statistics" class="btn btn-success pull-right">Statistics</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="graph-container">
                        <div id="map-canvas"></div>
                    </div>  
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <a href="?a=water-sources-map" class="btn btn-success pull-right">Water Sources</a>
                </div>
            </div>
        </div>
    </div-->
    <?php
}

function the_you_are_lost() {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">the 404</h3>
        </div>
        <div class="panel-body">
            <div class="row">               
                <div class="col-md-4 col-md-offset-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert">
                                <h1>Uhmmm.....you are lost...</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function the_access_denied() {
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">the 403</h3>
        </div>
        <div class="panel-body">
            <div class="row">               
                <div class="col-md-5 col-md-offset-4 text-center text-capitalize text-danger">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="alert">
                                <h1> <i class="glyphicon glyphicon-lock"></i></h1>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="alert">
                                <h3> Access Denied</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
