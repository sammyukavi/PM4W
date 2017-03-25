<?php

$attendant_id = $App->getValue('id');

$App->con->where("idu", $attendant_id);
$user = $App->con->getOne("users");


if (!isset($user['idu'])) {
    $App->setSessionMessage("Cannot find user");
    $App->navigate("/manage/water-users/");
}