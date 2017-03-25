<?php

spl_autoload_register(function ($class) {
    if (file_exists(__DIR__ . DS . $class . '.php')) {
        require_once __DIR__ . DS . $class . '.php';
    }
});
