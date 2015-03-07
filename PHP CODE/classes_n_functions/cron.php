<?php

$batch_schedule_date = getArrayVal($SYSTEM_CONFIG, 'batch_schedule_date');
$acountablility_recipients = getArrayVal($SYSTEM_CONFIG, 'acountablility_recipients');
$acountablility_cycle = getArrayVal($SYSTEM_CONFIG, 'acountablility_cycle');
$last_day_acountability_sms_was_sent = getArrayVal($SYSTEM_CONFIG, 'last_day_acountability_sms_was_sent');

if ((strtotime("$acountablility_cycle day", strtotime($last_day_acountability_sms_was_sent)) <= strtotime(getCurrentDate())) && (strtotime($batch_schedule_date) <= strtotime(getCurrentDate()))) {

    $water_sources = array();
    $funds_accountability_sms_template = getArrayVal($SYSTEM_CONFIG, 'funds_accountability_sms_template');
    $query = "SELECT * FROM water_sources";
    $result = $dbhandle->RunQueryForResults($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $water_sources[] = $row;
        }
    }

    foreach ($water_sources as $water_source) {
        $system_users_recepients = array();
        $water_user_recepients = array();
        $pnumbers = array();

        if ($acountablility_recipients === 'system_users') {
            $columns = array('users.idu', 'users.pnumber AS system_user_pnumber');
        } elseif ($acountablility_recipients === 'water_users') {
            $columns = array('water_users.id_user', 'water_users.pnumber AS water_user_pnumber');
        } elseif ($acountablility_recipients === 'all') {
            $columns = array('users.idu', 'users.pnumber AS system_user_pnumber', 'water_users.id_user', 'water_users.pnumber AS water_user_pnumber',);
        }

        $query = "SELECT " . implode(',', $columns) . " FROM water_source_caretakers,water_source_treasurers ";

        if ($acountablility_recipients === 'system_users') {
            $query .= "LEFT JOIN users ON idu= uid ";
        } elseif ($acountablility_recipients === 'water_users') {
            $query .= "LEFT JOIN water_users ON added_by= uid OR added_by= uid ";
        } elseif ($acountablility_recipients === 'all') {
            $query .= "LEFT JOIN users ON idu=uid ";
            $query .= "LEFT JOIN water_users ON added_by=uid ";
        }

        $query.=" WHERE water_source_caretakers.water_source_id=" . $water_source['id_water_source'] . " AND water_source_treasurers.water_source_id=" . $water_source['id_water_source'];

        if ($acountablility_recipients === 'system_users') {
            $query .= " GROUP BY idu ";
        } elseif ($acountablility_recipients === 'water_users') {
            $query .= " GROUP BY id_user ";
        } elseif ($acountablility_recipients === 'all') {
            $query .= " GROUP BY idu,id_user ";
        }

        $result = $dbhandle->RunQueryForResults($query);

        while ($row = $result->fetch_assoc()) {

            $system_users_recepients[] = $row['idu'];
            if (!empty($row['idu'])) {
                array_push($system_users_recepients, $row['idu']);
                // $system_users_recepients[] = $row['idu'];
            }
            if (!empty($row['system_user_pnumber'])) {
                $pnumbers[] = $row['system_user_pnumber'];
            }
            if (!empty($row['id_user'])) {
                $water_user_recepients[] = $row['id_user'];
            }
            if (!empty($row['water_user_pnumber'])) {
                $pnumbers[] = $row['water_user_pnumber'];
            }
        }

        $system_users_recepients = array_unique($system_users_recepients);
        $water_user_recepients = array_unique($water_user_recepients);
        $pnumbers = array_unique($pnumbers);

        $transactions = 0.0;
        $total_savings = 0.0;
        $total_expenses = 0.0;


        $query = "SELECT COUNT(id_sale) AS transactions FROM " . TABLE_PREFIX . "sales WHERE " . TABLE_PREFIX . "sales.water_source_id=" . $water_source['id_water_source'] . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";

        $result = $dbhandle->RunQueryForResults($query);
        if (isset($result->num_rows) && $result->num_rows > 0) {
            while ($sale = $result->fetch_assoc()) {
                if (!empty($sale['transactions'])) {
                    $transactions = floatval($sale['transactions']);
                }
            }
        }

        $last_day_acountability_sms_was_sent = $SYSTEM_CONFIG['last_day_acountability_sms_was_sent'];

        $query = "SELECT CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "sales WHERE " . TABLE_PREFIX . "sales.water_source_id=" . $water_source['id_water_source'] . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1 AND sale_date>='$last_day_acountability_sms_was_sent' AND sale_date<='" . getCurrentDate() . "'";
        $squery = "SELECT SUM(savings) AS savings FROM ($query) AS derived";
        //echo $squery;
        $result = $dbhandle->RunQueryForResults($squery);
        while ($sale = $result->fetch_assoc()) {
            $total_savings = floatval($sale['savings']);
        }

        $query = "SELECT SUM(expenditure_cost) AS total_expenses FROM " . TABLE_PREFIX . "expenditures WHERE water_source_id=" . $water_source['id_water_source'] . " AND expenditure_date>='$last_day_acountability_sms_was_sent' AND expenditure_date<='" . getCurrentDate() . "'";
        $result = $dbhandle->RunQueryForResults($query);

        while ($sale = $result->fetch_assoc()) {
            if (!empty($sale['total_expenses'])) {
                $total_expenses = floatval($sale['total_expenses']);
            }
        }

        $TEMPLATE_PARAMS = array(
            'system_name' => SYSTEM_NAME,
            'site_url' => SITE_URL,
            'water_source_name' => $water_source['water_source_name'],
            'water_source_location' => $water_source['water_source_location'],
            'monthly_charges' => $water_source['monthly_charges'],
            'percentage_saved' => $water_source['percentage_saved'],
            'total_sales' => $transactions,
            'total_expenditures' => $total_expenses,
            'total_savings' => $total_savings,
            'acountablility_cycle' => $acountablility_cycle
        );

        if (ENABLE_SMS == 1) {
            $params['message_content'] = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/', function($matches) use($TEMPLATE_PARAMS) {
                return (isset($TEMPLATE_PARAMS[$matches[1]]) ? $TEMPLATE_PARAMS[$matches[1]] : "");
            }, $funds_accountability_sms_template);
            $params['system_users'] = implode(',', $system_users_recepients);
            $params['water_users'] = implode(',', $water_user_recepients);
            $params['date_sent'] = getCurrentDate();
            $params['sent_by'] = 0;
            $params['sent'] = 0;
            if (send_sms_message(implode(',', $pnumbers), $funds_accountability_sms_template)) {
                $params['sent'] = 1;
            } else {
                $params['sent'] = 0;
            }
            $dbhandle->Insert('sms_messages', $params);
        }
    }
    $dbhandle->Update('settings', array('last_day_acountability_sms_was_sent' => getCurrentDate()), array('id_system' => 1));
}
