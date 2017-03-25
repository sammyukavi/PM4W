<?php

namespace Wendo;

/**
 * Description of Utilities
 *
 * @author Sammy N Ukavi Jr
 */
use Detection\MobileDetect as MobileDetect;

class Utilities extends DbHandler {

    private $emailHeaders;
    public $emailTemplateParams;
    private $emailTemplateFileName = "default";
    private $default_language;
    private $langfile;
    private $locale;
    private $view;
    /*     * SEO* */
    private $siteName;
    private $siteDescription;
    private $siteKeyWords;
    private $googleSiteVerification;
    private $bingSiteVerification;
    private $alexaSiteVerification;
    private $yahooSiteVerification;
    private $ItemPropUrl;
    private $OgTitle;
    private $OgDescription;
    private $OgImageUrl;
    private $FBAppID;
    private $twitterHandle;
    private $twitterDescription;
    private $iphoneAppName;
    private $iphoneAppID;
    private $iphoneAppURL;
    private $ipadAppName;
    private $ipadAppID;
    private $ipadAppURL;
    private $androidAppName;
    private $androidAppID;
    private $androidAppURL;

    /*     * End Seo* */
    public $pageTitle;
    public $supportedLanguages;
    public $postValues = array();
    public $getValues = array();
    public $controller;
    public $action;
    public $procedure;
    public $path;
    public $subdomain;
    public $domain;
    public $userAgent;
    public $currentUserIpAddress;
    public $currentUserCountryCode;
    public $currentUserCountryName;
    public $currentUserRegionName;
    public $currentUserCityName;
    public $currentUserZipCode;
    public $currentUserLatitude;
    public $currentUserLongitude;
    public $currentUserTimeZone;
    public $browserLocationAllowed;
    public $browserLatitude;
    public $browserLongitude;
    public $domainWorkingFolder;

    public function __construct() {
        parent::__construct();
        global $CONFIG, $PUBLIC_VARS;

        //SEO
        $this->siteName = $CONFIG['site_name'];
        $this->siteDescription = $CONFIG['site_description'];
        $this->googleSiteVerification = $CONFIG['googleSiteVerification'];
        $this->bingSiteVerification = $CONFIG['bingSiteVerification'];
        $this->alexaSiteVerification = $CONFIG['alexaSiteVerification'];
        $this->yahooSiteVerification = $CONFIG['yahooSiteVerification'];
        $this->siteKeyWords = $CONFIG['site_keywords'];
        $this->OgTitle = $CONFIG['og_title'];
        $this->OgDescription = $CONFIG['OgDescription'];
        $this->OgImageUrl = $CONFIG['OgImageUrl'];
        //$this->FBAppID = $CONFIG['fb_app_id'];
        $this->twitterHandle = $CONFIG['twitterHandle'];
        $this->twitterDescription = $CONFIG['twitterDescription'];
        $this->iphoneAppName = $CONFIG['iphoneAppName'];
        $this->iphoneAppID = $CONFIG['iphoneAppID'];
        $this->iphoneAppURL = $CONFIG['iphoneAppURL'];
        $this->ipadAppName = $CONFIG['ipadAppName'];
        $this->ipadAppID = $CONFIG['ipadAppID'];
        $this->ipadAppURL = $CONFIG['ipadAppURL'];
        $this->androidAppName = $CONFIG['androidAppName'];
        $this->androidAppID = $CONFIG['androidAppID'];
        $this->androidAppURL = $CONFIG['androidAppURL'];

        //end seo
        $this->supportedLanguages = $CONFIG['supported_languages'];
        $this->default_language = $CONFIG['default_language'];
        $this->setEmailTemplateParams($PUBLIC_VARS);
        $this->locale = $this->detectLocale();
        $this->loadLanguage();
        $this->initializeCA();
        $this->postValues = $this->sanitizeVar($_POST);
        $this->getValues = $this->sanitizeVar($_GET);
        $this->subdomain = $this->extractSubdomain($this->siteURL());
        $this->domain = $this->extractDomain($this->siteURL());

        $this->domainWorkingFolder = $this->getDomainWorkingFolder();
        //$this->domainWorkingFolder = 'MANAGE';

        $this->currentUserIpAddress = $this->getIpAddress();
        $MobileDetect = new MobileDetect();
        $this->userAgent = $MobileDetect->getUserAgent();
        if ($CONFIG['enable_ipinfodbapi'] === 1) {
            $this->ipInfoDetectLocation();
        }

        $this->emailHeaders = array();
        $this->emailTemplateParams = array();
    }

