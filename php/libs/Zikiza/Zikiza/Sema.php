<?php

namespace Zikiza;

/**
 * Description of Sema
 *
 * @author Sammy N Ukavi Jr
 */
class Sema {

    protected static $URI = 'http://sema.eyeeza.loc/api/';

    public static function sendSMS($recipients, $message_content, $schedule_date = "") {
        if (empty($schedule_date)) {
            $schedule_date = date("Y-m-d H:i:s");
        }

        $fields = array(
            'recipients' => $recipients,
            'message_content' => $message_content
        );

        $postvars = '';
        $sep = '';
        foreach ($fields as $key => $value) {
            $postvars.= $sep . urlencode($key) . '=' . urlencode($value);
            $sep = '&';
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::$URI);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        //echo $result;

       // die();
    }

    private function mysqlDateisValid($date) {
        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $d && $d->format('Y-m-d H:i:s') == $date;
    }

}
