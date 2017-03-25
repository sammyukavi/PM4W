<?php

$action = $App->getValue("a");
$errors = array();

$id_repair_type = $App->getValue('id');
if (isset($_POST['submit'])) {
    if ($App->can_edit_repair_types) {
        $params['id_repair_type'] = $id_repair_type;
        $params['repair_type'] = $App->postValue('repair_type');
        $params['active'] = intval($App->postValue('active'));
        $params['last_updated'] = $App->getCurrentDateTime();

        if (empty($params['repair_type'])) {
            $errors[] = "A repair name is required";
        }

        if (empty($errors)) {
            if ($App->saveWaterRepairTypes($params)) {
                $App->setSessionMessage("Repair type updated", SUCCESS_STATUS_CODE);
                //header('location:' . SITE_URL . '/' . ADMIN_FOLDER . '/repair-types/edit/?id=' . $id_repair_type);
                //exit();
                $App->navigate('/manage/repair-types');
            } else {
                $App->setSessionMessage("An error occured adding the repair type. Please try again later");
            }
        } else {
            foreach ($errors as $error) {
                $App->setSessionMessage($error);
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
}


$App->con->where('id_repair_type', $id_repair_type);
$repair = $App->con->getOne('repair_types');
if (!isset($repair['id_repair_type'])) {
    $App->setSessionMessage("Repair type does not exist");
    $App->navigate('/manage/repair-types');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}