    public function ipInfoDetectLocation() {
        global $CONFIG;
        $ipinfodbapi_session_expire = $this->getSessionVariable(SESSION_KEYS_IPINFO);
        if (time() > $ipinfodbapi_session_expire) {
            $results = json_decode(file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key=' . $CONFIG['ipinfodbapi_key'] . '&ip=' . $this->currentUserIpAddress . '&format=json'));
            $this->setSessionVariable('ipinfodbapi_session_expire', time() + $CONFIG['ipinfodbapi_session']);
            $this->setSessionVariable('ipinforesults', $results);
        } else {
            $results = $this->getSessionVariable('ipinforesults');
        }

        if (!empty($results) && isset($results->statusCode) && strtolower($results->statusCode) !== 'error') {
            $this->currentUserCountryCode = $results->countryCode;
            $this->currentUserCountryName = $results->countryName;
            $this->currentUserRegionName = $results->regionName;
            $this->currentUserCityName = $results->cityName;
            $this->currentUserZipCode = $results->zipCode;
            $this->currentUserLatitude = $results->latitude;
            $this->currentUserLongitude = $results->longitude;
            $this->currentUserTimeZone = $results->timeZone;
        }
    }

    public function getIpAddress() {
        global $CONFIG;
        if (!empty($CONFIG['testIP'])) {
            return $CONFIG['testIP'];
        }

// check for shared internet/ISP IP
        if (!empty($_SERVER['HTTP_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

// check for IPs passing through proxies
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
// check if multiple ips exist in var
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if ($this->validate_ip($ip))
                        return $ip;
                }
            } else {
                if ($this->validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
                    return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_X_FORWARDED']))
            return $_SERVER['HTTP_X_FORWARDED'];
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && $this->validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && $this->validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
            return $_SERVER['HTTP_FORWARDED_FOR'];
        if (!empty($_SERVER['HTTP_FORWARDED']) && $this->validate_ip($_SERVER['HTTP_FORWARDED']))
            return $_SERVER['HTTP_FORWARDED'];

// return unreliable ip since all else failed
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Ensures an ip address is both a valid IP and does not fall within
     * a private network range.
     */
    public function validate_ip($ip) {
        if (strtolower($ip) === 'unknown')
            return false;
// generate ipv4 network address
        $ip = ip2long($ip);

// if the ip is set and not equivalent to 255.255.255.255
        if ($ip !== false && $ip !== -1) {
// make sure to get unsigned long representation of ip
// due to discrepancies between 32 and 64 bit OSes and
// signed numbers (ints default to signed in PHP)
            $ip = sprintf('%u', $ip);
// do private network range checking
            if ($ip >= 0 && $ip <= 50331647)
                return false;
            if ($ip >= 167772160 && $ip <= 184549375)
                return false;
            if ($ip >= 2130706432 && $ip <= 2147483647)
                return false;
            if ($ip >= 2851995648 && $ip <= 2852061183)
                return false;
            if ($ip >= 2886729728 && $ip <= 2887778303)
                return false;
            if ($ip >= 3221225984 && $ip <= 3221226239)
                return false;
            if ($ip >= 3232235520 && $ip <= 3232301055)
                return false;
            if ($ip >= 4294967040)
                return false;
        }
        return true;
    }

    public function PrintImageMagickInfo() {
        /*
          // This file will run a test on your server to determine the location and versions of ImageMagick.
          //It will look in the most commonly found locations. The last two are where most popular hosts (including "Godaddy") install ImageMagick.
          //
          // Upload this script to your server and run it for a breakdown of where ImageMagick is.
          //
         */
        echo '<h2>Test for versions and locations of ImageMagick</h2>';
        echo '<b>Path: </b> convert<br>';

        function alist($array) {  //This function prints a text array as an html list.
            $alist = "<ul>";
            for ($i = 0; $i < sizeof($array); $i++) {
                $alist .= "<li>$array[$i]";
            }
            $alist .= "</ul>";
            return $alist;
        }

        print("Try to get ImageMagick \"convert\" program version number. Print the return code: 0. if OK, nonzero if error. Print the output of \"convert -version\"\n");

        exec("convert -version", $out, $rcode);
        echo "Version return code is $rcode <br>";
        echo alist($out);
        echo '<br>';
        echo '<b>This should test for ImageMagick version 5.x</b><br>';
        echo '<b>Path: </b> /usr/bin/convert<br>';

        exec("/usr/bin/convert -version", $out, $rcode); //Try to get ImageMagick "convert" program version number.
        echo "Version return code is $rcode <br>"; //Print the return code: 0 if OK, nonzero if error.
        echo alist($out); //Print the output of "convert -version"

        echo '<br>';
        echo '<b>This should test for ImageMagick version 6.x</b><br>';
        echo '<b>Path: </b> /usr/local/bin/convert<br>';

        exec("/usr/local/bin/convert -version", $out, $rcode); //Try to get ImageMagick "convert" program version number.
        echo "Version return code is $rcode <br>"; //Print the return code: 0 if OK, nonzero if error.
        echo alist($out); //Print the output of "convert -version";

        die();
    }

    public function setView($page) {
        $this->view = $page;
    }

    public function getView() {
        return $this->view;
    }

    private function initializeCA() {
        $url = $this->fullURL($_SERVER);
        $siteURL = $this->siteURL();
// First, check to see if there is a 'p=N' or 'page_id=N' to match against
        /* if (preg_match('#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values)) {
          $id = abs(intval($values[2]));
          if ($id) {
          //  return $id;
          }
          } */

// Get rid of the #anchor
        $url_split = explode('#', $url);
        $url = $url_split[0];

// Get rid of URL ?query=string
        $url_split = explode('?', $url);
        $url = $url_split[0];

// Add 'www.' if it is absent and should be there
        if (false !== strpos($siteURL, '://www.') && false === strpos($url, '://www.'))
            $url = str_replace('://', '://www.', $url);

// Strip 'www.' if it is present and shouldn't be
        if (false === strpos($siteURL, '://www.'))
            $url = str_replace('://www.', '://', $url);

// Strip 'index.php/' if we're not using path info permalinks
        $url = str_replace('index.php' . '/', '', $url);

        if (false !== strpos(rtrim($url, '/\\') . '/', $siteURL)) {
// Chop off http://domain.com/[path]
            $url = str_replace($siteURL, '', $url);
        } else {
// Chop off /path/to/blog
            $home_path = parse_url($siteURL);
            $home_path = isset($home_path['path']) ? $home_path['path'] : '';
            $url = preg_replace(sprintf('#^%s#', preg_quote($home_path)), '', rtrim($url, '/\\') . "/");
        }
// Trim leading and lagging slashes
        $url = trim($url, '/');
        $url = explode('/', $url);
        $this->controller = $this->sanitizeVar($url, 0);
        $this->action = $this->sanitizeVar($url, 1);
        $this->procedure = $this->sanitizeVar($url, 2);
        $this->path = rtrim('/' . $this->controller . '/' . $this->action . '/' . $this->procedure, '/');
    }

    private function urlOrigin($s, $use_forwarded_host = false) {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    private function fullURL($s, $use_forwarded_host = false) {
        return $this->urlOrigin($s, $use_forwarded_host) . $s['REQUEST_URI'];
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getLocale() {
        return $this->locale;
    }

    public function getLangfile() {
        return $this->langfile;
    }

    protected function detectLocale() {
        $languages = explode(',', $this->sanitizeVar($_SERVER, 'HTTP_ACCEPT_LANGUAGE'));
        foreach ($languages as $lang) {
            if (in_array($lang, $this->supportedLanguages)) {
                return $lang;
            }
        }
        return $this->default_language;
    }

    public function lang($literal) {
        if (array_key_exists($literal, $this->langfile)) {
            return $this->langfile[$literal];
        } else {
            return "";
        }
    }

    protected function loadLanguage($locale = null) {
        if (empty($locale)) {
            $locale = $this->locale;
        }

        $buffer = "";
        $langfile = array();
        $filePath = LANGUAGES_PATH . DS . "$locale" . DS . "lng.conf";
        $exceptionStr = "<h1 style=\"text-align:center;\">Language files missing. Site cannot run without a default language file.<h1>";

        if (!file_exists($filePath)) {
            $filePath = LANGUAGES_PATH . DS . $this->default_language . DS . "lng.conf";
        }

        if (file_exists($filePath)) {
            $handle = null;
            try {
                /* $handle = fopen($filePath, "r");
                  if (!$handle) {
                  throw new Exception($exceptionStr);
                  }
                  while (!feof($handle)) {
                  $langfile[] = trim(fgets($handle));
                  } */
                if (file_exists($filePath)) {
                    $handle = fopen($filePath, "r");
                    if (!$handle) {
                        throw new Exception($exceptionStr);
                    }
                    while (!feof($handle)) {
                        $buffer.= "\r\n" . trim(fgets($handle));
                    }
                }
            } catch (Exception $e) {
                
            } /* finally {
              if (!is_null($handle)) {
              fclose($handle);
              }
              } */
        } else {
            die($exceptionStr);
        }

        global $PUBLIC_VARS;
        $variables = array_merge($this->emailTemplateParams, $PUBLIC_VARS);
        $buffer = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($variables) {
            return (isset($variables[$matches[1]]) ? $variables[$matches[1]] : "");
        }, $buffer);

        $buffer = htmlspecialchars_decode($buffer);

        $langfile = array_filter(preg_split('/\R/', $buffer));
        $langkeys = array();
        $langvalues = array();
        foreach ($langfile as $lang) {
            if (strstr($lang, "=")) {
                $slang = explode("=", $lang, 2);
                array_push($langkeys, trim($slang[0]));
                array_push($langvalues, trim($slang[1]));
            }
        }
        $this->langfile = array_merge(array_combine($langkeys, $langvalues), $PUBLIC_VARS);
        return $this->langfile;
    }

    /**
     * This function gets the site url without a leading slash
     * @return String
     */
    public function siteURL() {
        $siteURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $siteURL .= "s";
        }
        $siteURL .= "://";
        if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
            $siteURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } else {
            $siteURL .= $_SERVER["SERVER_NAME"];
        }
        return $siteURL;
    }

    public function mainSiteURL() {
        global $CONFIG;
        return $CONFIG['domains']['main_site_domain']['scheme'] . $CONFIG['domains']['main_site_domain']['domain'];
    }

    /**
     * This function is used to strip html tags from a literal
     * @param String $str The literal to strip
     * @param String $tags The html tags to strip
     * @param boolean $stripContent Strip content inside the tags. Default value is false
     * @return String  A string literal without the stripped tags
     */
    private function stripOnlyTags($str, $tags, $stripContent = false) {
        $content = '';
        if (!is_array($tags)) {
            $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
            if (end($tags) == '')
                array_pop($tags);
        }
        foreach ($tags as $tag) {
            if ($stripContent)
                $content = '(.+</' . $tag . '(>|\s[^>]*>)|)';
            $str = preg_replace('#</?' . $tag . '(>|\s[^>]*>)' . $content . '#is', '', $str);
        }
        return $str;
    }

    /**
     * 
     * @param array $array The array to extract a value from
     * @param String $name They array key to extract value
     * @return Mixed  Returns the value in it's corresponding data type. Null if key was not found
     */
    public function sanitizeVar(array $array, $name = "") {
        if (array_key_exists($name, $array)) {
            if (is_array($array[$name])) {
                $temp_array = array();
                foreach ($array[$name] as $key => $arr) {
                    $temp_array[$key] = $this->sanitizeVar($array[$name], $key);
                }
                return $temp_array;
            } else {
                //return htmlentities($array[$name], ENT_QUOTES);
                return $array[$name];
            }
        } elseif (is_array($array) && empty($name)) {
            $temp_array = array();
            foreach ($array as $key => $arr) {
                $temp_array[$key] = $this->sanitizeVar($array, $key);
            }
            return $temp_array;
        } else {
            return null;
        }
    }

    public function postValue($key) {
        return isset($this->postValues[$key]) ? $this->postValues[$key] : null;
    }

    public function getValue($key) {
        return isset($this->getValues[$key]) ? $this->getValues[$key] : null;
    }

    public function setEmailHeaders(array $emailHeaders) {
        $this->emailHeaders = array_merge($this->emailHeaders, $emailHeaders);
    }

    public function setEmailTemplateParams(array $emailTemplateParams) {
        if (!property_exists(__CLASS__, 'emailTemplateParams') || !is_array($this->emailTemplateParams)) {
            $this->emailTemplateParams = array();
        }
        //var_dump(debug_print_backtrace());
        $this->emailTemplateParams = array_merge($emailTemplateParams, $this->emailTemplateParams);
    }

    static function logError($title, $error, $description = "", $sendEmail = true) {
        global $CONFIG;
        if (!empty($description)) {
            $error.="<br/><br/>" . $description;
        }
        if ($sendEmail) {
            $error = $error . "<br/><br/>User IP Address: " . $_SERVER["REMOTE_ADDR"];
            $error.= "<br/>Time: " . date("l, jS F Y H:i:s A O");
            $email = new Utilities();
            $email->setLocale($email->detectLocale());
            $email->sendEmail($CONFIG['error_log_emails'], $title, $error);
        }
    }

    /**
     * TODO move this function to App.class.php and make it static. Can be called anywhere to translate
     */
    public function loadLanguageFile($locale) {
        $langfile = file(ROOT . DS . "application" . DS . "languages/$locale/lng.conf");
        $langkeys = array();
        $langvalues = array();
        foreach ($langfile as $lang) {
            if (strstr($lang, "=")) {
                $slang = explode("=", $lang);
                array_push($langkeys, trim($slang[0]));
                array_push($langvalues, trim($slang[1]));
            }
        }
        $langfile = array_combine($langkeys, $langvalues);
        if (!empty($langfile)) {
            return $langfile;
        } else {
            return false;
        }
    }

    public function sendEmail($semi_collon_separated_recepients, $subject, $message, array $emailHeaders = array()) {
        global $CONFIG, $PUBLIC_VARS;
        if ($CONFIG['enable_emails'] != 1) {
            return true;
        }

        if (!is_array($this->emailTemplateParams)) {
            $this->emailTemplateParams = array();
        }

        $this->emailTemplateParams = array_merge($this->emailTemplateParams, $PUBLIC_VARS);


        $this->emailTemplateParams['site_url'] = $this->siteURL();
        $this->emailTemplateParams['main_site_url'] = $this->mainSiteURL();

        $pass_this = $this;

        $subject = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($pass_this) {
            return (isset($pass_this->emailTemplateParams[$matches[1]]) ? $pass_this->emailTemplateParams[$matches[1]] : "");
        }, $subject);

        $message = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($pass_this) {
            return (isset($pass_this->emailTemplateParams[$matches[1]]) ? $pass_this->emailTemplateParams[$matches[1]] : "");
        }, $message);

        $this->emailTemplateParams['email_content'] = $message;

        $filePath = ROOT . DS . 'assets' . DS . 'templates' . DS . 'emails' . DS . $this->emailTemplateFileName . '.php';

        if (file_exists($filePath)) {
            $tpl = new Template($this->getLangfile());
            $tpl->setVariable("emailTemplateParams", $this->emailTemplateParams);
            $message = $tpl->parseSkin($filePath);
        }

        $headers = "";

        if (!isset($emailHeaders['from'])) {
            $headers .= "From: " . $CONFIG['notifications_email'] . " <" . $CONFIG['notifications_email'] . ">\n";
        }

        if (!isset($emailHeaders['return_path'])) {
            $headers .= "Return-Path: " . (isset($emailHeaders['from']) ? $emailHeaders['from'] : $CONFIG['notifications_email']) . "<" . (isset($emailHeaders['from']) ? $emailHeaders['from'] : $CONFIG['notifications_email']) . ">\n";
        }

        foreach ($emailHeaders as $key => $additional_header) {
            $headers .= ucwords(str_replace("_", "-", $key)) . ": " . $additional_header . "\n";
        }

        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/html; charset=\"utf-8\"\n";
        $headers .= "X-Priority: 1 (Highest)\n";
        $headers .= "X-MSMail-Priority: High\n";
        $headers .= "Importance: High\n";

        $recepients_array = explode(';', $semi_collon_separated_recepients);
        foreach ($recepients_array as $single_recepient) {
            if ($this->isValid("email", $single_recepient)) {
                mail($single_recepient, $subject, $message, $headers);
            }
        }

        if ($CONFIG['write_emails_to_log'] == 1) {
            $dirPath = ROOT . DS . 'logs' . DS . 'emails' . DS;
            if (!is_dir($dirPath)) {
                mkdir($dirPath, 0777, true);
            }
            $textFile = $dirPath . $subject . '-' . strtotime($this->getCurrentDateTime()) . ".html";
            if (!empty($message)) {
                $fh = fopen($textFile, 'w+') or die("can't open file");
                fwrite($fh, $message);
                fclose($fh);
            }
        }
        $this->emailTemplateParams = array();
        return true;
    }

    public function getEmailTemplate($locale, $template_name, $messagePart) {
        global $CONFIG;
        if (isset($CONFIG['email_templates'][$locale][$template_name][$messagePart])) {
            return $CONFIG['email_templates'][$locale][$template_name][$messagePart];
        }
        return "";
    }

    public function setEmailTemplateFileName($emailTemplateFileName) {
        $this->emailTemplateFileName = $emailTemplateFileName;
    }

    function isTaken($what, $value, $belongsTo = null) {
        switch ($what) {
            case 'email':
                if (empty($belongsTo)) {
                    $this->con->rawQuery('SELECT idu FROM ' . DB_TABLE_PREFIX . 'users WHERE email=?', array($value));
                } else {
                    $this->con->rawQuery('SELECT idu FROM ' . DB_TABLE_PREFIX . 'users WHERE email=? AND idu<>?', array($value, $belongsTo));
                }
                if ($this->con->count > 0) {
                    return true;
                }
                return false;
                break;
            case 'username':
                if (empty($belongsTo)) {
                    $this->con->rawQuery('SELECT idu FROM ' . DB_TABLE_PREFIX . 'users WHERE username=?', array($value));
                } else {
                    $this->con->rawQuery('SELECT idu FROM ' . DB_TABLE_PREFIX . 'users WHERE username=? AND idu<>?', array($value, $belongsTo));
                }
                if ($this->con->count > 0) {
                    return true;
                }
                return false;
                break;
            case 'category-slug':
                if (empty($belongsTo)) {
                    $this->con->rawQuery('SELECT id_category FROM ' . DB_TABLE_PREFIX . 'property_categories WHERE slug=?', array($value));
                } else {
                    $this->con->rawQuery('SELECT id_category FROM ' . DB_TABLE_PREFIX . 'property_categories WHERE slug=? AND id_category<>?', array($value, $belongsTo));
                }
                if ($this->con->count > 0) {
                    return true;
                }
                return false;
                break;
            case 'station-namespace':
                if (empty($belongsTo)) {
                    $this->con->rawQuery('SELECT id_station FROM ' . DB_TABLE_PREFIX . 'stations WHERE station_namespace=?', array($value));
                } else {
                    $this->con->rawQuery('SELECT id_station FROM ' . DB_TABLE_PREFIX . 'stations WHERE station_namespace=? AND id_station<>?', array($value, $belongsTo));
                }
                if ($this->con->count > 0) {
                    return true;
                }
                return false;
                break;
            case 'activate_hash':
                $this->con->rawQuery('SELECT uid FROM ' . DB_TABLE_PREFIX . 'user_passwords WHERE activate_hash=?', array($value));
                if ($this->con->count > 0) {
                    return true;
                }
                break;
            default:
                break;
        }
    }

    public function isValid($what, $string, $format = "Y-m-d H:i:s") {
        global $CONFIG;
        switch ($what) {
            case 'date':
                $d = \DateTime::createFromFormat($format, $string);
                return $d && $d->format($format) == $string;
                break;
            case 'email':
                if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                    return false;
                } else {
                    return true;
                }
                break;
            case 'username':
                if (empty($string)) {
                    return false;
                }
                $allowedCharsInUsername = explode(",", $CONFIG['allowed_chars_in_username']);
                $start_char = $string[0];
                if (in_array($start_char, $allowedCharsInUsername)) {
                    return false;
                }
                $start_char = $string[strlen($string) - 1];
                if (in_array($start_char, $allowedCharsInUsername)) {
                    return false;
                }

                if (ctype_alnum(str_replace($allowedCharsInUsername, '', $string))) {
                    if (strlen($string) >= $CONFIG['min_username_length'] && strlen($string) <= $CONFIG['max_username_length'] && $this->isAllowedWord($string)) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                break;
            case 'pnumber':
                $formats = array('+############', '############', '##########', '####');
                //$format = trim(preg_replace("/[0-9]/", "#", $string)); //outputs same as below            
                $format = trim(preg_replace("/\d/", "#", $string));
                return in_array($format, $formats) ? true : false;
            case 'password':
                return true;
                break;
            case 'name':
                return $this->isAllowedWord($string);
                break;
            default:
                return false;
                break;
        }
    }

    function autoCorrectPnumber($pnumber) {
        $formats = array('##########');
        $format = trim(preg_replace("/\d/", "#", $pnumber));
        if (in_array($format, $formats)) {
            return "+256" . ltrim($pnumber, '0');
        } else {
            return $pnumber;
        }
    }

    function isAllowedWord($string) {
        global $CONFIG;
        $invalid_words = explode(',', $CONFIG['invalid_words']);
        foreach ($invalid_words as $invalid_word => $value) {
            $invalid_words[$invalid_word] = strtolower($value);
        }
        return in_array($string, $invalid_words, true) ? false : true;
    }

    public function getCurrentDateTime($date = "", $humanreadable = false, $simple = false) {
        if (!empty($date)) {
            if ($humanreadable === true) {
                if ($simple === true) {
                    return is_numeric($date) ? date("d-m-Y H:i:s", $date) : date("d-m-Y H:i:s", strtotime($date));
                } else {
                    return is_numeric($date) ? date("M-d-Y h:i:s A", $date) : date("M-d-Y h:i:s A", strtotime($date));
                }
            } else {
                if ($simple === true) {
                    return is_numeric($date) ? date("d-m-Y H:i:s", $date) : date("d-m-Y H:i:s", strtotime($date));
                } else {
                    return is_numeric($date) ? date("Y-m-d H:i:s", $date) : date("Y-m-d H:i:s", strtotime($date));
                }
            }
        } else {
            if ($humanreadable === true) {
                if ($simple === true) {
                    return date("d-m-Y H:i:s");
                } else {
                    return date("M-d-Y h:i:s A");
                }
            } else {
                if ($simple === true) {
                    return date("d-m-Y H:i:s");
                } else {
                    return date("Y-m-d H:i:s");
                }
            }
        }
    }

    public static function killCookies($key = "") {
        global $CONFIG;
        $Utilities = new Utilities();
        if (!empty($key)) {
//Delete unencrypted cookies
            setcookie($key, '', time() - $CONFIG['offline_cookie_duration'], '/');
//Delete encrypted cookies
            setcookie($Utilities->encrypt_decrypt($key), '', time() - $CONFIG['offline_cookie_duration'], '/');
        } else {
//Delete unencrypted cookies
            setcookie(SESSION_KEYS_AUTH_KEY, '', time() - $CONFIG['offline_cookie_duration'], '/');
            setcookie(SESSION_KEYS_AUTHCODE, '', time() - $CONFIG['offline_cookie_duration'], '/');
            setcookie(SESSION_KEYS_IDU, '', time() - $CONFIG['offline_cookie_duration'], '/');

//Delete encrypted cookies
            setcookie($Utilities->encrypt_decrypt(SESSION_KEYS_AUTH_KEY), '', time() - $CONFIG['offline_cookie_duration'], '/');
            setcookie($Utilities->encrypt_decrypt(SESSION_KEYS_AUTHCODE), '', time() - $CONFIG['offline_cookie_duration'], '/');
            setcookie($Utilities->encrypt_decrypt(SESSION_KEYS_IDU), '', time() - $CONFIG['offline_cookie_duration'], '/');
        }

        return true;
    }

    public static function setSessionMessage($message, $alert_type = ERROR_STATUS_CODE) {
        switch ($alert_type) {
            case INFORMATION_STATUS_CODE:
                $info = self::getSessionVariable("info_message");
                if (is_array($info)) {
                    array_push($info, $message);
                } else {
                    $info = array($message);
                }
                self::setSessionVariable("info_message", $info);
                break;
            case WARNING_STATUS_CODE:
                $warning = self::getSessionVariable("warning_message");
                if (is_array($warning)) {
                    array_push($warning, $message);
                } else {
                    $warning = array($message);
                }
                self::setSessionVariable("warning_message", $warning);
                break;
            case SUCCESS_STATUS_CODE:
                $success = self::getSessionVariable("success_message");
                if (is_array($success)) {
                    array_push($success, $message);
                } else {
                    $success = array($message);
                }
                self::setSessionVariable("success_message", $success);
                break;
            case ERROR_STATUS_CODE:
                $error = self::getSessionVariable("error_message");
                if (is_array($error)) {
                    array_push($error, $message);
                } else {
                    $error = array($message);
                }
                self::setSessionVariable("error_message", $error);
                break;

            default:
                break;
        }
    }

    public static function setSessionVariable($variableName, $variableValue) {
        global $CONFIG;
        if ($CONFIG["encrypt_cookie_session_data"] == 1) {
            $variableValue = serialize($variableValue);
            $_SESSION[self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME))][self::sanitizeForKey(self::encrypt_decrypt($variableName))] = self::encrypt_decrypt($variableValue);
        } else {
            $_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))][$variableName] = $variableValue;
        }
        return true;
    }

    public static function setCookieVariable($variableName, $variableValue) {
        global $CONFIG;
        if ($CONFIG["encrypt_cookie_session_data"] == 1) {
            $variableValue = serialize($variableValue);
            $variableName = self::encrypt_decrypt($variableName);
            $variableValue = self::encrypt_decrypt($variableValue);
            setcookie($variableName, $variableValue, time() + $CONFIG['offline_cookie_duration'], '/');
        } else {
            setcookie($variableName, $variableValue, time() + $CONFIG['offline_cookie_duration'], '/');
        }
        return true;
    }

    protected static function sanitizeForKey($string) {
        return strtolower(preg_replace("/[^a-zA-Z]+/", "", $string));
    }

    public static function sessionVariableExists($variableName) {
        global $CONFIG;
        if ($CONFIG["encrypt_cookie_session_data"] == 1) {
            unset($_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))]);
            if (isset($_SESSION[self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME))][self::sanitizeForKey(self::encrypt_decrypt($variableName))])) {
                return true;
            } else {
                return false;
            }
        } else {
            unset($_SESSION[self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME))]);
            if (isset($_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))][$variableName])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function getSessionVariable($variableName) {
        global $CONFIG;
        if ($CONFIG["encrypt_cookie_session_data"] == 1) {
            unset($_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))]);
            if (isset($_SESSION[self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME))][self::sanitizeForKey(self::encrypt_decrypt($variableName))])) {
                return unserialize(self::encrypt_decrypt($_SESSION[self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME))][self::sanitizeForKey(self::encrypt_decrypt($variableName))], 'decrypt'));
            } else {
                return null;
            }
        } else {
            unset($_SESSION[self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME))]);
            if (isset($_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))][$variableName])) {
                return $_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))][$variableName];
            } else {
                return null;
            }
        }
    }

    public static function getCookieVariable($variableName) {
        global $CONFIG;
        $Utilities = new Utilities();
        if ($CONFIG["encrypt_cookie_session_data"] == 1) {
            $variableName = self::encrypt_decrypt($variableName);
            if (isset($_COOKIE[$variableName])) {
                return unserialize(self::encrypt_decrypt($Utilities->sanitizeVar($_COOKIE, $variableName), 'decrypt'));
            } else {
                return null;
            }
        } else {
            //$this->killCookies($variableName);
            if (isset($_COOKIE[$variableName])) {
                return $Utilities->sanitizeVar($_COOKIE, $variableName);
            } else {
                return null;
            }
        }
    }

    public static function encrypt_decrypt($string, $action = 'encrypt') {
        $output = false;
        $encrypt_method = "AES-256-CBC";
// hash
        $key = hash('sha256', SECRET_KEY);
// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', SECRET_IV), 0, 16);
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public static function printMessage() {
        global $App;
        $info_messages = self::getSessionVariable("info_message");
        if (is_array($info_messages)) {
            echo '<div class="alert alert-info alert-dismissable text-center">';
// echo '<button type="button" class="close" data-dismiss="alert">Ãƒâ€”</button>';
            foreach ($info_messages as $info_message) {
                echo $info_message . "<br/>";
            }
            echo '</div>';
        }

        self::deleteSessionVariable("info_message");

        $success_messages = self::getSessionVariable("success_message");

        if (is_array($success_messages)) {
            echo '<div class="alert alert-success alert-dismissable text-center">';
// echo '<button type="button" class="close" data-dismiss="alert">Ãƒâ€”</button>';
            foreach ($success_messages as $success_message) {
                echo $success_message . "<br/>";
            }
            echo '</div>';
        }

        self::deleteSessionVariable("success_message");

        $warning_messages = self::getSessionVariable("warning_message");
        if (is_array($warning_messages)) {
            echo '<div class="alert alert-warning alert-dismissable text-center">';
// echo '<button type="button" class="close" data-dismiss="alert">Ãƒâ€”</button>';
            foreach ($warning_messages as $warning_message) {
                echo $warning_message . "<br/>";
            }
            echo '</div>';
        }

        self::deleteSessionVariable("warning_message");

        $error_messages = self::getSessionVariable("error_message");

        if (isset($App->getValues['error']) && !empty($App->getValues['error']) && !is_array($App->getValues['error'])) {
            $error_messages[] = $App->getValues['error'];
        }

        if (is_array($error_messages)) {
            echo '<div class="alert alert-danger alert-dismissable text-center">';
//echo '<button type="button" class="close" data-dismiss="alert">Ãƒâ€”</button>';           
            foreach ($error_messages as $error_message) {
                echo $error_message . "<br/>";
            }
            echo '</div>';
        }

        self::deleteSessionVariable("error_message");
    }

    public static function deleteSessionVariable($variableName) {
        global $CONFIG;
        if ($CONFIG["encrypt_cookie_session_data"] == 1) {
            if (isset($_SESSION[strtolower(self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME)))][self::sanitizeForKey(self::encrypt_decrypt($variableName))])) {
                unset($_SESSION[strtolower(self::sanitizeForKey(self::encrypt_decrypt(SESSION_NAME)))][self::sanitizeForKey(self::encrypt_decrypt($variableName))]);
            }
        } else {
            if (isset($_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))][$variableName])) {
                unset($_SESSION[strtolower(self::sanitizeForKey(SESSION_NAME))][$variableName]);
            }
        }
    }

    /**
     * -----------------------------------------------------------------------------------------
     * Based on `https://github.com/mecha-cms/mecha-cms/blob/master/system/kernel/converter.php`
     * -----------------------------------------------------------------------------------------
     */
    public static function minifyHtml($input) {
        if (trim($input) === "")
            return $input;
        // Remove extra white-space(s) between HTML attribute(s)
        $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
            return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
        }, str_replace("\r", "", $input));
        // Minify inline CSS declaration(s)
        if (strpos($input, ' style=') !== false) {
            $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
                return '<' . $matches[1] . ' style=' . $matches[2] . self::minifyCss($matches[3]) . $matches[2];
            }, $input);
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
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^\/!])#s'
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
                ), $input);
    }

    /**
     * @param $files
     * @return mixed|string
     */
    private static function minifyJS($files) {
        $buffer = concatenateFiles($files);
        $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        $buffer = str_replace(array("\r\n", "\r", "\t", "\n", '  ', '    ', '     '), '', $buffer);
        $buffer = preg_replace(array('(( )+\))', '(\)( )+)'), ')', $buffer);
        return $buffer;
    }

    private static function minifyCss($input) {
        if (trim($input) === "")
            return $input;
        return preg_replace(
                array(
// Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
                ), array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
                ), $input);
    }

    private function concatenateFiles($files) {
        $buffer = "";
        $files = array_unique($files);
        foreach ($files as $style) {
            $filePathname = trim(ROOT . DS . $this->domainWorkingFolder . DS . 'assets' . DS . $style);
            if (file_exists($filePathname) && !is_dir($filePathname)) {
                $handle = fopen($filePathname, "r") or die("Couldn't get handle");
                if ($handle) {
                    while (!feof($handle)) {
                        $buffer.= fgets($handle, 4096);
                    }
                    fclose($handle);
                }
            } else {
                $this->logError("File missing", "Cannot locate file to script/stylesheet", "Failed to locate file in $filePathname");
            }
        }
        return $buffer;
    }

    public function parseStylesheet($files, $minify = true) {
        if (!is_array($files)) {
            $files = array($files);
        }
        $buffer = $this->concatenateFiles($files);

        if ($minify) {
            echo $this->minifyCss($buffer);
        } else {
            echo $buffer;
        }
    }

    public function parseScript($files, $minify = true) {
        if (!is_array($files)) {
            $files = array($files);
        }
        $buffer = $this->concatenateFiles($files);

        if ($minify) {
            echo $this->minifyJS($buffer);
        } else {
            echo $buffer;
        }
    }

    public function downloadFileToServer($url) {
        $urlParts = explode("?", $url);
        $urlParts = explode('.', $urlParts[0]);
        $extention = end($urlParts);
        $pathname = TEMP_DATA_PATH . DS . uniqid("", true) . "-" . time() . "." . $extention;
        //copy($url, $pathname);
        // if (file_exists($pathname)) {
        // return $pathname;
        // }
        //use curl to download the image
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (empty($data) || !empty($error)) {
            self::logError("Error downloading mage from facebook", "An error is occuring when "
                    . "trying to download and image from facebook. This code should be reviewed. $error ", "Error in class " . __CLASS__ . ", function " . __FUNCTION__);
        }

        $file = fopen($pathname, "w+");
        fputs($file, $data);
        fclose($file);

        if (file_exists($pathname)) {
            return $pathname;
        }
        return false;
    }

    public function extractDomain($url) {
        if (preg_match('/^(?:https?:\/\/)?(?:(?:[^@]*@)|(?:[^:]*:[^@]*@))?(?:www\.)?([^\/:]+)/', $url, $parts)) {
            return $parts[1];
        }
        return '';
    }

    public function getDomainWorkingFolder() {
        global $CONFIG;
        $domain = $this->domain;
        $domainWorkingFolder = "";
        if (empty($this->domain)) {
            if (preg_match('/^(?:https?:\/\/)?(?:(?:[^@]*@)|(?:[^:]*:[^@]*@))?(?:www\.)?([^\/:]+)/', $this->siteURL(), $parts)) {
                $domain = $parts[1];
            }
        }
        foreach ($CONFIG['domains'] as $value) {
            if ($value['domain'] === $domain) {
                $domainWorkingFolder = $value['folder'];
                break;
            }
        }
        return $domainWorkingFolder;
    }

    public function extractSubdomain($url) {
        $domain = $this->extractDomain($url);
        $parts = explode('.', $domain);
        if (count($parts) >= 3) {
            return $parts[0];
        } else {
            return '';
        }
    }

    function generateAlphaNumCode($length = 6, $mode = 'alnum') {
        switch ($mode) {
            case 'numerals':
                $chars = "123456789";
                break;
            case 'alphabetic':
                $chars = "bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
                break;
            case 'serial':
                $chars = "123456789";
                $code = "";
                for ($index = 0; $index < floor($length / 4); $index++) {
                    $code.=$chars[rand(0, (strlen($chars) - 1))];
                }
                $chars = "BCDFGHJKLMNPQRSTVWXYZ";
                for ($index = 0; $index < $length; $index++) {
                    $code.=$chars[mt_rand(0, (strlen($chars) - 1))];
                }
                return $code;
            default:
                $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
                break;
        }
        $code = "";
        for ($index = 0; $index < $length; $index++) {
            $code.=$chars[rand(0, (strlen($chars) - 1))];
        }
        return $code;
    }

    function readfile_chunked($filename, $retbytes = TRUE) {
        $buffer = '';
        $cnt = 0;
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, CHUNK_SIZE);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }
        return $status;
    }

    public function navigate($url = "") {
        $url = rtrim($url, '/');
        if (empty($url)) {
            $url = $this->siteURL();
        }
        header("Location: $url");
        exit();
    }

    public function setPageTitle($pageTitle) {
        $this->pageTitle = $pageTitle;
    }

    public function getPageTitle() {
        return $this->pageTitle;
    }

    public function getSiteName() {
        return $this->siteName;
    }

    public function getSiteDescription() {
        return $this->siteDescription;
    }

    public function getSiteKeywords() {
        return $this->siteKeyWords;
    }

    public function getGoogleSiteVerification() {
        return $this->googleSiteVerification;
    }

    public function getBingSiteVerification() {
        return $this->bingSiteVerification;
    }

    public function getAlexaSiteVerification() {
        return $this->alexaSiteVerification;
    }

    public function getYahooSiteVerification() {
        return $this->yahooSiteVerification;
    }

    public function getItemPropUrl() {
        return $this->ItemPropUrl;
    }

    public function getOgTitle() {
        return $this->OgTitle;
    }

    public function getOgDescription() {
        return $this->OgDescription;
    }

    public function getOgImageUrl() {
        return $this->OgImageUrl;
    }

    public function getFBAppID() {
        return $this->FBAppID;
    }

    public function getTwitterHandle() {
        return$this->twitterHandle;
    }

    public function getTwitterDescription() {
        return$this->twitterDescription;
    }

    public function getIphoneAppName() {
        return $this->iphoneAppName;
    }

    public function getIphoneAppID() {
        return $this->iphoneAppID;
    }

    public function getIphoneAppURL() {
        return $this->iphoneAppURL;
    }

    public function getIpadAppName() {
        return $this->ipadAppName;
    }

    public function getIpadAppID() {
        return $this->ipadAppID;
    }

    public function getIpadAppURL() {
        return $this->ipadAppURL;
    }

    public function getAndroidAppName() {
        return $this->androidAppName;
    }

    public function getAndroidAppID() {
        return $this->androidAppID;
    }

    public function getAndroidAppURL() {
        return $this->androidAppURL;
    }

    public function the_404_text($addContainer = true) {
        header("HTTP/1.0 404 Not Found");
        if ($addContainer) {
            ?>
            <div class="container">
            <?php } ?>
            <div class="row">                
                <div class="col-sm-12 text-capitalize text-center not-found">
                    <h1><i class="glyphicon glyphicon-question-sign"></i>  <?php echo $this->lang("four_oh_four"); ?></h1>
                    <?php echo $this->lang("four_oh_four_message"); ?>            
                </div>
            </div>
            <?php if ($addContainer) {
                ?>
            </div>
            <?php
        }
    }

    public static function slugify($text, $char = '-') {
// replace non letter or digits by -
//$text = preg_replace('~[^\\pL\d]+~u', $char, $text);
        $text = str_replace(' ', '-', $text);
        $text = preg_replace('/[^A-Za-z0-9\-]/', '', $text); //Removes special chars.    
        $text = preg_replace('/-+/', '-', $text); // Replaces multiple hyphens with single one.
// trim
        $text = trim($text, $char);
// transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
// lowercase
        $text = strtolower($text);
// remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '-', $text);
        if (empty($text)) {
            return $text;
        }
        return $text;
    }

    /**
     * Gets real MIME type and then see if its on allowed list
     * 
     * @param string $path_to_file : path to file
     */
    function file_is_audio($path_to_file) {
        $allowed = array(
            'audio/mpeg', 'audio/x-mpeg', 'audio/mpeg3', 'audio/x-mpeg-3', 'audio/aiff',
            'audio/mid', 'audio/x-aiff', 'audio/x-mpequrl', 'audio/midi', 'audio/x-mid',
            'audio/x-midi', 'audio/wav', 'audio/x-wav', 'audio/xm', 'audio/x-aac', 'audio/basic',
            'audio/flac', 'audio/mp4', 'audio/x-matroska', 'audio/ogg', 'audio/s3m', 'audio/x-ms-wax',
            'audio/xm'
        );

// check REAL MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $path_to_file);
        finfo_close($finfo);

// check to see if REAL MIME type is inside $allowed array
        if (in_array($type, $allowed)) {
            return true;
        } else {
            return false;
        }
    }

    function getFileMimeType($path_to_file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $path_to_file);
        finfo_close($finfo);
        return $type;
    }

    /**
     * Get excerpt from string * 
     * @param String $str String to get an excerpt from
     * @param Integer $startPos Position int string to start excerpt from
     * @param Integer $maxLength Maximum length the excerpt may be
     * @param boolean $trim_html Should html be stripped? use true for yes false for no. Default value is true
     * @return String excerpt
     */
    function getExcerpt($str, $startPos = 0, $maxLength = 100, $delimiter = ' ', $trim_html = true) {
        if ($trim_html) {
            $str = strip_tags($str);
        }
        if (strlen($str) > $maxLength) {
            $excerpt = substr($str, $startPos, $maxLength - 3);
            $lastSpace = strrpos($excerpt, $delimiter);
            $excerpt = substr($excerpt, 0, $lastSpace);
            $excerpt .= '...';
        } else {
            $excerpt = $str;
        }
        return $excerpt;
    }

}
