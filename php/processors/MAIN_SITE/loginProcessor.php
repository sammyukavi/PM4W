<?php

$App->setPageTitle('Login');
if ($App->isAuthenticated) {
    $App->navigate($App->getSessionVariable("next"));
}

if (isset($_POST['submit'])) {
    $errors = array();

    $username = $App->postValue('username');
    $password = $App->postValue('password');
    $remember = $App->postValue('remember') == '1' ? true : false;

    switch ($App->action) {

        case 'forgot-password':
            $email = $App->postValue('username');
            if (!empty($email)) {
                $columns = array('idu,pnumber,email,active');
                //$query = "SELECT idu,pnumber,email,active FROM " . TABLE_PREFIX . "users WHERE (username='$email' OR pnumber='$email' OR email='$email')";

                $App->con->where('username', $email, "=", "OR");
                $App->con->where('pnumber', $email, "=", "OR");
                $App->con->where('email', $email, "=", "OR");
                $account = $App->con->getOne('users', $columns);
                if (!empty($account) && isset($account['idu'])) {
                    if ($account['active'] == 1) {
                        $password = strtoupper($App->generateAlphaNumCode(6));
                        $params['uid'] = $account['idu'];
                        $params['password'] = $password;
                        if ($App->saveUserPasswordsData($params)) {


                            /* $TEMPLATE_PARAMS = array(
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
                              } */

                            if (!empty($account['email'])) {
                                $template = 'recovery_email_template';
                                $App->setEmailTemplateParams(array('password' => $password));
                                $App->sendEmail($account['email'], $App->getEmailTemplate($App->getLocale(), $template, "title"), $App->getEmailTemplate($App->getLocale(), $template, "body"));
                            }

                            if (!empty($account['pnumber'])) {
                                $template = 'recovery_sms_template';
                                $App->setEmailTemplateParams(array('password' => $password));
                                $App->sendSMS($account['pnumber'], $App->getEmailTemplate($App->getLocale(), $template, "body"));
                            }
                            $App->setSessionMessage("Your password has been reset", SUCCESS_STATUS_CODE);
                            $App->navigate('/login');
                            exit();
                        } else {
                            $App->setSessionMessage("An error occured resetting your password. Please try agin later. If this persists please consult your administrator.");
                        }
                    } else {
                        $App->setSessionMessage("Your account is inactive hence you cannot reset your password. Please consult your administrator for further advice.");
                    }
                } else {
                    $App->setSessionMessage("Your password could not be reset. No account exists with those details");
                }
            } else {
                $App->setSessionMessage("An email, username or phone number is required");
            }
            break;
        default:

            $email = trim($App->postValue("username"));
            $password = $App->encryptPassword(trim($App->postValue("password")));

            $App->con->where('username', $email);
            $App->con->where('password', $password);
            $App->con->join('user_passwords', 'uid = idu', 'LEFT');
            $details = $App->con->getOne("users");

            if (!empty($details)) {
                $auth_code = $App->generateAlphaNumCode();
                $auth_key = sha1($App->encrypt_decrypt($App->generateAlphaNumCode()) . time() . uniqid("", true));
                while ($App->auth_keysAreTaken($auth_code, $auth_key)) {
                    $auth_code = $App->generateAlphaNumCode();
                    $auth_key = sha1($App->encrypt_decrypt($App->generateAlphaNumCode()) . time() . uniqid("", true));
                }
                $params = array(
                    'uid' => $details['idu'],
                    'auth_code' => $auth_code,
                    'auth_key' => $auth_key,
                    'expires' => $App->getCurrentDateTime(time() + $CONFIG['offline_cookie_duration']),
                    'last_updated' => $App->getCurrentDateTime()
                );

                $App->saveUserLoginKeys($params);
                $App->killCookies();
                $session_data = array(
                    SESSION_KEYS_AUTH_KEY => $auth_key,
                    SESSION_KEYS_AUTHCODE => $auth_code,
                    SESSION_KEYS_IDU => $details['idu'],
                );

                foreach ($session_data as $sessKey => $sessVal) {
                    $App->setSessionVariable($sessKey, $sessVal);
                    $App->killCookies($sessKey);
                    if ($remember) {
                        $App->setCookieVariable($sessKey, $sessVal);
                    }
                }
                $next = $App->getSessionVariable("next");
                $next = empty($next) ? '/manage/' : $next;
                $App->navigate($next);
            } else {
                $App->setSessionMessage($App->lang('wrong_email_password'));
            }
            break;
    }
}