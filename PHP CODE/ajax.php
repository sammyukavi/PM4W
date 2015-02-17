<?php

require_once './config.php';
if (is_logged_in()) {


    $aColumns = array_unique(array(
        'sales.id_sale', 'sales.sale_date', 'sales.sale_ugx', 'water_sources.id_water_source',
        'water_sources.water_source_name', 'water_users.fname', 'water_users.lname', 'water_users.id_user',
        'users.idu', 'users.fname', 'users.lname'));

    $aJoinColumns = array(
        'sales.id_sale', 'sales.sale_date', 'sales.sale_ugx', 'water_sources.id_water_source',
        'water_sources.water_source_name', 'water_users.fname AS user_fname', 'water_users.lname AS user_lname', 'water_users.id_user',
        'users.idu', 'users.fname AS attendant_fname', 'users.lname AS attendant_lname');


    $sIndexColumn = "id_sale";

    $sTable = TABLE_PREFIX . "sales";

    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . $dbhandle->con->escape_string($_GET['iDisplayStart']) . ", " .
                $dbhandle->con->escape_string($_GET['iDisplayLength']);
    }

    $sOrder = "";
    if (isset($_GET['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
            if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
				 	" . $dbhandle->con->escape_string($_GET['sSortDir_' . $i]) . ", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }

    $sWhere = "";

    if (($USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales || $USER->can_view_sales ) && $USER->can_view_water_source_savings) {

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $dbhandle->con->escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
    } else {

        $sWhere = "WHERE users.idu=" . $USER->idu;

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere .= " AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $dbhandle->con->escape_string($_GET['sSearch']) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
    }

    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i] . " LIKE '%" . $dbhandle->con->escape_string($_GET['sSearch_' . $i]) . "%' ";
        }
    }
    $sJoin = "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
            . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by ";


    $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aJoinColumns)) . " FROM   $sTable $sJoin $sWhere $sOrder $sLimit";

    $rResult = $dbhandle->RunQueryForResults($sQuery);


    $sQuery = "SELECT FOUND_ROWS()";
    $rResultFilterTotal = $dbhandle->RunQueryForResults($sQuery);
    $aResultFilterTotal = $rResultFilterTotal->fetch_array();
    $iFilteredTotal = $aResultFilterTotal[0];

    $sQuery = "SELECT COUNT(" . $sIndexColumn . ") FROM   $sTable ";
    $rResultTotal = $dbhandle->RunQueryForResults($sQuery);
    $aResultTotal = $rResultTotal->fetch_array();
    $iTotal = $aResultTotal[0];

    $output = array(
        "sEcho" => intval(getArrayVal($_GET, 'sEcho')),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    while ($aRow = $rResult->fetch_assoc()) {//
        $link = "";
        if ($USER->can_edit_sales) {
            $link.="<a href=\"?a=edit-sale&id=" . $aRow['id_sale'] . "\" class=\"btn btn-info\" >Edit</a> ";
        }
        if ($USER->can_delete_sales) {
            $link .= "| <a href=\"?a=delete-sale&id=" . $aRow['id_sale'] . "\" class=\"btn btn-danger delete-link\">Delete</a>";
        }

        $row = array(
            $aRow['id_sale'],
            $aRow['user_fname'] . " " . $aRow['user_lname'],
            $aRow['water_source_name'],
            number_format($aRow['sale_ugx'], 2, '.', ','),
            '<a href="?a=attendants-sales&id=' . $aRow['idu'] . '">' . $aRow['attendant_fname'] . " " . $aRow['attendant_lname'] . '</a>',
            getCurrentDate($aRow['sale_date'],true,true),
            $link
                //'<a href="?a=edit-sale&id=' . $aRow['id_sale'] . '" class="btn btn-info">Edit</a> | <a href="?a=delete-sale&id=' . $aRow['id_sale'] . '" class="btn btn-danger delete-link">Delete</a>'
        );


        $c_name = trim($row[1]);
        if (empty($c_name)) {
            $c_name = "Daily Sale";
        } else {
            $c_name = '<a href="?a=view-water-user-transactions&id=' . $aRow['id_user'] . '">' . $c_name . '</a>';
        }

        $row[1] = $c_name;

        $water_source_name = '';
        if (!empty($aRow['water_source_name'])) {
            $water_source_name = '<a href="?a=show-water-source-sales&id=' . $aRow['id_water_source'] . '">' . $aRow['water_source_name'] . '</a>';
        }

        $row[2] = $water_source_name;

        $output['aaData'][] = $row;
    }
    echo json_encode($output);
} else {
    header("HTTP/1.0 403 Access forbidden!");
}