<?php

$action = $App->getValue("a");
$errors = array();

$uid = $App->getValue('id');

if (isset($_POST['submit'])) {
    if ($App->can_edit_system_users || $uid == $App->user->uid) {
        $params = array();
        //$query = "SELECT idu,group_id,username,pnumber,email,fname,lname,last_login FROM " . TABLE_PREFIX . "users WHERE idu=$uid";
        $App->con->where('idu', $uid);
        $account = $App->con->getOne('users');
        if (isset($account['idu'])) {
            $params['fname'] = $App->postValue('fname');
            $params['lname'] = $App->postValue('lname');
            $params['email'] = strtolower($App->postValue('email'));
            $params['pnumber'] = $App->postValue('pnumber');
            $params['last_updated'] = $App->getCurrentDateTime();
            $password = trim($App->postValue('password'));
            $cpass = trim($App->postValue('cpassword'));

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
            } elseif ($App->isTaken('email', $params['email']) && $account['email'] !== $params['email']) {
                $errors[] = "That email address is already in use";
            }

            if (!empty($params['pnumber']) && ($App->isTaken('pnumber', $params['pnumber']) && $account['pnumber'] !== $params['pnumber'])) {
                $errors [] = "That phone number is already in use";
            } elseif (!empty($params['pnumber']) && !$App->isValid("pnumber", $params['pnumber'])) {
                $errors[] = "Please use a valid phone number";
            }

            if ($App->can_edit_system_users) {

                $params['username'] = trim(strtolower($App->postValue('username')));
                $params['group_id'] = $App->postValue('group_id');

                if (empty($params['username'])) {
                    $errors[] = "A valid username is required";
                } elseif (!$App->isValid('username', $params['username'])) {
                    $errors [] = "The username you chose is not valid";
                } elseif ($App->isTaken('username', $params['username']) && $account['username'] !== $params['username']) {
                    $errors[] = "That username is already in use";
                }

                if (empty($params ['group_id'])) {
                    $errors[] = "A valid role is required";
                }

                $params['active'] = intval($App->postValue('active'));
            }

            if (empty($password) && empty($cpass)) {
                unset($params['password']);
            } elseif (strcmp($password, $cpass) !== 0) {
                $errors[] = "Passwords do not match";
            }

            if (empty($errors)) {
                $params['last_updated'] = $App->getCurrentDateTime();
                $params['idu'] = $uid;
                $uid = $App->saveUserData($params);
                if (is_int($uid)) {
                    if (!empty($password)) {
                        $params = array(
                            'uid' => $uid,
                            'password' => $password
                        );
                        $uid = $App->saveUserPasswordsData($params);
                    }
                    $App->setSessionMessage("User updated", SUCCESS_STATUS_CODE);
                    $App->navigate('/manage/users');
                } else {
                    $App->setSessionMessage("An error occured updating the account. Pl e ase try again later");
                }
            }
        } else {
            $App->setSessionMessage("That user does not exist");
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_UPDATE_SYSTEM_USER_ACCOUNT, $App->getCurrentDateTime(), "", $uid);
}


$App->con->where('idu', $uid);
$account = $App->con->getOne('users');

if (empty($account)) {
    $App->setSessionMessage("Cannot find user");
    $App->navigate('/manage/users');
}



foreach ($errors as $error) {
    $App->setSessionMessage($error);
}