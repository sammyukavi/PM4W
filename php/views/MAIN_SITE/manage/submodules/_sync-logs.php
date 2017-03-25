<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Sync logs
                <small>List of users' sync events</small>
            </h1>
            <form method="post" action="?a=set-filter">
                <div class="row">
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
                </div>

                <?php
                //$platform_filter = $App->getSessionVariable("platform_filter");
                //$vars = get_class_vars("events");
                // $events = array();
                //foreach ($vars as $key => $var) {
                //if (!is_array($var) && !is_object($var)) {
                // $events[$key] = ucwords(str_replace("_", " ", $var));
                //}
                // }
                //vardump($events);
                ?>
                <div  style="margin-top: 10px;">
                    <table class="table hover dt-responsive nowrap table-responsive ajax-powered-datatable" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="6%">
                                    <input type="checkbox" name="checkAll" id="checkAll" value="" class="check checkAll"/>
                                </th>
                                <th>
                                    Event
                                </th>
                                <th>
                                    User
                                </th>
                                <th>
                                    Platform
                                </th>
                                <th>
                                    Event Time
                                </th>
                                <th>
                                    <button type="submit" name="delete" value="delete" class="btn btn-danger delete-link">
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
                                <th>
                                    Event
                                </th>
                                <th>
                                    User
                                </th>
                                <th>
                                    Platform
                                </th>
                                <th>
                                    Event Time
                                </th>
                                <th>
                                    <button type="submit" name="delete" value="delete" class="btn btn-danger delete-link">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>