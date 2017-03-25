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

    $columns = array('fname', 'lname', 'idu', 'username', 'pnumber', 'group_name', 'app_version_in_use', 'last_login', 'active');
    $columns[] = ' CONCAT_WS(" ",fname,lname) ';

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


    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "users "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "user_groups ON group_id=id_group "
            . " $where "
            . " $order "
            . " $limit ";

    $data = array();

    $results = $App->con->rawQuery($sql);

    foreach ($results as $row) {
        $data[] = array(
            $App->user->uid != $row['idu'] ? '<input type="checkbox" name="ids[]" value="' . $row['idu'] . '" class="check"/>' : '',
            $row['fname'] . " " . $row['lname'],
            $row['username'],
            $row['pnumber'],
            $row['group_name'],
            $row['app_version_in_use'],
            $App->getCurrentDateTime($row['last_login'], true, true),
            '<div class="text-center">'
            . '<a href="/manage/users/?a=fix-sync-time&id=' . $row['idu'] . '" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Fix Sync time problem"><i class="fa fa-wrench"></i></a> '
            . ($App->user->uid != $row['idu'] ?
                    ( $row['active'] == 1 ? '<a href="/manage/users/?a=deactivate&id=' . $row['idu'] . '" class="btn btn-success" title="Dectivate"><i class="glyphicon glyphicon-off"></i></a>' : '<a href="/manage/users/?a=activate&id=' . $row['idu'] . '" class="btn btn-danger" title="Activate"><i class="glyphicon glyphicon-off"></i></a>')
                    . ' <a href="/manage/edit-user/?id=' . $row['idu'] . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> '
                    . ' <a href="/manage/users/?a=delete&id=' . $row['idu'] . '" class="btn btn-danger delete-link"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>' : '')
            . '</div>'
        );
    }



    $recordsFiltered = 0;
    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);


    $recordsTotal = 0;
    $results = $App->con->rawQuery("SELECT COUNT(idu) recordsTotal FROM " . DB_TABLE_PREFIX . "users ");
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

