<?php

$action = $App->getValue("a");
$errors = array();

if ($action == 'ajax') {

    $params = array(
    );

    $columns = array(
        'id_msg',
        'label',
        'message_content',
        'scheduled_send_date',
        'seen',
        'wu.fname',
        'wu.lname',
        'wu.pnumber'
    );

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


    $where = '';
    if (isset($_GET['search']) && $_GET['search']['value'] != '') {
        $where.=' WHERE (';
        $search_value = $App->sanitizeVar($_GET['search'], 'value');
        if ($search_value != '') {
            $where.= implode(" LIKE '%$search_value%' OR ", $columns) . " LIKE '%$search_value%'";
        }
        $where.=")";
    }

    $App->con->rawQuery("SET group_concat_max_len=2048");

    if (empty($where)) {
        $_where = " WHERE account_type='water_user'";
    } else {
        $_where = " AND account_type='water_user'";
    }

    $columns[] = "GROUP_CONCAT(wu.pnumber,' (',wu.fname,' ',wu.lname,')' separator ', ') recipients";
    $columns[] = "CONCAT_WS(' ',u.fname,u.lname) created_by";
    $columns[] = 'can_be_sent';

    $sql = "(SELECT  " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "sms_messages sm "
            . "LEFT JOIN " . DB_TABLE_PREFIX . "sms_messages_recipients sr ON msg_id=id_msg "
            . "LEFT JOIN " . DB_TABLE_PREFIX . "water_users wu ON wu.id_user=sr.id_user "
            . "LEFT JOIN " . DB_TABLE_PREFIX . "users u ON u.idu=created_by "
            . "$where "
            . "$_where "
            . "GROUP BY id_msg "
            . "$order "
            . "$limit) ";

    $sql .= " UNION ALL ";

    if (empty($where)) {
        $_where = " WHERE account_type='user'";
    } else {
        $_where = " AND account_type='user'";
    }

    $sql .= "(SELECT  " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "sms_messages sm "
            . "LEFT JOIN " . DB_TABLE_PREFIX . "sms_messages_recipients sr ON msg_id=id_msg "
            . "LEFT JOIN " . DB_TABLE_PREFIX . "users wu ON sr.idu=wu.idu "
            . "LEFT JOIN " . DB_TABLE_PREFIX . "users u ON u.idu=created_by "
            . "$where "
            . "$_where "
            . "GROUP BY id_msg "
            . "$order "
            . "$limit) ";

    $sql = "SELECT id_msg,label,message_content,scheduled_send_date,can_be_sent,seen,GROUP_CONCAT(recipients separator ', ') recipients,created_by  FROM ($sql) A GROUP BY id_msg";

    $results = $App->con->rawQuery($sql);

    $data = array();

    foreach ($results as $row) {
        $data[] = array(
            '<input type="checkbox" name="ids[]" value="' . $row['id_msg'] . '" class="check"/>',
            ucfirst($row['label']),
            $App->getExcerpt($row['recipients']),
            $row['message_content'],
            $App->getCurrentDateTime($row['scheduled_send_date'], true, true),
            $row['created_by'],
            '<div class="text-center">'
            //. ($row['can_be_sent'] == 1 ? '<a href="/manage/forward-message?id=' . $row['id_msg'] . '" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-reply"></i></a> ' : '')
            . '<a href="/manage/view-message?id=' . $row['id_msg'] . '" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-eye"></i></a> '
            . '<a href="/manage/sms-messages?a=delete&id=' . $row['id_msg'] . '" class="btn btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>'
            . '</div>'
        );
    }

    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $results = $App->con->rawQuery("SELECT COUNT(id_msg) recordsTotal FROM " . DB_TABLE_PREFIX . "sms_messages ");
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
    $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_A_MESSAGE;
    if (!empty($ids)) {
        $sql1 = " DELETE FROM " . DB_TABLE_PREFIX . "sms_messages_recipients WHERE msg_id IN(" . implode(",", $ids) . ") ";
        $sql2 = " DELETE FROM " . DB_TABLE_PREFIX . "sms_messages WHERE id_msg IN(" . implode(",", $ids) . ") ";

        $App->con->rawQuery($sql1);
        $App->con->rawQuery($sql2);

        $event = $App->event->EVENT_DELETED_A_MESSAGE;
        $App->setSessionMessage("Message(s) Deleted.", SUCCESS_STATUS_CODE);
    } else {
        $App->setSessionMessage("Select message or messages to delete");
    }

    $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
    $App->navigate('/manage/sms-messages');
}

switch ($action) {
    case 'delete':
        $ids = $App->getValue('id');
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_A_MESSAGE;
        if (!empty($ids)) {
            $sql1 = " DELETE FROM " . DB_TABLE_PREFIX . "sms_messages_recipients WHERE msg_id=" . $ids . " ";
            $sql2 = " DELETE FROM " . DB_TABLE_PREFIX . "sms_messages WHERE id_msg=" . $ids . " ";
            $App->con->rawQuery($sql1);
            $App->con->rawQuery($sql2);

            $event = $App->event->EVENT_DELETED_A_MESSAGE;
            $App->setSessionMessage("Message(s) Deleted.", SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage("Select message or messages to delete");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
        $App->navigate('/manage/sms-messages');
        break;
}


foreach ($errors as $error) {
    $App->setSessionMessage($error);
}

