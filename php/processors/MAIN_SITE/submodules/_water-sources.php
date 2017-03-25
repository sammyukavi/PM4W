<?php

$action = $App->getValue("a");
$errors = array();

if ($action == 'ajax') {

    $params = array();
    $where = '';
    if (!empty($params)) {
        $where.=' WHERE ';
        $counter = 0;
        $lastIndex = (count($params) - 1);
        foreach ($params as $keyId => $value) {
            if ($counter !== $lastIndex) {
                $where.= $App->con->mysqli()->escape_string($keyId) . "='" . $App->con->mysqli()->escape_string($value) . "' AND ";
            } else {
                $where.= $App->con->mysqli()->escape_string($keyId) . "='" . $App->con->mysqli()->escape_string($value) . "'";
            }
            $counter+=1;
        }
    }

    $columns = array('id_water_source', 'water_source_name', 'water_source_id', 'water_source_location');



    if (isset($_GET['search']) && $_GET['search']['value'] != '') {
        if (empty($where)) {
            $where.=' WHERE (';
        } else {
            $where.=' AND (';
        }
        $search_value = $App->sanitizeVar($_GET['search'], 'value');
        if (!empty($search_value)) {
            $where.= implode(" LIKE '%$search_value%' OR ", $columns) . " LIKE '%$search_value%'";
        }

        $where.=")";
    }

    $order = '';

    if (isset($_GET['order']) && count($_GET['order'])) {
        $orderBy = array();
        $orderByColumns = $App->getValue('order');
        foreach ($orderByColumns as $index => $orderByColumn) {
            $dir = $App->sanitizeVar($orderByColumn, 'dir');
            $orderBy[] = $App->sanitizeVar($columns, $App->sanitizeVar($orderByColumn, 'column')) . ($dir === 'asc' ? ' ASC ' : ' DESC ');
        }
        $order = 'ORDER BY ' . implode(', ', $orderBy);
    }

    $limit = '';

    if (isset($_GET['start']) && $_GET['length'] != -1) {
        $limit = " LIMIT " . intval($App->getValue('start')) . ", " . intval($_GET['length']);
    }

    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "water_sources "
            // . " LEFT JOIN " . DB_TABLE_PREFIX . "user_groups ON group_id=id_group "
            . " $where "
            . " $order "
            . " $limit ";


    $data = array();

    $results = $App->con->rawQuery($sql);

    foreach ($results as $row) {
        $data[] = array(
            '<input type="checkbox" name="ids[]" value="' . $row['id_water_source'] . '" class="check"/>',
            $row['water_source_name'],
            $row['water_source_id'],
            $row['water_source_location'],
            '<div class="text-center"><a href="/manage/water-source-sales' . "?id=" . $row['id_water_source'] . '" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Click To View Sales"><i class="fa fa-usd"></i></a> | '
            . '<a href="/manage/water-source-users?id=' . $row['id_water_source'] . '" class="btn btn-default"  data-toggle="tooltip" data-placement="top" title="Click To View Water Users"><i class="fa fa-group"></i></a> | '
            . ''
            . '<a href="/manage/water-source-savings?id=' . $row['id_water_source'] . '" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Click To View Savings & Statistics"><i class="fa fa-bar-chart-o"></i></a> | '
            . '<a href="/manage/edit-water-source?id=' . $row['id_water_source'] . '" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Click To Edit Water Source"><i class="fa fa-pencil"></i></a> | '
            . '<a href="?a=delete&id=' . $row['id_water_source'] . '" class="btn btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Click To Delete Water Source"><i class="fa fa-trash"></i></a></div>'
        );
    }

    $recordsFiltered = 0;
    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $recordsTotal = 0;
    $results = $App->con->rawQuery("SELECT COUNT(id_water_source) recordsTotal FROM " . DB_TABLE_PREFIX . "water_sources ");
    $recordsTotal = intval($results[0]['recordsTotal']);


    $server_reply = array(
        "draw" => intval($App->getValue('draw')),
        "recordsTotal" => intval($recordsTotal),
        "recordsFiltered" => intval($recordsFiltered),
        "data" => $data
    );

    echo json_encode($server_reply);
    exit();
}

switch ($action) {
    case 'delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_WATER_SOURCE;
        $water_source_id = $App->getValue('id');

        $sql1 = "DELETE FROM " . DB_TABLE_PREFIX . "expenditures WHERE water_source_id=$water_source_id";
        $sql2 = "DELETE FROM " . DB_TABLE_PREFIX . "sales WHERE water_source_id=$water_source_id";
        $sql3 = "DELETE FROM " . DB_TABLE_PREFIX . "water_source_caretakers WHERE water_source_id=$water_source_id";
        $sql4 = "DELETE FROM " . DB_TABLE_PREFIX . "water_source_treasurers WHERE water_source_id=$water_source_id";
        $sql5 = "DELETE FROM " . DB_TABLE_PREFIX . "water_sources WHERE id_water_source=$water_source_id";

        $App->con->rawQuery($sql1);
        $App->con->rawQuery($sql2);
        $App->con->rawQuery($sql3);
        $App->con->rawQuery($sql4);
        $App->con->rawQuery($sql5);

        $event = $App->event->EVENT_DELETED_WATER_SOURCE;
        $App->setSessionMessage("Water source deleted", SUCCESS_STATUS_CODE);

        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $water_source_id);
        $App->navigate('/manage/water-sources');
        break;

    default:
        break;
}


foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
$App->LogEevent($App->user->uid, $App->event->EVENT_LISTED_WATER_SOURCE, $App->getCurrentDateTime());
