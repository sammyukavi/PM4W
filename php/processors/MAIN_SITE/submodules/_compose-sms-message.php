<?php

$errors = array();

if (isset($_POST['submit'])) {
    $event = $App->event->EVENT_ATTEMPTED_TO_QUEUE_SMS_MESSAGE_FOR_SENDING;
    $params = $pnumbers = array();
    $params['type'] = 'sms';
    $params['label'] = 'outbox';
    $params['created_by'] = $App->user->uid;
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
            $App->setSessionMessage("SMS queued for sending", SUCCESS_STATUS_CODE);
            $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
            $App->navigate('/manage/sms-messages');
            exit();
        } else {
            $App->setSessionMessage("An error occured queueing the SMS message for sending.");
        }
    }
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
