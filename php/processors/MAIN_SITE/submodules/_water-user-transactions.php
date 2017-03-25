<?php

$errors = array();
$id_user = $App->getValue('id');

$App->con->join('water_sources', 'id_water_source=water_users.water_source_id', 'LEFT');
$App->con->where('id_user', $id_user);
$customer = $App->con->getOne('water_users water_users');

if (!isset($customer['id_user'])) {
    $App->setSessionMessage("User does not exist");
    $App->navigate('/manage/water-users');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}