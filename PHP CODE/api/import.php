<?php

require '../config.php';

$query = file_get_contents('sql.sql');

//$dbhandle->multi_query($query);
//var_dump($query);

$result = $dbhandle->RunQueryForResults($query);

if ($result) { // will return true if succefull else it will return false
    echo 'success';
} else {
    echo $dbhandle->con->error;
}