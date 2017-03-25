<?php

$action = $App->getValue("a");
$errors = array();

$id_water_source = $App->getValue('id');

$App->con->where("id_water_source", $id_water_source);
$water_source_data = $App->con->getOne("water_sources");

if (!isset($water_source_data['id_water_source'])) {
    $App->setSessionMessage(" Water source does not exist");
    $App->navigate("/manage/water-users");
}

$columns = array(
    'water_users.id_user water_user_id',
    'water_users.fname w_u_fname',
    'water_users.lname w_u_lname',
    'water_users.date_added',
    'users.fname w_a_fname',
    'users.lname w_a_lname',
    'users.idu'
);

$App->con->where('water_source_id', $id_water_source);
$App->con->join('users users', 'users.idu=water_users.added_by');
$App->con->orderBy('w_u_fname', 'ASC');
$water_source_users = $App->con->get("water_users water_users", null, $columns);
