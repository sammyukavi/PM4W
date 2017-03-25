<?php

function fatal_handler() {
    $error = error_get_last();
    if (!empty($error)) {
        $App = new \Wendo\App();
        $App->deleteSessionVariable(SESSION_KEYS_IDU);
        $App->deleteSessionVariable(SESSION_KEYS_AUTHCODE);
        $App->deleteSessionVariable(SESSION_KEYS_AUTH_KEY);

        $App->killCookies(SESSION_NAME);
        $App->killCookies(SESSION_KEYS_IDU);
        $App->killCookies(SESSION_KEYS_AUTHCODE);
        $App->killCookies(SESSION_KEYS_AUTH_KEY);
        BookMyAdErrorHandler($error["type"], $error["message"], $error["file"], $error["line"]);
        //\Wendo\App::setSessionMessage($App->lang("login_with_fb_error"));
        $App->navigate('/?error=' . $App->lang("login_with_fb_error"));
    }
}

if (USE_CUSTOM_SHUTDOWN_FUNCTION == 1) {
//register_shutdown_function("fatal_handler");
}

if (USE_CUSTOM_ERRORHANDLER == 1) {
    set_error_handler("BookMyAdErrorHandler");
}

function BookMyAdErrorHandler($errno, $errstr, $errfile = '', $errline = 0, $errcontext = array()) {
    $App = new \Wendo\App();
    $desc = nl2br("<strong>ERRNO</strong> $errno\n" .
            "<strong>ERRSTR</strong> $errstr\n" .
            "<strong>ERRFILE</strong> $errfile\n" .
            "<strong>ERRLINE</strong> $errline\n" .
            "<strong>ERRCONTEXT</strong> \n" . print_r($errcontext, true) . "\n" .
            "<strong>Backtrace of errorHandler()</strong>\n" . print_r(debug_backtrace(), true));
    $App->logError("An error was handled by the custom BookMyAdErrorHandler ", $errstr, $desc);
}
