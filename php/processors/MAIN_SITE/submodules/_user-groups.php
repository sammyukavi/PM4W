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

    $columns = array('id_group', 'group_name', 'group_is_enabled', 'can_access_app', 'can_receive_emails', 'can_send_sms');

    // $columns[] = ' CONCAT_WS(" ",fname,lname) ';


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


    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "user_groups "
            // . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON group_id=id_group "
            . " $where "
            . " $order "
            . " $limit ";

    $data = array();

    $results = $App->con->rawQuery($sql);
    foreach ($results as $row) {
        $data[] = array(
            '<input type="checkbox" name="ids[]" value="' . $row['id_group'] . '" class="check"/>',
            $row['group_name'],
            ($row['group_is_enabled'] == 1 ? 'Yes' : 'No'),
            ($row['can_access_app'] == 1 ? 'Yes' : 'No'),
            ($row['can_receive_emails'] == 1 ? 'Yes' : 'No'),
            ($row['can_send_sms'] == 1 ? 'Yes' : 'No'),
            '<div class="text-center">'
            . ($row['id_group'] == $App->user->group_id ? '<button class="btn btn-active disabled" data-toggle="tooltip" data-placement="top" title="You cannot enable/Disable the group in which you belong"><i class="glyphicon glyphicon-off"></i></button>' : ( $row['group_is_enabled'] == 1 ? '<a href="/manage/user-groups/?a=deactivate&id=' . $row['id_group'] . '" class="btn btn-success" title="Dectivate"><i class="glyphicon glyphicon-off"></i></a>' : '<a href="/manage/user-groups/?a=activate&id=' . $row['id_group'] . '" class="btn btn-danger" title="Activate"><i class="glyphicon glyphicon-off"></i></a>'))
            . ' <a href="/manage/edit-user-group?id=' . $row['id_group'] . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> '
            . ' <a href="/manage/user-groups/?a=delete&id=' . $row['id_group'] . '" class="btn btn-danger delete-link" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></div>'
        );
    }

    $recordsFiltered = 0;
    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $recordsTotal = 0;
    $results = $App->con->rawQuery("SELECT COUNT(id_group) recordsTotal FROM " . DB_TABLE_PREFIX . "user_groups ");
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
    case 'deactivate':
        $group_id = $App->getValue('id');
        $event = $App->event->EVENT_ATTEMPTED_TO_DEACTIVATE_SYSTEM_USER_GROUP;
        if ($App->can_edit_user_permissions) {
            if ($App->saveUserGroup(array('group_is_enabled' => 0, 'id_group' => $group_id))) {
                $event = $App->event->EVENT_DEACTIVATED_SYSTEM_USER_GROUP;
                $App->setSessionMessage("User group deactivated.", SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $group_id);
        $App->navigate('/manage/user-groups');
        break;
    case 'activate':
        $group_id = $App->getValue('id');
        $event = $App->event->EVENT_ATTEMPTED_TO_ACTIVATE_SYSTEM_USER_GROUP;
        if ($App->can_edit_user_permissions) {
            if ($App->saveUserGroup(array('group_is_enabled' => 1, 'id_group' => $group_id))) {
                $event = $App->event->EVENT_ACTIVATED_SYSTEM_USER_GROUP;
                $App->setSessionMessage("User group activated.", SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $group_id);
        $App->navigate('/manage/user-groups');
        break;
    case 'delete':
        $group_id = $App->getValue('id');
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_SYSTEM_USER_GROUP;
        if ($App->can_delete_user_permissions) {
            if ($App->deleteFromDb("user_groups", array('id_group' => $group_id))) {
                $event = $App->event->EVENT_DELETED_SYSTEM_USER_GROUP;
                $App->setSessionMessage("User group deleted.", SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $group_id);
        $App->navigate('/manage/user-groups');
        break;

    default:
        break;
}

foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
