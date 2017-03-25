<?php

if (!empty($App->controller)) {
    if (file_exists(dirname(__FILE__) . DS . $App->domainWorkingFolder . DS . $App->controller . "Processor.php")) {
        require_once dirname(__FILE__) . DS . $App->domainWorkingFolder . DS . $App->controller . "Processor.php";
    }
} else {
    if (file_exists(dirname(__FILE__) . DS . $App->domainWorkingFolder . DS . 'home-page' . "Processor.php")) {
        require_once dirname(__FILE__) . DS . $App->domainWorkingFolder . DS . 'home-page' . "Processor.php";
    }
}
