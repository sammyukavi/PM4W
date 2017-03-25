<?php

$errors = array();
$id_msg = $App->getValue('id');

$App->con->where('id_msg', $id_msg);
$sms = $App->con->getOne('sms_messages');
if (empty($sms['id_msg']) || !isset($sms['id_msg'])) {
    $App->setSessionMessage("SMS does not exist", ERROR_STATUS_CODE);
    $App->navigate('/manage/sms-messages');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}