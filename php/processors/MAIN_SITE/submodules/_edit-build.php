<?php

$id_build = $App->getValue('id');

$App->con->where('id_build', $id_build);
$App->con->join('files', 'file_id=id_file');
$build = $App->con->getOne('app_builds');

if (isset($_POST['submit'])) {

    $params['id_build'] = $id_build;
    $params['build_name'] = $App->postValue('build_name');
    $params['build_version'] = $App->postValue('build_version');
    $params['compatible_devices'] = $App->postValue('compatible_devices');
    $params['build_features'] = $App->postValue('build_features');
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
                    $params['file_id'] = $file['id'];

                    @unlink($build['file_path']);
                    $App->con->where("id_file", $build['id_file']);
                    $App->con->delete('files');
                } else {
                    $errors[] = "Only android apk files are supported";
                    @unlink($uploader->file_dst_pathname);
                }
                $uploader->Clean();
            }
        }
    }

    if (empty($errors)) {
        if ($params['preferred'] == 1) {
            $App->con->update('app_builds', array('preferred' => 0));
        }
        $build_id = $App->saveBuild($params);
        if (is_int($build_id)) {
            $App->setSessionMessage("Saved", SUCCESS_STATUS_CODE);
            $App->navigate('/manage/builds');
        }
        $App->setSessionMessage('Error uploading build. Please try again later');
    }


    foreach ($errors as $error) {
        $App->setSessionMessage($error);
    }
}

if (empty($build)) {
    $App->setSessionMessage("Build does not exist");
    $App->navigate('/manage/builds');
}