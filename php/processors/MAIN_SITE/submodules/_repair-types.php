<?php

$action = $App->getValue("a");
switch ($action) {
    case 'delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_REPAIR_TYPES;
        if ($App->can_delete_repair_types) {
            $id_repair_type = $App->getValue('id');

            $App->con->where('id_repair_type', $id_repair_type);
            $repair_type = $App->con->getOne('repair_types');

            if (isset($repair_type['id_repair_type'])) {
                if ($App->deleteFromDb("repair_types", array('id_repair_type' => $id_repair_type))) {
                    $event = $App->event->EVENT_DELETED_A_REPAIR_TYPE;
                    $App->setSessionMessage("Repair Type Deleted.", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
                }
            } else {
                $App->setSessionMessage("That Repair Type does not exist");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $id_repair_type);
        $App->navigate('/manage/repair-types/');
        break;
    default:
        break;
}