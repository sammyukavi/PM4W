<?php

$action = $App->getValue("a");
if ($action == 'ajax') {

    $columns = array(
        'build_name',
        'build_version',
        'build_date',
        'is_stable',
        'date_uploaded'
    );

    $columns[] = ' CONCAT_WS(" ",' . DB_TABLE_PREFIX . 'users.fname,' . DB_TABLE_PREFIX . 'users.lname) ';

    $where = "";
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

    $columns[] = 'id_build';
    $columns[] = 'build_features';
    $columns[] = 'build_date';
    $columns[] = 'published';
    $columns[] = 'file_name';
    $columns[] = 'fname';
    $columns[] = 'lname';
    $columns[] = 'preferred';

    $sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "app_builds "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON uploaded_by=idu "
            . " LEFT JOIN " . DB_TABLE_PREFIX . "files ON id_file=file_id "
            . " $where "
            . " $order "
            . " $limit ";

    $data = array();

    $results = $App->con->rawQuery($sql);
    foreach ($results as $row) {
        $data[] = array(
           // '<input type="checkbox" name="ids[]" value="' . $row['id_build'] . '" class="check"/>',
            $row['build_name'] . ' ' . ($row['published'] == 1 ? ' <span class="label label-default">Published</span> ' : '') . ' ' . ($row['preferred'] == 1 ? ' <span class="label label-success" data-toggle="tooltip" data-placement="top" title="Preferred"><i class="fa fa-check"></i></span> ' : ''),
            $row['build_version'],
            $row['build_date'],
            $row['is_stable'] == 1 ? 'Stable' : 'Nightly Build',
            $row['date_uploaded'],
            $row['fname'] . ' ' . $row['lname'],
            '<div class="text-center"><a href="/attachment/' . $row['file_name'] . '" class="btn btn-default"><i class="fa fa-download"></i></a> <a href="/manage/edit-build?id=' . $row['id_build'] . '" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="?a=delete&id=' . $row['id_build'] . '" class="btn btn-danger delete-link"><i class="fa fa-trash"></i></a></div>'
        );
    }

    $results = $App->con->rawQuery('SELECT FOUND_ROWS() recordsFiltered');
    $recordsFiltered = intval($results[0]['recordsFiltered']);

    $results = $App->con->rawQuery("SELECT COUNT(id_build) recordsTotal FROM " . DB_TABLE_PREFIX . "app_builds ");
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

if ($action == "delete") {
    $App->con->where('id_build', $App->getValue('id'));
    $App->con->join('files', 'file_id=id_file');
    $build = $App->con->getOne('app_builds');
    if (!empty($build)) {
        @unlink($build['file_path']);
        $App->con->where("id_file", $build['id_file']);
        $App->con->delete('files');

        $App->con->where("id_build", $build['id_build']);
        $App->con->delete('app_builds');
        $App->setSessionMessage("Deleted", SUCCESS_STATUS_CODE);
    }

    $App->navigate('/manage/builds');
}