<?php
require_once '../../config.php';

if (!$church->isLoggedIn()) {
    header("Location: " . SITE_URL . "/login/");
    exit();
}

$errors = array();

if (isset($_POST['submit'])) {

    $owned_by = getArrayVal($_POST, 'owned_by');
    $church_id = getArrayVal($_POST, 'church_id');
    $uid = getArrayVal($_POST, 'uid');

    if ($owned_by === 'user') {
        $owner_id = $uid;
    } elseif ($owned_by === 'church') {
        $owner_id = $church_id;
    } else {
        $errors[] = "A file must have an owner";
    }

    $files = array();

    foreach ($_FILES['upload'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (!array_key_exists($i, $files)) {
                $files[$i] = array();
            }
            $files[$i][$k] = $v;
        }
    }

    foreach ($files as $file) {

        if (!empty($file['name']) && !empty($file['type']) && !empty($file['tmp_name'])) {
            $handle = new Upload($file);
            if ($handle->uploaded) {
                if (!empty($GLOBAL_SETTINGS['forced_conversion_extention'])) {
                    $handle->image_convert = $GLOBAL_SETTINGS['forced_conversion_extention'];
                }

                $handle->file_new_name_body = mktime() . '-' . uniqid();
                $handle->dir_auto_create