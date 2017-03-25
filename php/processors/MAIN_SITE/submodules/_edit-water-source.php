<?php

$action = $App->getValue("a");
$errors = array();

$id_water_source = $App->getValue('id');
if (isset($_POST['submit'])) {
    if ($App->can_edit_water_sources) {
        $params['water_source_id'] = $App->postValue('water_source_id');
        $params['water_source_name'] = $App->postValue('water_source_name');
        $params['water_source_location'] = $App->postValue('water_source_location');
        $params['water_source_coordinates'] = $App->postValue('water_source_coordinates');
        $params['monthly_charges'] = $App->postValue('monthly_charges');
        $params['percentage_saved'] = $App->postValue('percentage_saved');
        $params['last_updated'] = $App->getCurrentDateTime();

        if (empty($params['water_source_location'])) {
            $errors[] = "A water source's location is required";
        }

        if (empty($params['water_source_coordinates'])) {
            $errors[] = "A water source's coordinates are required";
        }

        if (empty($errors)) {
            $p_id = $App->getValue('id');
            $params['id_water_source'] = $p_id;
            $p_id = $App->saveWaterSource($params);
            //if ($App->Update('water_sources', $params, array('id_water_source' => $p_id))) {
            if (is_int($p_id)) {
                $App->LogEevent($App->user->uid, $App->event->EVENT_UPDATED_WATER_SOURCE, $App->getCurrentDateTime(), "", $id_water_source);
                $new_params = array();
                if (empty($params['water_source_id'])) {
                    $new_params['water_source_id'] = '';

                    $exploded_location = explode(',', $params['water_source_location']);
                    foreach ($exploded_location as $index => $value) {
                        $exploded_location[$index] = ucwords(trim(ltrim($value)));
                    }

                    foreach ($exploded_location as $loc) {
                        foreach (explode(' ', $loc) as $value) {
                            $new_params ['water_source_id'] .= $value[0];
                        }
                    }
                    $new_params['water_source_id'] = $new_params['water_source_id'] . '-' . strtoupper(generateAlphaNumCode());
                    while ($App->checkIFExists('water_sources', array('water_source_id' => $new_params ['water_source_id']))) {
                        $tmp = explode('-', $new_params['water_source_id']);
                        $new_params['water_source_id'] = $tmp[0] . '-' . strtoupper(generateAlphaNumCode());
                    }
                }

                if (empty($params ['water_source_name'])) {
                    $new_params['water_source_name'] = strtoupper(preg_replace('/[ ,]+/', '-', trim($params['water_source_location']))) . "-WTR-SRC-" . 1;
                    while ($App->checkIFExists('water_sources', array('water_source_name' => $new_params ['water_source_name']))) {
                        $exploded = explode('-', $new_params ['water_source_name']);
                        $exploded[count($exploded) - 1] = intval($exploded[count($exploded) - 1]) + 1;
                        $new_params['water_source_name'] = strtoupper(preg_replace('/[ ,]+/', '-', implode(',', $exploded)));
                    }
                }
                if (!empty($new_params)) {
                    $new_params['id_water_source'] = $p_id;
                    $App->saveWaterSource($new_params);
                }

                //$App->deleteFromDb('water_source_caretakers', array('water_source_id' => $p_id));

                $App->con->where('water_source_id', $p_id);
                $App->con->where('uid', $App->postValue('attendants'), 'NOT IN');
                $App->con->delete('water_source_caretakers');

                if (isset($_POST['attendants'])) {
                    foreach ($_POST['attendants'] as $key => $uid) {
                        $App->saveWaterSourceCaretaker(array(
                            'water_source_id' => $p_id,
                            'uid' => $uid,
                            'date_created' => $App->getCurrentDateTime(),
                            'last_updated' => $App->getCurrentDateTime()
                        ));
                    }
                }

                //$App->deleteFromDb('water_source_treasurers', array('water_source_id ' => $p_id));

                $App->con->where('water_source_id', $p_id);
                $App->con->where('uid', $App->postValue('treasurers'), 'NOT IN');
                $App->con->delete('water_source_treasurers');


                if (isset($_POST['treasurers'])) {
                    foreach ($_POST['treasurers'] as $key => $uid) {
                        $App->saveWaterSourceTreasurer(array(
                            'water_source_id' => $p_id,
                            'uid' => $uid,
                            'date_created' => $App->getCurrentDateTime(),
                            'last_updated' => $App->getCurrentDateTime()
                        ));
                    }
                }
                $App->setSessionMessage("Water source updated", SUCCESS_STATUS_CODE);
                $App->navigate('/manage/water-sources');
            } else {
                $App->setSessionMessage("An error occured updating the water source. Please try again later");
            }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }
    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_UPDATE_WATER_SOURCE, $App->getCurrentDateTime(), "", $id_water_source);
}


$App->con->where('id_water_source', $id_water_source);
$water_source = $App->con->getOne('water_sources');

if (!isset($water_source['id_water_source'])) {
    $App->setSessionMessage("Cannot find water source");
    $App->navigate('/manage/water-sources');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
