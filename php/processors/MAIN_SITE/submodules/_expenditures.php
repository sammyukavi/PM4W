<?php

$action = $App->getValue("a");
$errors = array();

$id_water_source = $App->getValue('id');

if (isset($_POST['delete'])) {
    $ids = $App->postValue('ids');
    $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_EXPENDITURE;
    if ($App->can_delete_expenses) {
        if (!empty($ids)) {
            $sql2 = " DELETE FROM " . DB_TABLE_PREFIX . "expenditures WHERE id_expenditure IN(" . implode(",", $ids) . ") ";
            $App->con->rawQuery($sql2);
            $event = $App->event->EVENT_DELETED_EXPENDITURE;
            $App->setSessionMessage("Expenditure(s) Deleted.", SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage("Select expenditure or expenditures to delete");
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
    $App->navigate('/manage/expenditures');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}

switch ($action) {
    case 'delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_EXPENDITURE;
        if ($App->can_delete_expenses) {
            $id_expenditure = $App->getValue('id');
            $query = "SELECT * FROM " . DB_TABLE_PREFIX . "expenditures WHERE id_expenditure=$id_expenditure";
            $expenditure = $App->con->rawQuery($query);

            if (isset($expenditure[0]['id_expenditure'])) {
                if ($App->deleteFromDb("expenditures", array('id_expenditure' => $id_expenditure))) {
                    $event = $App->event->EVENT_DELETED_EXPENDITURE;
                    $App->setSessionMessage("Record Deleted.", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
                }
            } else {
                $App->setSessionMessage("That expenditure does not exist");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $id_sale);
        $App->navigate('/manage/expenditures');
        break;
    default:
        break;
}

$expenditures = array();

$query = " SELECT id_expenditure,water_source_name,repair_type,expenditure_cost,expenditure_date,benefactor, fname,lname FROM " . DB_TABLE_PREFIX . "expenditures "
        . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "expenditures.water_source_id=" . DB_TABLE_PREFIX . "water_sources.id_water_source "
        . " LEFT JOIN " . DB_TABLE_PREFIX . "repair_types ON " . DB_TABLE_PREFIX . "expenditures.repair_type_id=" . DB_TABLE_PREFIX . "repair_types.id_repair_type "
        . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "expenditures.logged_by=" . DB_TABLE_PREFIX . "users.idu ";

if (($App->can_edit_sales || $App->can_view_sales || $App->can_delete_sales || $App->can_view_sales ) && $App->can_view_water_source_savings) {
    if (!empty($id_water_source) && is_numeric($id_water_source)) {
        $query .= " WHERE id_water_source=" . $id_water_source;
    }
} else {
    $query .= " WHERE users.idu=" . $App->user->uid;
}
//echo $query;
$expenditures = $App->con->rawQuery($query);

$App->LogEevent($App->user->uid, $App->event->EVENT_LISTED_EXPENDITURES, $App->getCurrentDateTime());
