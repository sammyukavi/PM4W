<?php global $customer, $id_user; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Water User Transactions</h3>   
                            <p>Payments done by a user</p>
                        </div>
                        <div class="panel-body">                    
                            <div class="row">
                                <div class="col-md-12">                
                                    <h4>Name: <?php echo ucwords($customer['fname'] . " " . $customer['lname']); ?></h4> 
                                    <h4>Phone #: <?php echo $customer['pnumber']; ?></h4>                            
                                    <h4>Water Source: <?php echo $customer['water_source_name']; ?></h4>
                                    <h4>Date Added: <?php echo $App->getCurrentDateTime($customer['date_added'], true); ?></h4>
                                    <hr class="stripe"/>
                                </div>
                            </div>
                            <?php
                            $transactions = array();
                            $query = "SELECT id_sale,idu,sale_date,sale_ugx,id_water_source,water_source_name, "
                                    . DB_TABLE_PREFIX . "water_sources.id_water_source, "
                                    . DB_TABLE_PREFIX . "water_sources.water_source_id, "
                                    . DB_TABLE_PREFIX . "water_sources.water_source_name, "
                                    . DB_TABLE_PREFIX . "users.fname AS attendant_fname, "
                                    . DB_TABLE_PREFIX . "users.lname AS attendant_lname "
                                    . "FROM " . DB_TABLE_PREFIX . "sales "
                                    . "LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "water_sources.id_water_source=" . DB_TABLE_PREFIX . "sales.water_source_id "
                                    . "LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "users.idu=" . DB_TABLE_PREFIX . "sales.sold_by "
                                    . "WHERE sold_to=$id_user ORDER BY id_sale DESC";
                            //var_dump($query);

                            $transactions = $App->con->rawQuery($query);
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
                                <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
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

                                            $link = "";
                                            if ($App->can_edit_sales) {
                                                $link.='<a href="/manage/sales/edit/?id=' . $transaction['id_sale'] . '" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
                                            }
                                            if ($App->can_delete_sales) {
                                                $link .= ' | <a href="/manage/sales/?a=delete-sale&id=' . $transaction['id_sale'] . '" class="btn btn-danger delete-link"><i class="fa fa-trash"></a>';
                                            }

                                            echo '<tr>
                            <td>' . $transaction['id_sale'] . '</td>
                            <td><a href="/manage/water-source-sales/?id=' . $transaction['id_water_source'] . '" data-toggle="tooltip" data-placement="top" title="Click To Tiew More Sales From ' . $transaction['water_source_name'] . '">' . $transaction['water_source_name'] . '</a></td>
                            <td><a href="/manage/attendants-sales/?id=' . $transaction['idu'] . '" data-toggle="tooltip" data-placement="top" title="Click To Tiew More Sales By ' . $transaction['attendant_fname'] . ' ' . $transaction['attendant_lname'] . '">' . $transaction['attendant_fname'] . ' ' . $transaction['attendant_lname'] . '</a></td>
                            <td>' . $transaction['sale_ugx'] . '</td>
                            <td>' . $App->getCurrentDateTime($transaction['sale_date'], true) . '</td>
                            <td>' . $link . '</td>
                        </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            ?>
                        </div>                      
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
