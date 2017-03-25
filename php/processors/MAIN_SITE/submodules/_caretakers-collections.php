<?php

$action = $App->getValue("a");
$errors = array();
switch ($action) {
    case 'submit':
        $event = $App->event->EVENT_ATTEMPTED_TO_SUBMIT_CARETAKER_SALES;
        $timestamp = $App->getValue('t');
        $water_source_id = $App->getValue('id');
        $sold_by = $App->getValue('idu');
        if ($App->can_submit_attendant_daily_sales) {
            if (!empty($timestamp) && $timestamp >= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }
            if (isset($sale_date) && $App->checkIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                $params = array(
                    'submitted_to_treasurer' => 1,
                    'submitted_by' => $App->user->uid,
                    'submittion_to_treasurer_date' => $App->getCurrentDateTime(),
                    'treasurerer_approval_status' => 0,
                    'last_updated' => $App->getCurrentDateTime(),
                );

                $App->con->where('sold_by', $sold_by);
                $App->con->where('submitted_to_treasurer', 0);
                $App->con->where('treasurerer_approval_status', 1, '<>');


                if ($App->con->update('sales', $params)) {
                    $event = $App->event->EVENT_SUBMITTED_CARETAKER_SALES;
                    $App->setSessionMessage('Your request has been received and is awaiting approval. The savings submited are now pending. ', SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage('An error occured making your request. Please try again later.');
                }
            } else {
                $App->setSessionMessage('An error occured making your request. Invalid date. Please try again later.');
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "water_source_id=$water_source_id", $sold_by);
        $App->navigate('/manage/caretakers-collections/');
        exit();
        break;

    default:
        break;
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
$App->LogEevent($App->user->uid, $App->event->EVENT_VIEWED_CARETAKER_SALES, $App->getCurrentDateTime());
