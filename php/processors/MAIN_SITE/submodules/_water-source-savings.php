<?php

$action = $App->getValue("a");
$errors = array();

$water_source_id = $App->getValue('id');

if ($action == 'ajax') {

    $view = $App->getValue('v');

    switch ($view) {
        case 'savings-graph':
            $savings = array();
            $current_year = 0;
            if (!is_numeric($water_source_id)) {
                $water_source_id = 0;
            }
            $query = "SELECT DATE_FORMAT(sale_date, '%Y-%m-%d') sale_date,(CASE WHEN " . DB_TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . DB_TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END)-(SUM(expenditure_cost)) AS savings FROM " . DB_TABLE_PREFIX . "sales "
                    . "LEFT JOIN " . DB_TABLE_PREFIX . "expenditures ON " . DB_TABLE_PREFIX . "expenditures.water_source_id= " . DB_TABLE_PREFIX . "sales.water_source_id WHERE " . DB_TABLE_PREFIX . "sales.water_source_id=$water_source_id AND submitted_to_treasurer=1 AND treasurerer_approval_status=1"
                    . " GROUP BY DATE(sale_date) ORDER BY DATE(sale_date) ";

            $results = $App->con->rawQuery($query);

            $current_year_savings = array();

            foreach ($results as $row) {
                $current_year_savings[] = array(
                    $row['sale_date'],
                    intval($row['savings'])
                );
            }

            $savings[] = $current_year_savings;
            echo json_encode($savings);

            break;
        case 'devices-map':
            if (!is_numeric($water_source_id)) {
                $water_source_id = 0;
            }

            $seen_devices = array();

            $missing_devices = array();

            $columns = array(
                DB_TABLE_PREFIX . 'users.idu',
                'water_source_coordinates',
                'water_source_name',
                'fname',
                'lname',
                'pnumber',
                'device_imei',
                'last_known_location',
                'last_login'
            );

            $sql1 = " SELECT " . implode(",", $columns) . " FROM " . DB_TABLE_PREFIX . "water_source_caretakers "
                    . " INNER JOIN   " . DB_TABLE_PREFIX . "users on  " . DB_TABLE_PREFIX . "users.idu= " . DB_TABLE_PREFIX . "water_source_caretakers.uid "
                    . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "water_sources.id_water_source=" . DB_TABLE_PREFIX . "water_source_caretakers.water_source_id "
                    . "  WHERE " . DB_TABLE_PREFIX . "water_source_caretakers.water_source_id=$water_source_id "
                    . " UNION "
                    . " SELECT " . implode(",", $columns) . " FROM " . DB_TABLE_PREFIX . "water_source_treasurers "
                    . " INNER JOIN   " . DB_TABLE_PREFIX . "users on  " . DB_TABLE_PREFIX . "users.idu= " . DB_TABLE_PREFIX . "water_source_treasurers.uid "
                    . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "water_sources.id_water_source=" . DB_TABLE_PREFIX . "water_source_treasurers.water_source_id "
                    . "  WHERE " . DB_TABLE_PREFIX . "water_source_treasurers.water_source_id=$water_source_id "
            ;


            $sql = " SELECT *  FROM ($sql1) A WHERE last_known_location<>''";

            $seen_devices = $App->con->rawQuery($sql);


            $sql = " SELECT *  FROM ($sql1) A WHERE last_known_location=''";

            $missing_devices = $App->con->rawQuery($sql);


            echo json_encode(
                    array(
                        'seen_devices' => $seen_devices,
                        'missing_devices' => $missing_devices
            ));


            break;


        default:
            break;
    }

    exit();
}


$water_source_data = array();

$water_source_id = $App->getValue('id');

$query = "SELECT * FROM " . DB_TABLE_PREFIX . "water_sources WHERE id_water_source=$water_source_id ORDER BY id_water_source ASC LIMIT 1";

$App->con->where('id_water_source', $water_source_id);
$water_source_data = $App->con->getOne('water_sources');

if (!isset($water_source_data['id_water_source'])) {
    $App->setSessionMessage("Water Source does not exist");
    $App->navigate('/manage/');
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
$App->LogEevent($App->user->uid, $App->event->EVENT_VIEWED_WATER_SOURCE_SAVINGS, $App->getCurrentDateTime(), "", $water_source_data['id_water_source']);
