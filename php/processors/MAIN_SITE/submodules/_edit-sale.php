<?php

$action = $App->getValue('a');
$errors = array();

$id_sale = $App->getValue('id');


if (isset($_POST['submit'])) {
    if ($App->can_edit_sales) {
        $params['id_sale'] = $id_sale;
        $params['water_source_id'] = $App->postValue('water_source_id');
        $params['sold_by'] = $App->postValue('sold_by');
        $params['sold_to'] = $App->postValue('sold_to');
        $params['sale_ugx'] = floatval($App->postValue('sale_ugx'));

        if (empty($params['sold_by'])) {
            $params['sold_by'] = $App->user->uid;
        }

        if (empty($params['sold_to'])) {
            $params['sale_ugx'] = floatval($App->postValue('sale_ugx'));
        } else {
            $params['sale_ugx'] = -1;
        }

        //$params['sale_date'] = $App->getCurrentDateTime();

        if (ctype_alnum($params['sale_ugx'])) {
            $errors[] = "A sale value can only be in numbers.";
        } elseif (empty($params['sale_ugx'])) {
            $errors[] = "A sale value is required.";
        } elseif (empty($params['sold_to']) && floatval($params['sale_ugx']) < 0) {
            $errors[] = "A sale value is required.";
        }

        if (empty($errors)) {
            $App->con->where('id_water_source', $params['water_source_id']);
            $water_source_data = $App->con->getOne("water_sources");
            if (!empty($water_source_data)) {
                if ($App->checkIFExists("water_source_caretakers", array(
                            'water_source_id' => $water_source_data['id_water_source'],
                            'uid' => $params['sold_by']))) {
                    $params['water_source_id'] = $water_source_data['id_water_source'];
                    $params['percentage_saved'] = $water_source_data['percentage_saved'];
                    if ($params['sale_ugx'] < 0) {
                        $params['sale_ugx'] = floatval($water_source_data['monthly_charges']);
                    }
                    $id_sale = $App->saveWaterSale($params);
                    if (is_int($id_sale)) {
                        $App->LogEevent($App->user->uid, $App->event->EVENT_ADDED_SALE, $App->getCurrentDateTime(), $params['sale_ugx'], $id_sale);
                        $App->setSessionMessage("Sale saved", SUCCESS_STATUS_CODE);
                        $App->navigate('/manage/sales/');
                    } else {
                        $App->setSessionMessage("An error occured adding the sale. Please try again later");
                    }
                } else {
                    $App->setSessionMessage('The user you selected is not authorised to add users for this water source. If you feel this is an error, please consult your administrator.');
                }
            } else {
                $App->setSessionMessage('That water source does not exist. If this persi sts, plese consult your administrator.');
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_UPDATE_SALE, $App->getCurrentDateTime(), "", $id_sale);
}


$App->con->where('id_sale', $id_sale);
$sale = $App->con->getOne('sales');

if (!isset($sale['id_sale'])) {
    $App->setSessionMessage("Sale does not exist");
    $App->navigate('/manage/sales/');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}