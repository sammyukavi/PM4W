<?php

$action = $App->getValue("a");
$errors = array();

if (isset($_POST['submit'])) {
    if ($App->can_add_repair_types) {
        $params['repair_type'] = $App->postValue('repair_type');
        $params['added_by'] = $App->user->uid;        
        $params['active'] = intval($App->postValue('active'));
        $params['date_created'] = $App->getCurrentDateTime();
        $params['last_updated'] = $App->getCurrentDateTime();

        if (empty($params['repair_type'])) {
            $errors[] = "A repair name is required";
        }

        if (empty($errors)) {
            $id_repair_type = $App->saveWaterRepairTypes($params);
            if (is_int($id_repair_type)) {
                $App->setSessionMessage("Repair type added", SUCCESS_STATUS_CODE);                
                $App->navigate('/manage/repair-types');
            } else {
                $App->setSessionMessage("An error occured adding t he repair type. Please try again later");
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}