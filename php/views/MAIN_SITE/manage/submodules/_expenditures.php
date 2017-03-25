<?php global $expenditures; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Expenditures</h3>
                            <p>Costs incrurred in water sources</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            if (empty($expenditures)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">        
                                        <div class="alert alert-info">
                                            No expenditures have been incurred yet.
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                // var_dump($sql);
                                ?>
                                <form action="" method="post" class="form-horizontal">
                                    <table class="table hover dt-responsive nowrap table-responsive managed-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>  
                                                <th width="6%">
                                                    <input type="checkbox" name="checkAll" id="checkAll" value="" class="check checkAll"/>
                                                </th>
                                                <th>Water Source</th>
                                                <th>Repair Type</th>  
                                                <th>Cost</th>
                                                <th>Date</th>
                                                <th>Benefactor</th>
                                                <th>Added By</th>
                                                <th width="15.5%" class="text-center">
                                                    <button name="delete" id="delete" class="btn btn-danger delete-link">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php
                                            foreach ($expenditures as $repair_type) {
                                                if (empty($repair_type['repair_type'])) {
                                                    $repair_type['repair_type'] = "Other";
                                                }

                                                $link = "";
                                                if ($App->can_edit_expenses) {
                                                    $link.="<a href=\"/manage/edit-expenditure/?id=" . $repair_type['id_expenditure'] . "\" class=\"btn btn-primary\"><i class=\"fa fa-pencil\"></i></a> ";
                                                }
                                                if ($App->can_delete_expenses) {
                                                    $link .= "| <a href=\"?a=delete&id=" . $repair_type['id_expenditure'] . "\" class=\"btn btn-danger delete-link\"><i class=\"fa fa-trash\"></i></a>";
                                                }
                                                //<a href="?id=' . $repair_type['id_expenditure'] . '" class="btn btn-primary">Edit</a> | <a href="?a=delete&id=' . $repair_type['id_expenditure'] . '" class="btn btn-danger delete-link">Delete</a>

                                                echo '<tr>  
                                        <td> <input type="checkbox" name="ids[]" value="' . $repair_type['id_expenditure'] . '" class="check"/></td>
                                        <td>' . $repair_type['water_source_name'] . '</td>
                                        <td>' . $repair_type['repair_type'] . '</td>
                                         <td>' . number_format($repair_type['expenditure_cost'], 2, '.', ',') . '</td>
                                          <td>' . $App->getCurrentDateTime($repair_type['expenditure_date'], true) . '</td>
                                              <td>' . $repair_type['benefactor'] . '</td>
                                        <td>' . $repair_type['fname'] . ' ' . $repair_type['lname'] . '</td>                                                                   
                                        <td>' . $link . '</td>
                                    </tr>';
                                            }
                                            ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>  
                                                <th width="6%">
                                                    <input type="checkbox" name="checkAll" id="checkAll" value="" class="check checkAll"/>
                                                </th>
                                                <th>Water Source</th>
                                                <th>Repair Type</th>  
                                                <th>Cost</th>
                                                <th>Date</th>
                                                <th>Benefactor</th>
                                                <th>Added By</th>
                                                 <th width="15.5%" class="text-center">
                                                    <button name="delete" id="delete" class="btn btn-danger delete-link">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </tfoot>                       
                                    </table>
                                </form>
                            <?php } ?>
                        </div>                       
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
