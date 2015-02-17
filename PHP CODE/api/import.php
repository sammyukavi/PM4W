<?php

require '../config.php';

$query = file_get_contents('sql.sql');

//$dbhandle->multi_query($query);

$result = $dbhandle->RunQueryForResults($query);
