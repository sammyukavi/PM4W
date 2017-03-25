<?php

$action = $App->getValue("a");
$errors = array();

if ($action == 'ajax') {

    $params = array();
    $snippet = "";

    $platform_filter = $App->getSessionVariable("platform_filter");

    if (!empty($platform_filter)) {
        $params['system_used'] = $platform_filter;
    }

    $user_filter = $App->getSessionVariable("user_filter");

    if (!empty($user_filter)) {
        $params['uid'] = $user_filter;
    }

    $event_filter = $App->getSessionVariable("event_filter");

    if (!empty($event_filter)) {
        $params['event'] = $App->event->$event_filter;
    }

    $time_filter = $App->getSessionVariable("time_filter");


    switch ($time_filter) {
        case 'last_four_weeks':
            $start_time = date("Y-m-d H:i:s", strtotime("-4 week", strtotime($App->getCurrentDateTime())));
            break;
        case 'past_week':
            $start_time = date("Y-m-d H:i:s", strtotime("-1 week", strtotime($App->getCurrentDateTime())));
            break;
        case 'past_day':
            $start_time = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($App->getCurrentDateTime())));
            break;
        case 'past_hour':
            $start_time = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($App->getCurrentDateTime())));
            break;
        default:
            $start_time = $App->getCurrentDateTime("show as 1970");
            break;
    }


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

    $columns = array(
        'id_event', 'event', 'event_time', 'system_used'
    );

    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) ';


    if (isset($App->getValues['search']) && $App->getValues['search']['value'] != '') {
        if (empty($where)) {
            $where.=' WHERE (';
        } else {
            $where.=' AND (';
        }
        $search_value = $App->sanitizeVar($_GET['search'], 'value');

        if ($search_value != '') {
            $where.= implode(" LIKE '%$search_value%' OR ", $columns) . " LIKE '%$search_value%'";
        }

        $where.=")";
    }

    if (empty($where)) {
        $snippet.=" WHERE DATE(event_time)>=DATE('$start_time') ";
    } else {
        $snippet.=" AND DATE(event_time)>=DATE('$start_time') ";
    }

    $order = '';

    if (isset($_GET['order']) && count($_GET['order'])) {
        $orderBy = array();
        $orderByColumns = $App->sanitizeVar($_GET, 'order');
        foreach ($orderByColumns as $index => $orderByColumn) {
            $dir = $App->sanitizeVar($orderByColumn, 'dir');
            $orderBy[] = $App->sanitizeVar($columns, $App->sanitizeVar($orderByColumn, 'column')) . ($dir === 'asc' ? ' ASC ' : ' DESC ');
        }
        $order = 'ORDER BY ' . implode(', ', $orderBy);
    }

    $limit = '';

    if (isset($_GET['start']) && $_GET['length'] != -1) {
        $limit = " LIMIT " . intval($App->sanitizeVar($_GET, 'start')) . ", " . intval($_GET['length']);
    }

    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) system_user ';
// $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) system_user ';



    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "event_logs "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON idu=uid "
            . " $where "
            . " $snippet "
            . " $order "
            . " $limit ";

    $data = array();


//echo($sql);

    $results = $App->con->rawQuery($sql);
    foreach ($results as $row) {
        $data[] = array(
            '<input type="checkbox" name="ids[]" value="' . $row['id_event'] . '" class="check"/>',
            ucwords(str_replace("_", " ", $row['event'])),
            $row['system_user'],
            ucwords(str_replace("_", " ", $row['system_used'])),
            $App->getCurrentDateTime($row['event_time'], true, true),
            '<a href="' . '/manage/access-logs/?a=delete&id=' . $row['id_event'] . '" class="btn btn-danger delete-link"  data-toggle="tooltip" data-placement="top" title="Click To Delete Event"><i class="fa fa-trash"></i></a>'
        );
    }

    $recordsFiltered = 0;
    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $recordsTotal = 0;
    $results = $App->con->rawQuery("SELECT COUNT(id_event) recordsTotal FROM " . DB_TABLE_PREFIX . "event_logs ");

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

if (isset($_POST['delete'])) {
    $ids = $App->sanitizeVar($_POST, 'ids');
    $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_AN_EVENT_LOG;
    if (!empty($ids)) {
        $sql1 = "DELETE FROM " . DB_TABLE_PREFIX . "event_logs WHERE id_event IN(" . implode(",", $ids) . ") ";
        if ($App->con->rawQuery($sql1)) {
            $event = $App->event->EVENT_DELETED_AN_EVENT_LOG;
            $App->setSessionMessage("Event(s) Deleted.", SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
        }
    } else {
        $App->setSessionMessage("Select a log or logs to delete");
    }
    $App->LogEevent($App->user->idu, $event, $App->getCurrentDateTime());
    header("Location: /manage/access-logs/");
    exit();
}

switch ($action) {
    case 'set-filter':
        $App->setSessionVariable("platform_filter", $App->sanitizeVar($_POST, "platform_filter"));
        $App->setSessionVariable("user_filter", $App->sanitizeVar($_POST, "user_filter"));
        $App->setSessionVariable("event_filter", $App->sanitizeVar($_POST, "event_filter"));
        $App->setSessionVariable("time_filter", $App->sanitizeVar($_POST, "time_filter"));
        header("Location: /manage/access-logs/");
        exit();
        break;
    case 'delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_AN_EVENT_LOG;
        $id_event = $App->sanitizeVar($_GET, 'id');
        if ($App->deleteFromDb("event_logs", array('id_event' => $id_event))) {
            $event = $App->event->EVENT_DELETED_AN_EVENT_LOG;
            $App->setSessionMessage("Event Deleted.", SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
        }
        $App->LogEevent($App->user->idu, $event, $App->getCurrentDateTime(), "", $id_event);
        header("Location: /manage/access-logs/");
        exit();
        break;
    default :
        break;
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
