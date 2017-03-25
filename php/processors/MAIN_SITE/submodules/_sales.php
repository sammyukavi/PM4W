<?php
$action = $App->getValue("a");
$water_source_id = $App->getValue('water_source_id');
$sold_by = $App->getValue('sold_by');
$sold_to = $App->getValue('sold_to');
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

    $columns = array('id_sale', 'idu', 'id_user', 'id_water_source', 'water_source_name', 'sold_by', 'sale_date');
    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'water_users.fname,' . DB_TABLE_PREFIX . 'water_users.lname) ';
    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) ';

    $columns[] = 'sale_ugx';

    if (isset($_GET['search']) && $_GET['search']['value'] != '') {
        if (empty($where)) {
            $where.=' WHERE (';
        } else {
            $where.=' AND (';
        }
        $search_value = $App->sanitizeVar($App->getValue('search'), 'value');

        if ($search_value != '') {
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

    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'water_users.fname,' . DB_TABLE_PREFIX . 'water_users.lname) water_user';
    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) system_user ';
    // $columns[] = ' SUM(sale_ugx) sale_ugx ';

    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "sales "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "water_users ON sold_to=id_user "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON sold_by=idu "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON id_water_source=" . DB_TABLE_PREFIX . "sales.water_source_id "
            . " $where "
            //. " GROUP BY DATE(sale_date) "
            . " $order "
            . " $limit ";

    $data = array();

    $results = $App->con->rawQuery($sql);
    foreach ($results as $row) {

        $link = "";
        if ($App->can_edit_sales) {
            $link.="<a href=\"/manage/edit-sale/?id=" . $row['id_sale'] . "\" class=\"btn btn-primary\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\"><i class=\"fa fa-pencil\"></i></a> ";
        }
        if ($App->can_delete_sales) {
            $link .= "| <a href=\"?a=delete-sale&id=" . $row['id_sale'] . "\" class=\"btn btn-danger delete-link\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\"><i class=\"fa fa-trash\"></i></a>";
        }

        $c_name = trim($row['water_user']);
        if (empty($c_name)) {
            $c_name = "Daily Sale";
        } else {
            $c_name = '<a href="/manage/water-user-transactions/?id=' . $row['id_user'] . '"  data-toggle="tooltip" data-placement="top" title="Click To View User Payments History">' . $row['water_user'] . '</a>';
        }

        $data[] = array(
            '<input type="checkbox" name="ids[]" value="' . $row['id_sale'] . '" class="check"/>',
            $c_name,
            '<a href="/manage/water-source-sales/?id=' . $row['id_water_source'] . '"  data-toggle="tooltip" data-placement="top" title="Click To Tiew More Sales From ' . $row['water_source_name'] . '">' . $row['water_source_name'] . '</a>',
            number_format($row['sale_ugx'], 2, '.', ','),
            '<a href="/manage/attendants-sales/?id=' . $row['idu'] . '"  data-toggle="tooltip" data-placement="top" title="Click To Tiew More Sales By ' . $row['system_user'] . '">' . $row['system_user'] . '</a>',
            $App->getCurrentDateTime($row['sale_date'], true),
            $link
        );
    }

    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $results = $App->con->rawQuery("SELECT COUNT(id_sale) recordsTotal FROM " . DB_TABLE_PREFIX . "sales ");
    $recordsTotal = intval($results[0]['recordsTotal']);

    $server_reply = array(
        "draw" => intval($App->getValue('draw')),
        "recordsTotal" => intval($recordsTotal),
        "recordsFiltered" => intval($recordsFiltered),
        "data" => $data
    );

    echo json_encode($server_reply);
    exit();
} elseif ($action == 'fetch-attendants') {
    //$sql = "SELECT  FROM " . TABLE_PREFIX . "water_source_caretakers," . TABLE_PREFIX . "users WHERE water_source_id=$water_source_id AND  ORDER BY fname,lname ";

    $columns = array("idu,CONCAT_WS(' ',fname,lname) name");
    $App->con->where('water_source_id', $water_source_id);
    $App->con->orderBy('fname,lname', 'ASC');
    $App->con->groupBy('idu');
    $App->con->join('users', 'idu=uid', 'LEFT');
    $water_sources = $App->con->get('water_source_caretakers', null, $columns);
    ?>
    <select name="sold_by" id="sold_by" class="form-control selectpicker-with-search">
        <?php
        echo count($water_sources) == 0 ? '<option>-----</option>' : '';
        foreach ($water_sources as $attendant) {
            ?>
            <option value="<?php echo $attendant['idu']; ?>" <?php echo $sold_by == $attendant['idu'] ? 'selected="selected"' : ''; ?>><?php echo $attendant['name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
    exit();
} elseif ($action == 'fetch-water-users') {
    //$sql = "SELECT  FROM " . TABLE_PREFIX . "water_users WHERE water_source_id=$water_source_id ORDER BY fname,lname ";

    $columns = array("id_user,CONCAT_WS(' ',fname,lname) name");
    $App->con->where('water_source_id', $water_source_id);
    $App->con->orderBy('fname,lname', 'ASC');
    $water_users = $App->con->get('water_users', null, $columns);
    ?>
    <select name="sold_to" id="sold_to" class="form-control selectpicker-with-search">
        <option value="0" <?php echo $sold_to == 0 ? 'selected="selected"' : ''; ?>>Daily Sale</option>
        <?php
        foreach ($water_users as $water_user) {
            ?>
            <option value="<?php echo $water_user['id_user']; ?>" <?php echo $sold_to == $water_user['id_user'] ? 'selected="selected"' : ''; ?>><?php echo $water_user['name'] ?></option>
            <?php
        }
        ?>
    </select>
    <?php
    exit();
}

if (isset($_POST['delete'])) {
    $ids = $App->postValue('ids');
    $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_SALE;
    if (!empty($ids)) {
        $App->con->where('id_sale', $ids, 'IN');
        if ($App->con->delete('sales')) {
            $event = $App->event->EVENT_DELETED_SALE;
            $App->setSessionMessage("Sale(s) Deleted.", SUCCESS_STATUS_CODE);
        } else {
            $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
        }
    } else {
        $App->setSessionMessage("Select sale or sales to delete");
    }
    $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime());
    $App->navigate('/manage/sales/');
}

switch ($action) {

    case 'delete-sale':
        $event = $App->event->EVENT_ATTEMPTED_TO_DELETE_SALE;
        if ($App->can_delete_sales) {
            $id_sale = $App->getValue('id');

            $App->con->where('id_sale', $id_sale);
            $sale = $App->con->getOne('sales');

            if (isset($sale['id_sale'])) {
                if ($App->deleteFromDb("sales", array('id_sale' => $id_sale))) {
                    $event = $App->event->EVENT_DELETED_SALE;
                    $App->setSessionMessage("Record Deleted.", SUCCESS_STATUS_CODE);
                } else {
                    $App->setSessionMessage("An error occured trying to perform the task. Please try agin later");
                }
            } else {
                $App->setSessionMessage("That sale does not exist");
            }
        } else {
            $App->setSessionMessage("You do not have the required rights to perform this action");
        }
        $App->LogEevent($App->user->uid, $event, $App->getCurrentDateTime(), "", $id_sale);
        $App->navigate('/manage/sales/');
        break;
    default:
        break;
}
