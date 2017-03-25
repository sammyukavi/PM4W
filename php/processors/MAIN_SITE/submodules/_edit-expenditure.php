<?php

$action = $App->getValue("a");
$errors = array();

$id_expenditure = $App->getValue('id');

$App->con->where('id_expenditure', $id_expenditure);
$expenditure = $App->con->getOne("expenditures");

if (isset($_POST['submit'])) {
    $params['water_source_id'] = $App->postValue('water_source_id');
    if ($App->can_edit_expenses) {
        $params['id_expenditure'] = $id_expenditure;
        $params['repair_type_id'] = $App->postValue('repair_type_id');
        $params['expenditure_date'] = $App->getCurrentDateTime($App->postValue('expenditure_date'));
        $params['expenditure_cost'] = $App->postValue('expenditure_cost');
        $params['benefactor'] = $App->postValue('benefactor');
        $params['description'] = $App->postValue('description');


        if (empty($params['water_source_id'])) {
            $errors[] = "Please select a water source";
        }

        if (!is_numeric($params['repair_type_id'])) {
            $errors[] = "Please select a repair type";
        }
        if (strtotime("1970-01-01 03:00:00") === strtotime($params['expenditure_date'])) {
            $errors[] = "Please select a date";
        }
        if (empty($params['expenditure_cost'])) {
            $errors[] = "Repair cost must be anumber and not zero (0)";
        }
        if (empty($params['benefactor'])) {
            $errors[] = "The benefactor's name is required.";
        }

        if (empty($params['description'])) {
            $errors[] = "Please describe the expenditure";
        }

        if (empty($errors)) {
            if ($App->saveExpenditure($params)) {
                $App->LogEevent($App->user->uid, $App->event->EVENT_UPDATED_EXPENDITURE, $App->getCurrentDateTime(), "water_source_id=" . $params['water_source_id'], $id_expenditure);
                $App->setSessionMessage("Expenditure updated", SUCCESS_STATUS_CODE);
                $App->navigate('/manage/expenditures');
            } else {
                $App->setSessionMessage("An error occured updating the expenditure. Please try again later", 0);
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_UPDATE_EXPENDITURE, $App->getCurrentDateTime(), "water_source_id=" . $params['water_source_id'], $id_expenditure);
}

if (empty($expenditure)) {
    $App->setSessionMessage("Expenditure does not exist");
    $App->navigate('/manage/expenditures');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}