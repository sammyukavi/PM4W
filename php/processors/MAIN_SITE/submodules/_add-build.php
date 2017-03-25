<?php

$App->setPageTitle("Upload app build");

$errors = array();
if (isset($_POST['submit'])) {

    $params['build_name'] = $App->postValue('build_name');
    $params['build_version'] = $App->postValue('build_version');
    $params['compatible_devices'] = $App->postValue('compatible_devices');
    $params['build_features'] = htmlentities($App->postValue('build_features'));
    $params['build_date'] = $App->getCurrentDateTime($App->postValue('build_date'));
    $params['preferred'] = intval($App->postValue('preferred'));
    $params['is_stable'] = intval($App->postValue('is_stable'));
    $params['published'] = intval($App->postValue('published'));
    $params['uploaded_by'] = $App->user->uid;
    $params['date_uploaded'] = $App->getCurrentDateTime();

    if (empty($params['build_name'])) {
        $errors[] = "The build name is required";
    }
    if (empty($params['build_version'])) {
        $errors[] = "The build version is required";
    }
    if (empty($params['compatible_devices'])) {
        $errors[] = "Compatible devices are required";
    }
    if (empty($params['build_date'])) {
        $errors[] = "The build date is required";
    }

    if (empty($errors)) {
        $uploaded_by = $App->user->uid;
        $uploader = new Upload($_FILES['build_file']);
        if ($uploader->uploaded) {
            $uploader->file_new_name_body = uniqid("", true) . "-" . time();
            $uploader->Process(TEMP_DATA_PATH);
            if ($uploader->processed) {
                $file_is_apk = $App->file_is_apk($uploader->file_dst_pathname);
                if ($file_is_apk) {
                    $file = $App->saveFile($uploader->file_dst_pathname, $App->user->uid, $uploaded_by, true);
                } else {
                    $errors[] = "Only android apk files are supported";
                    @unlink($uploader->file_dst_pathname);
                }
                $uploader->Clean();
            } else {
                $errors[] = $uploader->error;
            }
        } else {
            $errors[] = "Upload the build file";
        }
    }

    if (empty($errors)) {
        $params['file_id'] = $file['id'];
        if ($params['preferred'] == 1) {
            $App->con->update('app_builds', array('preferred' => 0));
        }
        $build_id = $App->saveBuild($params);
        if (is_int($build_id)) {
            $App->setSessionMessage('Build uploaded', SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage('Error uploading build. Please try again later');
        }
        $App->navigate('/manage/builds');
    }

    foreach ($errors as $error) {
        $App->setSessionMessage($error);
    }
}