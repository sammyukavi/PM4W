<?php

$App->con->where('idu', $App->getValue('id'));
$user = $App->con->getOne('users');

if (!isset($user['idu'])) {
    $App->setSessionMessage("User does not exist");
    $App->navigate('/manage/sales/');
}