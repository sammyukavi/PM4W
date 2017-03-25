<?php

if (isset($_POST['submit'])) {
    $params['fname'] = $App->postValue('fname');
    $params['lname'] = $App->postValue('lname');
    $params['email'] = strtolower($App->postValue('email'));
    $params['pnumber'] = $App->postValue('pnumber');
    $password = $App->postValue('password');
    $cpass = $App->postValue('cpassword');

    if (empty($params['fname'])) {
        $errors[] = "First name is required";
    }
    if (empty($params['lname'])) {
        $errors[] = "Last name is required";
    }

    if (empty($params['email'])) {
        //$errors[] = "A valid email is required";
    } elseif (!$App->isValid('email', $params['email'])) {
        $errors [] = "Please use a valid email address";
    } elseif ($App->isTaken('email', $params['email']) && $App->user->email !== $params['email']) {
        $errors[] = "That email address is already in use";
    }

    if (!empty($params['pnumber']) && ($App->isTaken('pnumber', $params['pnumber']) && $account['pnumber'] !== $params['pnumber'])) {
        $errors [] = "That phone number is already in use";
    } elseif (!empty($params['pnumber']) && !$App->isValid("pnumber", $params['pnumber'])) {
        $errors[] = "Please use a valid phone number";
    }



    $params['username'] = $App->postValue('username');
    $params['group_id'] = $App->postValue('group_id');

    if (empty($params['username'])) {
        $errors[] = "A valid username is required";
    } elseif (!$App->isValid('username', $params['username'])) {
        $errors [] = "The username you chose is not valid";
    } elseif ($App->isTaken('username', $params['username']) && $App->user->username !== $params['username']) {
        $errors[] = "That username is already in use";
    }

    if (empty($params ['group_id'])) {
        $errors[] = "A valid role is required";
    }

    $params['active'] = intval($App->postValue('active'));


    if (empty($password) && empty($cpass)) {
        unset($params['password']);
    } elseif (strcmp($password, $cpass) !== 0) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $params['last_updated'] = $App->getCurrentDateTime();
        $params['idu'] = $App->user->uid;
        $uid = $App->saveUserData($params);
        if (is_int($uid)) {
            if (!empty($password)) {
                $params = array(
                    'uid' => $App->user->uid,
                    'password' => $password
                );
                $App->saveUserPasswordsData($params);

                $auth_code = $App->generateAlphaNumCode();
                $auth_key = sha1($App->encrypt_decrypt($App->generateAlphaNumCode()) . time() . uniqid("", true));
                while ($App->auth_keysAreTaken($auth_code, $auth_key)) {
                    $auth_code = $App->generateAlphaNumCode();
                    $auth_key = sha1($App->encrypt_decrypt($App->generateAlphaNumCode()) . time() . uniqid("", true));
                }
                $params = array(
                    'uid' => $App->user->uid,
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
                    SESSION_KEYS_IDU => $App->user->uid,
                );

                foreach ($session_data as $sessKey => $sessVal) {
                    $App->setSessionVariable($sessKey, $sessVal);
                    $App->killCookies($sessKey);
                    if ($remember) {
                        $App->setCookieVariable($sessKey, $sessVal);
                    }
                }
            }
            $App->setSessionMessage("Your changes have been successfully saved", SUCCESS_STATUS_CODE);
            $App->navigate('/manage/my-account');
        } else {
            $App->setSessionMessage("An error occured updating the account. Please try again later");
        }
    }
}