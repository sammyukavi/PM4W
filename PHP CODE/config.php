<?php

define('SITE_URL', 'http://yoursite/manage'); //No forward slash


//define('DB_HOST', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'pm4w_app');



define("DEFAULT_SITE_EMAIL", "adminemail@email.com");

define('TABLE_PREFIX', '');
date_default_timezone_set("Africa/Kampala");
ini_set('display_errors', 0);
ini_set('max_execution_time', 600); //10 minutes
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