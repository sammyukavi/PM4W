<?php

$action = $App->getValue("a");
$errors = array();

if (isset($_POST['submit'])) {
    $database = $App->sanitizeVar($_FILES, 'database');
    if (!empty($database['name']) && !empty($database['type']) &&
            file_exists($database['tmp_name']) &&
            $database['error'] == 0 && $database['size'] > 0) {
        $query = '';
        $handle = @fopen($database['tmp_name'], "r");
        if ($handle) {
            while (!feof($handle)) {
                $query.= fgets($handle, 4096);
            }
            fclose($handle);
        }
        if (!empty($query)) {
            $App->importDb($query);
            $App->navigate('/manage/export');
            exit();
        }
    } else {
        $errors[] = "An error occured uploading your database";
    }
    die();
} elseif ($action === "export") {
    $App->exportDb();
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}