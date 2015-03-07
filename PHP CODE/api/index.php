<?php

require '../config.php';
define('API_VER', '1.0');
$request_status = 0; // '0' for failed '1' for successful '2' for pending
$server_info = array(
    'site_name' => SYSTEM_NAME,
    'api_ver' => API_VER,
    'server_status' => SYSTEM_STATUS// '0' for off '1' for on '2' for upgrades
);

//$_POST['username'] = 'sukavi';
//$_POST['request_hash'] = 'fa6977c99b809db68e1c56888ec38bd004719b39';
//$_POST['uid'] = 12;

switch ($action) {
    case 'login':
        $email = getArrayVal($_POST, 'username');
        $password = sha1(getArrayVal($_POST, 'password'));
        $query = "SELECT idu,group_id,username,pnumber,email,fname,lname,last_login,active FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')"
                . " AND password='$password'";
        $result = $dbhandle->RunQueryForResults($query);
        while ($row = $result->fetch_assoc()) {
            $account = $row;
        }

        if (!empty($account) && isset($account['idu'])) {

            if ($account['active'] == 1) {
                $user_group = $dbhandle->Fetch("user_groups", "*", array('id_group' => $account['group_id']));
                if (!empty($user_group)) {

                    if ($user_group['group_is_enabled'] == 1) {

                        if ($user_group['can_access_app'] == 1) {

                            foreach ($user_group as $key => $value) {
                                if ($key !== 'id_group' && $key !== 'group_name') {
                                    unset($user_group[$key]);
                                    $user_group[strtoupper($key)] = $value == 1 ? true : false;
                                } else {
                                    $user_group[strtoupper($key)] = $value;
                                }
                            }

                            unset($account['password']);
                            $account['request_hash'] = $password;
                            $account = array_merge($account, $user_group);

                            $request_status = 1;
                            $data['action'] = $action;
                            $data['account'] = $account;
                            $data['msgs'] = array('Login successful.');

                            $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));
                        } else {
                            $data['msgs'] = array("You cannot be logged in by the use of the app. Please consult your administrator for further advice.");
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
        } else {
            $data['msgs'] = array('Wrong username or password.');
        }
        break;
    case 'resume-session':
        $email = getArrayVal($_POST, 'username');
        $password = getArrayVal($_POST, 'request_hash');
        $query = "SELECT idu,group_id,username,pnumber,email,fname,lname,last_login,active FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')"
                . " AND password='$password'";
        $result = $dbhandle->RunQueryForResults($query);
        $account = $result->fetch_assoc();

        if (!empty($account) && isset($account['idu'])) {

            if ($account['active'] == 1) {
                $user_group = $dbhandle->Fetch("user_groups", "*", array('id_group' => $account['group_id']));
                if (!empty($user_group)) {

                    if ($user_group['group_is_enabled'] == 1) {

                        if ($user_group['can_access_app'] == 1) {

                            foreach ($user_group as $key => $value) {
                                if ($key !== 'id_group' && $key !== 'group_name') {
                                    unset($user_group[$key]);
                                    $user_group[strtoupper($key)] = $value == 1 ? true : false;
                                } else {
                                    $user_group[strtoupper($key)] = $value;
                                }
                            }

                            unset($account['active']);
                            $account['request_hash'] = $password;
                            $account = array_merge($account, $user_group);

                            $request_status = 1;
                            $data['action'] = $action;
                            $data['account'] = $account;
                            $data['msgs'] = array('Login successful.');


                            $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));
                        } else {
                            $data['msgs'] = array("You cannot be logged in by the use of the app. Please consult your administrator for further advice.");
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
        } else {
            $data['msgs'] = array('Your session has ended. Please log in again.');
        }
        break;
    case 'recover-password':
        $email = getArrayVal($_POST, 'username');
        $query = "SELECT idu,pnumber,email FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')";
        $result = $dbhandle->RunQueryForResults($query);
        $account = $result->fetch_assoc();
        if (!empty($account) && isset($account['idu'])) {
            if (appUserIsLoggedIn()) {
                $data['msgs'] = array('You need to log out first');
            } else {
                $password = generateAlphaNumCode(6);
                if ($dbhandle->Update('users', array('password' => sha1($password)), array('idu' => $account['idu']))) {
                    $request_status = 1;
                    if (isset($account['email'])) {
                        $message = "Your " . SYSTEM_NAME . " password has been reset. <br/> Your new password is <strong>$password<strong>";
                        send_email($account['email'], 'Your ' . SYSTEM_NAME . ' password has been reset', $message);
                    }

                    $message = "Your " . SYSTEM_NAME . " password has been reset. Your new password is $password";

                    if (!empty($account['pnumber'])) {
                        send_sms_message($account['pnumber'], $message);
                    }

                    $data['msgs'] = array("Your password has been reset. Please check your email or SMS inbox for instructions.");
                } else {
                    $data['msgs'] = array("An error occured resetting your password. Please try agin later. If this persists please consult your administrator.");
                }
            }
        } else {
            $data['msgs'] = array("Your password could not be reset. No account exists with those details");
        }

        break;
    case "register-device":
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $uid = getArrayVal($_POST, "uid");
            $gcm_regid = getArrayVal($_POST, "gcm_regid");
            if (!empty($gcm_regid)) {
                if ($dbhandle->Update('users', array('gcm_regid' => $gcm_regid), array('idu' => $uid))) {
                    $request_status = 1;
                    $data['msgs'] = array('Device successfully added');
                } else {
                    $data['msgs'] = array('Error from server when registering device');
                }
            } else {
                $data['msgs'] = array('GCM Reg ID is required');
            }
        }
        break;
    case "fetch-water-users":
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $request_status = 1;
            $water_users = array();
            $uid = getArrayVal($_POST, "uid");

            $query = "SELECT id_user,fname,lname FROM " . TABLE_PREFIX . "water_users WHERE added_by=$uid AND marked_for_delete=0 ORDER BY fname";

            $result = $dbhandle->RunQueryForResults($query);
            if (isset($result->num_rows) && $result->num_rows > 0) {
                while ($customer = $result->fetch_assoc()) {
                    if (!empty($customer['id_user'])) {
                        $water_users[] = $customer;
                    }
                }
            }
            $data['water_users'] = $water_users;
            $data['msgs'] = array('Request successful.');
        }
        break;
    case 'register-water-user':
        if (ENABLE_WATER_USER_REGISTRATIONS === 1) {
            if (!appUserIsLoggedIn()) {
                $data['msgs'] = array('Login is required. Please close the application then start it again.');
            } else {
                $params['fname'] = ucwords(getArrayVal($_POST, 'fname'));
                $params['lname'] = ucwords(getArrayVal($_POST, 'lname'));
                $params['pnumber'] = getArrayVal($_POST, 'pnumber');
                $params['date_added'] = getCurrentDate();
                $params['added_by'] = getArrayVal($_POST, 'uid');

                if (empty($params['fname'])) {
                    $errors[] = "First name is required.";
                }
                if (empty($params['lname'])) {
                    $errors[] = "Last name is required.";
                }

                if (!empty($params['pnumber']) && isTaken('user_pnumber', $params['pnumber'])) {
                    $errors[] = "That phone number is already in use.";
                }

                if (!isset($errors)) {
                    $uid = $dbhandle->Insert('water_users', $params);
                    if (is_int($uid)) {
                        $request_status = 1;
                        $data['msgs'] = array('User added.');
                    } else {
                        $data['msgs'] = array('An error occured adding the user. Please try again later.');
                    }
                } else {
                    foreach ($errors as $error) {
                        $data['msgs'][] = $error;
                    }
                }
            }
        } else {
            $data['msgs'] = array('Water User registrations have been disabled for now.');
        }
        break;
    case 'fetch-water-user':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $water_user = array();
            $id_user = getArrayVal($_POST, 'id_user');
            $w_u = $dbhandle->Fetch("water_users", "id_user,fname,lname,pnumber", array('id_user' => $id_user, 'marked_for_delete' => 0));
            if (!empty($w_u) && isset($w_u['id_user'])) {
                $water_user = $w_u;
                $request_status = 1;
                $data['msgs'] = array('Request successful.');
            } else {
                $data['msgs'] = array('That user account does not exist');
            }
            $data['water_user'] = $water_user;
        }
        break;
    case 'update-water-user':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $id_user = getArrayVal($_POST, 'id_user');
            $query = "SELECT * FROM " . TABLE_PREFIX . "water_users WHERE id_user=$id_user";
            $result = $dbhandle->RunQueryForResults($query);
            $account = $result->fetch_assoc();
            if (isset($account['id_user'])) {

                $params['fname'] = getArrayVal($_POST, 'fname');
                $params['lname'] = getArrayVal($_POST, 'lname');
                $params['pnumber'] = getArrayVal($_POST, 'pnumber');

                if (empty($params['fname'])) {
                    $errors[] = "First name is required";
                }
                if (empty($params['lname'])) {
                    $errors[] = "Last name is required";
                }

                if (!empty($params['pnumber'])) {
                    $params['pnumber'] = autoCorrectPnumber($params['pnumber']);
                }

                if (!empty($params['pnumber']) && (isTaken('user_pnumber', $params['pnumber'])) && $account['pnumber'] !== $params['pnumber']) {
                    $errors[] = "That phone number is already in use";
                } elseif (!empty($params['pnumber']) && !isValid('pnumber', $params['pnumber'])) {
                    $errors[] = "Please use a valid phone number";
                }

                if (!isset($errors)) {
                    if ($dbhandle->Update('water_users', $params, array('id_user' => $id_user))) {
                        $request_status = 1;
                        $data['msgs'] = array('User Updated.');
                    } else {
                        $data['msgs'] = array('An error occured updating the account. Please try again later.');
                    }
                } else {
                    foreach ($errors as $error) {
                        $data['msgs'][] = $error;
                    }
                }
            } else {
                $data['msgs'] = array("That water user does not exist", 0);
            }
        }
        break;
    case 'mark-water-user-for-delete':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $id_user = getArrayVal($_POST, 'id_user');
            if ($dbhandle->Update('water_users', array('marked_for_delete' => 1), array('id_user' => $id_user))) {
                $request_status = 1;
                $data['msgs'] = array('User deleted.');
            } else {
                $data['msgs'] = array('An error occured deleting the user. Please try again later.');
            }
        }

        break;
    case 'fetch-water-sources':

        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $request_status = 1;
            $uid = getArrayVal($_POST, 'uid');
            $water_sources = array();
            $query = "SELECT id_water_source,water_source_name,monthly_charges FROM " . TABLE_PREFIX . "water_source_caretakers,water_sources WHERE water_source_caretakers.water_source_id=water_sources.id_water_source AND uid=$uid";
            //echo $query;
            $result = $dbhandle->RunQueryForResults($query);
            while ($row = $result->fetch_assoc()) {
                $water_sources[] = $row;
            }
            $data['per_month_charges'] = 0;
            $data['water_sources'] = $water_sources;
            $data['msgs'] = array('Request successful.');
        }
        break;
    case 'fetch-all-water-sources':

        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $uid = getArrayVal($_POST, 'uid');
            $water_sources = array();
            $query = "SELECT id_water_source,water_source_name,monthly_charges FROM " . TABLE_PREFIX . "water_source_caretakers," . TABLE_PREFIX . "water_source_treasurers,water_sources WHERE water_source_caretakers.water_source_id=water_sources.id_water_source AND water_source_treasurers.water_source_id=water_sources.id_water_source AND water_source_treasurers.uid=$uid AND water_source_caretakers.uid=$uid GROUP BY id_water_source ORDER BY water_source_name";
            //echo $query;
            $result = $dbhandle->RunQueryForResults($query);
            while ($row = $result->fetch_assoc()) {
                $water_sources[] = $row;
            }
            $request_status = 1;
            $data['per_month_charges'] = 0;
            $data['water_sources'] = $water_sources;
            $data['msgs'] = array('Request successful.');
        }
        break;
    case 'fetch-water-sources-data':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $src_id = getArrayVal($_POST, 'id_water_source');
            $water_source_data = array();
            $query = "SELECT * FROM water_sources WHERE id_water_source=$src_id ORDER BY id_water_source ASC LIMIT 1";

            $result = $dbhandle->RunQueryForResults($query);
            while ($sale = $result->fetch_assoc()) {
                $water_source_data = $sale;
            }
            if (!empty($water_source_data)) {

                $data['water_source_name'] = $water_source_data['water_source_name'];
                $data['water_source_location'] = $water_source_data['water_source_location'];
                $data['count_total_water_users'] = number_format(calculateTotalWaterUsersFromWaterSource($src_id), 0, '.', ',');
                $data['count_total_tansactions'] = number_format(calculateTotalWaterSourceTransactions($src_id), 0, '.', ',');
                $data['count_total_savings'] = number_format(calculateTotalSavingsFromWaterSource($src_id), 2, '.', ',');

                $data['msgs'] = array('Request successful.');
                $request_status = 1;
            } else {

                $data['msgs'] = array('Water source does not exist.');
            }
        }
        break;
    case"add-sale":
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
            $params['sold_by'] = getArrayVal($_POST, 'uid');
            $params['sold_to'] = getArrayVal($_POST, 'sold_to');
            $params['sale_ugx'] = floatval(getArrayVal($_POST, 'sale_ugx'));
            $params['sale_date'] = getArrayVal($_POST, 'sale_date');

            if (empty($params['sale_ugx'])) {
                $errors[] = "A sale must have a cost and the cost cannot be zero (\"0\") or less than zero (\"0\")";
            } elseif ($params['sale_ugx'] < 0) {
                $errors[] = "A transaction must have a cost and the cost cannot be zero (\"0\") or less than zero (\"0\")";
            }

            if (intval($params['sold_to']) === 0) {
                $params['sale_date'] = getCurrentDate();
            } else {
                
            }

            if (!empty($params['sale_date'])) {
                $params['sale_date'] = getCurrentDate(getArrayVal($_POST, 'sale_date'));
            } else {
                $params['sale_date'] = getCurrentDate();
            }

            if (strtotime($params['sale_date']) > strtotime(getCurrentDate())) {
                $errors[] = "You can only enter the current date or a past date. Future dates are not supported yet";
            }

            if (!isset($errors)) {
                $water_source_data = $dbhandle->Fetch("water_sources", "*", array('id_water_source' => $params['water_source_id']), null, true, 1);
                if (!empty($water_source_data)) {
                    if ($dbhandle->CheckIFExists("water_source_caretakers", array('water_source_id' => $water_source_data['id_water_source'],
                                'uid' => getArrayVal($_POST, 'uid')))) {
                        $params['water_source_id'] = $water_source_data['id_water_source'];
                        $params['percentage_saved'] = $water_source_data['percentage_saved'];
                        $id_sale = $dbhandle->Insert('sales', $params);
                        if (is_int($id_sale)) {
                            $request_status = 1;
                            $data['msgs'] = array('Sale added.');
                        } else {
                            $data['msgs'] = array('An error occured adding the sale. Please try again later.');
                        }
                    } else {
                        $data['msgs'] = array('You are not authorised to add users for this water source. If you feel this is an error, please consult your administrator.');
                    }
                } else {
                    $data['msgs'] = array('That water source does not exist. If this persists, plese consult your administrator.');
                }
            } else {
                foreach ($errors as $error) {
                    $data['msgs'][] = $error;
                }
            }
        }

        break;
    case"follow-up":

        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            // $query = "SELECT id_user,fname,lname,date_added,sale_date FROM " . TABLE_PREFIX . "water_users LEFT JOIN " . TABLE_PREFIX . "sales ON id_user=sold_to WHERE added_by=" . getArrayVal($_POST, 'uid') . " AND marked_for_delete=0 GROUP BY MONTH(sale_date) ORDER BY DATE(sale_date) ASC";

            $query = "SELECT id_user,fname,lname,date_added,sale_date FROM water_users LEFT JOIN sales ON id_user=sold_to WHERE added_by=" . $_SESSION['idu'] . " AND marked_for_delete=0 GROUP BY (CASE WHEN MONTH(sale_date) IS NULL THEN id_user ELSE MONTH(sale_date) END) ORDER BY DATE(sale_date) ASC";

            // echo $query;
            $result = $dbhandle->RunQueryForResults($query);

            $defaulters = array();

            while ($row = $result->fetch_assoc()) {
                //var_dump($row);

                $start_date = $row['date_added'];

                while (strtotime($start_date) < strtotime(getCurrentDate())) {
                    $this_month = date("Y-m", strtotime($start_date));
                    $sale_date = date("Y-m", strtotime($row['sale_date']));
                    if ((strtotime($this_month) !== strtotime($sale_date)) && (strtotime($sale_date) <= strtotime($this_month))) {
                        $defaulters[] = array(
                            'id_user' => $row['id_user'],
                            'name' => $row['fname'] . " " . $row['lname'],
                            'defaulted_month' => date("M Y", strtotime($this_month))
                        );
                    }
                    $start_date = date("Y-m-d H:i:s", strtotime("+30 day", strtotime($start_date))); //Monthly sale
                }
            }
            $data['defaulters'] = $defaulters;
            $request_status = 1;
            $data['msgs'] = array('Successful');
        }
        break;
    case 'send-sms-message':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $water_user = array();

            $id_user = getArrayVal($_POST, 'id_user');
            $msg_content = trim(getArrayVal($_POST, 'msg_content'));

            if (empty($msg_content)) {
                $errors[] = "You cannot send an empty message";
            }

            $w_u = $dbhandle->Fetch("water_users", "id_user,fname,lname,pnumber", array('id_user' => $id_user, 'marked_for_delete' => 0));
            if (!empty($w_u) && isset($w_u['id_user']) && !empty($w_u['pnumber'])) {
                $water_user = $w_u;
                $request_status = 1;
            } else {
                $errors[] = 'That water user has no phone number therefore it\'s not possible to send an sms';
            }
            if (!isset($errors)) {
                $params['message_content'] = $msg_content;
                $params['system_users'] = '';
                $params['water_users'] = $id_user;
                $params['date_sent'] = getCurrentDate();
                $params['sent_by'] = getArrayVal($_POST, 'uid');
                $params['sent'] = 0;
                if (ENABLE_SMS == 1) {
                    if (send_sms_message(implode(',', $recepients), $msg_content)) {
                        $params['sent'] = 1;
                    } else {
                        $params['sent'] = 0;
                    }
                } else {
                    $errors[] = "SMS sending has been disabled. However your SMS message has been saved. The administrator will resend the messages later.";
                }

                $id_sms = $dbhandle->Insert('sms_messages', $params);
                if (is_int($id_sms)) {
                    if ($params['sent'] == 1) {
                        $data['msgs'] = array('SMS sent and logged.');
                    } else {
                        $data['msgs'] = array('SMS not sent. However your SMS message has been saved. The administrator will resend the messages later.');
                    }
                } else {
                    $errors[] = "SMS message not been sent.";
                }
            } else {
                foreach ($errors as $error) {
                    $data['msgs'][] = $error;
                }
            }
        }

        break;
    case'report-water-user':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $id_user = getArrayVal($_POST, 'id_user');
            $data['id_user'] = $id_user;
            if ($dbhandle->Update('water_users', array('reported_defaulter' => 1), array('id_user' => $id_user))) {
                $request_status = 1;
                $data['msgs'] = array('User reported.');
            } else {
                $data['msgs'] = array('An error occured reporting the user. Please try again later.');
            }
            $data['defaulters'] = array(); //don't remove this line unless you have altered the code in the app
        }
        break;
    case 'attendants-submissions':

        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $sales = array();
            $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_treasurers "
                    . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_treasurers.water_source_id "
                    . "LEFT JOIN " . TABLE_PREFIX . "water_source_caretakers ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                    . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                    . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                    . "WHERE  " . TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1 AND " . TABLE_PREFIX . "water_source_treasurers.uid=" . $_SESSION['idu'] . " "
                    . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date ASC";

            $result = $dbhandle->RunQueryForResults($query);
            while ($sale = $result->fetch_assoc()) {
                $sale['sale_date'] = date("d-M-Y", strtotime($sale['sale_date']));
                $sales[] = $sale;
            }

            $data['submissions'] = $sales;
            $request_status = 1;
            $data['msgs'] = array('Successful');
        }
        break;
    case 'submit-attendants-sales':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $timestamp = strtotime(getArrayVal($_POST, 't'));
            $water_source_id = getArrayVal($_POST, 'id');
            $sold_by = getArrayVal($_POST, 'idu');

            if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }

            if ($dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                $query = "UPDATE " . TABLE_PREFIX . "sales SET " . TABLE_PREFIX . "sales.submitted_to_treasurer=1, " . TABLE_PREFIX . "sales.submitted_by=" . $_SESSION['idu'] . ", " . TABLE_PREFIX . "sales.submittion_to_treasurer_date='" . getCurrentDate() . "', " . TABLE_PREFIX . "sales.treasurerer_approval_status=0 WHERE " . TABLE_PREFIX . "sales.sold_by=$sold_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND " . TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1";

                if ($dbhandle->RunQueryForResults($query)) {
                    $request_status = 1;
                    $data['msgs'] = array('Your request has been received and is awaiting approval. The savings submited are now pending. ');
                } else {
                    $errors[] = "An error occured making your request. Please try again later.";
                }
            } else {
                $errors[] = "An error occured making your request. $water_source_id Please try again later.";
            }

            if (isset($errors)) {
                foreach ($errors as $error) {
                    $data['msgs'][] = $error;
                }
            }
            $data['submissions'] = array(); //don't remove this line unless you have altered the code in the app
        }
        break;
    case 'cancel-attendants-sales':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $timestamp = strtotime(getArrayVal($_POST, 't'));
            $water_source_id = getArrayVal($_POST, 'id');
            $sold_by = getArrayVal($_POST, 'idu');

            if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }

            if ($dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                $query = "UPDATE " . TABLE_PREFIX . "sales SET " . TABLE_PREFIX . "sales.submitted_to_treasurer=0, " . TABLE_PREFIX . "sales.submitted_by=" . $_SESSION['idu'] . ", " . TABLE_PREFIX . "sales.submittion_to_treasurer_date='" . getCurrentDate() . "', " . TABLE_PREFIX . "sales.treasurerer_approval_status=0 WHERE " . TABLE_PREFIX . "sales.sold_by=$sold_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND " . TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1";

                if ($dbhandle->RunQueryForResults($query)) {
                    $request_status = 1;
                    $data['msgs'] = array('Your request has been received and is awaiting approval. The savings submited are now pending. ');
                } else {
                    $errors[] = "An error occured making your request. Please try again later.";
                }
            } else {
                $errors[] = "An error occured making your request. $water_source_id Please try again later.";
            }

            if (isset($errors)) {
                foreach ($errors as $error) {
                    $data['msgs'][] = $error;
                }
            }
            $data['submissions'] = array(); //don't remove this line unless you have altered the code in the app
        }
        break;
    case 'treasurers-submissions':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $sales = array();

            $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_treasurers "
                    . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_treasurers.water_source_id "
                    . "LEFT JOIN " . TABLE_PREFIX . "water_source_caretakers ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                    . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                    . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                    . "WHERE  " . TABLE_PREFIX . "sales.submitted_to_treasurer=1 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1 AND " . TABLE_PREFIX . "water_source_treasurers.uid=" . $_SESSION['idu'] . " "
                    . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date ASC";


            $result = $dbhandle->RunQueryForResults($query);
            while ($sale = $result->fetch_assoc()) {
                $sale['sale_date'] = date("d-M-Y", strtotime($sale['sale_date']));
                $sales[] = $sale;
            }

            $data['submissions'] = $sales;
            $request_status = 1;
            $data['msgs'] = array('Successful');
        }
        break;

    case 'submit-treasurers-sales':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $timestamp = strtotime(getArrayVal($_POST, 't'));
            $water_source_id = getArrayVal($_POST, 'id');
            $sold_by = getArrayVal($_POST, 'idu');

            if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }

            if ($dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                //$query = "UPDATE " . TABLE_PREFIX . "sales SET " . TABLE_PREFIX . "sales.submitted_to_treasurer=1, " . TABLE_PREFIX . "sales.submitted_by=" . $_SESSION['idu'] . ", " . TABLE_PREFIX . "sales.submittion_to_treasurer_date='" . getCurrentDate() . "', " . TABLE_PREFIX . "sales.treasurerer_approval_status=0 WHERE " . TABLE_PREFIX . "sales.sold_by=$sold_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND " . TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1";
                $query = "UPDATE " . TABLE_PREFIX . "sales SET " . TABLE_PREFIX . "sales.submitted_to_treasurer=1, " . TABLE_PREFIX . "sales.treasurerer_approval_status=1, " . TABLE_PREFIX . "sales.reviewed_by=" . $_SESSION['idu'] . "," . TABLE_PREFIX . "sales.date_reviewed='" . getCurrentDate() . "' WHERE " . TABLE_PREFIX . "sales.submitted_by=$sold_by AND MONTH(" . TABLE_PREFIX . "sales.sale_date)=$month AND YEAR(" . TABLE_PREFIX . "sales.sale_date)=$year AND DAY(" . TABLE_PREFIX . "sales.sale_date)=$day AND " . TABLE_PREFIX . "sales.submitted_to_treasurer=1 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status=0";
                if ($dbhandle->RunQueryForResults($query)) {
                    $request_status = 1;
                    $data['msgs'] = array('Your request has been received and is awaiting approval. The savings submited are now pending. ');
                } else {
                    $errors[] = "An error occured making your request. Please try again later.";
                }
            } else {
                $errors[] = "An error occured making your request. Please try again later.";
            }

            if (isset($errors)) {
                foreach ($errors as $error) {
                    $data['msgs'][] = $error;
                }
            }
            $data['submissions'] = array(); //don't remove this line unless you have altered the code in the app
        }
        break;
    case 'cancel-treasurers-sales':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $timestamp = strtotime(getArrayVal($_POST, 't'));
            $water_source_id = getArrayVal($_POST, 'id');
            $sold_by = getArrayVal($_POST, 'idu');

            if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }

            if ($dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                //$query = "UPDATE " . TABLE_PREFIX . "sales SET " . TABLE_PREFIX . "sales.submitted_to_treasurer=1, " . TABLE_PREFIX . "sales.submitted_by=" . $_SESSION['idu'] . ", " . TABLE_PREFIX . "sales.submittion_to_treasurer_date='" . getCurrentDate() . "', " . TABLE_PREFIX . "sales.treasurerer_approval_status=0 WHERE " . TABLE_PREFIX . "sales.sold_by=$sold_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND " . TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1";
                $query = "UPDATE " . TABLE_PREFIX . "sales SET " . TABLE_PREFIX . "sales.submitted_to_treasurer=0, " . TABLE_PREFIX . "sales.treasurerer_approval_status=0, " . TABLE_PREFIX . "sales.reviewed_by=" . $_SESSION['idu'] . "," . TABLE_PREFIX . "sales.date_reviewed='" . getCurrentDate() . "' WHERE " . TABLE_PREFIX . "sales.submitted_by=$sold_by AND MONTH(" . TABLE_PREFIX . "sales.sale_date)=$month AND YEAR(" . TABLE_PREFIX . "sales.sale_date)=$year AND DAY(" . TABLE_PREFIX . "sales.sale_date)=$day AND " . TABLE_PREFIX . "sales.submitted_to_treasurer=1 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status=0";
                if ($dbhandle->RunQueryForResults($query)) {
                    $request_status = 1;
                    $data['msgs'] = array('Your request has been received and is awaiting approval. The savings submited are now pending. ');
                } else {
                    $errors[] = "An error occured making your request. Please try again later.";
                }
            } else {
                $errors[] = "An error occured making your request. Please try again later.";
            }

            if (isset($errors)) {
                foreach ($errors as $error) {
                    $data['msgs'][] = $error;
                }
            }
            $data['submissions'] = array(); //don't remove this line unless you have altered the code in the app
        }
        break;
    case 'fetch-expenses-data':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {


            $uid = getArrayVal($_POST, 'uid');
            $water_sources = array();
            $query = "SELECT id_water_source,water_source_name,monthly_charges FROM " . TABLE_PREFIX . "water_source_caretakers,water_sources WHERE " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source AND uid=$uid";
            //echo $query;
            $result = $dbhandle->RunQueryForResults($query);
            while ($row = $result->fetch_assoc()) {
                $water_sources[] = $row;
            }
            $data['water_sources'] = $water_sources;

            $repair_types = array();
            $query = "SELECT id_repair_type,repair_type FROM " . TABLE_PREFIX . "repair_types";
            //echo $query;
            $result = $dbhandle->RunQueryForResults($query);
            while ($row = $result->fetch_assoc()) {
                $repair_types[] = $row;
            }

            $repair_types[] = array('id_repair_type' => 0, 'repair_type' => 'Other');
            $data['repair_types'] = $repair_types;
            $request_status = 1;
            $data['msgs'] = array('Request successful.');
        }
        break;
    case 'add-expense':
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
            $params['repair_type_id'] = getArrayVal($_POST, 'repair_type_id');
            $params['expenditure_date'] = getCurrentDate();
            $params['expenditure_cost'] = getArrayVal($_POST, 'expenditure_cost');
            $params['benefactor'] = getArrayVal($_POST, 'benefactor');
            $params['description'] = getArrayVal($_POST, 'description');
            $params['logged_by'] = $_SESSION['idu'];
            $params['date_logged'] = getCurrentDate();

            if (empty($params['water_source_id'])) {
                $errors[] = "Please select a water source";
            }

            if (!is_numeric($params['repair_type_id'])) {
                $errors[] = "Please select a repair type";
            }

            if (empty($params['expenditure_cost'])) {
                $errors[] = "Repair cost must be anumber and not zero (0)";
            }
            if (empty($params['benefactor'])) {
                $errors[] = "The benefactors name is required.";
            }

            if (empty($params['description'])) {
                $errors[] = "Please describe the expenditure";
            }

            if (!isset($errors)) {

                $id_expenditure = $dbhandle->Insert('expenditures', $params);
                if (is_int($id_expenditure)) {
                    $request_status = 1;
                    $data['msgs'] = array('Expenditure added');
                } else {
                    $errors[] = "An error occured adding the expenditure. Please try again later";
                }
            } else {
                if (isset($errors)) {
                    foreach ($errors as $error) {
                        $data['msgs'][] = $error;
                    }
                }
            }
        }

        break;
    case "fetch-account-balance":
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {

            $uid = getArrayVal($_POST, "uid");

            $account_balance = 0;
            $inflow = 0;
            $outflow = 0;

            $query = "SELECT CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS inflow FROM sales WHERE sold_by=" . $uid . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
            $squery = "SELECT SUM(inflow) AS inflow FROM ($query) AS derived";
            //echo $squery;
            $result = $dbhandle->RunQueryForResults($squery);
            while ($sale = $result->fetch_assoc()) {
                $inflow = $sale['inflow'];
            }

            $query = "SELECT SUM(expenditure_cost) AS outflow FROM expenditures WHERE logged_by=" . $uid . "";

            $result = $dbhandle->RunQueryForResults($query);
            while ($expenditure = $result->fetch_assoc()) {
                $outflow = $expenditure['outflow'];
            }
            $account_balance = $inflow - $outflow;

            $request_status = 1;
            $data['account_name'] = $_SESSION['fname'] . " " . $_SESSION['lname'];
            $data['account_balance'] = number_format($account_balance, 2, '.', ',');
            $data['msgs'] = array('Transaction completed.');
        }
        break;
    case "fetch-mini-statement":
        if (!appUserIsLoggedIn()) {
            $data['msgs'] = array('Login is required. Please close the application then start it again.');
        } else {
            $uid = getArrayVal($_POST, "uid");
            $transactions = array();
            $sales = array();
            $query = "SELECT *,CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS savings FROM sales WHERE sold_by=" . $uid . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1 ORDER BY id_sale DESC LIMIT 5";
            $squery = "SELECT date_reviewed, SUM(savings) AS savings FROM ($query) AS derived GROUP BY DATE(sale_date)";
            //echo $squery;
            $result = $dbhandle->RunQueryForResults($squery);
            while ($sale = $result->fetch_assoc()) {
                $sales[] = array(
                    'type' => 'Deposit',
                    'beneficiary' => $_SESSION['fname'] . ' ' . $_SESSION['lname'],
                    'amount' => number_format($sale['savings'], 2, '.', ','),
                    'date' => getCurrentDate($sale['date_reviewed'], true, true),
                );
            }

            $expenditures = array();
            $query = "SELECT * FROM expenditures WHERE logged_by=" . $uid . " ORDER BY id_expenditure DESC LIMIT 5 ";

            $result = $dbhandle->RunQueryForResults($query);
            while ($expenditure = $result->fetch_assoc()) {
                $expenditures[] = array(
                    'type' => 'Expenditure',
                    'beneficiary' => $expenditure['benefactor'],
                    'amount' => number_format($expenditure['expenditure_cost'], 2, '.', ','),
                    'date' => getCurrentDate($expenditure['expenditure_date'], true, true),
                );
            }
            $transactions = array_merge($expenditures, $sales);
            $transactions = sortArray($transactions, "date");
            $transactions = array_slice($transactions, 0, 5);


            $data['transactions'] = $transactions;
            $data['msgs'] = array('Transaction completed.');
            $request_status = 1;
        }
        break;
    case 'check-update':

        $extensionsToDisplay = array(
            'apk'
        );

        $files = array();

        foreach (new DirectoryIterator(SITE_PATH . "/downloads") as $fileInfo) {
            if (!$fileInfo->isDot() && in_array(strtolower($fileInfo->getExtension()), $extensionsToDisplay)) {
                $part_1 = explode('-', $fileInfo->getFilename());
                $part_2 = explode('.', $part_1[1]);
                $files[] = array(
                    strtolower($fileInfo->getFilename()),
                    $fileInfo->getMTime(),
                    $part_2[0] . '.' . $part_2[1]
                );
            }
        }

        arsort($files);

        //var_dump($files);

        if (!empty($files)) {
            $counter = 0;
            foreach ($files as $key => $file) {
                if ($counter === 0) {
                    $updates = array(
                        array('version' => floatval($file[2]),
                            'url' => 'http://' . getArrayVal($_SERVER, 'SERVER_NAME') . "/downloads/" . $file[0],
                            'name' => $file[0], //always change the value, app does not overwrite instead deletes
                            'size' => filesize(SITE_PATH . "/downloads/" . $file[0])
                        ),
                    );
                }
                $counter+=1;
            }
        } else {
            $updates = array(
                array('version' => 0.0,
                    'url' => '',
                    'name' => '', //always change the value, app does not overwrite instead deletes
                    'size' => 0
                ),
            );
        }
        $data['updates'] = $updates;
        $request_status = 1;
        $data['msgs'] = array('Successful');
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

//header('Content-Type: application/json');
echo json_encode($server_reply);