if (isset($_POST['fixSync'])) {

    $ids = $App->sanitizeVar($_POST, "ids");

    if (!empty($ids)) {
        foreach ($ids as $uid) {
            $sql = " SELECT CONCAT_WS(' ',fname,lname) name, last_login FROM " . DB_TABLE_PREFIX . "users WHERE idu=$uid ";
            $account = $App->con->rawQuery($sql);
            if (isset($account[0]['last_login'])) {
                $sql1 = " UPDATE " . DB_TABLE_PREFIX . "event_logs SET event_time='" . $account[0]['last_login'] . "' WHERE DATE(event_time)< DATE('" . $CONFIG["launch_date"] . "') AND uid=$uid";
                $sql2 = " UPDATE " . DB_TABLE_PREFIX . "event_logs SET event_description=event_time WHERE (event='" . $App->event->EVENT_SYNC_COMPLETE . "' OR  event='" . $App->event->EVENT_SYNC_UNCOMPLETE . "') AND uid=$uid ";
                $sql3 = " UPDATE " . DB_TABLE_PREFIX . "expenditures SET expenditure_date=last_updated,last_updated='" . $App->getCurrentDateTime() . "' WHERE DATE(expenditure_date)<2015 AND logged_by=$uid";
                $sql4 = " UPDATE " . DB_TABLE_PREFIX . "sales SET sale_date=last_updated,last_updated='" . $App->getCurrentDateTime() . "' WHERE DATE(sale_date)<DATE('" . $CONFIG["launch_date"] . "') AND sold_by=$uid ";

                $App->con->rawQuery($sql1);
                $App->con->rawQuery($sql2);
                $App->con->rawQuery($sql3);
                $App->con->rawQuery($sql4);

                $App->setSessionMessage("Sync time fixed for " . $account[0]['name'], SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("User account does not exist");
            }
        }
    } else {
        $App->setSessionMessage("Select a user or users to fix the time sync");
    } $App->navigate('/manage/users');
}

if (isset($_POST['activate'])) {
    $ids = $App->sanitizeVar($_POST, "ids");
    if (!empty($ids) && is_array($ids)) {
        if (($key = array_search($App->user->uid, $ids)) !== false) {
            unset($messages[$key]);
        }
        $sql = "UPDATE " . DB_TABLE_PREFIX . "users SET active=1 WHERE idu IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);
        $App->setSessionMessage("User account(s) activated", SUCCESS_STATUS_CODE);
    } else {
        $App->setSessionMessage("Select a user or users to activate user accounts");
    }
    $App->navigate('/manage/users');
}

if (isset($_POST['deactivate'])) {
    $ids = $App->sanitizeVar($_POST, "ids");
    if (!empty($ids) && is_array($ids)) {
        $sql = " UPDATE " . DB_TABLE_PREFIX . "users SET active=0 WHERE idu IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);
        $App->setSessionMessage("User account(s) deactivated", SUCCESS_STATUS_CODE);
    } else {
        $App->setSessionMessage("Select a user or users to deactivate user accounts");
    }
    $App->navigate('/manage/users');
}

if (isset($_POST['delete'])) {
    $ids = $App->sanitizeVar($_POST, "ids");
    if (!empty($ids) && is_array($ids)) {
        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "users WHERE idu IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "event_logs WHERE uid IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "expenditures WHERE logged_by IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "sms_messages WHERE created_by IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "sms_messages_recipients WHERE idu IN(" . implode(',', $ids) . ") AND account_type='user'";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "user_passwords WHERE uid IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "user_sessions WHERE uid IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "water_source_caretakers WHERE uid IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "water_source_treasurers WHERE uid IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $sql = "DELETE FROM " . DB_TABLE_PREFIX . "water_users WHERE added_by IN(" . implode(',', $ids) . ")";
        $App->con->rawQuery($sql);

        $App->setSessionMessage("User account(s) deleted", SUCCESS_STATUS_CODE);
    } else {
        $App->setSessionMessage("Select a user or users to delete");
    }
    $App->navigate('/manage/users');
}



switch ($action) {
    case 'fix-sync-time':
        $uid = $App->getValue('id');
        $sql = " SELECT last_login FROM " . DB_TABLE_PREFIX . "users WHERE idu=$uid ";
        $account = $App->con->rawQuery($sql);
        if (isset($account[0]['last_login'])) {
            $sql1 = " UPDATE " . DB_TABLE_PREFIX . "event_logs SET event_time='" . $account[0]['last_login'] . "' WHERE DATE(event_time)< DATE('" . $CONFIG["launch_date"] . "') AND uid=$uid";
            $sql2 = " UPDATE " . DB_TABLE_PREFIX . "event_logs SET event_description=event_time WHERE (event='" . $App->event->EVENT_SYNC_COMPLETE . "' OR  event='" . $App->event->EVENT_SYNC_UNCOMPLETE . "') AND uid=$uid ";
            $sql3 = " UPDATE " . DB_TABLE_PREFIX . "expenditures SET expenditure_date=last_updated,last_updated='" . $App->getCurrentDateTime() . "' WHERE DATE(expenditure_date)<DATE('" . $CONFIG["launch_date"] . "') AND logged_by=$uid";
            $sql4 = " UPDATE " . DB_TABLE_PREFIX . "sales SET sale_date=last_updated,last_updated='" . $App->getCurrentDateTime() . "' WHERE DATE(sale_date)<DATE('" . $CONFIG["launch_date"] . "') AND sold_by=$uid ";
            $App->con->rawQuery($sql1);
            $App->con->rawQuery($sql2);
            $App->con->rawQuery($sql3);
            $App->con->rawQuery($sql4);
            $App->setSessionMessage("Sync time fixed", SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage("User account does not exist");
        }
        $App->navigate('/manage/users');
        break;
    case 'activate':
        $event = $App->event->EVENT_ATTEMPTED_TO_ACTIVATE_SYSTEM_USER_ACCOUNT;
        $uid = $App->getValue('id');
        if ($App->can_edit_system_users) {
            if ($App->checkIFExists("users", array('idu' => $uid))) {
                if ($App->saveUserData(array('active' => 1, 'idu' => $uid))) {
                    $event = $App->event->EVENT_ACTIVATED_SYSTEM_USER_ACCOUNT;
                    $App->setSessionMessage("account activated.", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("Failed to activate the account, please try again later.");
                }
            } else {
                $App->setSessionMessage("User does not exist.");
            }
        } else {
            $App->setSessionMessage("You do not have sufficient permisions to perform the action.");
        }

        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $uid);

        $App->navigate('/manage/users');
        break;
    case 'deactivate':
        $event = $App->event->EVENT_ATTEMPTED_TO_DEACTIVATE_SYSTEM_USER_ACCOUNT;
        if ($App->can_edit_system_users) {
            $uid = $App->getValue('id');
            if ($App->checkIFExists("users", array('idu' => $uid))) {
                if ($App->saveUserData(array('active' => 0, 'idu' => $uid))) {
                    $event = $App->event->EVENT_DEACTIVATED_SYSTEM_USER_ACCOUNT;
                    $App->setSessionMessage("account deactivated.", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("Failed to deactivate the account, please try again later.");
                }
            } else {
                $App->setSessionMessage("User does not exist.");
            }
        } else {
            $App->setSessionMessage("You do not have sufficient permisions to perform the action.");
        }

        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $uid);
        $App->navigate('/manage/users');
        break;
    case 'delete':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_SYSTEM_USER_ACCOUNT;
        if ($App->can_delete_system_users) {
            $uid = $App->getValue('id');

            $App->con->where('idu', $uid);
            $user = $App->con->getOne('users');

            if (isset($user['idu'])) {

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "users WHERE idu=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "event_logs WHERE uid=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "expenditures WHERE logged_by=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "sms_messages WHERE created_by=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "sms_messages_recipients WHERE idu=$uid AND account_type='user'";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "user_passwords WHERE uid=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "user_sessions WHERE uid=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "water_source_caretakers WHERE uid=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "water_source_treasurers WHERE uid=$uid";
                $App->con->rawQuery($sql);

                $sql = "DELETE FROM " . DB_TABLE_PREFIX . "water_users WHERE added_by=$uid";
                $App->con->rawQuery($sql);

                $event = $App->event->EVENT_DELETED_SYSTEM_USER_ACCOUNT;
                $App->setSessionMessage("User Deleted.", SUCCESS_STATUS_CODE);
            } else {
                $App->setSessionMessage("That user does not exist");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $uid);
        $App->navigate('/manage/users');
        break;

    default:
        break;
}
$App->LogEevent($App->user->uid, $App->event->EVENT_LISTED_SYSTEM_USERS, $App->getCurrentDateTime());
foreach ($errors as $error) {
    $App->setSessionMessage($error);
}
