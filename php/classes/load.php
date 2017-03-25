<?php

function autoloader($className) {
    foreach (new DirectoryIterator(ROOT . DS . 'libs') as $fileinfo) {
        if (!$fileinfo->isDot()) {
            if (file_exists($fileinfo->getPathname() . DS . 'autoload.php')) {
                require_once($fileinfo->getPathname() . DS . 'autoload.php');
            }
        }
    }
    
    $className =str_replace("\\", "/", $className);
    
    if (file_exists(ROOT . DS . 'classes' . DS . str_replace("\\", "/", $className) . '.class.php')) {
        require_once(ROOT . DS . 'classes' . DS . $className . '.class.php');
    } else if (file_exists(ROOT . DS . 'controllers' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'controllers' . DS . $className . '.php');
    } else if (file_exists(ROOT . DS . 'models' . DS . $className . '.php')) {
        require_once(ROOT . DS . 'models' . DS . $className . '.php');
    }
}

spl_autoload_register('autoloader');
