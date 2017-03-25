<?php

$action = $App->getValue("a");
$errors = array();

if (isset($_POST['submit'])) {
    if ($App->can_add_system_users) {
        $params['fname'] = $App->postValue('fname');
        $params['lname'] = $App->postValue('lname');
        $params['username'] = trim(strtolower($App->postValue('username')));
        $params['email'] = strtolower($App->postValue('email'));
        $params['pnumber'] = $App->postValue('pnumber');
        $params['group_id'] = $App->postValue('group_id');
        $password = trim($App->postValue('password'));
        $params['date_added'] = $App->getCurrentDateTime();
        $params['last_updated'] = $App->getCurrentDateTime();
        $cpass = trim($App->postValue('cpassword'));

        if (empty($params['fname'])) {
            $errors[] = "First name is required";
        }
        if (empty($params['lname'])) {
            $errors[] = "Last name is required";
        }
        if (empty($params['username'])) {
            $errors[] = "A valid username is required";
        } elseif (!$App->isValid('username', $params['username'])) {
            $errors[] = "The username you chose is not valid";
        } elseif ($App->isTaken('username', $params['username'])) {
            $errors[] = "That username is already in use";
        }

        if (empty($params['email'])) {
            //$errors[] = "A valid email is required";
        } elseif (!$App->isValid('email', $params['email'])) {
            $errors[] = "Please use a valid email address";
        } elseif ($App->isTaken('email', $params['email'])) {
            $errors [] = "That email address is already in use";
        }

        if (!empty($params['pnumber']) && $App->isTaken('pnumber', $params['pnumber'])) {
            $errors[] = "That phone number is already in use";
        } elseif (!empty($params['pnumber']) && !$App->isValid("pnumber", $params['pnumber'])) {
            $errors[] = "Please use a valid phone number";
        }

        if (empty($params['group_id'])) {
            $errors[] = "A valid role is required";
        }

        if (empty($password)) {
            $errors [] = "A valid password is required";
        } elseif (strcmp($password, $cpass) !== 0) {
            $errors[] = "Passwords do not match";
        }

        $params['active'] = intval($App->postValue('active'));

        if (empty($errors)) {
            $uid = $App->saveUserData($params);
            if (is_int($uid)) {
                $TEMPLATE_PARAMS = array(
                    'first_name' => $params['fname'],
                    'last_name' => $params['lname'],
                    'email' => $params['email'],
                    'username' => $params['username'],
                    'password' => $password,
                    'phone_number' => $params['pnumber']);

                $params = array(
                    'uid' => $uid,
                    'password' => $password
                );
                $uid = $App->saveUserPasswordsData($params);
                if (is_int($uid)) {

                    /* if (isset($params['email'])) {
                      $message = $SYSTEM_CONFIG ['account_created_email_template'];
                      send_email($params['email'], 'Your ' . SYSTEM_NAME . ' password has been reset', $message);
                      }

                      $message = $SYSTEM_CONFIG['account_created_sms_template'];
                      if (!empty($params['pnumber'])) {
                      send_sms_message($params['pnumber'], $message);
                      } */


                    if (!empty($params['email'])) {
                        $template = 'account_created_email_template';
                        $App->setEmailTemplateParams($TEMPLATE_PARAMS);
                        $App->sendEmail($params['email'], $App->getEmailTemplate($App->getLocale(), $template, "title"), $App->getEmailTemplate($App->getLocale(), $template, "body"));
                    }

                    if (!empty($params['pnumber'])) {
                        $template = 'account_created_sms_template';
                        $App->setEmailTemplateParams($TEMPLATE_PARAMS);
                        $App->sendSMS($params['pnumber'], $App->getEmailTemplate($App->getLocale(), $template, "body"));
                    }

                    $App->LogEevent($App->user->uid, $App->event->EVENT_CREATED_SYSTEM_USER_ACCOUNT, $App->getCurrentDateTime(), "", $uid);
                    $App->setSessionMessage("User added", SUCCESS_STATUS_CODE);
                    $App->navigate('/manage/users');
                }
            } else {
                $App->setSessionMessage("An error occured adding the user. Please try agai n later");
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }

    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_DELETE_SYSTEM_USER_ACCOUNT, $App->getCurrentDateTime());
}



foreach ($errors as $error) {
    $App->setSessionMessage($error);
}