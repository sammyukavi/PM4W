<?php

define("DB_USERNAME", '');
define("DB_PASSWORD", '');

define("DB_USERNAME", '');
define("DB_PASSWORD", '');



define("DB_PORT", 3306);
define('DB_TABLE_PREFIX', '');
define('DB_TABLE_CHARSET', 'utf8mb4_unicode_ci');
define('SMS_CHARACTERS_LIMIT', 100);

date_default_timezone_set("Europe/London");//Africa/Kampala

ini_set('display_errors', 1);
ini_set('max_execution_time', 30); //Forever
//ini_set('post_max_size', '64M');
//ini_set('upload_max_filesize', '64M');

$imageSizes = array(
    array(
        'width' => 80,
        'height' => 80
    ),
    array(
        'width' => 210,
        'height' => 210
    ),
    array(
        'width' => 600,
        'height' => 400
    )
);

define('USE_CUSTOM_ERRORHANDLER', 0); //When enabled all errrors will be sent via email
define('USE_CUSTOM_SHUTDOWN_FUNCTION', 0);

define('USE_INTERNAL_SESSION_FOLDER', 0);
define("SESSION_NAME", "_pm4w");
define("SECRET_KEY", "AMvQ+Tz+`/8fRBv,qXPu|#YC@_Wi[pp+L@AG;+5uasxHlO9wIyD3~D1qa+8(");
define("SECRET_IV", '>(d`5n[.q<UN<WsW746k0&/@Wc6j&HY~E4%OrV g8up}zt]S{V0Jz*P5$V|');

define("ROOT", dirname(__FILE__));
define("DS", DIRECTORY_SEPARATOR);
define("CLASSES_PATH", ROOT . DS . "classes");
define("LANGUAGES_PATH", ROOT . DS . "languages");

define("UPLOADS_PATH", ROOT . DS . "uploads");
define("DATA_PATH", ROOT . DS . "data");
define("TEMP_DATA_PATH", DATA_PATH . DS . "temp");
define("FILE_PRIVACY_PRIVATE", 0);
define("FILE_PRIVACY_PUBLIC", 1);
define("FILE_PRIVACY_SHARED", 2);

define("INFORMATION_STATUS_CODE", 3, true);
define("SUCCESS_STATUS_CODE", 2, true);
define("WARNING_STATUS_CODE", 1, true);
define("ERROR_STATUS_CODE", 0, true);

define("MESSAGE_STATUS_PENDING", 0);
define("MESSAGE_STATUS_SENT", 2);
define("MESSAGE_STATUS_NOT_SENT", 1);

define("STATUS", "status");
define("MSG", "msg");
define("MOBILE_APP_ANDROID", "android_app");

define("SESSION_KEYS_AUTHCODE", "auth_code", true);
define("SESSION_KEYS_AUTH_KEY", "auth_key", true);
define("SESSION_KEYS_IDU", "idu", true);
define("SESSION_KEYS_ID_PASSWORD", "passkeyId", true);
define("SESSION_KEYS_IPINFO", "ipinfodbapi_session_expire", true);

define('CHUNK_SIZE', 1024 * 1024); // Size (in bytes) of tiles chunk

if (!is_dir(UPLOADS_PATH)) {
    if (!mkdir(UPLOADS_PATH, 0777)) {
        die("Cannot create data folder in set path");
    }
}

if (!is_dir(DATA_PATH)) {
    if (!mkdir(DATA_PATH, 0777)) {
        die("Cannot create data folder in set path");
    }
}
if (!is_dir(TEMP_DATA_PATH)) {
    if (!mkdir(TEMP_DATA_PATH, 0777)) {
        die("Cannot create data folder in set path");
    }
}

if (USE_INTERNAL_SESSION_FOLDER == 1) {
    define("SESSIONS_DATA_PATH", DATA_PATH . DS . "sessions");
    if (!is_dir(SESSIONS_DATA_PATH)) {
        if (!mkdir(SESSIONS_DATA_PATH, 0777)) {
            die("Cannot create sessions folder in set path");
        }
    }
    if (!file_exists(SESSIONS_DATA_PATH . DS . ".htaccess")) {
        $f = fopen(SESSIONS_DATA_PATH . DS . ".htaccess", "a+");
        fwrite($f, "deny from all");
        fclose($f);
    }

    session_save_path(SESSIONS_DATA_PATH);
}

