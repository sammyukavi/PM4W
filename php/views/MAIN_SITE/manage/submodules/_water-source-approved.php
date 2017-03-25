<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Approved Sales</h3>
                            <p>Approved sales that are used to calculate the savings</p>
                        </div>
                        <?php
                        $id_water_source = $App->getValue('id');

                        $App->con->where('id_water_source', $id_water_source);
                        $water_source_data = $App->con->getOne('water_sources');

                        $sales = array();
                        $query = "SELECT "
                                . DB_TABLE_PREFIX . "sales.id_sale, "
                                . DB_TABLE_PREFIX . "sales.sale_date, "
                                . DB_TABLE_PREFIX . "sales.sale_ugx, "
                                . DB_TABLE_PREFIX . "water_sources.water_source_id, "
                                . DB_TABLE_PREFIX . "water_users.fname AS user_fname, "
                                . DB_TABLE_PREFIX . "water_users.lname AS user_lname, "
                                . DB_TABLE_PREFIX . "users.idu, "
                                . DB_TABLE_PREFIX . "water_users.id_user, "
                                . DB_TABLE_PREFIX . "users.fname AS attendant_fname, "
                                . DB_TABLE_PREFIX . "users.lname AS attendant_lname "
                                . " FROM " . DB_TABLE_PREFIX . "sales "
                                . "LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "sales.water_source_id=" . DB_TABLE_PREFIX . "water_sources.id_water_source "
                                . "LEFT JOIN " . DB_TABLE_PREFIX . "water_users ON " . DB_TABLE_PREFIX . "water_users.id_user=" . DB_TABLE_PREFIX . "sales.sold_to "
                                . "LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "users.idu=" . DB_TABLE_PREFIX . "sales.sold_by "
                                . "WHERE " . DB_TABLE_PREFIX . "water_sources.id_water_source=$id_water_source AND submitted_to_treasurer=1 AND treasurerer_approval_status=1";

                        if ($App->can_approve_treasurers_submissions) {
                            //This is District Water Officer
                            //Query to summarise from water sources
                        } elseif ($App->can_approve_attendants_submissions) {
                            //This is the Water Board Treasurer
                            //Query to summarise from attendatnts
                        } elseif ($App->can_submit_attendant_daily_sales) {
                            //This is the Water User Committee Treasurer        
                        } else {
                            //This is the attendant
                            $query.=" AND users.idu=" . $App->user->uid;
                        }

                        $gross_sales = $App->con->rawQuery($query);

                        foreach ($gross_sales as $gross_sale) {
                            if (!empty($gross_sale['id_sale'])) {
                                $sales[] = $gross_sale;
                            }
                        }
                        ?>
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
                                        <h4>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h4>
                                        <h4>ID: <?php echo ucwords($water_source_data['water_source_id']); ?></h4>
                                        <h4>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h4>
                                        <hr class="stripe"/>
                                    </div>
                                </div>
                                <?php
                                if (empty($sales)) {
                                    ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                No sales have been approved so far
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
                                                <!--th>Water Source</th-->
                                                <th>Sale(UGX)</th>
                                                <th>Sold By</th>                       
                                                <th>Date</th>
                                                <th style="width: 15%!important;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>   
                                            <?php
                                            foreach ($sales as $gross_sale) {

                                                $c_name = trim($gross_sale['user_fname'] . ' ' . $gross_sale['user_lname']);
                                                if (empty($c_name)) {
                                                    $c_name = "Daily Sale";
                                                } else {
                                                    $c_name = '<a href="/manage/water-users/water-user-transactions/?id=' . $gross_sale['id_user'] . '"  data-toggle="tooltip" data-placement="top" title="Click To View User Payments History">' . $c_name . '</a>';
                                                }

                                                $link = "";
                                                if ($App->can_edit_sales) {
                                                    $link.="<a href=\"/manage/edit-sale/?id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-primary\"><i class=\"fa fa-pencil\"></i></a> ";
                                                }
                                                if ($App->can_delete_sales) {
                                                    $link .= "| <a href=\"/manage/sales/?a=delete-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-danger delete-link\"><i class=\"fa fa-trash\"></i></a>";
                                                }

                                                //<a href=\"?a=edit-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-primary\" >Edit</a> | <a href=\"?a=delete-sale&id=" . $gross_sale["id_sale"] . "\" class=\"btn btn-danger delete-link\">Delete</a>


                                                echo "<tr>
                    <td>" . $gross_sale["id_sale"] . "</td>
                    <td>" . $c_name . "</td>                    
                    <td>" . number_format($gross_sale['sale_ugx'], 2, '.', ',') . "</td>
                    <td><a href=\"/manage/attendants-sales/?id=" . $gross_sale["idu"] . "\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Click To Tiew More Sales By " . $gross_sale["attendant_fname"] . " " . $gross_sale["attendant_lname"] . "\">" . $gross_sale["attendant_fname"] . " " . $gross_sale["attendant_lname"] . "</a></td>                  
                    <td>" . $App->getCurrentDateTime($gross_sale["sale_date"], true) . "</td>
                    <td class=\"text-center\">$link</td>
                </tr>";
                                            }
                                            ?>             
                                        </tbody>
                                    </table>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>                          
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
