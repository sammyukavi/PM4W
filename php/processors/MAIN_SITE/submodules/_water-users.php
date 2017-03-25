<?php
$action = $App->sanitizeVar($_GET, "a");
$errors = array();

if ($action == 'water-sources') {
    $uid = $App->getValue('uid');
    $water_source_id = $App->getValue('water_source_id');
    $water_source_ids = $water_sources = array();

    $App->con->where('uid', $uid);
    $results = $App->con->get('water_source_caretakers', null, array('water_source_id'));
    if (is_array($results)) {
        foreach ($results as $row) {
            $water_source_ids[] = $row['water_source_id'];
        }
    }

    $App->con->where('uid', $uid);
    $results = $App->con->get('water_source_treasurers', null, array('water_source_id'));
    if (is_array($results)) {
        foreach ($results as $row) {
            $water_source_ids[] = $row['water_source_id'];
        }
    }

    $water_source_ids = array_unique($water_source_ids);

    if (!empty($water_source_ids)) {
        $App->con->where('id_water_source', $water_source_ids, "IN");
        $results = $App->con->get('water_sources', null, array('id_water_source,water_source_name'));
        if (is_array($results)) {
            foreach ($results as $row) {
                $water_sources[] = $row;
            }
        }
    }
    ?>
    <select name="water_source_id" class="form-control selectpicker-with-search">
        <?php
        echo count($water_sources) == 0 || $water_source_id == "0" ? '<option>-----</option>' : '';
        foreach ($water_sources as $water_source) {
            ?>
            <option value="<?php echo $water_source['id_water_source']; ?>" <?php echo $water_source_id == $water_source['id_water_source'] ? 'selected="selected"' : ''; ?>><?php echo $water_source['water_source_name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
    exit();
} elseif ($action == 'ajax') {
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

    $columns = array('idu', 'id_user', DB_TABLE_PREFIX . 'water_users.pnumber', 'marked_for_delete', DB_TABLE_PREFIX . 'water_users.last_updated', 'added_by', 'id_water_source', 'water_source_name',);

    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'water_users.fname,' . DB_TABLE_PREFIX . 'water_users.lname) ';
    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) ';


    if (isset($_GET['search']) && $_GET['search']['value'] != '') {
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

    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'water_users.fname,' . DB_TABLE_PREFIX . 'water_users.lname) water_user';
    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) system_user ';



    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "water_users "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON added_by=idu "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON id_water_source=" . DB_TABLE_PREFIX . "water_users.water_source_id "
            . " $where "
            . " $order "
            . " $limit ";

    $data = array();


    //echo($sql);

    $results = $App->con->rawQuery($sql);
    foreach ($results as $row) {
        $data[] = array(
            '<input type="checkbox" name="ids[]" value="' . $row['id_user'] . '" class="check"/>',
            '<a href="/manage/water-user-transactions/?id=' . $row['id_user'] . '" data-toggle="tooltip" data-placement="top" title="Click To View User Payments History">' . $row['water_user'] . '</a>',
            $row['pnumber'],
            '<a href="/manage/water-source-users/?id=' . $row['id_water_source'] . '" data-toggle="tooltip" data-placement="top" title="Click To Tiew More Water Users From ' . $row['water_source_name'] . '">' . $row['water_source_name'] . '</a>',
            $App->getCurrentDateTime($row['last_updated'], true, true),
            '<a href="/manage/added-by-attendant/?id=' . $row['idu'] . '" data-toggle="tooltip" data-placement="top" title="Click To Tiew More Water Users Added By ' . $row['system_user'] . '">' . $row['system_user'] . '</a>',
            ($row['marked_for_delete'] == 1 ? '<span class="label label-primary">Marked for delete</span>' : '<span class="label label-success">Active</span>'),
            '<div class="text-center"><span class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" name="dropdownMenu' . $row['id_user'] . '" id="dropdownMenu' . $row['id_user'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Actions
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">' .
            ($row['marked_for_delete'] == 1 ?
                    '<li><a href="/manage/water-users?a=unmark-for-delete&id=' . $row['id_user'] . '">Unmark for delete</a></li>' : '<li><a href="/manage/water-users?a=mark-for-delete&id=' . $row['id_user'] . '">Mark for Delete</a></li>'
            ) .
            '</ul>
</span>'
            . ' <a href="/manage/edit-water-user/?id=' . $row['id_user'] . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> '
            . ' <a href="/manage/water-users?a=delete&id=' . $row['id_user'] . '" class="btn btn-danger delete-link"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>'
            . '</div>'
        );
    }

    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $results = $App->con->rawQuery("SELECT COUNT(id_user) recordsTotal FROM " . DB_TABLE_PREFIX . "water_users ");
    $recordsTotal = intval($results[0]['recordsTotal']);

    $server_reply = array(
        "draw" => intval($App->sanitizeVar($_GET, 'draw')),
        "recordsTotal" => intval($recordsTotal),
        "recordsFiltered" => intval($recordsFiltered),
        "data" => $data
    );

    echo json_encode($server_reply);
    exit();
}

if (isset($_POST['topbulkAction']) && !empty($_POST['topbulkAction']) ||
        isset($_POST['bottombulkAction']) && !empty($_POST['bottombulkAction'])) {
    $bulkAction = "";
    $top_bulkAction = $App->sanitizeVar($_POST, 'topbulkAction');
    $bottom_bulkAction = $App->sanitizeVar($_POST, 'bottombulkAction');

    if (!empty($top_bulkAction) && empty($bottom_bulkAction)) {
        $bulkAction = $top_bulkAction;
    } elseif (empty($top_bulkAction) && !empty($bottom_bulkAction)) {
        $bulkAction = $bottom_bulkAction;
    }

    switch ($bulkAction) {
        case 'markForDelete':
            $ids = $App->sanitizeVar($_POST, 'ids');
            $event = $App->event->EVENT_ATTEMPTED_TO_DEACTIVATE_WATER_USER_ACCOUNT;
            if (!empty($ids)) {
                $sql = "UPDATE " . DB_TABLE_PREFIX . "water_users SET "
                        . " marked_for_delete=1, "
                        . " last_updated='" . $App->getCurrentDateTime() . "'"
                        . " WHERE id_user IN(" . implode(",", $ids) . ") ";
                if ($App->con->rawQuery($sql)) {
                    $event = $App->event->EVENT_DEACTIVATED_WATER_USER_ACCOUNT;
                    $App->setSessionMessage("Water user(s) has been marked for delete", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
                }
            } else {
                $App->setSessionMessage("Select user or users to mark for delete");
            }

            break;
        case 'unmarkForDelete':
            $ids = $App->sanitizeVar($_POST, 'ids');
            $event = $App->event->EVENT_ATTEMPTED_TO_ACTIVATE_WATER_USER_ACCOUNT;
            if (!empty($ids)) {
                $sql = "UPDATE " . DB_TABLE_PREFIX . "water_users SET "
                        . " marked_for_delete=0, "
                        . " last_updated='" . $App->getCurrentDateTime() . "'"
                        . " WHERE id_user IN(" . implode(",", $ids) . ") ";
                if ($App->con->rawQuery($sql)) {
                    $event = $App->event->EVENT_ACTIVATED_WATER_USER_ACCOUNT;
                    $App->setSessionMessage("Water user(s) has been unmarked for delete", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
                }
            } else {
                $App->setSessionMessage("Select user or users to unmark for delete");
            }
            break;

        default:
            break;
    }
    $App->navigate('/manage/water-users');
}

if (isset($_POST['delete'])) {
    $ids = $App->sanitizeVar($_POST, 'ids');
    $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_WATER_USER_ACCOUNT;
    if (!empty($ids)) {
        $sql1 = "DELETE FROM " . DB_TABLE_PREFIX . "water_users WHERE id_user IN(" . implode(",", $ids) . ") ";
        $sql2 = "DELETE FROM " . DB_TABLE_PREFIX . "sales WHERE sold_to IN(" . implode(",", $ids) . ") ";

        $App->con->rawQuery($sql1);
        $App->con->rawQuery($sql2);

        $event = $App->event->EVENT_DELETED_WATER_USER_ACCOUNT;
        $App->setSessionMessage("Water User(s) Deleted.", SUCCESS_STATUS_CODE);
    } else {
        $App->setSessionMessage("Select user or users to delete");
    }
    $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
    $App->navigate('/manage/water-users');
}


switch ($action) {
    case'mark-for-delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DEACTIVATE_WATER_USER_ACCOUNT;
        $id_user = $App->sanitizeVar($_GET, 'id');
        if ($App->checkIFExists("water_users", array('id_user' => $id_user))) {
            $App->con->where('id_user', $id_user);
            if ($App->con->update('water_users', array('marked_for_delete' => 1, 'last_updated' => $App->getCurrentDateTime()))) {
                $event = $App->event->EVENT_DEACTIVATED_WATER_USER_ACCOUNT;
                $App->setSessionMessage("Water user has been marked for delete", SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("Failed to mark for delete, please try again later.");
            }
        } else {
            $App->setSessionMessage("Water user does not exist.");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $id_user);
        $App->navigate('/manage/water-users');
        break;
    case'unmark-for-delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_ACTIVATE_WATER_USER_ACCOUNT;
        $id_user = $App->sanitizeVar($_GET, 'id');
        if ($App->checkIFExists("water_users", array('id_user' => $id_user))) {
            $App->con->where('id_user', $id_user);
            if ($App->con->update('water_users', array('marked_for_delete' => 0, 'last_updated' => $App->getCurrentDateTime()))) {
                $event = $App->event->EVENT_ACTIVATED_WATER_USER_ACCOUNT;
                $App->setSessionMessage("Water user has been unmarked for delete", SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("Failed to unmark for delete, please try again later.");
            }
        } else {
            $App->setSessionMessage("Water user does not exist.");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $id_user);
        $App->navigate('/manage/water-users');
        break;
    case 'delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_WATER_USER_ACCOUNT;
        if ($App->can_delete_water_users) {
            $uid = $App->sanitizeVar($_GET, 'id');
            $query = "SELECT * FROM " . DB_TABLE_PREFIX . "water_users WHERE id_user=$uid";
            $App->con->where('id_user', $uid);
            $user = $App->con->getOne('water_users');
            if (isset($user['id_user'])) {
                if ($App->deleteFromDb("water_users", array('id_user' => $uid)) && $App->deleteFromDb("sales", array('sold_to' => $uid))) {
                    $event = $App->event->EVENT_DELETED_WATER_USER_ACCOUNT;
                    $App->setSessionMessage("Water User Deleted.", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
                }
            } else {
                $App->setSessionMessage("That user does not exist");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $uid);
        $App->navigate('/manage/water-users');
        break;
    default:
        break;
}
$App->LogEevent($App->user->uid, $App->event->EVENT_LISTED_WATER_USERS, $App->getCurrentDateTime());
foreach ($errors as $error) {
    $App->setSessionMessage($error);
}