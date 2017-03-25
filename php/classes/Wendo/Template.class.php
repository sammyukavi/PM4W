<?php

/**
 * Description of Template
 *
 * @author Sammy N Ukavi Jr
 */

namespace Wendo;

use Detection\MobileDetect as MobileDetect;

class Template {

    protected $variables = array();
    protected $language = array();
    private $App;

    function __construct(array $languageVars = array()) {
        $this->language = $languageVars;
    }

    /** Set Variables * */
    public function setVariable($name, $value) {
        if (is_array($value)) {
            $this->variables = array_merge($this->variables, $value);
        } else {
            $this->variables[$name] = $value;
        }
    }

    public function parseSkin($filePath) {
        $buffer = "";
        $variables = $this->variables;
        $language = $this->language;

        if (file_exists($filePath)) {
            $handle = fopen($filePath, "r") or die("Couldn't get handle");
            if ($handle) {
                while (!feof($handle)) {
                    $buffer.= fgets($handle, 4096);
                }
                fclose($handle);
            }
        }

        $tagname = "php";
        $buffer = preg_replace_callback("#{\s*?$tagname\b[^}]*}(.*?){/$tagname\b[^}]*}#s", function($matches) {
            if (isset($matches[1])) {
                ob_start();
                eval("$matches[1]");
                $buffer = ob_get_contents();
                ob_end_clean();
                return $buffer;
            } else {
                return "";
            }
        }, $buffer);

        $buffer = preg_replace_callback('/{\$lang->(.+?)}/i', function($matches) use($language) {
            return (isset($language[$matches[1]]) ? $language[$matches[1]] : "");
        }, $buffer);

        $buffer = preg_replace_callback('/{\$(.+?)}/', function($matches) use($variables) {
            return (isset($variables[$matches[1]]) ? $variables[$matches[1]] : "");
        }, $buffer);
        //$buffer = \Wendo\Utilities::minifyHtml($buffer);
        return $buffer;
    }

    public function render(\Wendo\App $App) {
        global $CONFIG;
        $MobileDetect = new MobileDetect();
        $isMobile = $MobileDetect->isMobile();

        $isAndroidOS = $MobileDetect->isAndroidOS();
        $isiOS = $MobileDetect->isiOS();
        $isBlackBerryOS = $MobileDetect->isBlackBerryOS();
        $isWindowsPhoneOS = $MobileDetect->isWindowsPhoneOS();
        $isTizen = $MobileDetect->isTizen();

        $this->App = $App;

        if ($CONFIG['force_mobile_redirect'] == 1 && $isMobile && ($isAndroidOS || $isiOS || $isBlackBerryOS || $isWindowsPhoneOS || $isTizen) && $App->domain != $CONFIG['domains']['touch_site_domain']['domain']) {
            header("location: " . $CONFIG['domains']['touch_site_domain']['scheme'] . $CONFIG['domains']['touch_site_domain']['domain']);
            exit();
        } if ($CONFIG['force_mobile_redirect'] == 1 && $isMobile && (!$isAndroidOS && !$isiOS && !$isBlackBerryOS && !$isWindowsPhoneOS && !$isTizen) && $App->domain != $CONFIG['domains']['mobile_site_domain']['domain']) {
            header("location: " . $CONFIG['domains']['mobile_site_domain']['scheme'] . $CONFIG['domains']['mobile_site_domain']['domain']);
            exit();
        } if ($CONFIG['force_mobile_redirect'] == 1 && !$isMobile && $App->domain != $CONFIG['domains']['main_site_domain']['domain']) {
            header("location: " . $CONFIG['domains']['main_site_domain']['scheme'] . $CONFIG['domains']['main_site_domain']['domain']);
            exit();
        }

        $this->loadView($App->controller);
        session_write_close();
    }

    public function loadView($view) {
        global $CONFIG;
        $App = $this->App;
        if (empty($view)) {
            $view = $this->App->getView();
        }


        if (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'content.php')) {
            $content = ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'content.php';
        } elseif (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . '.php')) {
            $content = ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . '.php';
        } elseif (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'not-found.php')) {
            header("HTTP/1.0 404 Not Found");
            $content = ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'not-found.php';
        } else {
            header("HTTP/1.0 404 Not Found");
            $content = ROOT . DS . 'assets' . DS . 'views' . DS . 'not-found.php';
        }

        if (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'header.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'header.php';
        } elseif (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'header.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'header.php';
        } else {
            require_once ROOT . DS . 'assets' . DS . 'views' . DS . 'header.php';
        }        

        if (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'left-sidebar.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'left-sidebar.php';
        } elseif (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'left-sidebar.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'left-sidebar.php';
        } else {
            require_once ROOT . DS . 'assets' . DS . 'views' . DS . 'left-sidebar.php';
        }

        echo '</nav><div id="page-wrapper"> ';
        require_once $content;
        echo ' </div>';

        if (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'modal.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'modal.php';
        } elseif (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'modal.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'modal.php';
        } else {
            require_once ROOT . DS . 'assets' . DS . 'views' . DS . 'modal.php';
        }

        if (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'footer.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . $view . DS . 'footer.php';
        } elseif (file_exists(ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'footer.php')) {
            require_once ROOT . DS . 'views' . DS . $App->domainWorkingFolder . DS . 'footer.php';
        } else {
            require_once ROOT . DS . 'assets' . DS . 'views' . DS . 'footer.php';
        }
    }

}
