<?php

function show_water_users() {
    global $dbhandle, $USER;
    $water_users = array();
    $query = "SELECT id_user,idu, "
            . TABLE_PREFIX . "water_users.fname AS user_fname, "
            . TABLE_PREFIX . "water_users.lname AS user_lname, "
            . TABLE_PREFIX . "water_users.pnumber AS user_pnumber, "
            . TABLE_PREFIX . "water_users.date_added, "
            . TABLE_PREFIX . "users.fname AS attendant_fname, "
            . TABLE_PREFIX . "users.lname AS attendant_lname, "
            . "water_sources.water_source_name, "
            . "water_sources.id_water_source "
            . "FROM " . TABLE_PREFIX . "water_users "
            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_users.added_by "
            . "LEFT JOIN " . TABLE_PREFIX . "water_source_caretakers ON uid=added_by "
            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON water_sources.id_water_source=water_source_caretakers.water_source_id";

    if ($USER->can_approve_treasurers_submissions) {
        //This is District Water Officer
        //Query to summarise from water sources
    } elseif ($USER->can_approve_attendants_submissions) {
        //This is the Water Board Treasurer
        //Query to summarise from attendatnts
    } elseif ($USER->can_submit_attendant_daily_sales) {
        //This is the Water User Committee Treasurer        
    } else {
        //This is the attendant
        $query.=" WHERE users.idu=" . $USER->idu . " AND marked_for_delete=0 ";
    }

    $result = $dbhandle->RunQueryForResults($query);

    while ($customer = $result->fetch_assoc()) {
        $water_users[] = $customer;
    }
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">All water users</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">        
                    <a href="?a=add-water-user" class="btn btn-primary pull-right">Add Water User</a>
                </div>               
            </div>
            <?php
            if (empty($water_users)) {
                ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"> 
                        <div class="alert alert-info">
                            No water users have been added yet.
                        </div>
                    </div>
                </div>       
            <?php } else {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <hr class="dashed"/> 
                        <div class="table-responsive">                          
                            <table class="table managed-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Water User</th>
                                        <th>P#</th>
                                        <th>Water Source</th>                                       
                                        <th>Date added</th>
                                        <th>Added by</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php
                                    foreach ($water_users as $customer) {
                                        $water_source_name = '';
                                        if (!empty($customer['water_source_name'])) {
                                            $water_source_name = '<a href="?a=show-water-source-sales&id=' . $customer['id_water_source'] . '">' . $customer['water_source_name'] . '</a>';
                                        }

                                        $link = "";
                                        if ($USER->can_edit_water_users) {
                                            $link.='<a href="?a=edit-water-user&id=' . $customer['id_user'] . '" class="btn btn-info">Edit</a>';
                                        }
                                        if ($USER->can_delete_water_users) {
                                            $link .= ' | <a href="?a=delete-water-user&id=' . $customer['id_user'] . '" class="btn btn-danger delete-link">Delete</a>';
                                        }

                                        echo '<tr>
                    <td>' . $customer['id_user'] . '</td>
                    <td><a href="?a=view-water-user-transactions&id=' . $customer['id_user'] . '">' . $customer['user_fname'] . ' ' . $customer['user_lname'] . '</a></td>
                    <td>' . $customer['user_pnumber'] . '</td>
                        <td>' . $water_source_name . '</td>
                    <td>' . getCurrentDate($customer['date_added'], true, true) . '</td>
                    <td><a href="?a=attendants-sales&id=' . $customer['idu'] . '">' . $customer['attendant_fname'] . ' ' . $customer['attendant_lname'] . '</a></td>                  
                    <td>' . $link . '</td>
                </tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <?php
}

function show_outgoing_sms() {
    global $dbhandle, $USER;
    $messages = array();
    if ($USER->can_view_water_source_savings) {
        $query = "SELECT * FROM sms_messages "
                . "LEFT JOIN users ON users.idu=sms_messages.sent_by "
                // . "LEFT JOIN water_users ON water_users.id_user IN (water_users)
                . "";
    } else {
        $query = "SELECT * FROM sms_messages "
                . "LEFT JOIN users ON users.idu=sms_messages.sent_by "
                // . "LEFT JOIN water_users ON water_users.id_user IN (water_users)
                . " WHERE sms_messages.sent_by=" . $USER->idu;
    }
    //echo $query;

    $result = $dbhandle->RunQueryForResults($query);

    while ($message = $result->fetch_assoc()) {
        $system_users = array();

        if (!empty($message['system_users'])) {
            $query2 = "SELECT fname,lname FROM users WHERE idu IN(" . $message['system_users'] . ")";
            $result2 = $dbhandle->RunQueryForResults($query2);
            while ($users = $result2->fetch_assoc()) {
                $system_users[] = $users['fname'] . " " . $users['lname'];
            }
        }
        $message['system_users'] = implode(',', $system_users);

        $water_users = array();
        if (!empty($message['water_users'])) {
            $query3 = "SELECT fname,lname FROM water_users WHERE id_user IN(" . $message['water_users'] . ")";
            $result3 = $dbhandle->RunQueryForResults($query3);
            while ($users = $result3->fetch_assoc()) {
                $water_users[] = $users['fname'] . " " . $users['lname'];
            }
        }
        $message['water_users'] = implode(',', $water_users);

        $messages[] = $message;
    }

    //var_dump($messages);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Outgoing SMS</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">        
                    <a href="?a=send-sms" class="btn btn-primary pull-right">Send SMS</a>
                </div>               
            </div>
            <?php
            if (empty($messages)) {
                ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"> 
                        <div class="alert alert-info">
                            No messages
                        </div>
                    </div>
                </div>       
            <?php } else {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <hr class="dashed"/> 
                        <div class="table-responsive">                          
                            <table class="table managed-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Recipient(s)</th>
                                        <th>Message</th>                                                                             
                                        <th>Date Sent</th>
                                        <th>Sent by</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php
                                    foreach ($messages as $message) {
                                        $link = "";
                                        if ($message['sent'] == 0) {
                                            $link = '<a href="?a=resend-sms&id=' . $message['id_sms'] . '" class="btn btn-info">Resend</a>';
                                        } else {
                                            $link = '<span class="label label-success">SMS Sent</span>';
                                        }
                                        $sent_by = "";
                                        if (empty($message['fname'])) {
                                            $sent_by = "System Generated";
                                        } else {
                                            $sent_by = $message['fname'] . ' ' . $message['lname'];
                                        }
                                        echo '<tr>
                    <td>' . $message['id_sms'] . '</td>
                    <td>' . $message['system_users'] . ',' . $message['water_users'] . '</td>
                    <td>' . $message['message_content'] . '</td>
                    <td>' . getCurrentDate($message['date_sent'], true, true) . '</td>
                    <td>' . $sent_by . '</td>                  
                    <td>' . $link . '</td>
                </tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

function show_outgoing_push_messages() {
    global $dbhandle, $USER;
    $messages = array();
    if ($USER->can_view_water_source_savings) {
        $query = "SELECT * FROM push_messages "
                . "LEFT JOIN users ON users.idu=push_messages.sent_by "
                // . "LEFT JOIN water_users ON water_users.id_user IN (water_users)
                . "";
    } else {
        $query = "SELECT * FROM push_messages "
                . "LEFT JOIN users ON users.idu=push_messages.sent_by "
                // . "LEFT JOIN water_users ON water_users.id_user IN (water_users)
                . " WHERE sms_messages.sent_by=" . $USER->idu;
    }
    //echo $query;

    $result = $dbhandle->RunQueryForResults($query);

    while ($message = $result->fetch_assoc()) {
        $system_users = array();

        if (!empty($message['system_users'])) {
            $query2 = "SELECT fname,lname FROM users WHERE idu IN(" . $message['system_users'] . ")";
            $result2 = $dbhandle->RunQueryForResults($query2);
            while ($users = $result2->fetch_assoc()) {
                $system_users[] = $users['fname'] . " " . $users['lname'];
            }
        }
        $message['system_users'] = implode(',', $system_users);

        $water_users = array();
        if (!empty($message['water_users'])) {
            $query3 = "SELECT fname,lname FROM water_users WHERE id_user IN(" . $message['water_users'] . ")";
            $result3 = $dbhandle->RunQueryForResults($query3);
            while ($users = $result3->fetch_assoc()) {
                $water_users[] = $users['fname'] . " " . $users['lname'];
            }
        }
        $message['water_users'] = implode(',', $water_users);

        $messages[] = $message;
    }

    //var_dump($messages);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Push Messages</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">        
                    <a href="?a=send-notification" class="btn btn-primary pull-right">Send Push Message</a>
                </div>               
            </div>
            <?php
            if (empty($messages)) {
                ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"> 
                        <div class="alert alert-info">
                            No messages
                        </div>
                    </div>
                </div>       
            <?php } else {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <hr class="dashed"/> 
                        <div class="table-responsive">                          
                            <table class="table managed-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Recipient(s)</th>
                                        <th>Message</th>                                                                             
                                        <th>Date Sent</th>
                                        <th>Sent by</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php
                                    foreach ($messages as $message) {
                                        $link = "";
                                        if ($message['sent'] == 0) {
                                            $link = '<a href="?a=resend-push-notification&id=' . $message['id_sms'] . '" class="btn btn-info">Resend</a>';
                                        } else {
                                            $link = '<span class="label label-success">SMS Sent</span>';
                                        }
                                        echo '<tr>
                    <td>' . $message['id_sms'] . '</td>
                    <td>' . $message['system_users'] . ',' . $message['water_users'] . '</td>
                    <td>' . $message['message_content'] . '</td>
                    <td>' . getCurrentDate($message['date_sent'], true, true) . '</td>
                    <td>' . $message['fname'] . ' ' . $message['lname'] . '</td>                  
                    <td>' . $link . '</td>
                </tr>';
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <?php
}

function show_water_user_transactions() {
    global $dbhandle, $USER;

    $id_user = getArrayVal($_GET, 'id');
    $query = "SELECT * FROM " . TABLE_PREFIX . "water_users WHERE id_user=$id_user";
    $result = $dbhandle->RunQueryForResults($query);

    if (!empty($result)) {
        $customer = $result->fetch_assoc();
    }
//can_view_water_source_savings,can_submit_attendant_daily_sales
    //var_dump($customer, $USER);

    if ($USER->idu == $customer['added_by'] || $USER->can_view_water_source_savings) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="row-title">User Transactions</h3>
            </div>
            <div class="panel-body">
                <?php if (!isset($customer['id_user'])) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                Water user does not exist
                            </div>
                        </div>
                    </div>                
                <?php } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12">                
                            <h3>Name: <?php echo ucwords($customer['fname'] . " " . $customer['lname']); ?></h3> 
                            <h3>Phone # <?php echo $customer['pnumber']; ?></h3>
                            <hr class="stripe"/>
                        </div>
                    </div>
                    <?php
                    $transactions = array();
                    $query = "SELECT id_sale,idu,sale_date,sale_ugx, "
                            . TABLE_PREFIX . "water_sources.id_water_source, "
                            . TABLE_PREFIX . "water_sources.water_source_id, "
                            . TABLE_PREFIX . "water_sources.water_source_name, "
                            . TABLE_PREFIX . "users.fname AS attendant_fname, "
                            . TABLE_PREFIX . "users.lname AS attendant_lname "
                            . "FROM " . TABLE_PREFIX . "sales "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "sales.water_source_id "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by "
                            . "WHERE sold_to=$id_user ORDER BY id_sale DESC";
                    //var_dump($query);

                    $result = $dbhandle->RunQueryForResults($query);
                    while ($transaction = $result->fetch_assoc()) {
                        $transactions[] = $transaction;
                    }
                    if (empty($transactions)) {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <?php echo ucwords($customer['fname']); ?> does not have any transactions yet.
                                </div>
                            </div>
                        </div>                
                        <?php
                    } else {
                        ?>
                        <div class="row">
                            <div class="col-md-12">                            
                                <div class="table-responsive">
                                    <table class="table managed-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Water Source</th>
                                                <th>Attendant</th>
                                                <th>Sale</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead> 
                                        <tbody> 
                                            <?php
                                            foreach ($transactions as $transaction) {

                                                $water_source_name = '';
                                                if (!empty($transaction['water_source_name'])) {
                                                    $water_source_name = '<a href="?a=show-water-source-sales&id=' . $transaction['id_water_source'] . '">' . $transaction['water_source_name'] . '</a>';
                                                }

                                                $link = "";
                                                if ($USER->can_edit_sales) {
                                                    $link.='<a href="?a=edit-water-user&id=' . $customer['id_user'] . '" class="btn btn-info">Edit</a>';
                                                }
                                                if ($USER->can_delete_sales) {
                                                    $link .= ' | <a href="?a=delete-water-user&id=' . $customer['id_user'] . '" class="btn btn-danger delete-link">Delete</a>';
                                                }

                                                echo '<tr>
                            <td>' . $transaction['id_sale'] . '</td>
                            <td>' . $water_source_name . '</td>
                            <td><a href="?a=attendants-sales&id=' . $transaction['idu'] . '">' . $transaction['attendant_fname'] . ' ' . $transaction['attendant_lname'] . '</a></td>
                            <td>' . $transaction['sale_ugx'] . '</td>
                            <td>' . getCurrentDate($transaction['sale_date'], true, true) . '</td>
                            <td><a href="?a=edit-sale&id=' . $transaction['id_sale'] . '" class="btn btn-info">Edit</a> | <a href="?a=delete-sale&id=' . $transaction['id_sale'] . '" class="btn btn-danger delete-link">Delete</a></td>
                        </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        the_access_denied();
    }
}

function show_sales() {
    global $dbhandle;
    $sales = array();
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">All water sales</h3>
        </div>
        <div class="panel-body"> 
            <div class="row">
                <div class="col-md-12">        
                    <a href="?a=add-sale" class="btn btn-primary pull-right">Add Sale</a>
                </div>               
            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr class="dashed"/>
                    <div class="table-responsive">                        
                        <table class="ajax-managed-table">
                            <thead>
                                <tr>
                                    <th style="width: 1.28571428571429%;">#</th>
                                    <th style="width: 14.28571428571429%;">Water User</th>
                                    <th style="width: 14.28571428571429%;">Water Source</th>
                                    <th style="width: 14.28571428571429%;">Sale(UGX)</th>
                                    <th style="width: 14.28571428571429%;">Sold By</th>                       
                                    <th style="width: 14.28571428571429%;">Date</th>
                                    <th style="width: 14.28571428571429%;"></th>
                                </tr>
                            </thead>
                            <tbody> 

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>           
        </div>
    </div>
    <?php
}

function show_attendant_sales() {
    global $dbhandle, $USER;
    $attendant_id = getArrayVal($_GET, 'id');

    $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE idu=$attendant_id";
    $result = $dbhandle->RunQueryForResults($query);

    if (!empty($result)) {
        $user = $result->fetch_assoc();
    }
    if ($USER->idu == $user['idu'] || $USER->can_view_water_source_savings) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="row-title">Attendant sales</h3>
            </div>
            <div class="panel-body"> 
                <?php
                if (!isset($user['idu'])) {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                Attendant does not exist
                            </div>
                        </div>
                    </div> 
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12">                
                            <h3>Name : <?php echo ucwords($user['fname'] . " " . $user['lname']); ?></h3>               
                        </div>
                    </div>
                    <?php
                    $sales = array();
                    $query = "SELECT "
                            . TABLE_PREFIX . "sales.id_sale,"
                            . TABLE_PREFIX . "sales.sale_date,"
                            . TABLE_PREFIX . "sale_ugx,"
                            . TABLE_PREFIX . "water_sources.id_water_source,"
                            . TABLE_PREFIX . "water_sources.water_source_id,"
                            . TABLE_PREFIX . "water_sources.water_source_name,"
                            . TABLE_PREFIX . "water_users.fname AS user_fname,"
                            . TABLE_PREFIX . "water_users.lname AS user_lname,"
                            . "id_user,"
                            . TABLE_PREFIX . "users.idu,"
                            . TABLE_PREFIX . "users.fname AS attendant_fname,"
                            . TABLE_PREFIX . "users.lname AS attendant_lname"
                            . " FROM " . TABLE_PREFIX . "sales "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by "
                            . "WHERE sold_by=$attendant_id";

                    //var_dump($query);

                    $result = $dbhandle->RunQueryForResults($query);

                    while ($sale = $result->fetch_assoc()) {
                        if (!empty($sale['id_sale'])) {
                            $sales[] = $sale;
                        }
                    }


                    if (empty($sales)) {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <?php echo ucwords($user['fname']); ?> has not made any sales yet
                                </div>
                            </div>
                        </div> 
                        <?php
                    } else {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <hr class="dashed"/>
                                <div class="table-responsive">
                                    <table class="table managed-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Water User</th>
                                                <th>Water Source</th>
                                                <th>Sale(UGX)</th>                                                          
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>  
                                            <?php
                                            foreach ($sales as $sale) {

                                                $c_name = trim($sale['user_fname'] . ' ' . $sale['user_lname']);
                                                if (empty($c_name)) {
                                                    $c_name = "Daily Sale";
                                                } else {
                                                    $c_name = '<a href="?a=view-water-user-transactions&id=' . $sale['id_user'] . '">' . $c_name . '</a>';
                                                }

                                                $water_source_name = '';
                                                if (!empty($sale['water_source_name'])) {
                                                    $water_source_name = '<a href="?a=show-water-source-sales&id=' . $sale['id_water_source'] . '">' . $sale['water_source_name'] . '</a>';
                                                }

                                                $link = "";
                                                if ($USER->can_edit_sales) {
                                                    $link.="<a href=\"?a=edit-sale&id=" . $sale["id_sale"] . "\" class=\"btn btn-info\" >Edit</a> ";
                                                }
                                                if ($USER->can_delete_sales) {
                                                    $link .= "| <a href=\"?a=delete-sale&id=" . $sale["id_sale"] . "\" class=\"btn btn-danger delete-link\">Delete</a>";
                                                }
                                                //<a href="?a=edit-sale&id=' . $sale['id_sale'] . '">Edit</a> | <a href="?a=delete-sale&id=' . $sale['id_sale'] . '" class="delete-link">Delete</a>

                                                echo '<tr>
                    <td>' . $sale['id_sale'] . '</td>
                    <td>' . $c_name . '</td>
                    <td>' . $water_source_name . '</td>
                    <td>' . number_format($sale['sale_ugx'], 2, '.', ',') . '</td>                    
                    <td>' . getCurrentDate($sale['sale_date'], true, true) . '</td>
                    <td>' . $link . '</td>
                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <?php
    } else {
        the_access_denied();
    }
}

function show_user_statement() {
    global $dbhandle, $USER;

    $inflow = 0;
    $outflow = 0;
    $account_balance = 0;
    $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE idu=" . $USER->idu;
    $result = $dbhandle->RunQueryForResults($query);

    if (!empty($result)) {
        $user = $result->fetch_assoc();
    }


    $transactions = array();
    $sales = array();
    $query = "SELECT *,CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS savings FROM sales WHERE sold_by=" . $USER->idu . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
    $squery = "SELECT date_reviewed, SUM(savings) AS savings FROM ($query) AS derived GROUP BY DATE(sale_date)";
    //echo $squery;
    $result = $dbhandle->RunQueryForResults($squery);
    while ($sale = $result->fetch_assoc()) {
        $sales[] = array(
            'type' => 'Deposit',
            'beneficiary' => $USER->fname . ' ' . $USER->lname,
            'amount' => $sale['savings'],
            'date' => $sale['date_reviewed'],
        );
    }

    $expenditures = array();
    $query = "SELECT * FROM expenditures WHERE logged_by=" . $USER->idu . "";

    $result = $dbhandle->RunQueryForResults($query);
    while ($expenditure = $result->fetch_assoc()) {
        $expenditures[] = array(
            'type' => 'Expenditure',
            'beneficiary' => $expenditure['benefactor'],
            'amount' => $expenditure['expenditure_cost'],
            'date' => $expenditure['expenditure_date'],
        );
    }
    $transactions = array_merge($expenditures, $sales);
    $transactions = sortArray($transactions, "date");

    $query = "SELECT CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100 ELSE sale_ugx END AS inflow FROM sales WHERE sold_by=" . $USER->idu . " AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";
    $squery = "SELECT SUM(inflow) AS inflow FROM ($query) AS derived";
    //echo $squery;
    $result = $dbhandle->RunQueryForResults($squery);
    while ($sale = $result->fetch_assoc()) {
        $inflow = $sale['inflow'];
    }

    $query = "SELECT SUM(expenditure_cost) AS outflow FROM expenditures WHERE logged_by=" . $USER->idu . "";

    $result = $dbhandle->RunQueryForResults($query);
    while ($expenditure = $result->fetch_assoc()) {
        $outflow = $expenditure['outflow'];
    }
    $account_balance = $inflow - $outflow;

    if (isset($user['idu'])) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="row-title">User Statement</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">               
                            <div class="col-md-12">
                                <h5>Name: <?php echo ucwords($user['fname'] . " " . $user['lname']); ?></h5>
                                <!--h5>Role: <?php //echo getRoleName($user['group_id'])                         ?></h5-->
                                <h5>Username: <?php echo $user['username']; ?></h5>
                                <h5>Email: <?php echo $user['email']; ?> | Phone # <?php echo $user['pnumber']; ?></h5>
                                <h5>Account Balance: <?php echo number_format($account_balance, 2, '.', ','); ?></h5>
                            </div>
                        </div>
                        <div class="row">  
                            <hr class="stripe"/>
                            <div class="col-md-12">                               
                                <div class="table-responsive">
                                    <table class="table managed-table">
                                        <thead>
                                            <tr>                                                                             
                                                <th style="width: 24.75%;">Transaction</th>
                                                <th style="width: 24.75%;">Beneficiary</th>
                                                <th style="width: 24.75%;">Cost</th>                       
                                                <th style="width: 24.75%;">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php
                                            foreach ($transactions as $transaction) {
                                                echo '<tr>                                                                             
                                                <td>' . $transaction['type'] . '</td>
                                                <td>' . $transaction['beneficiary'] . '</td>
                                                <td>' . number_format($transaction['amount'], 2, '.', ',') . '</td>                       
                                                <td>' . getCurrentDate($transaction['date'], true, true) . '</td>
                                            </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                    
                        <?php
                    } else {
                        ?>
                        <div class="well">               
                            <div class="col-md-4 col-md-offset-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            That attendant does not exist
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }

                function show_savings() {

                    global $dbhandle, $USER;

                    $water_source_data = array();

                    $src_id = getArrayVal($_GET, 'id');

                    $query = "SELECT * FROM water_sources WHERE id_water_source=$src_id ORDER BY id_water_source ASC LIMIT 1";

                    $result = $dbhandle->RunQueryForResults($query);
                    while ($sale = $result->fetch_assoc()) {
                        $water_source_data = $sale;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">All Savings Collected</h3>
                        </div>
                        <div class="panel-body"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h5>
                                    <h5>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h5>
                                </div>
                            </div>

                            <div class="row"> 

                                <div class="col-md-4">
                                    <div class="dashboard-stat purple">
                                        <div class="visual">
                                            <i class="glyphicon glyphicon-user"></i>
                                        </div>
                                        <div class="details">
                                            <div class="number">
                                                <?php echo number_format(calculateTotalWaterUsersFromWaterSource($src_id), 0, '.', ','); ?>
                                            </div>
                                            <div class="desc">
                                                Monthly Billed Water Users
                                            </div>
                                        </div>
                                        <?php if ($USER->can_view_water_users) { ?>
                                            <a class="more" href="?a=water-users">
                                                View more <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dashboard-stat blue">
                                        <div class="visual">
                                            <i class="glyphicon glyphicon-transfer"></i>
                                        </div>
                                        <div class="details">
                                            <div class="number">
                                                <?php echo number_format(calculateTotalWaterSourceTransactions($src_id), 0, '.', ','); ?>
                                            </div>
                                            <div class="desc">
                                                Verified transactions
                                            </div>
                                        </div>
                                        <?php if ($USER->can_view_sales) { ?>
                                            <a class="more" href="?a=sales">
                                                View more <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="dashboard-stat yellow">
                                        <div class="visual">
                                            <i class="glyphicon glyphicon-usd"></i>
                                        </div>
                                        <div class="details">
                                            <div class="number">
                                                <?php echo number_format(calculateTotalSavingsFromWaterSource($src_id), 0, '.', ','); ?>
                                            </div>
                                            <div class="desc">
                                                Total Savings
                                            </div>
                                        </div>
                                        <?php if ($USER->can_view_sales) { ?>
                                            <a class="more" href="?a=sales">
                                                View more <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">                       
                                <div class="col-md-12">

                                </div>
                            </div>

                        </div>
                    </div>
                    <?php
                }

                function show_water_sources() {
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">All water sources</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            $water_sources = array();
                            global $dbhandle;
                            $p = $dbhandle->Fetch("water_sources");
                            if (is_array($p) && !empty($p) && !isset($p[0])) {
                                $water_sources[] = $p;
                            } else {
                                $water_sources = $p;
                            }

                            if (empty($water_sources)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4"> 
                                        <div class="alert alert-info">
                                            No water sources have been added yet.
                                        </div>
                                    </div>
                                </div>       
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">        
                                        <a href="?a=add-water-source" class="btn btn-primary pull-right">Add Water Source</a>
                                    </div>               
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <hr class="stripe"/>
                                        <div class="table-responsive">
                                            <table class="table managed-table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 1%;">#</th>                               
                                                        <th style="width: 15%;">Source Name</th>
                                                        <th style="width: 10%;">Source ID</th>
                                                        <th style="width: 21%;">Source Location</th>                       
                                                        <th style="width: 53%;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                    <?php
                                                    foreach ($water_sources as $water_source) {

                                                        $water_source_name = '';
                                                        if (!empty($transaction['water_source_name'])) {
                                                            $water_source_name = '<a href="?a=show-water-source-sales&id=' . $transaction['id_water_source'] . '">' . $transaction['water_source_name'] . '</a>';
                                                        }
                                                        echo "<tr>
                        <td>" . $water_source['id_water_source'] . "</td>
                         <td>" . $water_source['water_source_name'] . "</td>
                        <td>" . $water_source['water_source_id'] . "</td>                       
                        <td>" . $water_source['water_source_location'] . "</td>                       
                        <td><a href=\"?a=show-water-source-sales&id=" . $water_source['id_water_source'] . "\" class=\"btn btn-default\">Sales</a> | "
                                                        . "<a href=\"?a=show-water-source-users&id=" . $water_source['id_water_source'] . "\" class=\"btn btn-default\">Water Users</a> | "
                                                        . "<a href=\"?a=show-water-source-attendants&id=" . $water_source['id_water_source'] . "\" class=\"btn btn-default\">Care Takers</a> | "
                                                        . "<a href=\"?a=show-water-source-treasurers&id=" . $water_source['id_water_source'] . "\" class=\"btn btn-default\">Treasurers</a> | "
                                                        . "<a href=\"?a=edit-water-source&id=" . $water_source['id_water_source'] . "\" class=\"btn btn-info\">Edit</a> | "
                                                        . "<a href=\"?a=delete-water-source&id=" . $water_source['id_water_source'] . "\" class=\"btn btn-danger delete-link\">Delete</a></td>
                    </tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }

                function show_personal_sales() {
                    global $dbhandle, $USER;
                    $id_water_source = getArrayVal($_GET, 'id');

                    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
                    $result = $dbhandle->RunQueryForResults($query);

                    while ($row = $result->fetch_assoc()) {
                        $water_source_data = $row;
                    }

                    $sales = array();
                    $query = "SELECT "
                            . TABLE_PREFIX . "sales.id_sale, "
                            . TABLE_PREFIX . "sales.sale_date, "
                            . TABLE_PREFIX . "sales.sale_ugx, "
                            . TABLE_PREFIX . "water_sources.water_source_id, "
                            . TABLE_PREFIX . "water_users.fname AS user_fname, "
                            . TABLE_PREFIX . "water_users.lname AS user_lname, "
                            . TABLE_PREFIX . "users.idu, "
                            . TABLE_PREFIX . "water_users.id_user, "
                            . TABLE_PREFIX . "users.fname AS attendant_fname, "
                            . TABLE_PREFIX . "users.lname AS attendant_lname "
                            . " FROM " . TABLE_PREFIX . "sales "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by "
                            . "WHERE " . TABLE_PREFIX . "water_sources.id_water_source=$id_water_source  AND users.idu=" . $USER->idu;


                    $result = $dbhandle->RunQueryForResults($query);

                    while ($gross_sale = $result->fetch_assoc()) {
                        if (!empty($gross_sale['id_sale'])) {
                            $sales[] = $gross_sale;
                        }
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Water source sales</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!isset($water_source_data['id_water_source'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            Water source does not exist
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <h5>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h5>
                                        <h5>ID: <?php echo ucwords($water_source_data['water_source_id']); ?></h5>
                                        <h5>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h5>
                                        <hr class="stripe"/>
                                    </div>
                                </div>
                                <?php
                                if (empty($sales)) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                No sales have been done so far
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">                           
                                            <div class="table-responsive">
                                                <table class="table managed-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Water User</th>
                                                            <!--th>Water Source</th-->
                                                            <th>Sale(UGX)</th>
                                                            <th>Sold By</th>                       
                                                            <th>Date</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>   
                                                        <?php
                                                        foreach ($sales as $gross_sale) {

                                                            $c_name = trim($gross_sale['user_fname'] . ' ' . $gross_sale['user_lname']);
                                                            if (empty($c_name)) {
                                                                $c_name = "Daily Sale";
                                                            } else {
                                                                $c_name = '<a href="?a=view-water-user-transactions&id=' . $gross_sale['id_user'] . '">' . $c_name . '</a>';
                                                            }
                                                            $link = "";
                                                            if ($USER->can_edit_sales) {
                                                                $link.="<a href=\"?a=edit-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-info\" >Edit</a> ";
                                                            }
                                                            if ($USER->can_delete_sales) {
                                                                $link.= "| <a href=\"?a=delete-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-danger delete-link\">Delete</a>";
                                                            }

                                                            echo "<tr>
                    <td>" . $gross_sale["id_sale"] . "</td>
                    <td>" . $c_name . "</td>
                    <!--td>" . $gross_sale["water_source_id"] . "</td-->
                    <td>" . number_format($gross_sale['sale_ugx'], 2, '.', ',') . "</td>
                    <td><a href=\"?a=attendants-sales&id=" . $gross_sale["idu"] . "\">" . $gross_sale["attendant_fname"] . " " . $gross_sale["attendant_lname"] . "</a></td>                  
                    <td>" . getCurrentDate($gross_sale["sale_date"], true, true) . "</td>
                    <td>$link</td>
                </tr>";
                                                        }
                                                        ?>             
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }

                function show_water_source_sales() {
                    global $dbhandle, $USER;
                    $id_water_source = getArrayVal($_GET, 'id');

                    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
                    $result = $dbhandle->RunQueryForResults($query);

                    while ($row = $result->fetch_assoc()) {
                        $water_source_data = $row;
                    }

                    $sales = array();
                    $query = "SELECT "
                            . TABLE_PREFIX . "sales.id_sale, "
                            . TABLE_PREFIX . "sales.sale_date, "
                            . TABLE_PREFIX . "sales.sale_ugx, "
                            . TABLE_PREFIX . "water_sources.water_source_id, "
                            . TABLE_PREFIX . "water_users.fname AS user_fname, "
                            . TABLE_PREFIX . "water_users.lname AS user_lname, "
                            . TABLE_PREFIX . "users.idu, "
                            . TABLE_PREFIX . "water_users.id_user, "
                            . TABLE_PREFIX . "users.fname AS attendant_fname, "
                            . TABLE_PREFIX . "users.lname AS attendant_lname "
                            . " FROM " . TABLE_PREFIX . "sales "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by "
                            . "WHERE " . TABLE_PREFIX . "water_sources.id_water_source=$id_water_source ";

                    if ($USER->can_approve_treasurers_submissions) {
                        //This is District Water Officer
                        //Query to summarise from water sources
                    } elseif ($USER->can_approve_attendants_submissions) {
                        //This is the Water Board Treasurer
                        //Query to summarise from attendatnts
                    } elseif ($USER->can_submit_attendant_daily_sales) {
                        //This is the Water User Committee Treasurer        
                    } else {
                        //This is the attendant
                        $query.=" AND users.idu=" . $USER->idu;
                    }

                    $result = $dbhandle->RunQueryForResults($query);

                    while ($gross_sale = $result->fetch_assoc()) {
                        if (!empty($gross_sale['id_sale'])) {
                            $sales[] = $gross_sale;
                        }
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Water source sales</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!isset($water_source_data['id_water_source'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            Water source does not exist
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <h5>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h5>
                                        <h5>ID: <?php echo ucwords($water_source_data['water_source_id']); ?></h5>
                                        <h5>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h5>
                                        <hr class="stripe"/>
                                    </div>
                                </div>
                                <?php
                                if (empty($sales)) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                No sales have been done so far
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">                           
                                            <div class="table-responsive">
                                                <table class="table managed-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Water User</th>
                                                            <!--th>Water Source</th-->
                                                            <th>Sale(UGX)</th>
                                                            <th>Sold By</th>                       
                                                            <th>Date</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>   
                                                        <?php
                                                        foreach ($sales as $gross_sale) {

                                                            $c_name = trim($gross_sale['user_fname'] . ' ' . $gross_sale['user_lname']);
                                                            if (empty($c_name)) {
                                                                $c_name = "Daily Sale";
                                                            } else {
                                                                $c_name = '<a href="?a=view-water-user-transactions&id=' . $gross_sale['id_user'] . '">' . $c_name . '</a>';
                                                            }

                                                            $link = "";
                                                            if ($USER->can_edit_sales) {
                                                                $link.="<a href=\"?a=edit-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-info\" >Edit</a> ";
                                                            }
                                                            if ($USER->can_delete_sales) {
                                                                $link .= "| <a href=\"?a=delete-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-danger delete-link\">Delete</a>";
                                                            }

                                                            //<a href=\"?a=edit-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-info\" >Edit</a> | <a href=\"?a=delete-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-danger delete-link\">Delete</a>


                                                            echo "<tr>
                    <td>" . $gross_sale["id_sale"] . "</td>
                    <td>" . $c_name . "</td>
                    <!--td>" . $gross_sale["water_source_id"] . "</td-->
                    <td>" . number_format($gross_sale['sale_ugx'], 2, '.', ',') . "</td>
                    <td><a href=\"?a=attendants-sales&id=" . $gross_sale["idu"] . "\">" . $gross_sale["attendant_fname"] . " " . $gross_sale["attendant_lname"] . "</a></td>                  
                    <td>" . getCurrentDate($gross_sale["sale_date"], true, true) . "</td>
                    <td>$link</td>
                </tr>";
                                                        }
                                                        ?>             
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }

                function show_water_source_caretakers() {
                    $attendants = array();
                    global $dbhandle;
                    $water_source = array();


                    $id_water_source = getArrayVal($_GET, 'id');
                    $query = "SELECT idu,fname,lname,username,email,pnumber,last_login FROM " . TABLE_PREFIX . "water_source_caretakers "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "water_source_caretakers.uid=" . TABLE_PREFIX . "users.idu"
                            . " WHERE water_source_id=$id_water_source";
// var_dump($query);

                    $result = $dbhandle->RunQueryForResults($query);
                    if (isset($result->num_rows) && $result->num_rows > 0) {
                        while ($attendant = $result->fetch_assoc()) {
                            if (!empty($attendant['idu'])) {
                                $attendants[] = $attendant;
                            }
                        }
                    }
                    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
                    $result = $dbhandle->RunQueryForResults($query);

                    if (!empty($result)) {
                        $water_source_data = $result->fetch_assoc();
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Water source attendants</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!isset($water_source_data['id_water_source'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            Water source does not exist
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h5>
                                        <h5>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h5>
                                    </div>
                                </div>
                                <?php
                                if (empty($attendants)) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                No attendants
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr class="dashed"/>
                                            <div class="table-responsive">
                                                <table class="table managed-table">              
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Username</th>
                                                            <th>Email</th>
                                                            <th>P. Number</th>                            
                                                            <th>Last Activity Time</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                    
                                                        <?php
                                                        foreach ($attendants as $user) {
                                                            $last_login = $user['last_login'] === '0000-00-00 00:00:00' ? 'Never' : getCurrentDate($user['last_login'], true, true);
                                                            echo "<tr>
                        <td>" . $user['idu'] . "</td>
                        <td>" . $user['fname'] . "</td>
                        <td>" . $user['lname'] . "</td>
                        <td>" . $user['username'] . "</td>
                        <td>" . $user['email'] . "</td>
                        <td>" . $user['pnumber'] . "</td>                        
                        <td>" . $last_login . "</td>
                        <td><a href=\"?a=attendants-sales&id=" . $user['idu'] . "\" class=\"btn btn-default\">Sales</a> | <a href=\"?a=show-water-source-users&id=" . $user['idu'] . "\" class=\"btn btn-default\">Water Users</a> | <a href=\"?a=edit-user&id=" . $user['idu'] . "\" class=\"btn btn-info\">Edit</a> | <a href=\"?a=delete-user&id=" . $user['idu'] . "\" class=\"btn btn-danger delete-link\">Delete</a></td>
                    </tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>  
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }

                function show_water_source_treasurers() {
                    $attendants = array();
                    global $dbhandle;
                    $water_source = array();


                    $id_water_source = getArrayVal($_GET, 'id');
                    $query = "SELECT idu,fname,lname,username,email,pnumber,last_login FROM " . TABLE_PREFIX . "water_source_treasurers "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "water_source_treasurers.uid=" . TABLE_PREFIX . "users.idu"
                            . " WHERE water_source_id=$id_water_source";
// var_dump($query);

                    $result = $dbhandle->RunQueryForResults($query);
                    if (isset($result->num_rows) && $result->num_rows > 0) {
                        while ($attendant = $result->fetch_assoc()) {
                            if (!empty($attendant['idu'])) {
                                $attendants[] = $attendant;
                            }
                        }
                    }
                    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
                    $result = $dbhandle->RunQueryForResults($query);

                    if (!empty($result)) {
                        $water_source_data = $result->fetch_assoc();
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Water source Treasurers</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!isset($water_source_data['id_water_source'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            Water source does not exist
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h5>
                                        <h5>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h5>
                                    </div>
                                </div>
                                <?php
                                if (empty($attendants)) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                No attendants
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr class="dashed"/>
                                            <div class="table-responsive">
                                                <table class="table managed-table">              
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Username</th>
                                                            <th>Email</th>
                                                            <th>P. Number</th>                            
                                                            <th>Last Activity Time</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                    
                                                        <?php
                                                        foreach ($attendants as $user) {
                                                            $last_login = $user['last_login'] === '0000-00-00 00:00:00' ? 'Never' : getCurrentDate($user['last_login'], true, true);
                                                            echo "<tr>
                        <td>" . $user['idu'] . "</td>
                        <td>" . $user['fname'] . "</td>
                        <td>" . $user['lname'] . "</td>
                        <td>" . $user['username'] . "</td>
                        <td>" . $user['email'] . "</td>
                        <td>" . $user['pnumber'] . "</td>                        
                        <td>" . $last_login . "</td>
                        <td><a href=\"?a=treasurer-submissions&id=" . $user['idu'] . "\" class=\"btn btn-default\">Submissions/ Approvals</a> </td>
                    </tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>  
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }

                function show_water_source_users() {
                    global $dbhandle;
                    $id_water_source = getArrayVal($_GET, 'id');

                    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
                    $result = $dbhandle->RunQueryForResults($query);

                    while ($row = $result->fetch_assoc()) {
                        $water_source_data = $row;
                    }

                    $water_source_users = array();
                    $query = "SELECT "
                            . TABLE_PREFIX . "sales.id_sale, "
                            . TABLE_PREFIX . "sales.sale_date, "
                            . TABLE_PREFIX . "sales.sale_ugx, "
                            . TABLE_PREFIX . "water_sources.water_source_id, "
                            . TABLE_PREFIX . "water_users.fname AS user_fname, "
                            . TABLE_PREFIX . "water_users.lname AS user_lname, "
                            . TABLE_PREFIX . "users.idu, "
                            . TABLE_PREFIX . "water_users.id_user, "
                            . TABLE_PREFIX . "users.fname AS attendant_fname, "
                            . TABLE_PREFIX . "users.lname AS attendant_lname "
                            . " FROM " . TABLE_PREFIX . "sales "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                            . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
                            . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.sold_by "
                            . "WHERE " . TABLE_PREFIX . "water_sources.id_water_source=$id_water_source ";

                    /*   $query = "SELECT " . TABLE_PREFIX . "users.idu," . TABLE_PREFIX . "users.fname AS w_a_fname," . TABLE_PREFIX . "users.lname AS w_a_lname,"
                      . TABLE_PREFIX . "water_users.id_user AS water_user_id, " . TABLE_PREFIX . "water_users.fname AS w_u_fname, " . TABLE_PREFIX . "water_users.lname AS w_u_lname," . TABLE_PREFIX . "water_users.date_added FROM " . TABLE_PREFIX . "water_source_caretakers "
                      . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                      . "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.added_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                      . "WHERE water_source_id=$id_water_source ";

                      "SELECT " . TABLE_PREFIX . "users.fname AS w_a_fname," . TABLE_PREFIX . "users.lname AS w_a_lname,"
                      . TABLE_PREFIX . "water_users.id_user AS water_user_id, " . TABLE_PREFIX . "water_users.fname AS w_u_fname, " . TABLE_PREFIX . "water_users.lname AS w_u_lname," . TABLE_PREFIX . "water_users.date_added FROM " . TABLE_PREFIX . "water_users LEFT JOIN "
                      . TABLE_PREFIX . "water_source_caretakers ON " . TABLE_PREFIX . "water_users.added_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                      . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_users.added_by "
                      . "WHERE water_source_id=$id_water_source "; */

                    // var_dump($query);

                    $result = $dbhandle->RunQueryForResults($query);

                    while ($water_source_user = $result->fetch_assoc()) {
                        $water_source_users[] = $water_source_user;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Water source users</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (!isset($water_source_data['id_water_source'])) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            Water source does not exist
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } elseif (empty($water_source_users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            No water users have been added so far
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <h5>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h5>
                                        <h5>ID: <?php echo ucwords($water_source_data['water_source_id']); ?></h5>
                                        <h5>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h5>
                                        <hr class="stripe"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table managed-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Water User</th>
                                                        <th>Added By</th>                       
                                                        <th>Date Added</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>   
                                                    <?php
                                                    foreach ($water_source_users as $water_source_user) {

                                                        echo "<tr>
                    <td>" . $water_source_user["water_user_id"] . "</td>
                    <td><a href=\"?a=view-water-user-transactions&id=" . $water_source_user["water_user_id"] . "\">" . $water_source_user["w_u_fname"] . " " . $water_source_user["w_u_lname"] . "</a></td>
                    <td><a href=\"?a=attendants-sales&id=" . $water_source_user["idu"] . "\">" . $water_source_user["w_a_fname"] . " " . $water_source_user["w_a_lname"] . "</a></td>
                    <td>" . $water_source_user["date_added"] . "</td>
                    <td><a href=\"?a=edit-water-user&id=" . $water_source_user["water_user_id"] . "\" class=\"btn btn-info\" >Edit</a> | <a href=\"?a=delete-water-user&id=" . $water_source_user["water_user_id"] . "\" class=\"btn btn-danger delete-link\">Delete</a></td>
                </tr>";
                                                    }
                                                    ?>             
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }

                function show_users() {
                    $users = array();
                    global $dbhandle;

                    $query = "SELECT idu,username,fname,lname,email,pnumber,last_login,group_name,active FROM " . TABLE_PREFIX . "users LEFT JOIN " . TABLE_PREFIX . "user_groups ON group_id=id_group ORDER BY fname ASC";

//var_dump($query);
                    $result = $dbhandle->RunQueryForResults($query);

                    while ($row = $result->fetch_assoc()) {
                        $users[] = $row;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">System Users</h3>
                        </div>
                        <div class="panel-body">
                            <?php if (empty($users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            No added users
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">        
                                        <a href="?a=add-user" class="btn btn-primary pull-right">Add User</a>
                                    </div>               
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <hr class="stripe"/>
                                        <div class="table-responsive">
                                            <table class="table managed-table">
                                                <thead>
                                                    <tr>
                                                        <!--th width="2.5%">#</th-->
                                                        <th width="15.0%">Name</th>                       
                                                        <th width="10.5%">Username</th>
                                                        <th width="10.5%">Email</th>
                                                        <th width="12.5%">P. Number</th>
                                                        <th width="17.5%">User Group</th>
                                                        <th width="12.5%">Last Activity Time</th>
                                                        <th width="21.5%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                    <?php
                                                    foreach ($users as $user) {
                                                        if ($user['active'] == '1') {
                                                            $link = "<a href=\"?a=deactivate-user&id=" . $user['idu'] . "\" class=\"btn btn-success\" title=\"Dectivate\"><i class=\"glyphicon glyphicon-off\"></i></a>";
                                                        } else {
                                                            $link = "<a href=\"?a=activate-user&id=" . $user['idu'] . "\" class=\"btn btn-danger\" title=\"Activate\"><i class=\"glyphicon glyphicon-off\"></i></a>";
                                                        }

                                                        $delete_link = "<a href=\"?a=delete-user&id=" . $user['idu'] . "\" class=\"btn btn-danger delete-link\">Delete</a>";


                                                        $last_login = $user['last_login'] === '0000-00-00 00:00:00' ? 'Never' : getCurrentDate($user['last_login'], true, true);


                                                        echo "<tr>
                        <!--td>" . $user['idu'] . "</td-->
                        <td>" . $user['fname'] . " " . $user['lname'] . "</td>
                        <td>" . $user['username'] . "</td>
                        <td>" . $user['email'] . "</td>
                        <td>" . $user['pnumber'] . "</td>
                        <td>" . $user['group_name'] . "</td>
                        <td>" . $last_login . "</td>
                        <td> $link | <a href=\"?a=edit-user&id=" . $user['idu'] . "\" class=\"btn btn-info\">Edit</a> | $delete_link</td>
                    </tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }

                function show_submissions_for_attendants() {
                    global $USER;
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Savings Collected By Care Takers</h3>
                        </div>
                        <div class="panel-body">           

                            <?php
                            global $dbhandle;
                            $sales = array();


                            /* $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_caretakers "
                              . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                              . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_caretakers.water_source_id "
                              . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                              . "WHERE submitted_to_treasurer=0 AND treasurerer_approval_status<>1 "
                              . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date DESC"; */

                            $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_treasurers "
                                    . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_treasurers.water_source_id "
                                    . "LEFT JOIN " . TABLE_PREFIX . "water_source_caretakers ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                                    . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                                    . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                                    . "WHERE  " . TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1 AND " . TABLE_PREFIX . "water_source_treasurers.uid=" . $USER->idu . " "
                                    . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date ASC";
                            //echo $query;

                            $result = $dbhandle->RunQueryForResults($query);
                            while ($sale = $result->fetch_assoc()) {
                                $sales[] = $sale;
                            }
                            //var_dump($sales);
                            //die();
                            if (empty($sales)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <div class="alert alert-info">
                                            No sales have been done so far
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="dashed"/>
                                        <table class="table managed-table">
                                            <thead>
                                                <tr>
                                                    <th>Attendant</th>
                                                    <th>Water Source</th> 
                                                    <th>Date</th> 
                                                    <th>Transactions</th> 
                                                    <th>Savings (UGX)</th>  
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php
                                                foreach ($sales as $sale) {
                                                    echo '<tr>
                        <td><a href="?a=attendants-sales&id=' . $sale['idu'] . '">' . $sale['fname'] . ' ' . $sale['lname'] . '</a></td>
<td><a href="?a=show-water-source-sales&id=' . $sale['id_water_source'] . '">' . $sale['water_source_name'] . '</a></td>                     
<td>' . getCurrentDate($sale['sale_date'], true, false) . '</td>
                    <td>' . number_format($sale['sale_ugx'], 2, '.', ',') . '</td> 
                    <td>' . number_format($sale['savings'], 2, '.', ',') . '</td>                  
                    <td><a href="?a=submit-attendant-collections&t=' . strtotime($sale['sale_date']) . '&id=' . $sale['id_water_source'] . '&idu=' . $sale['idu'] . '" class="btn btn-success">Submit</a></td>
                </tr>';
                                                }
                                                ?>             
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <?php
                }

                function show_submissions_for_tresurers() {
                    global $USER;
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Savings Collected By Treasurers</h3>
                        </div>
                        <div class="panel-body"> 
                            <!--div class="row">
                                <div class="col-md-12">        
                                    <a href="?a=add-user-group" class="btn btn-primary pull-right">Add User Group</a>
                                </div>
                            </div-->

                            <?php
                            global $dbhandle, $USER;

                            $sales = array();
                            /* $query = "SELECT COUNT(id_sale) AS transactions, SUM(floor((sale_ugx) * (percentage_saved / 100) / 100) * 100) AS inflow,"
                              . TABLE_PREFIX . "sales.id_sale,"
                              . TABLE_PREFIX . "sales.sale_date,"
                              . TABLE_PREFIX . "sales.percentage_saved,"
                              . TABLE_PREFIX . "users.idu,"
                              . TABLE_PREFIX . "users.fname AS attendant_fname,"
                              . TABLE_PREFIX . "users.lname AS attendant_lname,"
                              . TABLE_PREFIX . "water_sources.id_water_source, "
                              . TABLE_PREFIX . "water_sources.water_source_name"
                              . " FROM " . TABLE_PREFIX . "sales "
                              . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "sales.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                              //. "LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sales.sold_to "
                              . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sales.submitted_by WHERE submitted_to_treasurer=1 AND treasurerer_approval_status=0 GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date DESC"
                              . "";
                             * */

                            /* $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_caretakers "
                              . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                              . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_caretakers.water_source_id "
                              . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                              . "WHERE submitted_to_treasurer=0 AND treasurerer_approval_status<>1 "
                              . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date DESC"; */

                            $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date,submitted_by," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_treasurers "
                                    . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_treasurers.water_source_id "
                                    . "LEFT JOIN " . TABLE_PREFIX . "water_source_caretakers ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                                    . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                                    . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                                    . "WHERE  " . TABLE_PREFIX . "sales.submitted_to_treasurer=1 AND " . TABLE_PREFIX . "sales.treasurerer_approval_status<>1 AND " . TABLE_PREFIX . "water_source_treasurers.uid=" . $USER->idu . " "
                                    . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date ASC";
                            //echo $query;


                            $result = $dbhandle->RunQueryForResults($query);
                            while ($sale = $result->fetch_assoc()) {
                                $sales[] = $sale;
                            }
                            //var_dump($sales);

                            if (empty($sales)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <div class="alert alert-info">
                                            No sales have been done so far
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="dashed"/>
                                        <table class="table managed-table">
                                            <thead>
                                                <tr>
                                                    <th>Treasurer</th>
                                                    <th>Water Source</th> 
                                                    <th>Date</th> 
                                                    <th>Transactions</th> 
                                                    <th>Savings (UGX)</th>  
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php
                                                foreach ($sales as $sale) {

                                                    $link = "";
                                                    if ($USER->can_approve_attendants_submissions) {
                                                        $link.='<a href="?a=approve-attendant-collections&t=' . strtotime($sale['sale_date']) . '&id=' . $sale['id_water_source'] . '&idu=' . $sale['submitted_by'] . '" class="btn btn-success">Approve</a> ';
                                                    }
                                                    if ($USER->can_cancel_attendants_submissions) {
                                                        $link.='| <a href="?a=cancel-attendant-collections&t=' . strtotime($sale['sale_date']) . '&id=' . $sale['id_water_source'] . '&idu=' . $sale['submitted_by'] . '" class="btn btn-danger">Cancel</a>';
                                                    }
                                                    echo '<tr>
                        <td><a href="?a=attendants-sales&id=' . $sale['idu'] . '">' . $sale['fname'] . ' ' . $sale['lname'] . '</a></td>
<td><a href="?a=show-water-source-sales&id=' . $sale['id_water_source'] . '">' . $sale['water_source_name'] . '</a></td>                     
<td>' . getCurrentDate($sale['sale_date'], true, true) . '</td>
                    <td>' . number_format($sale['sale_ugx'], 2, '.', ',') . '</td> 
                    <td>' . number_format($sale['savings'], 2, '.', ',') . '</td>                  
                    <td>' . $link . '</td>
                </tr>';
                                                }
                                                ?>             
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <?php
                }

                function show_submissions() {
                    global $USER;
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Treasurer's submissions</h3>
                        </div>
                        <div class="panel-body">           

                            <?php
                            global $dbhandle;
                            $sales = array();


                            /* $query = "SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_caretakers "
                              . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                              . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_caretakers.water_source_id "
                              . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                              . "WHERE submitted_to_treasurer=0 AND treasurerer_approval_status<>1 "
                              . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date DESC"; */

                            $query = "SELECT submitted_to_treasurer,treasurerer_approval_status,reviewed_by,idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . TABLE_PREFIX . "water_source_treasurers "
                                    . "LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_sources.id_water_source=" . TABLE_PREFIX . "water_source_treasurers.water_source_id "
                                    . "LEFT JOIN " . TABLE_PREFIX . "water_source_caretakers ON " . TABLE_PREFIX . "water_source_caretakers.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source "
                                    . "LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "water_source_caretakers.uid "
                                    . "LEFT JOIN " . TABLE_PREFIX . "sales ON " . TABLE_PREFIX . "sales.sold_by=" . TABLE_PREFIX . "water_source_caretakers.uid "
                                    . "WHERE  " . TABLE_PREFIX . "sales.submitted_to_treasurer=1 AND (" . TABLE_PREFIX . "sales.submitted_by=" . $USER->idu . " OR " . TABLE_PREFIX . "sales.reviewed_by=" . $USER->idu . ")"
                                    . "GROUP BY Date(sale_date)," . TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date ASC";
                            //echo $query;

                            $result = $dbhandle->RunQueryForResults($query);
                            while ($sale = $result->fetch_assoc()) {
                                $sales[] = $sale;
                            }


                            $query = "SELECT * FROM " . TABLE_PREFIX . "users WHERE idu=" . getArrayVal($_GET, 'id');
                            $result = $dbhandle->RunQueryForResults($query);

                            if (!empty($result)) {
                                $user = $result->fetch_assoc();
                            }

                            if (isset($user['idu'])) {
                                ?>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>Name: <?php echo ucwords($user['fname'] . " " . $user['lname']); ?></h5>
                                            <h5>Username: <?php echo $user['username']; ?></h5>
                                            <h5>Email: <?php echo $user['email']; ?> | Phone # <?php echo $user['pnumber']; ?></h5>                    
                                        </div>
                                    </div>
                                </div><?php
                                if (empty($sales)) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12"> 
                                            <div class="alert alert-info">
                                                No sales have been done so far
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    ?>
                                    <div class="row">

                                        <div class = "col-md-12">
                                            <hr class = "dashed"/>
                                            <table class = "table managed-table">
                                                <thead>
                                                    <tr>
                                                        <th>Water Source</th>
                                                        <th>Activity</th>
                                                        <th>Date</th>
                                                        <th>Transactions</th>
                                                        <th>Savings (UGX)</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($sales as $sale) {
                                                        if ($USER->idu == $sale['reviewed_by']) {
                                                            $activity = "Approval of Sales";
                                                        } else {
                                                            $activity = "Submission of Sales";
                                                        }

                                                        if ($sale['treasurerer_approval_status'] == 1) {
                                                            $status = '<span class="label label-success">Approved</span>';
                                                        } elseif ($sale['treasurerer_approval_status'] == 2) {
                                                            $status = '<span class="label label-danger">Cancelled</span>';
                                                        } else {
                                                            $status = '<span class="label label-default">Pending</span>';
                                                        }

                                                        echo '<tr>
                        <td><a href="?a=show-water-source-sales&id=' . $sale['id_water_source'] . '">' . $sale['water_source_name'] . '</a></td>  
                            <td>' . $activity . '</td>
<td>' . getCurrentDate($sale['sale_date'], true, false) . '</td>
                    <td>' . number_format($sale['sale_ugx'], 2, '.', ',') . '</td> 
                    <td>' . number_format($sale['savings'], 2, '.', ',') . '</td>                  
                    <td>' . $status . '</td>
                </tr>';
                                                    }
                                                    ?>             
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="well">               
                                    <div class="col-md-4 col-md-offset-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger">
                                                    That treasurer does not exist
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <?php
                }

                function show_user_groups() {
                    global $dbhandle;
                    $groups = array();

                    $g = $dbhandle->fetch("user_groups", "*", null, "group_name");

                    if (is_array($g) && !empty($g) && !isset($g[0])) {
                        $groups[] = $g;
                    } else {
                        $groups = $g;
                    }

// var_dump($groups);
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">User Groups</h3>
                        </div>
                        <div class="panel-body"> 
                            <div class="row">
                                <div class="col-md-12">        
                                    <a href="?a=add-user-group" class="btn btn-primary pull-right margin">Add User Group</a>
                                </div>
                            </div>
                            <?php
                            if (empty($groups)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4"> 
                                        <div class="alert alert-info">
                                            No user groups have been added yet.
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="dashed"/>
                                        <div class="table-responsive">
                                            <table class="table managed-table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Group name</th>
                                                        <th>Active</th>
                                                        <th>App access</th>
                                                        <th>Email Enabled</th>                       
                                                        <th>SMS Enabled</th>   
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php
                                                    foreach ($groups as $group) {
                                                        $group_is_enabled = $group['group_is_enabled'] == 0 ? "No" : "Yes";
                                                        $can_access_app = $group['can_access_app'] == 0 ? "No" : "Yes";
                                                        $can_receive_emails = $group['can_receive_emails'] == 0 ? "No" : "Yes";
                                                        $can_send_sms = $group['can_send_sms'] == 0 ? "No" : "Yes";
                                                        echo '<tr>
                    <td>' . $group['id_group'] . '</td>
                    <td>' . $group['group_name'] . '</td>
                    <td>' . $group_is_enabled . '</td>
                    <td>' . $can_access_app . '</td>
                    <td>' . $can_receive_emails . '</td>                  
                    <td>' . $can_send_sms . '</td>
                    <td><a href="?a=edit-user-group&id=' . $group['id_group'] . '" class="btn btn-info">Edit</a> | <a href="?a=delete-user-group&id=' . $group['id_group'] . '" class="btn btn-danger delete-link">Delete</a></td>
                </tr>';
                                                    }
                                                    ?>             
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                    <?php
                }

                function show_all_repair_types() {
                    global $dbhandle;

                    $repair_types = array();

                    $query = "SELECT id_repair_type,repair_type,added_by, fname,lname,repair_types.date_added FROM repair_types "
                            . "LEFT JOIN users ON repair_types.added_by=users.idu";

                    $results = $dbhandle->RunQueryForResults($query);

                    while ($row = $results->fetch_assoc()) {
                        $repair_types[] = $row;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Repair Types</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">        
                                    <a href="?a=add-repair-type" class="btn btn-primary pull-right">Add Repair Type</a>
                                </div>               
                            </div>
                            <?php
                            if (empty($repair_types)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4"> 
                                        <div class="alert alert-info">
                                            No repair types have been added yet.
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="dashed"/>
                                        <div class="table-responsive">
                                            <table class="table managed-table">
                                                <thead>
                                                    <tr>                                    
                                                        <th>Repair Type</th>
                                                        <th>Added By</th>
                                                        <th>Date Added</th>                                    
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php
                                                    foreach ($repair_types as $repair_type) {
                                                        echo '<tr>                                    
                                        <td>' . $repair_type['repair_type'] . '</td>
                                        <td>' . $repair_type['fname'] . ' ' . $repair_type['lname'] . '</td>
                                        <td>' . getCurrentDate($repair_type['date_added'], true, true) . '</td>                                    
                                        <td><a href="?a=edit-repair-type&id=' . $repair_type['id_repair_type'] . '" class="btn btn-info">Edit</a> | <a href="?a=delete-repair-type&id=' . $repair_type['id_repair_type'] . '" class="btn btn-danger delete-link">Delete</a></td>
                                    </tr>';
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }

                function show_all_expenditures() {
                    global $dbhandle, $USER;

                    $expenditures = array();

                    $query = "SELECT id_expenditure,water_source_name,repair_type,expenditure_cost,expenditure_date,benefactor, fname,lname FROM expenditures "
                            . "LEFT JOIN water_sources ON expenditures.water_source_id=water_sources.id_water_source "
                            . "LEFT JOIN repair_types ON expenditures.repair_type_id=repair_types.id_repair_type "
                            . "LEFT JOIN users ON expenditures.logged_by=users.idu ";

                    if (($USER->can_edit_sales || $USER->can_view_sales || $USER->can_delete_sales || $USER->can_view_sales ) && $USER->can_view_water_source_savings) {
                        
                    } else {
                        $query .= " WHERE users.idu=" . $USER->idu;
                    }

//echo $query;
                    $results = $dbhandle->RunQueryForResults($query);

                    while ($row = $results->fetch_assoc()) {
                        $expenditures[] = $row;
                    }
                    ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="row-title">Expenditures</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">        
                                    <a href="?a=add-expenditure" class="btn btn-primary pull-right">Add Expenditure</a>
                                </div>               
                            </div>
                            <?php
                            if (empty($expenditures)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4"> 
                                        <div class="alert alert-info">
                                            No expenditures been incurred yet.
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="dashed"/>
                                        <div class="table-responsive">
                                            <table class="table managed-table">
                                                <thead>
                                                    <tr>   
                                                        <th>Water Source</th>
                                                        <th>Repair Type</th>  
                                                        <th>Cost</th>
                                                        <th>Date</th>
                                                        <th>Benefactor</th>
                                                        <th>Added By</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody> 
                                                    <?php
                                                    foreach ($expenditures as $repair_type) {
                                                        if (empty($repair_type['repair_type'])) {
                                                            $repair_type['repair_type'] = "Other";
                                                        }

                                                        $link = "";
                                                        if ($USER->can_edit_expenses) {
                                                            $link.="<a href=\"?a=edit-expenditure&id=" . $repair_type['id_expenditure'] . "\" class=\"btn btn-info\" >Edit</a> ";
                                                        }
                                                        if ($USER->can_delete_expenses) {
                                                            $link .= "| <a href=\"?a=delete-expenditure&id=" . $repair_type['id_expenditure'] . "\" class=\"btn btn-danger delete-link\">Delete</a>";
                                                        }
                                                        //<a href="?a=edit-expenditure&id=' . $repair_type['id_expenditure'] . '" class="btn btn-info">Edit</a> | <a href="?a=delete-expenditure&id=' . $repair_type['id_expenditure'] . '" class="btn btn-danger delete-link">Delete</a>

                                                        echo '<tr>                                    
                                        <td>' . $repair_type['water_source_name'] . '</td>
                                        <td>' . $repair_type['repair_type'] . '</td>
                                         <td>' . number_format($repair_type['expenditure_cost'], 2, '.', ',') . '</td>
                                          <td>' . getCurrentDate($repair_type['expenditure_date'], true, true) . '</td>
                                              <td>' . $repair_type['benefactor'] . '</td>
                                        <td>' . $repair_type['fname'] . ' ' . $repair_type['lname'] . '</td>                                                                   
                                        <td>' . $link . '</td>
                                    </tr>';
                                                    }
                                                    ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
                