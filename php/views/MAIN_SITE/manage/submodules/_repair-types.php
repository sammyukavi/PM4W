<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Repair Types</h3>
                            <p>Types of expenditures on water sources</p>
                        </div>
                        <div class="panel-body">                    
                            <?php
                            $repair_types = array();

                            $query = "SELECT id_repair_type,repair_type,added_by,fname,lname," . DB_TABLE_PREFIX . "repair_types.active," . DB_TABLE_PREFIX . "repair_types.date_created FROM " . DB_TABLE_PREFIX . "repair_types "
                                    . "LEFT JOIN " . DB_TABLE_PREFIX . "users ON " . DB_TABLE_PREFIX . "repair_types.added_by=" . DB_TABLE_PREFIX . "users.idu";
                            $repair_types = $App->con->rawQuery($query);
                            ?>
                            <?php
                            if (empty($repair_types)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">        
                                        <div class="alert alert-info">
                                            No repair types have been added yet.
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                ?>
                                <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>                                    
                                            <th>Repair Type</th>
                                            <th>Status</th>
                                            <th>Added By</th>                                                                               
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php
                                        foreach ($repair_types as $repair_type) {
                                            echo '<tr>                                    
                                        <td>' . $repair_type['repair_type'] . '</td>
                                        <td>' . ($repair_type['active'] == 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>') . '</td>
                                        <td>' . $repair_type['fname'] . ' ' . $repair_type['lname'] . '</td>                                                                            
                                        <td><a href="/manage/edit-repair-type?id=' . $repair_type['id_repair_type'] . '" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Click To Edit Repair Types"><i class="fa fa-pencil"></i></a> | <a href="/manage/repair-types/?a=delete&id=' . $repair_type['id_repair_type'] . '" class="btn btn-danger delete-link"  data-toggle="tooltip" data-placement="top" title="Click To Delete Repair Types"><i class="fa fa-trash"></i></a></td>
                                    </tr>';
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
