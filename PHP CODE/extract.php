<?php

$zip = new ZipArchive;
$res = $zip->open('www.zip');
if ($res === TRUE) {
    $zip->extractTo(dirname(__FILE__) . "/");
    $zip->close();
    echo 'Extracted!!!';
} else {
    echo 'Failed!!!';
}
?>