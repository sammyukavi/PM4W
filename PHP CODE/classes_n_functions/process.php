<?php

$action = getArrayVal($_GET, 'a');
if (isset($_POST['submit'])) {
    switch ($action) {
        case 'login':
            $account = array();
            $email = strtolower(getArrayVal($_POST, 'email'));
            $password = sha1(getArrayVal($_POST, 'password'));
            $query = "SELECT idu,username,gcm_regid,group_id,username,pnumber,email,fname,lname,date_added,last_login,active FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')"
                    . " AND password='$password'";
            $result = $dbhandle->RunQueryForResults($query);

            // var_dump($query);
            //

            while ($row = $result->fetch_assoc()) {
                $account = $row;
            }

            if (!empty($account) && isset($account['idu'])) {
                if ($account['active'] == 1) {
                    $user_group = $dbhandle->Fetch("user_groups", "*", array('id_group' => $account['group_id']));
                    if (!empty($user_group)) {
                        if ($user_group['group_is_enabled'] == 1) {
                            foreach ($user_group as $key => $value) {
                                if ($key !== 'id_group' && $key !== 'group_name') {
                                    $user_group[$key] = $value === '1' ? true : false;
                                }
                            }

                            $USER = (object) array_merge($account, $user_group);
                            $_SESSION['idu'] = $USER->idu;
                            $_SESSION['username'] = $USER->username;

                            header('location:' . SITE_URL);
                            exit();

                            $dbhandle->Update('users', array('last_login' => getCurrentDate()), array('idu' => $account['idu']));
                        } else {
                            logMessage("You cannot be logged in because your user group has been deactivated. Please consult your administrator for further advice.");
                        }
                    } else {
                        logMessage("You cannot be logged in because you belong to no user group. Please consult your administrator for further advice.");
                    }
                } else {
                    logMessage("Your account has been deactivated hence you cannot log in. Please consult your administrator for further advice.");
                }
            } else {
                logMessage("Wrong username or password", 0);
            }
            break;
        case 'forgot-password':
            $email = getArrayVal($_POST, 'email');

            if (!empty($email)) {

                $query = "SELECT idu,pnumber,email FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')";
                $result = $dbhandle->RunQueryForResults($query);
                $account = $result->fetch_assoc();
                if (!empty($account) && isset($account['idu'])) {
                    if ($account['active'] == 1) {
                        $password = generateAlphaNumCode(6);
                        if ($dbhandle->Update('users', array('password' => sha1($password)), array('idu' => $account['idu']))) {

                            $TEMPLATE_PARAMS = array(
                                'system_name' => SYSTEM_NAME,
                                'site_url' => SITE_URL,
                                'email' => $account['email'],
                                'username' => $account['username'],
                                'password' => $password,
                                'pnumber' => $account['pnumber'],
                            );

                            if (isset($account['email'])) {
                                $message = $SYSTEM_CONFIG['recovery_email_template'];
                                send_email($account['email'], 'Your ' . SYSTEM_NAME . ' password has been reset', $message);
                            }

                            if (!empty($account['pnumber'])) {
                                $message = $SYSTEM_CONFIG['recovery_sms_template'];
                                send_sms_message($account['pnumber'], $message);
                            }
                            logMessage("Your password has been reset", 3);
                            header('location:' . SITE_URL);
                            exit();
                        } else {
                            logMessage("An error occured resetting your password. Please try agin later. If this persists please consult your administrator.", 0);
                        }
                    } else {
                        logMessage("Your account is inactive hence you cannot reset your password. Please consult your administrator for further advice.");
                    }
                } else {
                    logMessage("Your password could not be reset. No account exists with those details", 0);
                }
            } else {
                logMessage("An email, username or phone number is required", 0);
            }
            break;
        case 'add-water-user':

            if (is_logged_in()) {
                if ($USER->can_add_water_users) {
                    $params['fname'] = getArrayVal($_POST, 'fname');
                    $params['lname'] = getArrayVal($_POST, 'lname');
                    $params['pnumber'] = getArrayVal($_POST, 'pnumber');
                    $params['date_added'] = getCurrentDate();
                    $params['added_by'] = getArrayVal($_POST, 'uid');

                    if (empty($params['fname'])) {
                        $errors[] = "First name is required.";
                    }
                    if (empty($params['lname'])) {
                        $errors[] = "Last name is required.";
                    }

                    if (!empty($params['pnumber'])) {
                        $params['pnumber'] = autoCorrectPnumber($params['pnumber']);
                    }

                    if (!empty($params['pnumber']) && (isTaken('user_pnumber', $params['pnumber']))) {
                        $errors[] = "That phone number is already in use";
                    } elseif (!empty($params['pnumber']) && !isValid('pnumber', $params['pnumber'])) {
                        $errors[] = "Please use a valid phone number in the format shown";
                    }

                    if ($USER->can_edit_system_users) {
                        if (empty($params['added_by'])) {
                            $errors[] = "Please select the person under which the customer is added";
                        } elseif (!$dbhandle->CheckIFExists('users', array('idu' => $params['added_by']))) {
                            $errors[] = "The user you selected does not exist. Please select the person under which the customer is added";
                        }
                    } else {
                        $params['added_by'] = $USER->idu;
                    }

                    if (!isset($errors)) {
                        $uid = $dbhandle->Insert('water_users', $params);
                        if (is_int($uid)) {
                            logMessage("Water User added", 3);
                            header('location:' . SITE_URL . '/?a=edit-water-user&id=' . $uid);
                            exit();
                        } else {
                            logMessage("An error occured adding the customer. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }

            break;
        case'edit-water-user':
            if (is_logged_in()) {
                if ($USER->can_edit_water_users) {
                    $id_user = getArrayVal($_GET, 'id');
                    $query = "SELECT * FROM " . TABLE_PREFIX . "water_users WHERE id_user=$id_user";
                    $result = $dbhandle->RunQueryForResults($query);
                    $account = $result->fetch_assoc();
                    if (isset($account['id_user'])) {

                        $params['fname'] = getArrayVal($_POST, 'fname');
                        $params['lname'] = getArrayVal($_POST, 'lname');
                        $params['pnumber'] = getArrayVal($_POST, 'pnumber');
                        $params['added_by'] = getArrayVal($_POST, 'uid');

                        if (empty($params['added_by'])) {
                            $params['added_by'] = $USER->idu;
                        }
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
                            $errors[] = "Please use a valid phone number in the format shown";
                        }

                        if (!isset($errors)) {
                            if ($dbhandle->Update('water_users', $params, array('id_user' => $id_user))) {
                                logMessage("Water User updated", 3);
                                header('location:' . SITE_URL . '/?a=edit-water-user&id=' . $id_user);
                                exit();
                            } else {
                                logMessage("An error occured updating the account. Please try again later", 0);
                            }
                        } else {
                            foreach ($errors as $error) {
                                logMessage($error, 0);
                            }
                        }
                    } else {
                        logMessage("That water user does not exist", 0);
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'add-sale': if (is_logged_in()) {
                if ($USER->can_add_sales) {
                    $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                    $params['sold_by'] = getArrayVal($_POST, 'sold_by');
                    $params['sold_to'] = getArrayVal($_POST, 'sold_to');


                    if (empty($params['sold_by'])) {
                        $params['sold_by'] = $USER->idu;
                    }
                    if (empty($params['sold_to'])) {
                        $params['sale_ugx'] = floatval(getArrayVal($_POST, 'sale_ugx'));
                    } else {
                        $params['sale_ugx'] = -1;
                    }

                    $params['sale_date'] = getCurrentDate();

                    if (ctype_alnum($params['sale_ugx'])) {
                        $errors[] = "A sale value can only be in numbers.";
                    } elseif (empty($params['sale_ugx'])) {
                        $errors[] = "A sale value is required.";
                    } elseif (empty($params['sold_to']) && floatval($params['sale_ugx']) < 0) {
                        $errors[] = "A sale value is required.";
                    }

                    if (!isset($errors)) {

                        $water_source_data = $dbhandle->Fetch("water_sources", "*", array('id_water_source' => $params['water_source_id']), null, true, 1);
                        if (!empty($water_source_data)) {

                            if ($dbhandle->CheckIFExists("water_source_caretakers", array(
                                        'water_source_id' => $water_source_data['id_water_source'],
                                        'uid' => $params['sold_by']))) {

                                $params['water_source_id'] = $water_source_data['id_water_source'];
                                $params['percentage_saved'] = $water_source_data['percentage_saved'];
                                if ($params['sale_ugx'] < 0) {
                                    $params['sale_ugx'] = floatval($water_source_data['monthly_charges']);
                                }

                                $id_sale = $dbhandle->Insert('sales', $params);
                                if (is_int($id_sale)) {
                                    logMessage("Sale added", 3);
                                    header('location:' . SITE_URL . '/?a=edit-sale&id=' . $id_sale);
                                    exit();
                                } else {
                                    logMessage("An error occured adding the sale. Please try again later", 0);
                                }
                            } else {
                                logMessage('The user you selected is not authorised to add users for this water source. If you feel this is an error, please consult your administrator.', 0);
                            }
                        } else {
                            logMessage('That water source does not exist. If this persi sts, plese consult your administrator.', 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'edit-sale': if (is_logged_in()) {
                if ($USER->can_edit_sales) {
                    $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                    $params['sold_by'] = getArrayVal($_POST, 'sold_by');
                    $params['sold_to'] = getArrayVal($_POST, 'sold_to');
                    $params['sale_ugx'] = floatval(getArrayVal($_POST, 'sale_ugx'));

                    if (empty($params['sold_by'])) {
                        $params['sold_by'] = $USER->idu;
                    }

                    if (empty($params['sold_to'])) {
                        $params['sale_ugx'] = floatval(getArrayVal($_POST, 'sale_ugx'));
                    } else {
                        $params['sale_ugx'] = -1;
                    }

                    //$params['sale_date'] = getCurrentDate();

                    if (ctype_alnum($params['sale_ugx'])) {
                        $errors[] = "A sale value can only be in numbers.";
                    } elseif (empty($params['sale_ugx'])) {
                        $errors[] = "A sale value is required.";
                    } elseif (empty($params['sold_to']) && floatval($params['sale_ugx']) < 0) {
                        $errors[] = "A sale value is required.";
                    }

                    if (!isset($errors)) {
                        $water_source_data = $dbhandle->Fetch("water_sources", "*", array('id_water_source' => $params['water_source_id']), null, true, 1);
                        if (!empty($water_source_data)) {
                            if ($dbhandle->CheckIFExists("water_source_caretakers", array(
                                        'water_source_id' => $water_source_data['id_water_source'],
                                        'uid' => $params['sold_by']))) {

                                $params['water_source_id'] = $water_source_data['id_water_source'];
                                //$params['percentage_saved'] = $water_source_data['percentage_saved'];
                                if ($params['sale_ugx'] < 0) {
                                    $params['sale_ugx'] = floatval($water_source_data['monthly_charges']);
                                }

                                $id_sale = getArrayVal($_GET, 'id');
                                if ($dbhandle->Update('sales', $params, array('id_sale' => $id_sale))) {
                                    logMessage("Sale Updated", 3);
                                    header('location:' . SITE_URL . '/?a=edit-sale&id=' . $id_sale);
                                    exit();
                                } else {
                                    logMessage("An error occured adding the sale. Please try again later", 0);
                                }
                            } else {
                                logMessage('The user you selected is not authorised to add users for this water source. If you feel this is an error, please consult your administrator.', 0);
                            }
                        } else {
                            logMessage('That water source does not exist. If this persi sts, plese consult your administrator.', 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'add-water-source':
            if (is_logged_in()) {
                if ($USER->can_add_water_sources) {
                    $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                    $params['water_source_name'] = getArrayVal($_POST, 'water_source_name');
                    $params['water_source_location'] = getArrayVal($_POST, 'water_source_location');
                    $params['water_source_coordinates'] = getArrayVal($_POST, 'water_source_coordinates');
                    $params['monthly_charges'] = getArrayVal($_POST, 'monthly_charges');
                    $params['percentage_saved'] = getArrayVal($_POST, 'percentage_saved');

                    if (empty($params['water_source_location'])) {
                        $errors[] = "A water source's location is required";
                    }

                    if (empty($params['water_source_coordinates'])) {
                        $errors[] = "A water source's coordinates are required";
                    }

                    if (!isset($errors)) {
                        $p_id = $dbhandle->Insert('water_sources', $params);
                        if (is_int($p_id)) {
                            $new_params = array();
                            if (empty($params['water_source_id'])) {
                                $new_params['water_source_id'] = '';

                                $exploded_location = explode(',', $params['water_source_location']);
                                foreach ($exploded_location as $index => $value) {
                                    $exploded_location[$index] = ucwords(trim(ltrim($value)));
                                }

                                foreach ($exploded_location as $loc) {
                                    foreach (explode(' ', $loc) as $value) {
                                        $new_params ['water_source_id'] .= $value[0];
                                    }
                                }
                                $new_params['water_source_id'] = $new_params['water_source_id'] . '-' . strtoupper(generateAlphaNumCode());
                                while ($dbhandle->CheckIFExists('water_sources', array('water_source_id' => $new_params ['water_source_id']))) {
                                    $tmp = explode('-', $new_params['water_source_id']);
                                    $new_params['water_source_id'] = $tmp[0] . '-' . strtoupper(generateAlphaNumCode());
                                }
                            }

                            if (empty($params ['water_source_name'])) {
                                $new_params['water_source_name'] = strtoupper(preg_replace('/[ ,]+/', '-', trim($params['water_source_location']))) . "-WTR-SRC-" . 1;
                                while ($dbhandle->CheckIFExists('water_sources', array('water_source_name' => $new_params ['water_source_name']))) {
                                    $exploded = explode('-', $new_params ['water_source_name']);
                                    $exploded[count($exploded) - 1] = intval($exploded[count($exploded) - 1]) + 1;
                                    $new_params['water_source_name'] = strtoupper(preg_replace('/[ ,]+/', '-', implode(',', $exploded)));
                                }
                            }
                            if (!empty($new_params)) {
                                $dbhandle->Update('water_sources', $new_params, array('id_water_source' => $p_id));
                            }

                            if (isset($_POST['attendants'])) {
                                foreach ($_POST['attendants'] as $key => $uid) {
                                    $dbhandle->Insert('water_source_caretakers', array(
                                        'water_source_id' => $p_id,
                                        'uid' => $uid
                                    ));
                                }
                            }

                            if (isset($_POST['treasurers'])) {
                                foreach ($_POST['treasurers'] as $key => $uid) {
                                    $dbhandle->Insert('water_source_treasurers', array(
                                        'water_source_id' => $p_id,
                                        'uid' => $uid
                                    ));
                                }
                            }



                            logMessage("Water source added", 3);
                            header('location:' . SITE_URL . '/?a=edit-water-source&id=' . $p_id);
                            exit();
                        } else {

                            logMessage("An error occured adding th e water source. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }

            break;
        case 'edit-water-source':
            if (is_logged_in()) {
                if ($USER->can_edit_water_sources) {
                    $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                    $params['water_source_name'] = getArrayVal($_POST, 'water_source_name');
                    $params['water_source_location'] = getArrayVal($_POST, 'water_source_location');
                    $params['water_source_coordinates'] = getArrayVal($_POST, 'water_source_coordinates');
                    $params['monthly_charges'] = getArrayVal($_POST, 'monthly_charges');
                    $params['percentage_saved'] = getArrayVal($_POST, 'percentage_saved');

                    if (empty($params['water_source_location'])) {
                        $errors[] = "A water source's location is required";
                    }

                    if (empty($params['water_source_coordinates'])) {
                        $errors[] = "A water source's coordinates are required";
                    }

                    if (!isset($errors)) {
//$p_id = $dbhandle->Insert('water_sources', $params);
                        $p_id = getArrayVal($_GET, 'id');
                        if ($dbhandle->Update('water_sources', $params, array('id_water_source' => $p_id))) {
                            $new_params = array();
                            if (empty($params['water_source_id'])) {
                                $new_params['water_source_id'] = '';

                                $exploded_location = explode(',', $params['water_source_location']);
                                foreach ($exploded_location as $index => $value) {
                                    $exploded_location[$index] = ucwords(trim(ltrim($value)));
                                }

                                foreach ($exploded_location as $loc) {
                                    foreach (explode(' ', $loc) as $value) {
                                        $new_params ['water_source_id'] .= $value[0];
                                    }
                                }
                                $new_params['water_source_id'] = $new_params['water_source_id'] . '-' . strtoupper(generateAlphaNumCode());
                                while ($dbhandle->CheckIFExists('water_sources', array('water_source_id' => $new_params ['water_source_id']))) {
                                    $tmp = explode('-', $new_params['water_source_id']);
                                    $new_params['water_source_id'] = $tmp[0] . '-' . strtoupper(generateAlphaNumCode());
                                }
                            }

                            if (empty($params ['water_source_name'])) {
                                $new_params['water_source_name'] = strtoupper(preg_replace('/[ ,]+/', '-', trim($params['water_source_location']))) . "-WTR-SRC-" . 1;
                                while ($dbhandle->CheckIFExists('water_sources', array('water_source_name' => $new_params ['water_source_name']))) {
                                    $exploded = explode('-', $new_params ['water_source_name']);
                                    $exploded[count($exploded) - 1] = intval($exploded[count($exploded) - 1]) + 1;
                                    $new_params['water_source_name'] = strtoupper(preg_replace('/[ ,]+/', '-', implode(',', $exploded)));
                                }
                            }
                            if (!empty($new_params)) {
                                $dbhandle->Update('water_sources', $new_params, array('id_water_source' => $p_id));
                            }

                            $dbhandle->Delete('water_source_caretakers', array('water_source_id' => $p_id));



                            if (isset($_POST['attendants'])) {
                                foreach ($_POST['attendants'] as $key => $uid) {
                                    $dbhandle->Insert('water_source_caretakers', array(
                                        'water_source_id' => $p_id,
                                        'uid' => $uid
                                    ));
                                }
                            }

                            $dbhandle->Delete('water_source_treasurers', array('water_source_id ' => $p_id));


                            if (isset($_POST['treasurers'])) {
                                foreach ($_POST['treasurers'] as $key => $uid) {
                                    $dbhandle->Insert('water_source_treasurers', array(
                                        'water_source_id' => $p_id,
                                        'uid' => $uid
                                    ));
                                }
                            }

                            logMessage("Water source updated", 3);
                            header('location:' . SITE_URL . '/?a=edit-water-source&id=' . $p_id);
                            exit();
                        } else {

                            logMessage("An error occured updating th e water source. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'add-repair-type': if (is_logged_in()) {
                if ($USER->can_add_repair_types) {
                    $params['repair_type'] = getArrayVal($_POST, 'repair_type');
                    $params['added_by'] = $USER->idu;
                    $params['date_added'] = getCurrentDate();
                    $params['active'] = 1;

                    if (empty($params['repair_type'])) {
                        $errors[] = "A repair name is required";
                    }

                    if (!isset($errors)) {
                        $id_repair_type = $dbhandle->Insert('repair_types', $params);
                        if (is_int($id_repair_type)) {
                            logMessage("Repair type added", 3);
                            header('location:' . SITE_URL . '/?a=edit-repair-type&id=' . $id_repair_type);
                            exit();
                        } else {
                            logMessage("An error occured adding t he repair type. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'edit-repair-type' :
            if (is_logged_in()) {
                if ($USER->can_edit_repair_types) {
                    $id_repair_type = getArrayVal($_GET, 'id');
                    $params['repair_type'] = getArrayVal($_POST, 'repair_type');

                    if (empty($params['repair_type'])) {
                        $errors[] = "A repair name is required";
                    }

                    if (!isset($errors)) {

                        if ($dbhandle->Update('repair_types', $params, array('id_repair_type' => $id_repair_type))) {
                            logMessage("Repair type updated", 3);
                            header('location:' . SITE_URL . '/?a=edit-repair-type&id=' . $id_repair_type);
                            exit();
                        } else {
                            logMessage("An error occured adding t he repair type. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'add-expenditure': if (is_logged_in()) {
                if ($USER->can_add_expenses) {
                    $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                    $params['repair_type_id'] = getArrayVal($_POST, 'repair_type_id');
                    $params['expenditure_date'] = getCurrentDate(getArrayVal($_POST, 'expenditure_date'));
                    $params['expenditure_cost'] = getArrayVal($_POST, 'expenditure_cost');
                    $params['benefactor'] = getArrayVal($_POST, 'benefactor');
                    $params['description'] = getArrayVal($_POST, 'description');
                    $params['logged_by'] = $USER->idu;
                    $params['date_logged'] = getCurrentDate();

                    if (empty($params['water_source_id'])) {
                        $errors[] = "Please select a water source";
                    }

                    if (!is_numeric($params['repair_type_id'])) {
                        $errors[] = "Please select a repair type";
                    }
                    if (strtotime("1970-01-01 03:00:00") === strtotime($params['expenditure_date'])) {
                        $errors[] = "Please select a date";
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
                            logMessage("Expenditure added", 3);
                            header('location:' . SITE_URL . '/?a=edit-expenditure&id=' . $id_expenditure);
                            exit();
                        } else {
                            logMessage("An error occured adding the expenditure. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'edit-expenditure' :

            if (is_logged_in()) {

                if ($USER->can_edit_expenses) {
                    $id_expenditure = getArrayVal($_GET, 'id');

                    $params['water_source_id'] = getArrayVal($_POST, 'water_source_id');
                    $params['repair_type_id'] = getArrayVal($_POST, 'repair_type_id');
                    $params['expenditure_date'] = getCurrentDate(getArrayVal($_POST, 'expenditure_date'));
                    $params['expenditure_cost'] = getArrayVal($_POST, 'expenditure_cost');
                    $params['benefactor'] = getArrayVal($_POST, 'benefactor');
                    $params['description'] = getArrayVal($_POST, 'description');
// $params['logged_by'] = $USER->idu;

                    if (empty($params['water_source_id'])) {
                        $errors[] = "Please select a water source";
                    }

                    if (!is_numeric($params['repair_type_id'])) {
                        $errors[] = "Please select a repair type";
                    }
                    if (strtotime("1970-01-01 03:00:00") === strtotime($params['expenditure_date'])) {
                        $errors[] = "Please select a date";
                    }
                    if (empty($params['expenditure_cost'])) {
                        $errors[] = "Repair cost must be anumber and not zero (0)";
                    }
                    if (empty($params['benefactor'])) {
                        $errors[] = "The benefactor's name is required.";
                    }

                    if (empty($params['description'])) {
                        $errors[] = "Please describe the expenditure";
                    }

                    if (!isset($errors)) {

//$id_expenditure = $dbhandle->Update('expenditures', $params, array('id_expenditure' => $id_expenditure));
                        if ($dbhandle->Update('expenditures', $params, array('id_expenditure' => $id_expenditure))) {
                            logMessage("Expenditure updated", 3);
                            header('location:' . SITE_URL . '/?a=edit-expenditure&id=' . $id_expenditure);
                            exit();
                        } else {
                            logMessage("An error occured updating the expenditure. Please try again later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }

            break;
        case'send-sms':
            if (is_logged_in()) {
                if ($USER->can_send_sms) {
                    $msg_content = trim(getArrayVal($_POST, 'msg_content'));
                    $recepients = array();
                    $sanitized_system_users_recepients = array();
                    $system_users_recepients = isset($_POST['system_users_recepients']) ? $_POST['system_users_recepients'] : array();
                    foreach ($system_users_recepients as $key => $system_users_recepient) {
                        $sanitized_system_users_recepients[] = getArrayVal($system_users_recepients, $key);
                    }

                    if (!empty($sanitized_system_users_recepients)) {
                        $query = "SELECT pnumber FROM " . TABLE_PREFIX . "users WHERE pnumber <>'' AND idu IN (" . implode(',', $sanitized_system_users_recepients) . ")";
                        $result = $dbhandle->RunQueryForResults($query);
                        while ($row = $result->fetch_assoc()) {
                            $recepients[] = autoCorrectPnumber($row['pnumber']);
                        }
                    }

                    $sanitized_water_user_recepients = array();
                    $water_user_recepients = isset($_POST['water_user_recepients']) ? $_POST['water_user_recepients'] : array();
                    foreach ($water_user_recepients as $key => $water_user_recepient) {
                        $sanitized_water_user_recepients[] = getArrayVal($water_user_recepients, $key);
                    }

                    if (!empty($sanitized_water_user_recepients)) {
                        $query = "SELECT pnumber FROM " . TABLE_PREFIX . "water_users WHERE pnumber <>'' AND id_user IN (" . implode(',', $sanitized_water_user_recepients) . ")";
                        $result = $dbhandle->RunQueryForResults($query);
                        while ($row = $result->fetch_assoc()) {
                            $recepients[] = autoCorrectPnumber($row['pnumber']);
                        }
                    }
                    $recepients = array_unique($recepients);
                    //$recepients = implode(',', array_merge($sanitized_system_users_recepients, $sanitized_water_user_recepients));
                    //var_dump($recepients);
                    if (empty($recepients)) {
                        $errors[] = "Please select a recepient";
                    }

                    if (empty($msg_content)) {
                        $errors[] = "You cannot send an empty message";
                    }
                    if (!isset($errors)) {
                        $params['message_content'] = $msg_content;
                        $params['system_users'] = implode(',', $sanitized_system_users_recepients);
                        $params['water_users'] = implode(',', $sanitized_water_user_recepients);
                        $params['date_sent'] = getCurrentDate();
                        $params['sent_by'] = $USER->idu;
                        $params['sent'] = 0;
                        if (ENABLE_SMS == 1) {
                            if (send_sms_message(implode(',', $recepients), $msg_content)) {
                                $params['sent'] = 1;
                            } else {
                                $params['sent'] = 0;
                            }
                        } else {
                            logMessage("SMS sending has been disabled. However your SMS message has been saved. You will just click resend later  and it will be sent to users");
                        }


                        $id_sms = $dbhandle->Insert('sms_messages', $params);
                        if (is_int($id_sms)) {
                            logMessage("SMS sent and logged", 3);
                            header('location:' . SITE_URL . '/?a=all-sms');
                            exit();
                        } else {
                            logMessage("An error occured logging the SMS message. However the SMS message has been sent.", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case'send-notification':
            if (is_logged_in()) {
                if ($USER->can_send_sms) {
                    $msg_content = trim(getArrayVal($_POST, 'msg_content'));
                    $recepients = array();
                    $sanitized_system_users_recepients = array();
                    $system_users_recepients = isset($_POST['system_users_recepients']) ? $_POST['system_users_recepients'] : array();
                    foreach ($system_users_recepients as $key => $system_users_recepient) {
                        $sanitized_system_users_recepients[] = getArrayVal($system_users_recepients, $key);
                    }

                    if (!empty($sanitized_system_users_recepients)) {
                        $query = "SELECT gcm_regid FROM " . TABLE_PREFIX . "users WHERE gcm_regid <>'' AND idu IN (" . implode(',', $sanitized_system_users_recepients) . ")";
                        $result = $dbhandle->RunQueryForResults($query);
                        while ($row = $result->fetch_assoc()) {
                            $recepients[] = $row['gcm_regid'];
                        }
                    }

                    $recepients = array_unique($recepients);
                    //$recepients = implode(',', array_merge($sanitized_system_users_recepients, $sanitized_water_user_recepients));
                    //var_dump($recepients);
                    if (empty($recepients)) {
                        $errors[] = "Please select a recepient";
                    }

                    if (empty($msg_content)) {
                        $errors[] = "You cannot send an empty message";
                    }
                    if (!isset($errors)) {
                        $params['message_content'] = $msg_content;
                        $params['system_users'] = implode(',', $sanitized_system_users_recepients);
                        $params['date_sent'] = getCurrentDate();
                        $params['sent_by'] = $USER->idu;
                        $params['sent'] = 0;
                        if (ENABLE_PUSH_NOTIFICATIONS == 1) {
                            if (send_push_notification(implode('|', $recepients), $msg_content)) {
                                $params['sent'] = 1;
                            } else {
                                $params['sent'] = 0;
                            }
                        } else {
                            logMessage("Push notifications has been disabled. However your message has been saved. You will just click resend later  and it will be sent to users");
                        }


                        $id_sms = $dbhandle->Insert('push_messages', $params);
                        if (is_int($id_sms)) {
                            logMessage("Notification sent and logged", 3);
                            header('location:' . SITE_URL . '/?a=all-notifications');
                            exit();
                        } else {
                            logMessage("An error occured logging the notification. However the notification message has been sent.", 0);
                        }
                    } else {

                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case'add-user':
            if (is_logged_in()) {
                if ($USER->can_add_system_users) {
                    $params['fname'] = getArrayVal($_POST, 'fname');
                    $params ['lname'] = getArrayVal($_POST, 'lname');
                    $params['username'] = strtolower(getArrayVal($_POST, 'username'));
                    $params ['email'] = strtolower(getArrayVal($_POST, 'email'));
                    $params['pnumber'] = getArrayVal($_POST, 'pnumber');
                    $params['group_id'] = getArrayVal($_POST, 'group_id');
                    $params['password'] = getArrayVal($_POST, 'password');
                    $params['date_added'] = getCurrentDate();
                    $cpass = getArrayVal($_POST, 'cpassword');

                    if (empty($params['fname'])) {
                        $errors[] = "First name is required";
                    }
                    if (empty($params['lname'])) {
                        $errors[] = "Last name is required";
                    }
                    if (empty($params['username'])) {
                        $errors[] = "A valid username is required";
                    } elseif (!isValid('username', $params['username'])) {
                        $errors[] = "The username you chose is not valid";
                    } elseif (isTaken('username', $params['username'])) {
                        $errors[] = "That username is already in use";
                    }

                    if (empty($params['email'])) {
                        $errors[] = "A valid email is required";
                    } elseif (!isValid('email', $params['email'])) {
                        $errors[] = "Please use a valid email address";
                    } elseif (isTaken('email', $params['email'])) {
                        $errors [] = "That email address is already in use";
                    }

                    if (!empty($params['pnumber']) && isTaken('pnumber', $params['pnumber'])) {
                        $errors[] = "That phone number is already in use";
                    } elseif (!empty($params['pnumber']) && !isValid("pnumber", $params['pnumber'])) {
                        $errors[] = "Please use a valid phone number";
                    }

                    if (empty($params['group_id'])) {
                        $errors[] = "A valid role is required";
                    }

                    if (empty($params['password'])) {
                        $errors [] = "A valid password is required";
                    } elseif (strcmp($params['password'], $cpass) !== 0) {
                        $errors[] = "Passwords do not match";
                    } else {
                        $params['password'] = sha1($params['password']);
                    }

                    $params['active'] = intval(getArrayVal($_POST, 'active'));

                    $message = $SYSTEM_CONFIG['account_created_email_template'];

                    $TEMPLATE_PARAMS = array(
                        'system_name' => SYSTEM_NAME,
                        'site_url' => SITE_URL,
                        'email' => $params['email'],
                        'username' => $params['username'],
                        'password' => $cpass,
                        'pnumber' => $params['pnumber'],);

                    if (!isset($errors)) {
                        $uid = $dbhandle->Insert('users', $params);
                        if (is_int($uid)) {

                            if (isset($params['email'])) {
                                $message = $SYSTEM_CONFIG ['account_created_email_template'];
                                send_email($params['email'], 'Your ' . SYSTEM_NAME . ' password has been reset', $message);
                            }

                            $message = $SYSTEM_CONFIG['account_created_sms_template'];
                            if (!empty($params['pnumber'])) {
                                send_sms_message($params['pnumber'], $message);
                            }

                            logMessage("User added", 3);
                            header('location:' . SITE_URL . '/?a=edit-user&id=' . $uid);
                            exit();
                        } else {
                            logMessage("An error occured adding the user. Please try agai n later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }

            break;
        case'edit-user' :
            if (is_logged_in()) {

                $uid = getArrayVal($_GET, 'id');
                if ($USER->can_edit_system_users || $uid == $USER->idu) {

                    $query = "SELECT idu,group_id,username,pnumber,email,fname,lname,last_login FROM " . TABLE_PREFIX . "users WHERE idu=$uid";
                    $result = $dbhandle->RunQueryForResults($query);
                    $account = $result->fetch_assoc();
                    if (isset($account['idu'])) {

                        $params['fname'] = getArrayVal($_POST, 'fname');
                        $params['lname'] = getArrayVal($_POST, 'lname');
                        $params['email'] = strtolower(getArrayVal($_POST, 'email'));
                        $params['pnumber'] = getArrayVal($_POST, 'pnumber');
                        $params['password'] = getArrayVal($_POST, 'password');
                        $cpass = getArrayVal($_POST, 'cpassword');

                        if (empty($params['fname'])) {
                            $errors[] = "First name is required";
                        }
                        if (empty($params['lname'])) {
                            $errors[] = "Last name is required";
                        }

                        if (empty($params['email'])) {
                            $errors[] = "A valid email is required";
                        } elseif (!isValid('email', $params['email'])) {
                            $errors [] = "Please use a valid email address";
                        } elseif (isTaken('email', $params['email']) && $account['email'] !== $params['email']) {
                            $errors[] = "That email address is already in use";
                        }

                        if (!empty($params['pnumber']) && (isTaken('pnumber', $params['pnumber']) && $account['pnumber'] !== $params['pnumber'])) {
                            $errors [] = "That phone number is already in use";
                        } elseif (!empty($params['pnumber']) && !isValid("pnumber", $params['pnumber'])) {
                            $errors[] = "Please use a valid phone number";
                        }

                        if ($USER->can_edit_system_users) {

                            $params['username'] = getArrayVal($_POST, 'username');
                            $params['group_id'] = getArrayVal($_POST, 'group_id');

                            if (empty($params['username'])) {
                                $errors[] = "A valid username is required";
                            } elseif (!isValid('username', $params['username'])) {
                                $errors [] = "The username you chose is not valid";
                            } elseif (isTaken('username', $params['username']) && $account['username'] !== $params['username']) {
                                $errors[] = "That username is already in use";
                            }

                            if (empty($params ['group_id'])) {
                                $errors[] = "A valid role is required";
                            }

                            $params['active'] = intval(getArrayVal($_POST, 'active'));
                        }




                        if (empty($params['password']) && empty($cpass)) {
                            unset($params['password']);
                        } elseif (strcmp($params['password'], $cpass) !== 0) {
                            $errors[] = "Passwords do not match";
                        } else {
                            $params['password'] = sha1($params['password']);
                        }




//var_dump($params);
//
                        if (!isset($errors)) {
                            if ($dbhandle->Update('users', $params, array('idu' => $uid))) {
                                logMessage("Account updated", 3);
                                header('location:' . SITE_URL . '/?a=edit-user&id=' . $uid);
                                exit();
                            } else {
                                logMessage("An error occured updating the account. Pl e ase try again later", 0);
                            }
                        } else {
                            foreach ($errors as $error) {
                                logMessage($error, 0);
                            }
                        }
                    } else {
                        logMessage("That user does not exist", 0);
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case'add-user-group' :
            if (is_logged_in()) {
                if ($USER->can_add_user_permissions) {

                    $params = array(
                        'group_name' => getArrayVal($_POST, 'group_name'),
                        'group_is_enabled' => getArrayVal($_POST, 'group_is_enabled'),
                        'can_access_system_config' => getArrayVal($_POST, 'can_access_system_config'),
                        'can_receive_emails' => getArrayVal($_POST, 'can_receive_emails'),
                        'can_access_app' => getArrayVal($_POST, 'can_access_app'),
                        'can_send_sms' => getArrayVal($_POST, 'can_send_sms'),
                        'can_receive_push_notifications' => getArrayVal($_POST, 'can_receive_push_notifications'),
                        'can_send_sms' => getArrayVal($_POST, 'can_send_sms'),
                        'can_submit_attendant_daily_sales' => getArrayVal($_POST, 'can_submit_attendant_daily_sales'),
                        'can_approve_attendants_submissions' => getArrayVal($_POST, 'can_approve_attendants_submissions'),
                        'can_approve_treasurers_submissions' => getArrayVal($_POST, 'can_approve_treasurers_submissions'),
                        'can_cancel_attendant_daily_sales' => getArrayVal($_POST, 'can_cancel_attendant_daily_sales'),
                        'can_cancel_attendants_submissions' => getArrayVal($_POST, 'can_cancel_attendants_submissions'),
                        'can_cancel_treasurers_submissions' => getArrayVal($_POST, 'can_cancel_treasurers_submissions'),
                        'can_add_water_users' => getArrayVal($_POST, 'can_add_water_users'),
                        'can_edit_water_users' => getArrayVal($_POST, 'can_edit_water_users'),
                        'can_delete_water_users' => getArrayVal($_POST, 'can_delete_water_users'),
                        'can_view_water_users' => getArrayVal($_POST, 'can_view_water_users'),
                        'can_add_sales' => getArrayVal($_POST, 'can_add_sales'),
                        'can_edit_sales' => getArrayVal($_POST, 'can_edit_sales'),
                        'can_delete_sales' => getArrayVal($_POST, 'can_delete_sales'),
                        'can_view_sales' => getArrayVal($_POST, 'can_view_sales'),
                        'can_view_personal_savings' => getArrayVal($_POST, 'can_view_personal_savings'),
                        'can_view_water_source_savings' => getArrayVal($_POST, 'can_view_water_source_savings'),
                        'can_add_water_sources' => getArrayVal($_POST, 'can_add_water_sources'),
                        'can_edit_water_sources' => getArrayVal($_POST, 'can_edit_water_sources'),
                        'can_delete_water_sources' => getArrayVal($_POST, 'can_delete_water_sources'),
                        'can_view_water_sources' => getArrayVal($_POST, 'can_view_water_sources'),
                        'can_add_repair_types' => getArrayVal($_POST, 'can_add_repair_types'),
                        'can_edit_repair_types' => getArrayVal($_POST, 'can_edit_repair_types'),
                        'can_delete_repair_types' => getArrayVal($_POST, 'can_delete_repair_types'),
                        'can_view_repair_types' => getArrayVal($_POST, 'can_view_repair_types'),
                        'can_add_expenses' => getArrayVal($_POST, 'can_add_expenses'),
                        'can_edit_expenses' => getArrayVal($_POST, 'can_edit_expenses'),
                        'can_delete_expenses' => getArrayVal($_POST, 'can_delete_expenses'),
                        'can_view_expenses' => getArrayVal($_POST, 'can_view_expenses'),
                        'can_add_system_users' => getArrayVal($_POST, 'can_add_system_users'),
                        'can_edit_system_users' => getArrayVal($_POST, 'can_edit_system_users'),
                        'can_delete_system_users' => getArrayVal($_POST, 'can_delete_system_users'),
                        'can_view_system_users' => getArrayVal($_POST, 'can_view_system_users'),
                        'can_add_user_permissions' => getArrayVal($_POST, 'can_add_user_permissions'),
                        'can_edit_user_permissions' => getArrayVal($_POST, 'can_edit_user_permissions'),
                        'can_delete_user_permissions' => getArrayVal($_POST, 'can_delete_user_permissions'),
                        'can_view_user_permissions' => getArrayVal($_POST, 'can_view_user_permissions')
                    );



                    if (empty($params['group_name'])) {

                        $errors[] = "A group's name is required";
                    }

                    foreach ($params as $key => $param) {
                        if ($key !== 'group_name') {
                            if (is_numeric($param)) {
                                $params[$key] = intval($param);
                            } else {
                                $params[$key] = 0;
                            }
                        }
                    }

                    if (!isset($errors)) {
                        $uid = $dbhandle->Insert('user_groups', $params);
                        if (is_int($uid)) {
                            logMessage("User group added", 3);
                            header('location:' . SITE_URL . '/?a=edit-user-group&id=' . $uid);
                            exit();
                        } else {
                            logMessage("An error occured adding the user. Please  try agai n later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }
            break;
        case 'edit-user-group':
            if (is_logged_in()) {

                if ($USER->can_edit_user_permissions) {
                    $id_group = getArrayVal($_GET, 'id');

                    $params = array(
                        'group_name' => getArrayVal($_POST, 'group_name'),
                        'group_is_enabled' => getArrayVal($_POST, 'group_is_enabled'),
                        'can_access_system_config' => getArrayVal($_POST, 'can_access_system_config'),
                        'can_receive_emails' => getArrayVal($_POST, 'can_receive_emails'),
                        'can_access_app' => getArrayVal($_POST, 'can_access_app'),
                        'can_send_sms' => getArrayVal($_POST, 'can_send_sms'),
                        'can_receive_push_notifications' => getArrayVal($_POST, 'can_receive_push_notifications'),
                        'can_send_sms' => getArrayVal($_POST, 'can_send_sms'),
                        'can_submit_attendant_daily_sales' => getArrayVal($_POST, 'can_submit_attendant_daily_sales'),
                        'can_approve_attendants_submissions' => getArrayVal($_POST, 'can_approve_attendants_submissions'),
                        'can_approve_treasurers_submissions' => getArrayVal($_POST, 'can_approve_treasurers_submissions'),
                        'can_cancel_attendant_daily_sales' => getArrayVal($_POST, 'can_cancel_attendant_daily_sales'),
                        'can_cancel_attendants_submissions' => getArrayVal($_POST, 'can_cancel_attendants_submissions'),
                        'can_cancel_treasurers_submissions' => getArrayVal($_POST, 'can_cancel_treasurers_submissions'),
                        'can_add_water_users' => getArrayVal($_POST, 'can_add_water_users'),
                        'can_edit_water_users' => getArrayVal($_POST, 'can_edit_water_users'),
                        'can_delete_water_users' => getArrayVal($_POST, 'can_delete_water_users'),
                        'can_view_water_users' => getArrayVal($_POST, 'can_view_water_users'),
                        'can_add_sales' => getArrayVal($_POST, 'can_add_sales'),
                        'can_edit_sales' => getArrayVal($_POST, 'can_edit_sales'),
                        'can_delete_sales' => getArrayVal($_POST, 'can_delete_sales'),
                        'can_view_sales' => getArrayVal($_POST, 'can_view_sales'),
                        'can_view_personal_savings' => getArrayVal($_POST, 'can_view_personal_savings'),
                        'can_view_water_source_savings' => getArrayVal($_POST, 'can_view_water_source_savings'),
                        'can_add_water_sources' => getArrayVal($_POST, 'can_add_water_sources'),
                        'can_edit_water_sources' => getArrayVal($_POST, 'can_edit_water_sources'),
                        'can_delete_water_sources' => getArrayVal($_POST, 'can_delete_water_sources'),
                        'can_view_water_sources' => getArrayVal($_POST, 'can_view_water_sources'),
                        'can_add_repair_types' => getArrayVal($_POST, 'can_add_repair_types'),
                        'can_edit_repair_types' => getArrayVal($_POST, 'can_edit_repair_types'),
                        'can_delete_repair_types' => getArrayVal($_POST, 'can_delete_repair_types'),
                        'can_view_repair_types' => getArrayVal($_POST, 'can_view_repair_types'),
                        'can_add_expenses' => getArrayVal($_POST, 'can_add_expenses'),
                        'can_edit_expenses' => getArrayVal($_POST, 'can_edit_expenses'),
                        'can_delete_expenses' => getArrayVal($_POST, 'can_delete_expenses'),
                        'can_view_expenses' => getArrayVal($_POST, 'can_view_expenses'),
                        'can_add_system_users' => getArrayVal($_POST, 'can_add_system_users'),
                        'can_edit_system_users' => getArrayVal($_POST, 'can_edit_system_users'),
                        'can_delete_system_users' => getArrayVal($_POST, 'can_delete_system_users'),
                        'can_view_system_users' => getArrayVal($_POST, 'can_view_system_users'),
                        'can_add_user_permissions' => getArrayVal($_POST, 'can_add_user_permissions'),
                        'can_edit_user_permissions' => getArrayVal($_POST, 'can_edit_user_permissions'),
                        'can_delete_user_permissions' => getArrayVal($_POST, 'can_delete_user_permissions'),
                        'can_view_user_permissions' => getArrayVal($_POST, 'can_view_user_permissions')
                    );

                    if (empty($params['group_name'])) {
                        $errors[] = "A group's  name is required";
                    }

/// var_dump($params);
//

                    foreach ($params as $key => $param) {
                        if ($key !== 'group_name') {
                            if (is_numeric($param)) {
                                $params[$key] = intval($param);
                            } else {
                                $params[$key] = 0;
                            }
                        }
                    } if (!isset($errors)) {
                        if ($dbhandle->Update('user_groups', $params, array('id_group' => $id_group))) {
                            logMessage("User group updated", 3);
                            header('location:' . SITE_URL . '/?a=edit-user-group&id=' . $id_group);
                            exit();
                        } else {
                            logMessage("An error occured adding the user. Please  try agai n later", 0);
                        }
                    } else {
                        foreach ($errors as $error) {
                            logMessage($error, 0);
                        }
                    }
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }

            break;
        case "configurations":
            $tab = getArrayVal($_GET, 'tab');
            if (is_logged_in()) {
                if ($USER->can_access_system_config) {
                    switch ($tab) {
                        case 'seo' : $params ['robots'] = getArrayVal($_POST, 'robots');
                            $params ['site_desc'] = getArrayVal($_POST, 'site_desc');
                            $params['site_keywords'] = getArrayVal($_POST, 'site_keywords');
                            if (!isset($errors)) {
                                if ($dbhandle->Update('settings', $params, array('id_system' => 1))) {
                                    logMessage("SEO Settings saved", 3);
                                    header('location:' . SITE_URL . '/?a=configurations');
                                    exit();
                                } else {
                                    logMessage("An error occured saving your SEO settings. Please t ry again later", 0);
                                }
                            } else {
                                foreach ($errors as $error) {
                                    logMessage($error, 0);
                                }
                            }

                            break;
                        case 'configuration':

                            $params['system_name'] = getArrayVal($_POST, 'system_name');
                            if (empty($params['system_name'])) {
                                $errors[] = "The system name is required";
                            }
                            $params['system_status'] = getArrayVal($_POST, 'system_status');
                            $params['enable_water_user_registrations'] = getArrayVal($_POST, 'enable_water_user_registrations');
                            $params['default_locale_coordinates'] = getArrayVal($_POST, 'default_locale_coordinates');
                            if (!isset($errors)) {
                                if ($dbhandle->Update('settings', $params, array('id_system' => 1))) {
                                    logMessage("Configurations saved", 3);
                                    header('location:' . SITE_URL . '/?a=configurations&tab=configuration');
                                    exit();
                                } else {
                                    logMessage("An error occured saving your configurations. Please try again later", 0);
                                }
                            } else {
                                foreach ($errors as $error) {
                                    logMessage($error, 0);
                                }
                            }

                            break;
                        case 'messages-and-templates':

                            $i = getArrayVal($_GET, 'i');

                            switch ($i) {
                                case '':
                                    $params['account_created_email_template'] = getArrayVal($_POST, 'account_created_email_template');
                                    $params['account_created_sms_template'] = strip_tags(getArrayVal($_POST, 'account_created_sms_template'));
                                    $params['recovery_email_template'] = getArrayVal($_POST, 'recovery_email_template');
                                    $params['recovery_sms_template'] = strip_tags(getArrayVal($_POST, 'recovery_sms_template'));
                                    $params['funds_accountability_email_template'] = getArrayVal($_POST, 'funds_accountability_email_template');
                                    $params['funds_accountability_sms_template'] = strip_tags(getArrayVal($_POST, 'funds_accountability_sms_template'));
                                    if (!isset($errors)) {
                                        if ($dbhandle->Update('settings', $params, array('id_system' => 1))) {
                                            logMessage("Templates saved", 3);
                                            header('location:' . SITE_URL . '/?a=configurations&tab=messages-and-templates');
                                            exit();
                                        } else {
                                            logMessage("An error occured saving your templates. Please try again later", 0);
                                        }
                                    } else {
                                        foreach ($errors as $error) {
                                            logMessage($error, 0);
                                        }
                                    }

                                    break;
                                case '1':

                                    $params['enable_emails'] = getArrayVal($_POST, 'enable_emails');
                                    $params['enable_sms'] = getArrayVal($_POST, 'enable_sms');
                                    $params['sms_api_username'] = getArrayVal($_POST, 'sms_api_username');
                                    $params['sms_api_key'] = getArrayVal($_POST, 'sms_api_key');

                                    $params['enable_acountablility_sms'] = getArrayVal($_POST, 'enable_acountablility_sms');
                                    $params['acountablility_cycle'] = getArrayVal($_POST, 'acountablility_cycle');
                                    $params['batch_schedule_date'] = getCurrentDate(getArrayVal($_POST, 'batch_schedule_date'));
                                    $params['acountablility_recipients'] = getArrayVal($_POST, 'acountablility_recipients');

                                    $params['enable_push_notifications'] = getArrayVal($_POST, 'enable_push_notifications');
                                    $params['google_api_key'] = getArrayVal($_POST, 'google_api_key');

                                    if (!isset($errors)) {
                                        if ($dbhandle->Update('settings', $params, array('id_system' => 1))) {
                                            logMessage("Messaging settings saved", 3);
                                            header('location:' . SITE_URL . '/?a=configurations&tab=messages-and-templates&i=1');
                                            exit();
                                        } else {
                                            logMessage("An error occured saving your templates. Please try again later", 0);
                                        }
                                    } else {
                                        foreach ($errors as $error) {
                                            logMessage($error, 0);
                                        }
                                    } break;
                                default:
                                    logMessage("Undefined request", 0);
                                    break;
                            } break;

                        default:
                            logMessage("Undefined request", 0);
                            break;
                    }
                    break;
                } else {
                    logMessage("You do not have the required rights to perform this action");
                }
            } else {
                logMessage("You need to be logged in to perform the action");
            }

            $location = SITE_URL . '/?a=configurations';
            if (!empty($tab)) {
                if ($tab !== 'seo') {
                    $location.="&tab=$tab";
                }
            }
            header('location:' . $location);
            exit();
        default :
            break;
    }
}

switch ($action) {
    case 'logout':
        session_destroy();
        header("location: " . SITE_URL);
        exit();
        break;
    case 'activate-user' :

        if (is_logged_in()) {
            if ($USER->can_edit_system_users) {
                $uid = getArrayVal($_GET, 'id');

                if ($dbhandle->CheckIFExists("users", array('idu' => $uid))) {
                    if ($dbhandle->Update('users', array('active' => 1), array('idu' => $uid))) {
                        logMessage("account activated.", 3);
                    } else {
                        logMessage("Failed to activate the account, please try again later.");
                    }
                } else {
                    logMessage("User does not exist.");
                }
            } else {
                logMessage("You do not have sufficient permisions to perform the action.");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=users');
        exit();
        break;
    case 'deactivate-user' :
        if (is_logged_in()) {
            if ($USER->can_edit_system_users) {
                $uid = getArrayVal($_GET, 'id');
                if (intval($USER->idu) != intval($uid)) {
                    if ($dbhandle->CheckIFExists("users", array('idu' => $uid))) {
                        if ($dbhandle->Update('users', array('active' => 0), array('idu' => $uid))) {
                            logMessage("account activated.", 3);
                        } else {
                            logMessage("Failed to deactivate the account, please try again later.");
                        }
                    } else {
                        logMessage("User does not exist.");
                    }
                } else {
                    logMessage("You cannot deactivate your own account.");
                }
            } else {
                logMessage("You do not have sufficient permisions to perform the action.");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=users');
        exit();
        break;
    case 'submit-attendant-collections':
        $HTTP_REFERER = getArrayVal($_SERVER, 'HTTP_REFERER');
        if (is_logged_in()) {
            if ($USER->can_submit_attendant_daily_sales) {
                $timestamp = getArrayVal($_GET, 't');
                $water_source_id = getArrayVal($_GET, 'id');
                $sold_by = getArrayVal($_GET, 'idu');
                if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                    $sale_date = date("Y-m-d", $timestamp);
                }

                if (isset($sale_date) && $dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                    $month = date("m", strtotime($sale_date));
                    $year = date("Y", strtotime($sale_date));
                    $day = date("d", strtotime($sale_date));

                    $query = "UPDATE sales SET submitted_to_treasurer=1, submitted_by=" . $USER->idu . ", submittion_to_treasurer_date='" . getCurrentDate() . "', treasurerer_approval_status=0 WHERE sold_by=$sold_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND submitted_to_treasurer=0 AND treasurerer_approval_status<>1";

                    if ($dbhandle->RunQueryForResults($query)) {
                        $request_status = 1;
                        logMessage('Your request has been received and is awaiting approval. The savings submited are now pending. ', 3);
                    } else {
                        logMessage('An error occured making your request. Please try again later.', 0);
                    }
                } else {
                    logMessage('An error occured making your request. Invalid date. Please try again later.', 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . "/?a=attendants-submissions");
        exit();
        break;
    case 'approve-attendant-collections':
        $HTTP_REFERER = getArrayVal($_SERVER, 'HTTP_REFERER');
        if (is_logged_in()) {
            if ($USER->can_approve_attendants_submissions) {
                $timestamp = getArrayVal($_GET, 't');
                $water_source_id = getArrayVal($_GET, 'id');
                $submitted_by = getArrayVal($_GET, 'idu');
                if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                    $sale_date = date("Y-m-d", $timestamp);
                }

                if (isset($sale_date) && $dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                    $month = date("m", strtotime($sale_date));
                    $year = date("Y", strtotime($sale_date));
                    $day = date("d", strtotime($sale_date));

                    $query = "UPDATE sales SET submitted_to_treasurer=1, treasurerer_approval_status=1, reviewed_by=" . $USER->idu . ",date_reviewed='" . getCurrentDate() . "' WHERE submitted_by=$submitted_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND submitted_to_treasurer=1 AND treasurerer_approval_status<>1";

                    if ($dbhandle->RunQueryForResults($query)) {
                        $request_status = 1;
                        logMessage('The savings submited have been approved ', 3);
                    } else {
                        logMessage('An error occured making your request. Please try again later.', 0);
                    }
                } else {
                    logMessage('An error occured making your request. Invalid date. Please try again later.', 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . "/?a=treasurers-submissions");
        exit();

        break;
    case 'cancel-attendant-collections':
        $HTTP_REFERER = getArrayVal($_SERVER, 'HTTP_REFERER');
        if (is_logged_in()) {
            if ($USER->can_approve_attendants_submissions) {
                $timestamp = getArrayVal($_GET, 't');
                $water_source_id = getArrayVal($_GET, 'id');
                $submitted_by = getArrayVal($_GET, 'idu');
                if (!empty($timestamp) && strtotime($timestamp) <= strtotime("Thu 01-Jan-1970")) {
                    $sale_date = date("Y-m-d", $timestamp);
                }

                if (isset($sale_date) && $dbhandle->CheckIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                    $month = date("m", strtotime($sale_date));
                    $year = date("Y", strtotime($sale_date));
                    $day = date("d", strtotime($sale_date));

                    $query = "UPDATE sales SET submitted_to_treasurer=0, treasurerer_approval_status=2, reviewed_by=" . $USER->idu . ",date_reviewed='" . getCurrentDate() . "' WHERE submitted_by=$submitted_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND submitted_to_treasurer=1 AND treasurerer_approval_status=0";

                    if ($dbhandle->RunQueryForResults($query)) {
                        $request_status = 1;
                        logMessage('The savings submited have been cancelled ', 3);
                    } else {
                        logMessage('An error occured making your request. Please try again later.', 0);
                    }
                } else {
                    logMessage('An error occured making your request. Invalid date. Please try again later.', 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=treasurers-submissions');
        exit();
        break;
    case 'delete-water-user': if (is_logged_in()) {
            if ($USER->can_delete_water_users) {
                $id_user = getArrayVal($_GET, 'id');
                $query = "SELECT * FROM " . TABLE_PREFIX . "water_users WHERE id_user=$id_user";
                $result = $dbhandle->RunQueryForResults($query);

                if (!empty($result)) {
                    $customer = $result->fetch_assoc();
                }

                if (isset($customer['id_user'])) {

                    if ($USER->can_view_water_source_savings) {
                        if ($dbhandle->Delete("water_users", array('id_user' => $id_user))) {
                            $dbhandle->Delete("sales", array('sold_to' => $id_user));
                            logMessage("Record Deleted.", 3);
                        } else {
                            logMessage("An error occured trying to perform the task. Please try agin later", 0);
                        }
                    } else {
                        if ($dbhandle->Update('water_users', array('marked_for_delete' => 1), array('id_user' => $id_user))) {
                            $request_status = 1;
                            logMessage("Record Deleted.", 3);
                        } else {
                            logMessage("An error occured trying to perform the task. Please try agin later", 0);
                        }
                    }
                } else {
                    logMessage("That customer does not exist", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=water-users');
        exit();
        break;
    case 'delete-sale': if (is_logged_in()) {
            if ($USER->can_delete_sales) {
                $id_sale = getArrayVal($_GET, 'id');
                $query = "SELECT * FROM " . TABLE_PREFIX . "sales WHERE id_sale=$id_sale";
                $result = $dbhandle->RunQueryForResults($query);

                if (!empty($result)) {
                    $sale = $result->fetch_assoc();
                }

                if (isset($sale['id_sale'])) {
                    if ($dbhandle->Delete("sales", array('id_sale' => getArrayVal($_GET, 'id')))) {
                        logMessage("Record Deleted.", 3);
                    } else {
                        logMessage("An error occured trying to perform the task. Please try agin later", 0);
                    }
                } else {
                    logMessage("That sale does not exist", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=sales');
        exit();
        break;
    case 'delete-water-source' :
        if (is_logged_in()) {
            if ($USER->can_delete_water_sources) {
                $id_water_source = getArrayVal($_GET, 'id');
                $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
                $result = $dbhandle->RunQueryForResults($query);

                if (!empty($result)) {
                    $water_source = $result->fetch_assoc();
                } if (isset($water_source['id_water_source'])) {
                    if ($dbhandle->Delete("water_sources", array('id_water_source' => getArrayVal($_GET, 'id')))) {
                        $dbhandle->Delete("water_source_caretakers", array('water_source_id' => $id_water_source));
                        $dbhandle->Delete("water_source_benefactors", array('water_source_id' => $id_water_source));
                        $dbhandle->Delete("sales", array('water_source_id' => $id_water_source));
                        logMessage("Record Deleted.", 3);
                    } else {
                        logMessage("An error occured trying to perform the task. Please try agin later", 0);
                    }
                } else {
                    logMessage("That water source does not exist", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=water-sources');
        exit();
        break;
    case 'delete-user': if (is_logged_in()) {
            if ($USER->can_delete_system_users) {
                $id_user = getArrayVal($_GET, 'id');
                $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE idu=$id_user";
                $result = $dbhandle->RunQueryForResults($query);
                if (!empty($result)) {
                    $user = $result->fetch_assoc();
                } if (isset($user['idu'])) {
                    if ($dbhandle->Delete("users", array('idu' => $id_user))) {
//$dbhandle->Delete("water_source_caretakers", array('uid' => $id_user));
//$dbhandle->Delete("sales", array('sold_by' => $id_user));
                        logMessage("Record Deleted.", 3);
                    } else {
                        logMessage("An error occured trying to perform the task. Please try agin later", 0);
                    }
                } else {
                    logMessage("That user does not exist", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=users');
        exit();
        break;
    case 'delete-repair-type': if (is_logged_in()) {
            if ($USER->can_delete_repair_types) {
                if ($dbhandle->Delete("repair_types", array('id_repair_type' => getArrayVal($_GET, 'id')))) {
                    logMessage("Record Deleted.", 3);
                } else {
                    logMessage("An error occured trying to perform the task. Please try agin later", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=repair-types');
        exit();
        break;
    case 'delete-expenditure': if (is_logged_in()) {
            if ($USER->can_delete_expenses) {
                if ($dbhandle->Delete("expenditures", array('id_expenditure' => getArrayVal($_GET, 'id')))) {
                    logMessage("Record Deleted.", 3);
                } else {
                    logMessage("An error occured trying to perform the task. Please try agin later", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=all-expenditures');
        exit();
        break;
    case 'delete-user-group':
        if (is_logged_in()) {
            if ($USER->can_delete_user_permissions) {
                if ($dbhandle->Delete("user_groups", array('id_group' => getArrayVal($_GET, 'id')))) {
                    logMessage("Record Deleted.", 3);
                } else {
                    logMessage("An error occured trying to perform the task. Please try agin later", 0);
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=user-groups');
        exit();
        break;
    case 'resend-sms':
        if (is_logged_in()) {
            if ($USER->can_send_sms) {
                $id_sms = getArrayVal($_GET, 'id');
                $messages = array();
                $recepients = array();
                $corrected_recepients = array();

                $query = "SELECT * FROM sms_messages "
                        . "LEFT JOIN users ON users.idu=sms_messages.sent_by "
                        // . "LEFT JOIN water_users ON water_users.id_user IN (water_users)
                        . " WHERE sms_messages.id_sms=" . $id_sms;


                $result = $dbhandle->RunQueryForResults($query);

                while ($message = $result->fetch_assoc()) {
                    $system_users = array();

                    if (isset($message['system_users'])) {
                        $query2 = "SELECT pnumber FROM users WHERE idu IN(" . $message['system_users'] . ")";
                        $result2 = $dbhandle->RunQueryForResults($query2);
                        while ($users = $result2->fetch_assoc()) {
                            $system_users[] = $users['pnumber'];
                        }
                    }
                    $message['system_users'] = implode(',', $system_users);

                    $water_users = array();
                    if (isset($message['water_users'])) {
                        $query3 = "SELECT pnumber FROM water_users WHERE id_user IN(" . $message['water_users'] . ")";
                        $result3 = $dbhandle->RunQueryForResults($query3);
                        while ($users = $result3->fetch_assoc()) {
                            $water_users[] = $users['pnumber'];
                        }
                    }
                    $message['water_users'] = implode(',', $water_users);

                    $messages = $message;
                }

                if (!empty($messages)) {
                    $recepients = explode(',', $messages['system_users']);
                    $recepients = array_merge($recepients, explode(',', $messages['water_users']));
                    foreach ($recepients as $recepient) {
                        $corrected_recepients[] = autoCorrectPnumber($recepient);
                    }
                    $corrected_recepients = array_unique($corrected_recepients);
                    //var_dump($messages);
                    if (ENABLE_SMS == 1) {
                        if (send_sms_message(implode(',', $corrected_recepients), $messages['message_content'])) {
                            $params['sent'] = 1;
                            if ($dbhandle->Update('sms_messages', $params, array('id_sms' => $id_sms))) {
                                logMessage("SMS sent", 3);
                                header('location:' . SITE_URL . '/?a=all-sms');
                                exit();
                            } else {
                                logMessage("An error occured logging the SMS message. However the SMS message has been sent.", 0);
                            }
                        } else {
                            $params['sent'] = 0;
                        }
                    } else {
                        logMessage("SMS sending has been disabled. Resending the SMS message is not possible.");
                    }
                } else {
                    logMessage("That SMS does not exist");
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=all-sms');
        exit();
        break;

    case 'resend-push-notification':
        if (is_logged_in()) {
            if ($USER->can_send_sms) {
                $id_sms = getArrayVal($_GET, 'id');
                $messages = array();
                $recepients = array();
                $corrected_recepients = array();

                $query = "SELECT * FROM sms_messages "
                        . "LEFT JOIN users ON users.idu=sms_messages.sent_by "
                        // . "LEFT JOIN water_users ON water_users.id_user IN (water_users)
                        . " WHERE sms_messages.id_sms=" . $id_sms;


                $result = $dbhandle->RunQueryForResults($query);

                while ($message = $result->fetch_assoc()) {
                    $system_users = array();

                    if (isset($message['system_users'])) {
                        $query2 = "SELECT gcm_regid FROM users WHERE idu IN(" . $message['system_users'] . ")";
                        $result2 = $dbhandle->RunQueryForResults($query2);
                        while ($users = $result2->fetch_assoc()) {
                            $system_users[] = $users['gcm_regid'];
                        }
                    }
                    $message['system_users'] = implode('|', $system_users);


                    $messages = $message;
                }
                //var_dump($messages);
                if (!empty($messages)) {
                    if (ENABLE_PUSH_NOTIFICATIONS == 1) {
                        if (send_push_notification($messages['system_users'], $messages['message_content'])) {
                            $params['sent'] = 1;
                            if ($dbhandle->Update('push_messages', $params, array('id_sms' => $id_sms))) {
                                logMessage("Notification sent", 3);
                                header('location:' . SITE_URL . '/?a=all-notifications');
                                exit();
                            } else {
                                logMessage("An error occured logging the SMS message. However the SMS message has been sent.", 0);
                            }
                        } else {
                            $params['sent'] = 0;
                        }
                    } else {
                        logMessage("SMS sending has been disabled. Resending the SMS message is not possible.");
                    }
                } else {
                    logMessage("That SMS does not exist");
                }
            } else {
                logMessage("You do not have the required rights to perform this action");
            }
        } else {
            logMessage("You need to be logged in to perform the action");
        }
        header('location:' . SITE_URL . '/?a=all-sms');
        exit();
        break;

    default:
//logMessage("Undefined request", 0);
        break;
}    