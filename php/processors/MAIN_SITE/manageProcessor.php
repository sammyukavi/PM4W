<?php

if (!$App->isAuthenticated) {
    $App->setSessionVariable("next", $App->sanitizeVar($_SERVER, 'HTTP_REFERER'));
    $App->navigate('/login');
}

if (file_exists(__DIR__ . DS . "submodules" . DS . "_$App->action.php")) {
    require_once __DIR__ . DS . "submodules" . DS . "_$App->action.php";
} 
