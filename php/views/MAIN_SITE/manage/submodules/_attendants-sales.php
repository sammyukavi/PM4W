<?php global $user; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Attendant Sales</h3>
                            <p>Sales done by an attendant</p>
                        </div>
                        <div class="panel-body"> 
                            <div class="row">
                                <div class="col-md-12">                
                                    <h3>Name : <?php echo ucwords($user['fname'] . " " . $user['lname']); ?></h3>               
                                </div>
                            </div>
                            <?php
                            $sales = array();
                            $query = "SELECT "
                                    . DB_TABLE_PREFIX . "sales.id_sale,"
                                    . DB_TABLE_PREFIX . "sales.sale_date,"
                                    . DB_TABLE_PREFIX . "sales.sale_ugx,"
                                    . DB_TABLE_PREFIX . "water_sources.id_water_source,"
                                    . DB_TABLE_PREFIX . "water_sources.water_source_id,"
                                    . DB_TABLE_PREFIX . "water_sources.water_source_name,"
                                    . DB_TABLE_PREFIX . "water_users.fname AS user_fname,"
                                    . DB_TABLE_PREFIX . "water_users.lname AS user_lname,"
                                    . "id_user,"
                                    . DB_TABLE_PREFIX . "users.idu,"
                                    . DB_TABLE_PREFIX . "users.fname AS attendant_fname,"
                                    . DB_TABLE_PREFIX . "users.lname AS attendant_lname"
                                    . " FROM " . DB_TABLE_PREFIX . "sales "
                                    . "LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "sales.water_source_id=" . DB_TABLE_PREFIX . "water_sources.id_water_source "
                                    . "LEFT JOIN " . DB_TABLE_PREFIX . "water_users ON " . DB_TABLE_PREFIX . "water_users.id_user=" . DB_TABLE_PREFIX . "sales.sold_to "
                                    . "LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "users.idu=" . DB_TABLE_PREFIX . "sales.sold_by "
                                    . "WHERE sold_by=" . $App->getValue('id');

                            //var_dump($query);

                            $result = $App->con->rawQuery($query);

                            foreach ($result as $sale) {
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
                                <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
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
                                                $c_name = '<a href="/manage/water-user-transactions/?id=' . $sale['id_user'] . '" data-toggle="tooltip" data-placement="top" title="Click To View User Payments History">' . $c_name . '</a>';
                                            }

                                            $water_source_name = '';
                                            if (!empty($sale['water_source_name'])) {
                                                $water_source_name = '<a href="/manage/water-source-sales/?id=' . $sale['id_water_source'] . '"  data-toggle="tooltip" data-placement="top" title="Click To Tiew More Sales FROM ' . $sale['water_source_name'] . '">' . $sale['water_source_name'] . '</a>';
                                            }

                                            $link = "";
                                            if ($App->can_edit_sales) {
                                                $link.="<a href=\"/manage/edit-sale/?id=" . $sale["id_sale"] . "\" class=\"btn btn-primary\"><i class=\"fa fa-pencil\"></i></a> ";
                                            }
                                            if ($App->can_delete_sales) {
                                                $link .= "| <a href=\"/manage/sales/?a=delete-sale&id=" . $sale["id_sale"] . "\" class=\"btn btn-danger delete-link\"><i class=\"fa fa-trash\"></i></a>";
                                            }

                                            echo '<tr>
                    <td>' . $sale['id_sale'] . '</td>
                    <td>' . $c_name . '</td>
                    <td>' . $water_source_name . '</td>
                    <td>' . number_format($sale['sale_ugx'], 2, '.', ',') . '</td>                    
                    <td>' . $App->getCurrentDateTime($sale['sale_date'], true) . '</td>
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
