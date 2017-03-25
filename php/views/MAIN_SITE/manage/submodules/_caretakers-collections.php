<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Caretaker's Collections</h3>
                            <p>Savings Collected By Care Takers Pending Approval</p>
                        </div>
                        <div class="panel-body">           
                            <?php
                            $query = " SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . DB_TABLE_PREFIX . "sales.percentage_saved, CASE WHEN " . DB_TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . DB_TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings FROM " . DB_TABLE_PREFIX . "water_source_treasurers "
                                    . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "water_sources.id_water_source=" . DB_TABLE_PREFIX . "water_source_treasurers.water_source_id "
                                    . " LEFT JOIN " . DB_TABLE_PREFIX . "water_source_caretakers ON " . DB_TABLE_PREFIX . "water_source_caretakers.water_source_id=" . DB_TABLE_PREFIX . "water_sources.id_water_source "
                                    . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "users.idu=" . DB_TABLE_PREFIX . "water_source_caretakers.uid "
                                    . " LEFT JOIN " . DB_TABLE_PREFIX . "sales ON " . DB_TABLE_PREFIX . "sales.sold_by=" . DB_TABLE_PREFIX . "water_source_caretakers.uid "
                                    . " WHERE  " . DB_TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . DB_TABLE_PREFIX . "sales.treasurerer_approval_status<>1 AND " . DB_TABLE_PREFIX . "water_source_treasurers.uid=" . $App->user->uid . " "
                                    . " GROUP BY Date(sale_date)," . DB_TABLE_PREFIX . "sales.water_source_id ORDER BY submittion_to_treasurer_date ASC";

                            $query = " SELECT idu,id_water_source,water_source_name,fname,lname,SUM(sale_ugx) AS sale_ugx,sale_date," . DB_TABLE_PREFIX . "sales.percentage_saved, "
                                    . " CASE WHEN " . DB_TABLE_PREFIX . "sales.percentage_saved > 0 THEN SUM(FLOOR((sale_ugx) * (" . DB_TABLE_PREFIX . "sales.percentage_saved / 100) / 100) * 100) ELSE sale_ugx END AS savings "
                                    . " FROM " . DB_TABLE_PREFIX . "sales  "
                                    . " LEFT JOIN " . DB_TABLE_PREFIX . "water_sources ON " . DB_TABLE_PREFIX . "water_sources.id_water_source=" . DB_TABLE_PREFIX . "sales.water_source_id "
                                    . " LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "users.idu=" . DB_TABLE_PREFIX . "sales.sold_by "
                                    . " WHERE " . DB_TABLE_PREFIX . "sales.submitted_to_treasurer=0 AND " . DB_TABLE_PREFIX . "sales.treasurerer_approval_status<>1 AND sale_ugx>0 "
                                    . "GROUP BY idu ";

                            $sales = $App->con->rawQuery($query);

                            if (empty($sales)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12"> 
                                         <div class="alert alert-info text-center text-capitalize">
                                            No sales to approve
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr class="dashed"/>
                                        <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Attendant</th>
                                                    <th>Water Source</th> 
                                                    <!--th>Date</th--> 
                                                    <th>Transactions</th> 
                                                    <th>Savings (UGX)</th>  
                                                    <th style="width: 5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php
                                                foreach ($sales as $sale) {
                                                    echo '<tr>
                        <td><a href="/manage/attendants-sales/?id=' . $sale['idu'] . '" data-toggle="tooltip" data-placement="top" title="Click To View More Sales By ' . $sale['fname'] . ' ' . $sale['lname'] . '">' . $sale['fname'] . ' ' . $sale['lname'] . '</a></td>
<td><a href="/manage/water-source-sales/?id=' . $sale['id_water_source'] . '" data-toggle="tooltip" data-placement="top" title="Click To View More Sales From ' . $sale['water_source_name'] . '">' . $sale['water_source_name'] . '</a></td>                     
<!--td>' . $App->getCurrentDateTime($sale['sale_date'], true, false) . '</td-->
                    <td>' . number_format($sale['sale_ugx'], 2, '.', ',') . '</td> 
                    <td>' . number_format($sale['savings'], 2, '.', ',') . '</td>                  
                    <td><a href="?a=submit&t=' . strtotime($sale['sale_date']) . '&id=' . $sale['id_water_source'] . '&idu=' . $sale['idu'] . '"  data-toggle="tooltip" data-placement="top" title="Click To Submit Sales" class="btn btn-success"><i class="fa fa-check"></i></a></td>
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
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
