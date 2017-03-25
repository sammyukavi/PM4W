<?php

$errors = array();
if (isset($_POST['submit'])) {

    if ($App->can_add_water_users) {
        $params['fname'] = trim($App->postValue('fname'));
        $params['lname'] = trim($App->postValue('lname'));
        $params['pnumber'] = trim($App->postValue('pnumber'));
        $params['water_source_id'] = trim($App->postValue('water_source_id'));
        $params['date_added'] = $App->getCurrentDateTime();
        $params['added_by'] = trim($App->postValue('uid'));
        $params['last_updated'] = $App->getCurrentDateTime();

        //vardump($_POST);
        //vardump($params);
        //die();

        if (empty($params['fname'])) {
            $errors[] = "First name is required.";
        }
        if (empty($params['lname'])) {
            $errors[] = "Last name is required.";
        }

        if (!empty($params['pnumber'])) {
            $params['pnumber'] = $App->autoCorrectPnumber($params['pnumber']);
        }

        if (!empty($params['pnumber']) && ($App->isTaken('user_pnumber', $params['pnumber']))) {
            $errors[] = "That phone number is already in use";
        } elseif (!empty($params['pnumber']) && !$App->isValid('pnumber', $params['pnumber'])) {
            $errors[] = "Please use a valid phone number in the format shown";
        }

        if (!$App->checkIFExists("water_source_caretakers", array(
                    'water_source_id' => $params['water_source_id'],
                    'uid' => $params['added_by'])) && !$App->checkIFExists("water_source_treasurers", array(
                    'water_source_id' => $params['water_source_id'],
                    'uid' => $params['added_by']))
        ) {
            $errors[] = "The user you selected is not authorised to add users for this water source. If you feel this is an error, please consult your administrator.";
        }

        if ($App->can_edit_system_users) {
            if (empty($params['added_by'])) {
                $errors[] = "Please select the person under which the customer is added";
            } elseif (!$App->checkIFExists('users', array('idu' => $params['added_by']))) {
                $errors[] = "The user you selected does not exist. Please select the person under which the customer is added";
            }
        } else {
            $params['added_by'] = $App->user->uid;
        }

        if (empty($errors)) {
            $uid = $App->saveWaterUser($params);
            if (is_int($uid)) {
                $App->LogEevent($App->user->uid, "added_water_user", $App->getCurrentDateTime(), "", $uid);
                $App->setSessionMessage("Water User added", SUCCESS_STATUS_CODE);
                $App->navigate('/manage/water-users/');
            } else {
                $App->setSessionMessage("An error occured adding the customer. Please try again later");
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_CREATE_WATER_USER_ACCOUNT, $App->getCurrentDateTime());
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}