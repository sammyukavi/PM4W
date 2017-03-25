<?php

$action = $App->getValue("a");
$errors = array();

switch ($action) {
    case 'submit':
        $event = $App->event->EVENT_ATTEMPTED_TO_SUBMIT_TREASURER_SALES;
        if ($App->can_submit_attendant_daily_sales) {
            $timestamp = $App->getValue('t');
            $water_source_id = $App->getValue('id');
            $sold_by = $App->getValue('idu');
            if (!empty($timestamp) && $timestamp >= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }
            if (isset($sale_date) && $App->checkIFExists("water_sources", array('id_water_source' => $water_source_id))) {

                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                $query = "UPDATE " . DB_TABLE_PREFIX . "sales SET submitted_to_treasurer=1, submitted_by=" . $App->user->uid . ", submittion_to_treasurer_date='" . $App->getCurrentDateTime() . "', treasurerer_approval_status=0, last_updated='".$App->getCurrentDateTime()."', WHERE sold_by=$sold_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND submitted_to_treasurer=0 AND treasurerer_approval_status<>1";
                if ($App->RunQueryForResults($query)) {
                    $event = $App->event->EVENT_SUBMITTED_TREASURER_SAVINGS;
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
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
        //header('location:' . SITE_URL . '/' . ADMIN_FOLDER . '/savings/approvals/caretakers/');
        //exit();
        $App->navigate('/manage/treasurers-collections/');
        break;

    default:
        break;
}

switch ($action) {
    case "approve-attendant-collections":
        $event = $App->event->EVENT_ATTEMPTED_TO_APPROVE_CARETAKER_SALES;
        $timestamp = $App->getValue('t');
        $water_source_id = $App->getValue('id');
        $submitted_by = $App->getValue('idu');
        if ($App->can_approve_attendants_submissions) {
            if (!empty($timestamp) && $timestamp >= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }

            if (isset($sale_date) && $App->checkIFExists("water_sources", array('id_water_source' => $water_source_id))) {
                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                /* $query = "UPDATE " . DB_TABLE_PREFIX . "sales SET submitted_to_treasurer=1, treasurerer_approval_status=1, reviewed_by=" . $App->user->uid . ",date_reviewed='" . $App->getCurrentDateTime() . "' WHERE submitted_by=$submitted_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND submitted_to_treasurer=1 AND treasurerer_approval_status<>1";

                  $query = "UPDATE " . DB_TABLE_PREFIX . "sales SET submitted_to_treasurer=1,"
                  . "treasurerer_approval_status=1,"
                  . "reviewed_by=" . $App->user->uid . ","
                  . "date_reviewed='" . $App->getCurrentDateTime() . "' WHERE submitted_by=$submitted_by AND submitted_to_treasurer=1 AND treasurerer_approval_status<>1";
                 */
                $params = array(
                    'submitted_to_treasurer' => 1,
                    'treasurerer_approval_status' => 1,
                    'reviewed_by' => $App->user->uid,
                    'date_reviewed' => $App->getCurrentDateTime(),
                    'last_updated' => $App->getCurrentDateTime(),
                );

                $App->con->where('submitted_by', $submitted_by);
                $App->con->where('submitted_to_treasurer', 1);
                $App->con->where('treasurerer_approval_status', 1, '<>');

                if ($App->con->update('sales', $params)) {
                    $event = $App->event->EVENT_APPROVED_CARETAKER_SALES;
                    $App->setSessionMessage('The savings submited have been approved ', SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage('An error occured making your request. Please try again later.');
                }
            } else {
                $App->setSessionMessage('An error occured making your request. Invalid date. Please try again later.');
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "water_source_id=" . $water_source_id, $submitted_by);
        $App->navigate('/manage/treasurers-collections/');
        break;
    case 'cancel-attendant-collections':
        $event = $App->event->EVENT_ATTEMPTED_TO_CANCEL_CARETAKER_SALES;
        $timestamp = $App->getValue('t');
        $water_source_id = $App->getValue('id');
        $submitted_by = $App->getValue('idu');
        if ($App->can_approve_attendants_submissions) {
            if (!empty($timestamp) && $timestamp >= strtotime("Thu 01-Jan-1970")) {
                $sale_date = date("Y-m-d", $timestamp);
            }

            if (isset($sale_date) && $App->checkIFExists("water_sources", array('id_water_source' => $water_source_id))) {
                $month = date("m", strtotime($sale_date));
                $year = date("Y", strtotime($sale_date));
                $day = date("d", strtotime($sale_date));

                /* $query = "UPDATE " . DB_TABLE_PREFIX . "sales SET submitted_to_treasurer=0, treasurerer_approval_status=2, reviewed_by=" . $App->user->uid . ",date_reviewed='" . $App->getCurrentDateTime() . "' WHERE submitted_by=$submitted_by AND MONTH(sale_date)=$month AND YEAR(sale_date)=$year AND DAY(sale_date)=$day AND submitted_to_treasurer=1 AND treasurerer_approval_status=0";

                  $query = "UPDATE " . DB_TABLE_PREFIX . "sales SET "
                  . "submitted_to_treasurer=0,"
                  . "treasurerer_approval_status=2,"
                  . "reviewed_by=" . $App->user->uid . ","
                  . "date_reviewed='" . $App->getCurrentDateTime() . "' WHERE submitted_by=$submitted_by AND submitted_to_treasurer=1 AND treasurerer_approval_status=0";
                 */
                $params = array(
                    'submitted_to_treasurer' => 0,
                    'treasurerer_approval_status' => 2,
                    'reviewed_by' => $App->user->uid,
                    'date_reviewed' => $App->getCurrentDateTime(),
                    'last_updated' => $App->getCurrentDateTime(),
                );

                $App->con->where('submitted_by', $submitted_by);
                $App->con->where('submitted_to_treasurer', 1);
                $App->con->where('treasurerer_approval_status', 0);

                if ($App->con->update('sales', $params)) {
                    $event = $App->event->EVENT_CANCELED_CARETAKER_SALES;
                    $App->setSessionMessage('The savings submited have been cancelled ', SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage('An error occured making your request. Please try again later.');
                }
            } else {
                $App->setSessionMessage('An error occured making your request. Invalid date. Please try again later.');
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "water_source_id=" . $submitted_by, $water_source_id);
        $App->navigate('/manage/treasurers-collections/');
        break;

    default:
        break;
}



foreach ($errors as $error) {
    $App->setSessionMessage($error);
}

$App->LogEevent($App->user->uid, $App->event->EVENT_VIEWED_TREASURER_SAVINGS, $App->getCurrentDateTime());
