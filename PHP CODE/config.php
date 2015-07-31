<?php

define('SITE_URL', 'http://pm4w.uct.ac.za/manage'); //No forward slash


/* * * HIGHLY REQUIRED COMMENT THOSE BELOW WHEN PROPERLY EDITED** */
define("DB_HOST", 'localhost');
define("DB_USERNAME", '');
define("DB_PASSWORD", '');
define("DB_NAME", "");

define("DEFAULT_SITE_EMAIL", "");

define('TABLE_PREFIX', '');
date_default_timezone_set("Africa/Kampala");
ini_set('display_errors', 1);
ini_set('max_execution_time', 0); //10 minutes
ob_start('ob_gzhandler');
session_cache_limiter('none');
session_start();

$allowed_chars_in_username = ".";
$GLOBAL_SETTINGS['allowed_chars_in_username'] = '.';
$GLOBAL_SETTINGS['min_username_length'] = 4;
$GLOBAL_SETTINGS['max_username_length'] = 25;

define('SITE_PATH', str_replace('\\', '/', dirname(__FILE__)));
define('CLASS_PATH', SITE_PATH . "/classes_n_functions"); //no forward slash

require_once CLASS_PATH . '/AfricasTalkingGateway.php';
require_once CLASS_PATH . '/dboperations.class.php';

$dbhandle = new DBOperations();

$USER = array();

$SYSTEM_CONFIG = $dbhandle->Fetch("settings", "*", array("id_system" => 1));

if (is_array($SYSTEM_CONFIG)) {
    foreach ($SYSTEM_CONFIG as $key => $value) {
        if (is_numeric($value)) {
            define(strtoupper($key), intval($value));
        } else {
            define(strtoupper($key), $value);
        }
    }
} else {
    $SYSTEM_CONFIG = array();
}

require_once CLASS_PATH . '/functions.php';
require_once CLASS_PATH . '/forms_one.php';
require_once CLASS_PATH . '/forms.php';
require_once CLASS_PATH . '/tables.php';
require_once CLASS_PATH . '/process.php';
require_once CLASS_PATH . '/cron.php';
?>
