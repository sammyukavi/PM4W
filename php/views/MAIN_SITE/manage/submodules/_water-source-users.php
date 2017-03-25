<?php
global $water_source_users, $water_source_data;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Water Source Users</h3>
                            <p>List of water users from a water source</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (empty($water_source_users)) {
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
                                <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
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
                    <td><a href=\"/manage/water-user-transactions/?id=" . $water_source_user["water_user_id"] . "\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Click To View User Payments History\">" . $water_source_user["w_u_fname"] . " " . $water_source_user["w_u_lname"] . "</a></td>
                    <td><a href=\"/manage/added-by-attendant/?id=" . $water_source_user["idu"] . "\"  data-toggle=\"tooltip\" data-placement=\"top\" title=\"Click To Tiew More Water Users Added By " . $water_source_user["w_a_fname"] . " " . $water_source_user["w_a_lname"] . "\">" . $water_source_user["w_a_fname"] . " " . $water_source_user["w_a_lname"] . "</a></td>
                    <td>" . $App->getCurrentDateTime($water_source_user["date_added"], true) . "</td>
                    <td><a href=\"/manage/edit-water-user/?id=" . $water_source_user["water_user_id"] . "\" class=\"btn btn-primary\" data-placement=\"top\" title=\"Click To Edit\"><i class=\"fa fa-pencil\"></i></a> | <a href=\"/manage/water-users?a=delete&id=" . $water_source_user["water_user_id"] . "\" class=\"btn btn-danger delete-link\" data-placement=\"top\" title=\"Click To Delete This Water User\"><i class=\"fa fa-trash\"></i></a></td>
                </tr>";
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
