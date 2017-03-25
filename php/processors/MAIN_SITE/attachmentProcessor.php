<?php

if (!$App->isAuthenticated) {
    $App->con->where('published', 1);
}
$App->con->where('file_name', $App->action);
$App->con->join('files', 'id_file=file_id', 'LEFT');
$build = $App->con->getOne('app_builds');
if (empty($build)) {
    $build['file_name'] = md5("");
}

function getRequestHeaders() {
    if (function_exists("apache_request_headers")) {
        if ($headers = apache_request_headers()) {
            return $headers;
        }
    }
    $headers = array();
    // Grab the IF_MODIFIED_SINCE header 
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
    }
    return $headers;
}

$pathname = UPLOADS_PATH . DS . 'defaults' . DS . $build['file_name'];

if (file_exists($pathname)) {
    $mimetype = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $pathname);
    $fileModTime = filemtime($pathname);
    $headers = getRequestHeaders();
    if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $fileModTime)) {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileModTime) . ' GMT', true, 304);
    } else {
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileModTime) . ' GMT', true, 200);
        header('Content-Disposition: attachment; filename="' . $build['build_name'] . '-' . $build['build_version'] . '-' . ($build['is_stable'] == 1 ? 'stable' : 'nightly') . '.apk"');
        header('Content-Type: ' . $mimetype);
        header('Content-transfer-encoding: binary');
        header('Content-length: ' . filesize($pathname));
        $App->readfile_chunked($pathname);
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo '<h1>Not Found</h1>';
}
exit();
