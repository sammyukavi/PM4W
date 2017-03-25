<?php

$action = $App->getValue("a");
$errors = array();

if (isset($_POST['submit'])) {
    $params['water_source_id'] = $App->postValue('water_source_id');
    if ($App->can_add_expenses) {
        $params['repair_type_id'] = $App->postValue('repair_type_id');
        $params['expenditure_date'] = $App->getCurrentDateTime($App->postValue('expenditure_date'));
        $params['expenditure_cost'] = $App->postValue('expenditure_cost');
        $params['benefactor'] = $App->postValue('benefactor');
        $params['description'] = $App->postValue('description');
        $params['logged_by'] = $App->user->uid;
        $params['date_created'] = $App->getCurrentDateTime();
        $params['last_updated'] = $App->getCurrentDateTime();

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
            $errors[] = "The benefactors name is required.";
        }

        if (empty($params['description'])) {
            $errors[] = "Please describe the expenditure";
        }

        if (empty($errors)) {
            $id_expenditure = $App->saveExpenditure($params);
            if (is_int($id_expenditure)) {
                $App->LogEevent($App->user->uid, $App->event->EVENT_LOGGED_EXPENDITURE, $App->getCurrentDateTime(), "water_source_id=" . $params['water_source_id'], $id_expenditure);
                $App->setSessionMessage("Expenditure added", SUCCESS_STATUS_CODE);
                $App->navigate('/manage/expenditures');
            } else {
                $App->setSessionMessage("An error occured adding the expenditure. Please try again later");
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_ADD_EXPENDITURE, $App->getCurrentDateTime(), "", $params['water_source_id']);
}


foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