session_name(SESSION_NAME);
session_cache_limiter('private');
//session_cache_expire($CONFIG['session_cache_expire']);
//session_set_cookie_params($CONFIG['session_cookie_duration']);
session_start();

if (defined('MINIFY_CODE') && MINIFY_CODE === 1) {
    ob_start(function($content) {
        if (trim($content) === "")
            return $content;
        // Remove extra white-space(s) between HTML attribute(s)
        $content = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", "", $content));
        // Minify inline CSS declaration(s)
        //Commented this line because css will be minified on output there is an unkown function being called by this scipt
        if (strpos($content, ' style=') !== false) {
            /* $content = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
              return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
              }, $content); */
        }
        return preg_replace(
                array(
            // t = text
            // o = tag open
            // c = tag close
            // Keep important white-space(s) after self-closing HTML tag(s)
            '#<(img|input)(>| .*?>)#s',
            // Remove a line break and two or more white-space(s) between tag(s)
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
            // Remove HTML comment(s) except IE comment(s)
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
                ), array(
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            ""
                ), $content);
    });
} else {
    ob_start();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once ROOT . DS . 'required' . DS . 'required.php';
require_once ROOT . DS . 'classes' . DS . 'load.php';

/* * SEO settings* */

function loadSettings() {
    $dbhandler = new \Wendo\DbHandler();

    //$dbhandler->con->rawQuery("DROP TABLE beta_beta_settings");
    //die();


    $settings = $dbhandler->con->getOne('settings');
    return unserialize($settings['config']);
}

$CONFIG = loadSettings();

$CONFIG['domains'] = array(
    'mobile_site_domain' => array(
        'domain' => '',
        'scheme' => '',
        'folder' => '',
    ),
    'touch_site_domain' => array(
        'domain' => '',
        'scheme' => '',
        'folder' => '',
    ),
    'main_site_domain' => array(
        'domain' => 'pm4w.uct.ac.za',
        'scheme' => 'http://',
        'folder' => 'MAIN_SITE',
    ),
    'admin_site_domain' => array(
        'domain' => '',
        'scheme' => '',
        'folder' => '',
    )
);

$CONFIG['supported_languages'] = array('en-GB'); //this are languages that you already have the files
$CONFIG['default_language'] = 'en-GB';

$CONFIG['enable_ipinfodbapi'] = 0;
$CONFIG['ipinfodbapi_key'] = "";
$CONFIG['ipinfodbapi_session'] = 3600; //after how long should we query for the user's location? Time is in seconds 
$CONFIG['testIP'] = '154.70.157.1'; //an IP that you can use to test a location. Leave null to detect user ip

/**
 * Security configs
 */
$CONFIG['invalid_words'] = "pm4w,test,administrtator,Wendo,pm4w,administrator,admini"; //words that canot be used as property names, category names or usernames, separate multiple with commas

$CONFIG['max_username_length'] = 15; //maximum is 25 chars in database
$CONFIG['min_username_length'] = 4; //maximum is 25 chars in database

$CONFIG['enable_cookies'] = 1;
$CONFIG['encrypt_cookie_session_data'] = 1; //encrypt both the cookie and the session data
$CONFIG['offline_cookie_duration'] = 1209600; //2 weeks
$CONFIG['session_cache_expire'] = 30; //Cached session files expire after this time
$CONFIG['session_cookie_duration'] = 3600; //How long the session cookie should last


/**
 * Varibles that are publicly available. Can be used in a lang file or email teplate
 */
$PUBLIC_VARS = array(
    'site_name' => $CONFIG['site_name'],
    'company_name' => $CONFIG['company_name'],
    'support_email' => $CONFIG['support_email'],
    'support_phone_number' => $CONFIG['support_phone_number'],
    'notifications_email' => $CONFIG['notifications_email']
);


$App = new Wendo\App();

//$params['config'] = serialize($CONFIG);
//$App->saveSettings($params);
//$App->PrintImageMagickInfo();

$App->isAuthenticated = $App->userIsLoggedIn();
$App->setView("landing");

require_once ROOT . DS . 'processors' . DS . 'load.php';
$tpl = new Wendo\Template($App->getLangfile());
$tpl->render($App);

function vardump($var) {
    echo "<pre><span style=\"color:red;\">" . gettype($var) . "(" . (is_array($var) || is_object($var) ? count($var) : (is_string($var) ? strlen($var) : $var)) . ")</span> => ";
    print_r($var);
    echo "</pre>";
}
