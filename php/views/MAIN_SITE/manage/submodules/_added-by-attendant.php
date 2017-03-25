<?php global $user, $attendant_id; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Added by Attendant</h3>
                            <p>Water users added by a water source attendant</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">                
                                    <h3>Name : <?php echo ucwords($user['fname'] . " " . $user['lname']); ?></h3>               
                                </div>
                            </div>
                            <?php
                            /* $water_users = array();
                              $sql = "SELECT id_user,CONCAT_WS(' ',fname,lname) name, date_added," . TABLE_PREFIX . "water_users.pnumber,id_water_source,water_source_name FROM " . TABLE_PREFIX . "water_users "
                              . " LEFT JOIN " . TABLE_PREFIX . "water_sources ON " . TABLE_PREFIX . "water_users.water_source_id=" . TABLE_PREFIX . "water_sources.id_water_source WHERE " . TABLE_PREFIX . "water_users.added_by=$attendant_id";
                              //var_dump($sql);
                              $results = $pm4w->RunQueryForResults($sql);
                              if ($results) {
                              while ($row = $results->fetch_assoc()) {
                              //var_dump($row);
                              $water_users[] = $row;
                              }
                              } */

                            $columns = array(
                                'id_user',
                                'CONCAT_WS(\' \',fname,lname) name',
                                'date_added',
                                'water_users.pnumber',
                                'id_water_source',
                                'water_source_name');

                            $App->con->where("added_by", $attendant_id);
                            $App->con->join("water_sources water_sources", "water_users.water_source_id=water_sources.id_water_source");
                            $water_users = $App->con->get("water_users water_users", null, $columns);

                            if (empty($water_users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <?php echo ucwords($user['fname']); ?> has not added any water users yet
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="2.5%">#</th>
                                            <th>Water User</th>                      
                                            <th>P. Number</th>
                                            <th>Water Source</th>                                 
                                            <th>Date added</th> 
                                            <th style="width: 12%  !important;"></th> 
                                        </tr>
                                    </thead>
                                    <tbody>                    
                                        <?php
                                        foreach ($water_users as $key => $water_user) {
                                            ?>
                                            <tr>
                                                <td width="2.5%"><?php echo $water_user['id_user']; ?></td>
                                                <td><a href="<?php echo '/manage/water-user-transactions/?id=' . $water_user['id_user'] ?>" data-toggle="tooltip" data-placement="top" title="Click To View User Payments History"><?php echo $water_user['name']; ?></a></td>                      
                                                <td><?php echo $water_user['pnumber']; ?></td>
                                                <td><a href="<?php echo '/manage/water-source-users/?id=' . $water_user['id_water_source'] ?>"  data-toggle="tooltip" data-placement="top" title="Click To Tiew More Water Users From <?php echo $water_user['water_source_name']; ?>"><?php echo $water_user['water_source_name']; ?></a></td>                                 
                                                <td><?php echo $App->getCurrentDateTime($water_user['date_added'], true); ?></td> 
                                                <td style="width: 12%  !important;"><?php
                                                    echo '<a href="' . '/manage/edit/?id=' . $water_user['id_user'] . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a> '
                                                    . ' <a href="' . '/manage/?a=delete&id=' . $water_user['id_user'] . '" class="btn btn-danger delete-link"  data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a></div>';
                                                    ?></td> 
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>                       
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
