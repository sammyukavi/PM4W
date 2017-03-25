<?php
$App->setPageTitle('Home');
if (!empty($_POST)) {
    $code = ERROR_STATUS_CODE;
    $msgs = array();
    $name = trim($App->postValue('name'));
    $email = trim($App->postValue('email'));
    $message = trim($App->postValue('message'));
    $g_recaptcha_response = $App->postValue('g-recaptcha-response');

    if (empty($name)) {
        $msgs[] = "Your name is required";
    }

    if (empty($email)) {
        $msgs[] = "Your email is required";
    } elseif (!$App->isValid('email', $email)) {
        $msgs[] = "Please use a valid email address";
    }

    if (empty($name)) {
        $msgs[] = "Please say something. Type a message";
    }


    if (empty($g_recaptcha_response)) {
        $msgs[] = "Captcha is required";
    } else {
        $recaptcha = new \ReCaptcha\ReCaptcha($CONFIG['recaptcha_secret_key']);
        $resp = $recaptcha->verify($g_recaptcha_response, $_SERVER['REMOTE_ADDR']);
        if (!$resp->isSuccess()) {
            $msgs[] = "Invalid captcha";
        }
    }

    if (empty($msgs)) {
        $App->setEmailTemplateParams(array(
            'name' => $name,
            'email' => $email,
            'message' => $message,
            'date' => $App->getCurrentDateTime()
        ));
        $code = SUCCESS_STATUS_CODE;
        $msgs[] = "Thank you for your message.";
        $App->sendEmail($CONFIG['error_log_emails'], $App->getEmailTemplate($App->getLocale(), "contact_form_message", "title"), $App->getEmailTemplate($App->getLocale(), "contact_form_message", "body"));
    }

    $server_reply = array(
        'code' => $code,
        'msgs' => $msgs
    );
    echo json_encode($server_reply);
    exit();
}