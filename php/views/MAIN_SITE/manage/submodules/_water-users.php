<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3>Water Users</h3>
                    <p>List of monthly billable water users</p>
                </div>
                <div class="panel-body">
                    <form method="post" action="" class="form-horizontal" role="form">
                        <!--div class="row">
                            <div class="col-sm-3 col-sm-offset-6">                      
                                <label>Filter By:</label>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-sm-3 col-sm-offset-6">
                                <label for="user_filter">User</label>
                        <?php
                        $user_filter = $App->getSessionVariable("user_filter");
                        $App->con->orderBy('fname', 'asc');
                        $users = $App->con->get('users', null, array("fname,lname,idu"));
                        ?>
                                <select class="form-control selectpicker-with-search" name="user_filter" id="user_filter">
                                    <option value=""  <?php echo $user_filter == "" ? 'selected="selected"' : ''; ?>>All</option>
                        <?php foreach ($users as $user) {
                            ?>
                                                    <option value="<?php echo $user['idu']; ?>" <?php echo $user_filter == $user['idu'] ? 'selected="selected"' : ''; ?>>
                            <?php echo $user['fname'] . ' ' . $user['lname']; ?>
                                                    </option>
                        <?php }
                        ?>
                                </select>
                            </div>  
                            <div class="col-sm-3">
                                <label for="time_filter">Time</label>
                        <?php $time_filter = $App->getSessionVariable("time_filter"); ?>
                                <select class="form-control selectpicker" name="time_filter" id="time_filter">
                                    <option value=""  <?php echo $time_filter == "" ? 'selected="selected"' : ''; ?>>The beginning of time</option>
                                    <option value="last_four_weeks" <?php echo $time_filter == "last_four_weeks" ? 'selected="selected"' : ''; ?>>The last four weeks</option>
                                    <option value="past_week" <?php echo $time_filter == "past_week" ? 'selected="selected"' : ''; ?>>The past week</option>
                                    <option value="past_day" <?php echo $time_filter == "past_day" ? 'selected="selected"' : ''; ?>>The past day</option>
                                    <option value="past_hour" <?php echo $time_filter == "past_hour" ? 'selected="selected"' : ''; ?>>The past hour</option>
                                </select>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-sm-offset-9" style="margin-top: 10px;">
                                <input type="submit" value="Filter" name="submit" class="btn btn-primary form-control"/>
                            </div>
                        </div-->
                        <div class="row margin-top-10">
                            <div class="col-md-12">
                                <table class="table hover dt-responsive nowrap table-responsive ajax-powered-datatable" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="6%">
                                                <input type="checkbox" name="checkAll" id="checkAll" value="" class="check checkAll"/>
                                            </th>
                                            <th>Water User</th>                      
                                            <th>P. Number</th>
                                            <th>Water Source</th> 
                                            <th>Last Update</th>
                                            <th>Added By</th> 
                                            <th>Status</th> 
                                            <th style="width: 30%;" class="text-center">
                                                <select class="form-control selectpicker" name="topbulkAction" id="topbulkAction" onchange="this.form.submit()">
                                                    <option value="">Select Option</option>
                                                    <option value="markForDelete">Mark for Delete</option>
                                                    <option value="unmarkForDelete">Unmark for delete</option>
                                                </select>                             
                                                <button name="delete" class="btn btn-danger delete-link">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </th> 
                                        </tr>
                                    </thead>
                                    <tbody>                    

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th width="6%">
                                                <input type="checkbox" name="checkAll" id="checkAll" value="" class="check checkAll"/>
                                            </th>
                                            <th>Water User</th>                      
                                            <th>P. Number</th>
                                            <th>Water Source</th> 
                                            <th>Last Update</th>
                                            <th>Added By</th> 
                                            <th>Status</th> 
                                            <th style="width: 30%;" class="text-center">
                                                <select class="form-control selectpicker" name="bottombulkAction" id="bottombulkAction" onchange="this.form.submit()">
                                                    <option value="">Select Option</option>
                                                    <option value="markForDelete">Mark for Delete</option>
                                                    <option value="unmarkForDelete">Unmark for delete</option>
                                                </select>                             
                                                <button name="delete" class="btn btn-danger delete-link">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>                       
            </div>                
        </div>                
    </div>   
</div>