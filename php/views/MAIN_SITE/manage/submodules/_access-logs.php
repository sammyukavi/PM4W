<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Access logs
                <small>List of users and activities</small>
            </h1>
            <form method="post" action="?a=set-filter">
                <div class="row">
                    <div class="col-lg-12">                            
                        <label>Filter By:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <label for="platform_platform_filter">Platform</label>
                        <?php $platform_filter = $App->getSessionVariable("platform_filter"); ?>
                        <select class="form-control selectpicker" name="platform_filter" id="platform_filter">
                            <option value=""  <?php echo $platform_filter == "" ? 'selected="selected"' : ''; ?>>All</option>
                            <option value="web_app" <?php echo $platform_filter == "web_app" ? 'selected="selected"' : ''; ?>>Web</option>
                            <option value="android_app" <?php echo $platform_filter == "android_app" ? 'selected="selected"' : ''; ?>>Android App</option>
                        </select>
                    </div>  
                    <div class="col-sm-3">
                        <label for="user_filter">User</label>
                        <?php
                        $user_filter = $App->getSessionVariable("user_filter");
                        $App->con->orderBy("fname", "asc");
                        $users = $App->con->get("users", null, array("fname,lname,idu"));
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
                        <label for="event_filter">Event Type</label>
                        <?php
                        $event_filter = $App->getSessionVariable("event_filter");
                        $events = new Wendo\events();
                        $vars = get_class_vars(get_class($events));
                        $events = array();
                        foreach ($vars as $key => $var) {
                            if (!is_array($var) && !is_object($var)) {
                                $events[$key] = ucwords(str_replace("_", " ", $var));
                            }
                        }
                        array_multisort($events);
                        ?>

                        <select class="form-control selectpicker-with-search" name="event_filter" id="event_filter">
                            <option value=""  <?php echo $event_filter == "" ? 'selected="selected"' : ''; ?>>All</option>
                            <?php foreach ($events as $key => $event) {
                                ?>
                                <option value="<?php echo $key; ?>" <?php echo $event_filter == $key ? 'selected="selected"' : ''; ?>><?php echo $event; ?></option>
                                <?php
                            }
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

                <div class="row margin-top-10">
                    <div class="col-sm-3 col-sm-offset-9">
                        <input type="submit" value="Filter" name="submit" class="btn btn-primary form-control"/>
                    </div>
                </div>

                <?php
                $platform_filter = $App->getSessionVariable("platform_filter");
                $events = new Wendo\events();
                $vars = get_class_vars(get_class($events));
                $events = array();
                foreach ($vars as $key => $var) {
                    if (!is_array($var) && !is_object($var)) {
                        $events[$key] = ucwords(str_replace("_", " ", $var));
                    }
                }
                //vardump($events);
                ?>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-12">
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
                </div>
            </form>
        </div>
    </div>
</div>