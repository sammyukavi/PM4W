<?php

require '../config.php';
define('API_VER', '1.0');
$request_status = 0; // '0' for failed '1' for successful '2' for pending
$server_info = array(
    'site_name' => SYSTEM_NAME,
    'api_ver' => API_VER,
    'server_status' => SYSTEM_STATUS// '0' for off '1' for on '2' for upgrades
);

//header("Content-Type: application/json");

switch ($action) {
    case 'login':
        $email = getArrayVal($_POST, 'username');
        $password = sha1(getArrayVal($_POST, 'password'));
        $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')"
                . " AND password='$password'";
        $result = $dbhandle->RunQueryForResults($query);
        $account = $result->fetch_assoc();

        if (!empty($account) && isset($account['idu'])) {

            if ($account['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {

                $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));

                $request_status = 1;
                unset($account['password']);
                $account['request_hash'] = $password;
                $data['action'] = $action;
                $data['account'] = $account;
                $data['msgs'] = array('Login successful.');
            }
        } else {
            $data['msgs'] = array('Wrong username or password.');
        }
        break;
    case 'recover-password':
        $email = getArrayVal($_POST, 'username');
        $query = "SELECT idu,pnumber,email FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')";
        $result = $dbhandle->RunQueryForResults($query);
        $account = $result->fetch_assoc();
        if (!empty($account) && isset($account['idu'])) {
            if ($account['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
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
    case 'resume-session':
        $email = getArrayVal($_POST, 'username');
        $password = getArrayVal($_POST, 'request_hash');
        $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')"
                . " AND password='$password'";
        $result = $dbhandle->RunQueryForResults($query);
        $account = $result->fetch_assoc();

        if (!empty($account) && isset($account['idu'])) {
            if ($account['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));

                $request_status = 1;
                unset($account['password']);
                $account['request_hash'] = $password;
                $data['action'] = $action;
                $data['account'] = $account;
                $data['msgs'] = array('Login successful.');
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case 'register-water-user':
        if (ENABLE_CUSTOMER_REGISTRATIONS === 1) {
            if (appUserIsLoggedIn()) {
                if ($_SESSION['group_id'] != 6) {
                    $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
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
                            $data['msgs'] = array('An error occured addin g the user. Please try again later.');
                        }
                    } else {
                        foreach ($errors as $error) {
                            $data['msgs'][] = $error;
                        }
                    }
                }
            } else {
                $data['msgs'] = array('Wrong username or request hash.');
            }
        } else {
            $data['msgs'] = array('Water User registrations have been disabled for now.');
        }
        break;
    case"add-sale":

        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                $params['sold_by'] = getArrayVal($_POST, 'uid');
                $params['sold_to'] = getArrayVal($_POST, 'sold_to');
                $params['sale_ugx'] = floatval(getArrayVal($_POST, 'sale_ugx'));

                if (empty($params['sale_ugx'])) {
                    $errors[] = "A sale must have a cost and the cost cannot be zero or less than zero \"0\"";
                } elseif ($params['sale_ugx'] < 0) {
                    $errors[] = "A transaction must have a cost and the cost cannot be zero or less than zero \"0\"";
                }

                if (isset($_POST['payment_type'])) {
                    if ($_POST['payment_type'] === "cash") {
                        $params['payment_cash'] = 1;
                    } elseif ($_POST['payment_type'] === "credit") {
                        $params['payment_cash'] = 0;
                    } else {
                        $data['msgs'] = array('Please select the payment mode.');
                    }
                } else {
                    $data ['msgs'] = array('Please select the payment mode.');
                }

                if (intval($params['sold_to']) === 0) {
                    $params['sale_date'] = getCurrentDate();
                } else {
                    $params['sale_date'] = getCurrentDate(getArrayVal($_POST, 'sale_date'));
                }

                if (!isset($errors)) {
                    $water_source_data = $dbhandle->Fetch("water_sources", "*", array('water_source_id' => $params['water_source_id']), null, true, 1);
                    if (!empty($water_source_data)) {
                        if ($dbhandle->CheckIFExists("water_source_caretakers", array('water_source_id' => $water_source_data['id_water_source'],
                                    'uid' => getArrayVal($_POST, 'uid')))) {
                            $params['water_source_id'] = $water_source_data['id_water_source'];
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
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }

        break;
    case 'fetch-user-water-sources':
        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $request_status = 1;
                $uid = getArrayVal($_POST, 'uid');
                $water_sources = array();
                $query = "SELECT " . TABLE_PREFIX . "water_sources.water_source_id FROM " . TABLE_PREFIX . "water_source_caretakers LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source WHERE uid=$uid";
                $result = $dbhandle->RunQueryForResults($query);
                if (!empty($result)) {
                    while ($row = $result->fetch_array()) {
                        $water_sources[] = $row['water_source_id'];
                    }
                }
                $data['water_sources'] = $water_sources;
                $data['msgs'] = array('Request successful.');
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }

        break;
    case "fetch-registered-water-users":
        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $request_status = 1;
                $water_users = array();
                $uid = getArrayVal($_POST, "uid");
                //$query = "SELECT id_user,fname,lname FROM " . TABLE_PREFIX . "water_source_caretakers LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_source_caretakers.uid=" . TABLE_PREFIX . "water_users.added_by WHERE uid=$uid";

                $query = "SELECT id_user,fname,lname FROM " . TABLE_PREFIX . "water_users WHERE added_by=$uid ORDER BY fname";

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
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case 'fetch-submitted_to_treasurer-sales':

        //$_POST['username'] = 'sukavi';
        //$_POST['request_hash'] = 'fa6977c99b809db68e1c56888ec38bd004719b39';
        //$_POST['uid'] = 2;


        if (appUserIsLoggedIn()) {

            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {

                $request_status = 1;
                $daily_sales = array();
                $status = array('submitted_to_treasurer', 'pending', 'denied', 'not_submitted_to_treasurer');

                $start_date = date("D d-M-Y", strtotime(getArrayVal($_POST, 'start_date')));

                if (empty($start_date) || strtotime($start_date) <= strtotime("Thu 01-Jan-1970")) {
                    $start_date = date("D d-M-Y");
                }

                $end_date = date("D d-M-Y", strtotime("-6 day", strtotime($start_date)));

                $joined_date = date("Y-m-d", strtotime($_SESSION['date_added']));

                if (strtotime($end_date) <= strtotime($joined_date)) {
                    $end_date = $joined_date;
                }

                $start_date2 = $start_date;

                $days_count = 0;

                while (strtotime($start_date2) >= strtotime($joined_date)) {
                    $days_count+=1;
                    $start_date2 = date("D d-M-Y", strtotime("-1 day", strtotime($start_date2)));
                }


                while (strtotime($start_date) >= strtotime($end_date)) {
                    $daily_sale['sale_date'] = $start_date;
                    $daily_sale['status'] = $status[rand(0, 3)];
                    $daily_sales[] = $daily_sale;
                    $start_date = date("D d-M-Y", strtotime("-1 day", strtotime($start_date)));
                }

                $data['daily_sales'] = $daily_sales;
                $data['days_count'] = $days_count;
                $data['msgs'] = array('Request successful.');
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case 'fetch-daily-sales':

        //$_POST['username'] = 'sukavi';
        //$_POST['request_hash'] = 'fa6977c99b809db68e1c56888ec38bd004719b39';
        //$_POST['uid'] = 2;

        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $inflow = 0.0;
                $outflow = 0.0;

                $uid = getArrayVal($_POST, "uid");
                $sale_date = getArrayVal($_POST, "sale_date");
                $submit_transactions = getArrayVal($_GET, "submit_transactions");

                if (empty($sale_date) || strtotime($sale_date) <= strtotime("Thu 01-Jan-1970")) {
                    $sale_date = date("Y-m-d");
                } else {
                    $sale_date = date("Y-m-d", strtotime($sale_date));
                }


                $query = "SELECT SUM(sale_ugx) AS inflow FROM sales WHERE sold_by=$uid";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();

                if (!empty($result['inflow'])) {
                    $inflow = floatval($result['inflow']);
                }

                $query = "SELECT SUM(transaction_cost) AS outflow FROM transactions WHERE transaction_initiated_by=$uid AND deductable=1";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();

                if (!empty($result['outflow'])) {
                    $outflow = floatval($result['outflow']);
                }

                $data['submit_transactions'] = 0;

                if ($submit_transactions === "1") {
                    $data['submit_transactions'] = 1;
                    $query = "UPDATE sales SET submitted_to_treasurer=1,submittion_to_treasurer_date='" . getCurrentDate() . "',percentage_saved=" . PERCENTAGE_SAVING . " WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND status<>1 AND submitted_to_treasurer=0";
                    if ($dbhandle->RunQueryForResults($query)) {
                        $request_status = 1;
                        $data['msgs'] = array('Your request has been received and is awaiting the tresurer\'s approval. The sales submited are now pending. ');
                    } else {
                        $data['msgs'] = array('An error occured making your request. Please try again later.');
                    }
                } else {
                    $request_status = 1;
                    $data['msgs'] = array('Request successful.');
                }

                $query = "SELECT SUM(sale_ugx) AS days_total_sales FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND sale_ugx>0";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['days_total_sales'])) {
                    $data['days_total_sales'] = $result['days_total_sales'];
                } else {
                    $data['days_total_sales'] = "0.00";
                }
                $madeni = 0;
                $query = "SELECT SUM(sale_ugx) AS madeni FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND sale_ugx<0";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['madeni'])) {
                    $data['days_total_sales'] += trim($result['madeni'], "-");
                    $madeni = trim($result['madeni'], "-");
                } else {
                    $data['days_total_sales'] += 0;
                }


                $data['days_expected_pending_submission'] = number_format(floor($data['days_total_sales'] * (PERCENTAGE_SAVING / 100) / 100) * 100, 2, '.', ',');
                $data['days_total_sales'] = number_format($data['days_total_sales'], 2, '.', ',');

                $query = "SELECT SUM(sale_ugx) AS days_submitted_to_treasurer_sales FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND submitted_to_treasurer=1 AND status=1 AND sale_ugx>0";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['days_submitted_to_treasurer_sales'])) {
                    $data['days_submitted_to_treasurer_sales'] = number_format(floor(($result['days_submitted_to_treasurer_sales'] + $madeni) * (PERCENTAGE_SAVING / 100) / 100) * 100, 2, '.', ',');
                } else {
                    $data['days_submitted_to_treasurer_sales'] = "0.00";
                }

                $query = "SELECT COUNT(id_sale) AS total_transactions FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "'";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['total_transactions'])) {
                    $data['total_transactions'] = floatval($result['total_transactions']);
                } else {
                    $data['total_transactions'] = 0;
                }

                $query = "SELECT COUNT(id_sale) AS submitted_to_treasurer_transactions FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND submitted_to_treasurer=1";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['submitted_to_treasurer_transactions'])) {
                    $data['submitted_to_treasurer_transactions'] = floatval($result['submitted_to_treasurer_transactions']);
                } else {
                    $data['submitted_to_treasurer_transactions'] = 0;
                }

                $query = "SELECT COUNT(id_sale) AS approved FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND submitted_to_treasurer=1 AND status=1";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['approved'])) {
                    $data['approved'] = floatval($result['approved']);
                } else {
                    $data['approved'] = 0;
                }

                $query = "SELECT COUNT(id_sale) AS pending FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND submitted_to_treasurer=1 AND status=0 ";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['pending'])) {
                    $data['pending'] = floatval($result['pending']);
                } else {
                    $data['pending'] = 0;
                }

                $query = "SELECT COUNT(id_sale) AS denied FROM sales WHERE sold_by=$uid AND sale_date>='" . $sale_date . " 00:00:00" . "' AND sale_date<='" . $sale_date . " 23:59:59" . "' AND submitted_to_treasurer=1 AND status=2";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();
                if (!empty($result['denied'])) {
                    $data['denied'] = floatval($result['denied']);
                } else {
                    $data['denied'] = 0;
                }
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case 'fetch-water-sources-and-mechanics':
        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $request_status = 1;
                $uid = getArrayVal($_POST, 'uid');
                $uid = 2;
                $water_sources = array();
                $query = "SELECT " . TABLE_PREFIX . "water_sources.water_source_id FROM " . TABLE_PREFIX . "water_source_caretakers LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source WHERE uid=$uid";
                $result = $dbhandle->RunQueryForResults($query);
                if (!empty($result)) {
                    while ($row = $result->fetch_array()) {
                        $water_sources[] = $row['water_source_id'];
                    }
                }
                $data['water_sources'] = $water_sources;

                $mechanics = array();

                $query = "SELECT idu,fname,lname FROM " . TABLE_PREFIX . "water_source_caretakers "
                        . "LEFT JOIN water_source_mechanics ON water_source_mechanics.water_source_id "
                        . "LEFT JOIN users ON water_source_mechanics.uid=users.idu "
                        . "WHERE water_source_caretakers.uid=$uid GROUP BY users.idu";

                $result = $dbhandle->RunQueryForResults($query);
                if (!empty($result)) {
                    while ($row = $result->fetch_array()) {
                        $mechanics [] = $row['idu'] . "-" . $row['fname'] . " " . $row['lname'];
                    }
                }
                $data['mechanics'] = $mechanics;

                $data['msgs'] = array('Request successful.');
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case 'verify-mechanic':
        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $water_source_id = getArrayVal($_POST, 'water_source_id');
                $id_mechanic = explode("-", getArrayVal($_POST, 'water_source_mechanic'))[0];
                $water_source = $dbhandle->Fetch("water_sources", "*", array('water_source_id' => $water_source_id));

                if (isset($water_source['water_source_id'])) {
                    if ($dbhandle->CheckIFExists("water_source_mechanics", array('water_source_id' => $water_source['id_water_source'],
                                'uid' => $id_mechanic))) {
                        $request_status = 1;
                        $data['id_mechanic'] = $id_mechanic;
                        $data['msgs'] = array('Mechanic is allowed');
                    } else {
                        $data['msgs'] = array('That mechanic is not allowed to repair that water source therefore his payments'
                            . ' cannot be processed.');
                    }
                } else {
                    $data['msgs'] = array('That water source does not exist.');
                }
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case "pay-for-repair":
        if (ENABLE_MECHANIC_PAYMENTS) {
            if (appUserIsLoggedIn()) {
                if ($_SESSION['group_id'] != 6) {
                    $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
                } else {
                    $inflow = 0.0;
                    $outflow = 0.0;

                    $uid = getArrayVal($_POST, "uid");

                    $query = "SELECT SUM(sale_ugx) AS inflow FROM sales WHERE sold_by=$uid";
                    $result = $dbhandle->RunQueryForResults($query);
                    $result = $result->fetch_assoc();

                    if (!empty($result['inflow'])) {
                        $inflow = floatval($result['inflow']);
                    }

                    $query = "SELECT SUM(transaction_cost) AS outflow FROM transactions WHERE transaction_initiated_by=$uid AND deductable=1";
                    $result = $dbhandle->RunQueryForResults($query);
                    $result = $result->fetch_assoc();

                    if (!empty($result['outflow'])) {
                        $outflow = floatval($result['outflow']);
                    }

                    $data['account_balance'] = floor(($inflow) * (PERCENTAGE_SAVING / 100) / 100) * 100 - $outflow;


                    $transaction_code = strtoupper(generateAlphaNumCode(7));
                    while ($dbhandle->CheckIFExists("transactions", array("transaction_code" => $transaction_code))) {
                        $transaction_code = strtoupper(generateAlphaNumCode(7));
                    }
                    $params['transaction_code'] = $transaction_code;
                    $params['transaction_name'] = getArrayVal($_POST, "transaction_name");
                    $params['transaction_description'] = getArrayVal($_POST, "transaction_description");
                    $params['transaction_initiated_by'] = getArrayVal($_POST, "transaction_initiated_by");
                    $params['transaction_initiated_to'] = getArrayVal($_POST, "transaction_initiated_to");
                    $params['transaction_cost'] = floatval(getArrayVal($_POST, "transaction_cost"));
                    $params['transaction_date'] = getCurrentDate();
                    $params['deductable'] = 1;

                    if (empty($params['transaction_name'])) {
                        $errors[] = "A transaction name is required";
                    }

                    if (empty($params['transaction_description'])) {
                        $errors[] = "A transaction description is required";
                    }

                    if (empty($params['transaction_initiated_by'])) {
                        $errors[] = "Who initiated the transaction?";
                    }

                    if (empty($params['transaction_initiated_to'])) {
                        $errors[] = "The transaction is for who?";
                    }

                    if (empty($params['transaction_cost'])) {
                        $errors[] = "A transaction must have a cost and the cost cannot be zero or less than zero \"0\"";
                    } elseif ($params['transaction_cost'] < 0) {
                        $errors[] = "A transaction must have a cost and the cost cannot be zero or less than zero \"0\"";
                    }


                    if (intval($data['account_balance']) <= 0) {
                        $errors[] = "Sorry. You do not have enough funds in your account to facilitate the transaction.";
                    } else if (intval($params['transaction_cost']) > intval($data['account_balance'])) {
                        $errors[] = "Sorry. You do not have enough funds in your account to facilitate the transaction.";
                    }

                    if (!isset($errors)) {
                        $uid = $dbhandle->Insert('transactions', $params);
                        if (is_int($uid)) {
                            $request_status = 1;
                            $data['msgs'] = array('Transaction completed.');
                        } else {
                            $data['msgs'] = array('An error occured processing the transaction. Please try again later.');
                        }
                    } else {
                        foreach ($errors as $error) {
                            $data['msgs'][] = $error;
                        }
                    }
                }
            } else {
                $data['msgs'] = array('Wrong username or request hash.');
            }
        } else {
            $data['msgs'] = array('Repair payments have been disabled for now.');
        }
        break;
    case "fetch-account-balance":

        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $inflow = 0.0;
                $outflow = 0.0;

                $uid = getArrayVal($_POST, "uid");

                $query = "SELECT SUM(sale_ugx) AS inflow FROM sales WHERE sold_by=$uid AND sale_ugx>0 AND submitted_to_treasurer=1 AND status=1";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();

                if (!empty($result['inflow'])) {
                    $inflow += floatval($result['inflow']);
                }

                $query = "SELECT SUM(sale_ugx) AS outflow FROM sales WHERE sold_by=$uid AND sale_ugx<0";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();

                if (!empty($result['outflow'])) {
                    $outflow += floatval($result['outflow']) * -1;
                }

                $query = "SELECT SUM(transaction_cost) AS outflow FROM transactions WHERE transaction_initiated_by=$uid AND deductable=1";
                $result = $dbhandle->RunQueryForResults($query);
                $result = $result->fetch_assoc();

                if (!empty($result['outflow'])) {
                    $outflow += floatval($result['outflow']);
                }

                $request_status = 1;

                $data['account_name'] = $_SESSION['fname'] . " " . $_SESSION['lname'];
                //$data['account_balance'] = ceil(($inflow - $outflow) * PERCENTAGE_SAVING) . ".00";
                $data['account_balance'] = number_format((floor(($inflow) * (PERCENTAGE_SAVING / 100) / 100) * 100) - $outflow, 2, '.', ',');
                $data['msgs'] = array('Transaction completed.');
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }
        break;
    case "fetch-mini-statement":
        if (appUserIsLoggedIn()) {
            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $request_status = 1;
                $data['msgs'] = array('Request Successful.');
                $uid = getArrayVal($_POST, "uid");

                $transactions = array();
                $query = "SELECT "
                        . TABLE_PREFIX . "transactions.transaction_name,"
                        . TABLE_PREFIX . "transactions.transaction_cost,"
                        . TABLE_PREFIX . "transactions.transaction_date"
                        . " FROM " . TABLE_PREFIX . "transactions "
                        . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "transactions.transaction_initiated_to=" . TABLE_PREFIX . "users.idu "
                        . "WHERE transaction_initiated_by=$uid OR transaction_initiated_to=$uid ORDER BY " . TABLE_PREFIX . "transactions.id_transaction DESC LIMIT 5 ";
                $result = $dbhandle->RunQueryForResults($query);
                if (isset($result->num_rows) && $result->num_rows > 0) {
                    while ($transaction = $result->fetch_assoc()) {
                        if (!empty($transaction['transaction_name'])) {
                            $transaction['transaction_date'] = date("D d-M-Y h:i:s A", strtotime($transaction['transaction_date']));
                            $transaction['transaction_cost'] = number_format($transaction['transaction_cost'], 2, '.', ',');
                            $transactions[] = $transaction;
                        }
                    }
                }

                $data["transactions"] = $transactions;
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }

        break;
    case "withdraw-funds":

        if (ENABLE_WITHDRAWALS === 1) {
            if (appUserIsLoggedIn()) {
                if ($_SESSION['group_id'] != 6) {
                    $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
                } else {
                    $inflow = 0.0;
                    $outflow = 0.0;

                    $uid = getArrayVal($_POST, "uid");

                    $query = "SELECT SUM(sale_ugx) AS inflow FROM sales WHERE sold_by=$uid";
                    $result = $dbhandle->RunQueryForResults($query);
                    $result = $result->fetch_assoc();

                    if (!empty($result['inflow'])) {
                        $inflow = floatval($result['inflow']);
                    }

                    $query = "SELECT SUM(sale_ugx) AS outflow FROM sales WHERE sold_by=$uid AND sale_ugx<0";
                    $result = $dbhandle->RunQueryForResults($query);
                    $result = $result->fetch_assoc();

                    if (!empty($result['outflow'])) {
                        $outflow += floatval($result['outflow']) * -1;
                    }

                    $query = "SELECT SUM(transaction_cost) AS outflow FROM transactions WHERE transaction_initiated_by=$uid AND deductable=1";
                    $result = $dbhandle->RunQueryForResults($query);
                    $result = $result->fetch_assoc();

                    if (!empty($result['outflow'])) {
                        $outflow += floatval($result['outflow']);
                    }

                    $data['account_balance'] = $inflow - $outflow;

                    $transaction_code = strtoupper(generateAlphaNumCode(7));
                    while ($dbhandle->CheckIFExists("transactions", array("transaction_code" => $transaction_code))) {
                        $transaction_code = strtoupper(generateAlphaNumCode(7));
                    }
                    $params['transaction_code'] = $transaction_code;
                    $params['transaction_name'] = getArrayVal($_POST, "transaction_name");
                    $params['transaction_description'] = getArrayVal($_POST, "transaction_description");
                    $params['transaction_initiated_by'] = getArrayVal($_POST, "transaction_initiated_by");
                    $params['transaction_initiated_to'] = getArrayVal($_POST, "transaction_initiated_to");
                    $params['transaction_cost'] = floatval(getArrayVal($_POST, "transaction_cost"));
                    $params['transaction_date'] = getCurrentDate();

                    if (empty($params['transaction_name'])) {
                        $errors[] = "A transaction name is required";
                    }

                    if (empty($params['transaction_description'])) {
                        $errors[] = "A transaction description is required";
                    }

                    if (empty($params['transaction_initiated_by'])) {
                        $errors[] = "Who initiated the transaction?";
                    }

                    if (empty($params['transaction_initiated_to'])) {
                        $errors[] = "The transaction is for who?";
                    }

                    if (empty($params['transaction_cost'])) {
                        $errors[] = "A transaction must have a cost and the cost cannot be zero or less than zero \"0\"";
                    } elseif ($params['transaction_cost'] < 0) {
                        $errors[] = "A transaction must have a cost and the cost cannot be zero or less than zero \"0\"";
                    }

                    if (intval($data['account_balance']) <= 0) {
                        $errors[] = "You do not have enough funds in your account that you can withdraw.";
                    } else if (intval($params['transaction_cost']) > intval($data['account_balance'])) {
                        $errors[] = "You are trying to withrdaw more than you have in your account. Please enter an amount lower than your account balance.";
                    }

                    if (!isset($_SESSION['pnumber'])) {
                        $errors[] = "You need to have your mobile money number registerd in the system to withdraw"
                                . " your funds.";
                    } elseif (!isValid("pnumber", $_SESSION['pnumber'])) {
                        $errors[] = "The mobile money number registerd in the system is not a valid number.";
                    }

                    if (!isset($errors)) {
                        $uid = $dbhandle->Insert('transactions', $params);
                        if (is_int($uid)) {
                            $request_status = 1;
                            $data['msgs'] = array('Transaction completed.');
                            if (ENABLE_SMS === 1) {
                                $smsObject = new AfricasTalkingGateway(SMS_API_USERNAME, SMS_API_KEY);
                                $message_ = $params['transaction_code'] . " Confirmed. You have received UGX " . $params['transaction_cost'] . " "
                                        . "From " . SYSTEM_NAME;
                                try {
                                    $reply = $smsObject->sendMessage($_SESSION['pnumber'], $message_);
                                } catch (AfricasTalkingGatewayException $e) {
                                    //echo "Encountered an error while sending: " . $e->getMessage();
                                }
                            }
                        } else {
                            $data['msgs'] = array('An error occured processing the transaction. Please try again later.');
                        }
                    } else {
                        foreach ($errors as $error) {
                            $data['msgs'][] = $error;
                        }
                    }
                }
            } else {
                $data['msgs'] = array('Wrong username or request hash.');
            }
        } else {
            $data['msgs'] = array('Cash withdrawals have been disabled for now.');
        }

        break;
    case"fetch-debts":

        //$_POST['username'] = 'sukavi';
        //$_POST['request_hash'] = 'fa6977c99b809db68e1c56888ec38bd004719b39';
        //$_POST['uid'] = 2;

        if (appUserIsLoggedIn()) {

            if ($_SESSION['group_id'] != 6) {
                $data['msgs'] = array('Your account rights are not supported by the app. Please log on to ' . SITE_URL);
            } else {
                $debts = array();
                $uid = getArrayVal($_POST, 'uid');
                $sold_to = getArrayVal($_POST, 'id_user');

                $user = $dbhandle->fetch("water_users", "*", array('id_user' => $sold_to));


                $start_date = $user['date_added'];
                $end_date = getCurrentDate();
                while (strtotime($start_date) <= strtotime($end_date)) {

                    $month = date("m", strtotime($start_date));
                    $year = date("Y", strtotime($start_date));
                    $query = "SELECT id_sale,SUM(sale_ugx) AS debt,sale_date FROM sales WHERE "
                            . "MONTH(sale_date)=$month AND YEAR(sale_date)=$year "
                            . "AND sold_to=$sold_to "
                            . "AND payment_cash=1 "
                            . "AND sold_by=$uid "
                            . "GROUP BY Date(sale_date)";

                    // var_dump($query);

                    $result = $dbhandle->RunQueryForResults($query);
                    if (!empty($result)) {
                        while ($row = $result->fetch_assoc()) {
                            $row['sale_date'] = date("M-Y", strtotime($row['sale_date']));
                            $row['debt'] = number_format($row['debt'], 2, '.', ',');
                            $debts[] = $row;
                        }
                    } $start_date = date("D d-M-Y", strtotime("+30 day", strtotime($start_date))); //Monthly sale
                }
                $data['msgs'] = array('Request Successful.');
                $request_status = 1;
                $data['debts'] = $debts;
            }
        } else {
            $data['msgs'] = array('Wrong username or request hash.');
        }

        break;

    default:
        $data['msgs'] = array('Undefined request');
        break;
}







$data['request_status'] = $request_status;




$server_reply = array(
    'server_info' => $server_info,
    'data' => $data
);

//header('Content-Type: application/json');
echo json_encode($server_reply);
