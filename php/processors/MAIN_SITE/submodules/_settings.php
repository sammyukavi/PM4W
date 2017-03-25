<?php

$tab = $App->procedure;
$lng = $App->getValue('lng');
$errors = array();

if (isset($_POST['submit'])) {
    if ($App->can_access_system_config) {
        switch ($tab) {
            case 'templates':
                $CONFIG['email_templates'][$lng] = $App->postValue('email_templates');
                if (empty($errors)) {
                    $params['config'] = serialize($CONFIG);
                    $id = $App->saveSettings($params);
                    if (is_int($id)) {
                        $App->LogEevent($App->user->uid, $App->event->EVENT_EDITTED_SETTINGS, $App->getCurrentDateTime(), "editted_configuration_settings");
                        $App->setSessionMessage("Configurations saved", SUCCESS_STATUS_CODE);
                        $App->navigate('/manage/settings/templates?lng=' . $lng);
                    } else {
                        $App->setSessionMessage("An error occured saving your configurations. Please try again later");
                    }
                }
                break;
            case 'basic-configuration':

                $CONFIG['site_name'] = $App->postValue('site_name');
                if (empty($CONFIG['site_name'])) {
                    $errors[] = "The system name is required";
                }
                $CONFIG['system_status'] = $App->postValue('system_status');
                $CONFIG['enable_water_user_registrations'] = $App->postValue('enable_water_user_registrations');
                $CONFIG['default_locale_coordinates'] = $App->postValue('default_locale_coordinates');
                $CONFIG['enable_emails'] = $App->postValue('enable_emails');
                $CONFIG['enable_sms'] = $App->postValue('enable_sms');
                $CONFIG['enable_acountablility_sms'] = $App->postValue('enable_acountablility_sms');
                $CONFIG['acountablility_cycle'] = $App->postValue('acountablility_cycle');
                $CONFIG['batch_schedule_date'] = $App->getCurrentDateTime($App->postValue('batch_schedule_date'));
                $CONFIG['acountablility_recipients'] = $App->postValue('acountablility_recipients');
                $CONFIG['sms_api_username'] = $App->postValue('sms_api_username');
                $CONFIG['sms_api_key'] = $App->postValue('sms_api_key');
                $CONFIG['enable_push_notifications'] = $App->postValue('enable_push_notifications');
                $CONFIG['google_api_key'] = $App->postValue('google_api_key');
                if (empty($errors)) {
                    $params['config'] = serialize($CONFIG);
                    $id = $App->saveSettings($params);
                    if (is_int($id)) {
                        $App->LogEevent($App->user->uid, $App->event->EVENT_EDITTED_SETTINGS, $App->getCurrentDateTime(), "editted_configuration_settings");
                        $App->setSessionMessage("Configurations saved", SUCCESS_STATUS_CODE);
                        $App->navigate('/manage/settings/basic-configuration');
                    } else {
                        $App->setSessionMessage("An error occured saving your configurations. Please try again later");
                    }
                }

                $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_EDIT_SETTINGS, $App->getCurrentDateTime(), "attempted_to_edit_configuration_settings");
                break;
            case 'advanced-configuration':
                var_dump($App->postValues);
                die();
                break;
            case 'seo':
            default:
                if (isset($App->postValues['robots']) || isset($App->postValues['site_description']) || isset($App->postValues['site_keywords'])) {
                    $CONFIG['robots'] = $App->postValue('robots');
                    $CONFIG['site_description'] = $App->postValue('site_description');
                    $CONFIG['site_keywords'] = $App->postValue('site_keywords');

                    $CONFIG['googleSiteVerification'] = $App->postValue('googleSiteVerification');
                    $CONFIG['bingSiteVerification'] = $App->postValue('bingSiteVerification');
                    $CONFIG['alexaSiteVerification'] = $App->postValue('alexaSiteVerification');
                    $CONFIG['yahooSiteVerification'] = $App->postValue('yahooSiteVerification');

                    $CONFIG['androidAppName'] = $App->postValue('androidAppName');
                    $CONFIG['androidAppID'] = $App->postValue('androidAppID');
                    $CONFIG['androidAppURL'] = $App->postValue('androidAppURL');
                    $CONFIG['iphoneAppName'] = $App->postValue('iphoneAppName');
                    $CONFIG['iphoneAppID'] = $App->postValue('iphoneAppID');
                    $CONFIG['iphoneAppURL'] = $App->postValue('iphoneAppURL');
                    $CONFIG['ipadAppName'] = $App->postValue('ipadAppName');
                    $CONFIG['ipadAppID'] = $App->postValue('ipadAppID');
                    $CONFIG['ipadAppURL'] = $App->postValue('ipadAppURL');
                    $CONFIG['site_w3c_itemprop_url'] = $App->postValue('site_w3c_itemprop_url');
                    $CONFIG['OgDescription'] = $App->postValue('OgDescription');
                    $CONFIG['OgImageUrl'] = $App->postValue('OgImageUrl');
                    $CONFIG['og_title'] = $App->postValue('og_title');
                    $CONFIG['twitterHandle'] = $App->postValue('twitterHandle');
                    $CONFIG['twitterDescription'] = $App->postValue('twitterDescription');

                    if (empty($errors)) {
                        $params['config'] = serialize($CONFIG);
                        $id = $App->saveSettings($params);
                        if (is_int($id)) {
                            $App->LogEevent($App->user->uid, $App->event->EVENT_EDITTED_SETTINGS, $App->getCurrentDateTime(), "editted_seo_settings");
                            $App->setSessionMessage("SEO Settings saved", SUCCESS_STATUS_CODE);
                            $App->navigate('/manage/settings');
                        } else {
                            $App->setSessionMessage("An error occured saving your SEO settings. Please t ry again later");
                        }
                    }
                    $App->LogEevent($App->user->uid, $App->event->EVENT_ATTEMPTED_TO_EDIT_SETTINGS, $App->getCurrentDateTime(), "attempted_to_edit_seo_settings");
                    break;
                }
        }
    } else {
        $App->setSessionMessage("You do not have the required rights to perform this action");
    }


    /* $location = '/manage/settings/';
      if (!empty($tab)) {
      if ($tab !== 'seo') {
      $location.="&tab=$tab";
      }
      }
      $App->navigate($location); */
}


foreach ($errors as $error) {
    $App->setSessionMessage($error);
}