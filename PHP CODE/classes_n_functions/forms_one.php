<?php

function the_login_form() {
    ?>
    <div class="row"> 
        <form method="post" action="?a=login" autocomplete="off">
            <div class="col-md-offset-4 col-md-4">
                <h3 class="row-title">Login</h3>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label" for="email">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="email,username or phone number">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label" for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"> 
                        <span class="pull-left"><strong><a href="?a=forgot-password" title="Forgot password">[ ? ]</a></strong></span>
                        <input type="submit" value="Login" name="submit" class="btn btn-primary pull-right">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                    </div>
                </div>
            </div> 
        </form>
    </div> 
    <?php
}

function the_recovery_form() {
    ?>
    <div class="row"> 
        <form method="post" action="?a=forgot-password" autocomplete="off">
            <div class="col-md-offset-4 col-md-4">
                <h3 class="row-title">Recover password</h3>
                <div class="row">
                    <div class="col-md-12">
                        <label class="control-label" for="email">Enter email, username or phone number <br/>(format 256777123456)</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="email,username or phone number">
                    </div>
                </div>                
                <div class="row">
                    <div class="col-md-12"> 
                        <span class="pull-left"><strong><a href="<?php echo SITE_URL; ?>" title="Login">[ login ]</a></strong></span>
                        <input type="submit" value="Recover" name="submit" class="btn btn-primary pull-right">
                    </div>
                </div>                
            </div> 
        </form>
    </div> 
    <?php
}

function the_add_water_user_form() {
    $users = array();
    global $dbhandle, $USER;
    //$u = $dbhandle->Fetch("users", '*', array('group_id' => 6));

    $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . TABLE_PREFIX . "users "
            . "LEFT JOIN user_groups ON group_id=id_group "
            . "WHERE active=1";

    $result = $dbhandle->RunQueryForResults($query);

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Add new water user</h3>
        </div>
        <div class="panel-body">  
            <form method="post" action="?a=add-water-user" autocomplete="off">   

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" for="email">First name</label>
                        <input type="text" name="fname" id="fname" class="form-control" placeholder="User first name" value="<?php echo getArrayVal($_POST, 'fname'); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="control-label" for="email">Last name</label>
                        <input type="text" name="lname" id="lname" class="form-control" placeholder="User last name" value="<?php echo getArrayVal($_POST, 'lname'); ?>">
                    </div>
                </div> 


                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" for="pnumber">Phone number (Format 256777123456, 0777123456)</label>
                        <input type="text" name="pnumber" id="pnumber" class="form-control" placeholder="Phone number" value="<?php echo getArrayVal($_POST, 'pnumber'); ?>">
                    </div>

                    <?php if ($USER->can_edit_system_users) { ?>
                        <div class="col-md-6">
                            <label class="control-label" for="uid">Added by</label>
                            <select name="uid" class="selecter_3" id="uid" data-selecter-options='{"cover":"true"}'>
                                <option value="" selected="selected">-----</option>
                                <?php foreach ($users as $user) {
                                    ?>
                                    <option value="<?php echo $user['idu'] ?>"><?php echo $user['fname'] . " " . $user['lname'] ?></option>
                                <?php }
                                ?>
                            </select>                        
                        </div>                     
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-12">        
                        <input type="submit" value="Add Water User" name="submit" class="btn btn-primary pull-right">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
}

function the_edit_water_user_form() {
    global $dbhandle, $USER;

    $users = array();
    //$u = $dbhandle->Fetch("users", '*', array('group_id' => 6));
    $u = $dbhandle->Fetch("users", "*", NULL, 'fname');
    if (is_array($u) && !empty($u) && !isset($u[0])) {
        $users[] = $u;
    } else {
        $users = $u;
    }

    $id_user = getArrayVal($_GET, 'id');

    $query = "SELECT * FROM " . TABLE_PREFIX . "water_users WHERE id_user=$id_user";


    $result = $dbhandle->RunQueryForResults($query);

    if (!empty($result)) {
        $customer = $result->fetch_assoc();
    }
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Edit water user</h3>
        </div>
        <div class="panel-body">
            <?php
            if (!isset($customer['id_user'])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            Water user does not exist
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>        
                <form method="post" action="?a=edit-water-user&id=<?php echo $id_user; ?>" autocomplete="off">    
                    <div class="row">
                        <div class="col-md-12">        
                            <a href="?a=add-water-user" class="btn btn-primary pull-right">Add water user</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="fname">First name</label>
                            <input type="text" name="fname" id="fname" class="form-control" placeholder="User first name" value="<?php echo getArrayVal($customer, 'fname'); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="control-label" for="lname">Last name</label>
                            <input type="text" name="lname" id="lname" class="form-control" placeholder="User last name" value="<?php echo getArrayVal($customer, 'lname'); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="pnumber">Phone number (Format 256777123456, 0777123456)</label>
                            <input type="text" name="pnumber" id="pnumber" class="form-control" placeholder="Phone number" value="<?php echo getArrayVal($customer, 'pnumber'); ?>">
                        </div>

                        <?php if ($USER->can_edit_system_users) { ?>

                            <div class="col-md-6">
                                <label class="control-label" for="uid">Added by</label>
                                <select name="uid" class="selecter_3" id="uid" data-selecter-options='{"cover":"true"}'>
                                    <option value="" selected="selected">-----</option>
                                    <?php foreach ($users as $user) {
                                        ?>
                                        <option value="<?php echo $user['idu'] ?>" <?php echo $customer['added_by'] === $user['idu'] ? 'selected="selected"' : '' ?>><?php echo $user['fname'] . " " . $user['lname'] ?></option>
                                    <?php }
                                    ?>
                                </select>                        
                            </div>

                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-12">        
                            <input type="submit" value="Update" name="submit" class="btn btn-primary pull-right">
                        </div>
                    </div>
            </div>  
        </div>
        </form>           
        <?php
    }
    ?>
    </div> 
    </div>
    <?php
}

function the_add_sale_form() {
    global $dbhandle, $USER;

    $attendants = array();
    $p = array();

    $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . TABLE_PREFIX . "users "
            . "LEFT JOIN user_groups ON group_id=id_group "
            . "WHERE active=1";

    $result = $dbhandle->RunQueryForResults($query);



    while ($row = $result->fetch_assoc()) {
        $attendants[] = $row;
    }

    $water_sources = array();

    if ($USER->can_edit_water_sources) {

        $p = $dbhandle->Fetch("water_sources", '*', null, 'water_source_id');
    } else {
        $query = "SELECT " . TABLE_PREFIX . "water_sources.water_source_name,water_source_location,id_water_source FROM " . TABLE_PREFIX . "water_source_caretakers," . TABLE_PREFIX . "water_sources WHERE uid=" . $USER->idu . " AND id_water_source=water_source_caretakers.water_source_id";
        //echo $query;
        $results = $dbhandle->RunQueryForResults($query);
        while ($row = $results->fetch_assoc()) {
            $p[] = $row;
        }
    }


    if (is_array($p) && !empty($p) && !isset($p[0])) {
        $water_sources[] = $p;
    } else {
        $water_sources = $p;
    }

    $water_users = array();

    $c = $dbhandle->Fetch("water_users", '*', null, 'fname');


    if ($USER->can_approve_treasurers_submissions) {
        //This is District Water Officer
        //Query to summarise from water sources
    } elseif ($USER->can_approve_attendants_submissions) {
        //This is the Water Board Treasurer
        //Query to summarise from attendatnts
    } elseif ($USER->can_submit_attendant_daily_sales) {
        //This is the Water User Committee Treasurer        
    } else {
        //This is the attendant
        $c = $dbhandle->Fetch("water_users", '*', array('added_by' => $USER->idu, 'marked_for_delete' => 0), 'fname');
    }



    if (is_array($c) && !empty($c) && !isset($c[0])) {
        $water_users[] = $c;
    } else {
        $water_users = $c;
    }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title text-center">Add sale</h3>
        </div>
        <div class="panel-body"> 
            <form method="post" action="?a=add-sale" autocomplete="off">     

                <div class="col-md-4 col-md-offset-4">                
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="water_source_id">Select the Water Source</label>
                            <select name="water_source_id" class="selecter_3" id="water_source_id" data-selecter-options='{"cover":"true"}'>
                                <?php foreach ($water_sources as $water_source) {
                                    ?>
                                    <option value="<?php echo $water_source['id_water_source'] ?>"><?php echo $water_source['water_source_name'] ?></option>
                                <?php }
                                ?>
                            </select>                        
                        </div>
                    </div>     
                    <?php if ($USER->can_edit_water_sources) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="sold_by">Select the attendant</label>
                                <select name="sold_by" class="selecter_3" id="sold_by" data-selecter-options='{"cover":"true"}'>
                                    <?php foreach ($attendants as $attendant) {
                                        ?>
                                        <option value="<?php echo $attendant['idu'] ?>"><?php echo $attendant['fname'] . " " . $attendant['lname'] ?></option>
                                    <?php }
                                    ?>
                                </select>                        
                            </div>
                        </div>   
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="sold_to">Select the customer</label>
                            <select name="sold_to" class="selecter_3" id="sold_to" data-selecter-options='{"cover":"true"}'>
                                <option value="0">Daily Sale</option>
                                <?php foreach ($water_users as $customer) {
                                    ?>
                                    <option value="<?php echo $customer['id_user'] ?>"><?php echo $customer['fname'] . " " . $customer['lname'] ?></option>
                                <?php }
                                ?>
                            </select>                        
                        </div>
                    </div>                    
                    <div class="row sale_ugx_div">
                        <div class="col-md-12">
                            <label class="control-label" for="sale_ugx">Sale UGX</label>
                            <input type="text" name="sale_ugx" id="water_source_location" class="form-control" placeholder="UGX" value="<?php echo getArrayVal($_POST, 'sale_ugx'); ?>">
                        </div>
                    </div>                
                    <!--div class="row">
                        <div class="col-md-12">
                            <label class="control-label" for="sale_date">Date</label>
                            <input type="text" name="sale_date" id="sale_date" class="form-control datetimepicker" data-date-format="DD/MM/YYYY hh:mm A" value="<?php echo getArrayVal($_POST, 'sale_ugx'); ?>">
                        </div>                    
                    </div-->
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" value="Add Sale" name="submit" class="btn btn-primary pull-right">
                        </div>                    
                    </div>
                </div>            
            </form>
        </div>  
    </div>



    <?php
}

function the_edit_sale_form() {
    global $dbhandle, $USER;
    $c = array();
    $attendants = array();
    $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . TABLE_PREFIX . "users "
            . "LEFT JOIN " . TABLE_PREFIX . "user_groups ON group_id=id_group "
            . "WHERE active=1";
    $result = $dbhandle->RunQueryForResults($query);

    while ($row = $result->fetch_assoc()) {
        $attendants[] = $row;
    }

    $water_sources = array();

    if ($USER->can_edit_water_sources) {

        $p = $dbhandle->Fetch("water_sources", '*', null, 'water_source_id');
    } else {
        $query = "SELECT " . TABLE_PREFIX . "water_sources.water_source_name,water_source_location,id_water_source FROM " . TABLE_PREFIX . "water_source_caretakers," . TABLE_PREFIX . "water_sources WHERE uid=" . $USER->idu . " AND id_water_source=" . TABLE_PREFIX . "water_source_caretakers.water_source_id";
        //echo $query;
        $results = $dbhandle->RunQueryForResults($query);
        while ($row = $results->fetch_assoc()) {
            $p[] = $row;
        }
    }



    if (is_array($p) && !empty($p) && !isset($p[0])) {
        $water_sources[] = $p;
    } else {
        $water_sources = $p;
    }

    $water_users = array();

    if ($USER->can_approve_treasurers_submissions) {
        //This is District Water Officer
        //Query to summarise from water sources
        $c = $dbhandle->Fetch("water_users", '*', array('marked_for_delete' => 0), 'fname');
    } elseif ($USER->can_approve_attendants_submissions) {
        //This is the Water Board Treasurer
        //Query to summarise from attendatnts
        $c = $dbhandle->Fetch("water_users", '*', array('marked_for_delete' => 0), 'fname');
    } elseif ($USER->can_submit_attendant_daily_sales) {
        //This is the Water User Committee Treasurer        
    } else {
        //This is the attendant
        $c = $dbhandle->Fetch("water_users", '*', array('added_by' => $USER->idu, 'marked_for_delete' => 0), 'fname');
    }

    if (is_array($c) && !empty($c) && !isset($c[0])) {
        $water_users[] = $c;
    } else {
        $water_users = $c;
    }

    $sale = array();
    $id_sale = getArrayVal($_GET, 'id');
    $query = "SELECT * FROM " . TABLE_PREFIX . "sales WHERE id_sale=$id_sale";
    $result = $dbhandle->RunQueryForResults($query);
    while ($row = $result->fetch_assoc()) {
        $sale = $row;
    }
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title text-center">Edit water sale</h3>
        </div>
        <div class="panel-body"> 
            <?php
            if (!isset($sale['id_sale'])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            Water sale does not exist
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <form method="post" action="?a=edit-sale&id=<?php echo $id_sale; ?>" autocomplete="off">
                    <div class="col-md-4 col-md-offset-4">       
                        <div class="row">
                            <div class="col-md-12">
                                <a href="?a=add-sale" class="btn btn-primary pull-right">Add Sale</a>
                            </div>                    
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_id">Select the Water Source ID</label>
                                <select name="water_source_id" class="selecter_3" id="water_source_id" data-selecter-options='{"cover":"true"}'>
                                    <?php foreach ($water_sources as $water_source) {
                                        ?>
                                        <option value="<?php echo $water_source['id_water_source'] ?>" <?php echo $sale['water_source_id'] === $water_source['id_water_source'] ? 'selected="selected"' : ''; ?>><?php echo $water_source['water_source_name'] ?></option>
                                    <?php }
                                    ?>
                                </select>                        
                            </div>
                        </div>
                        <?php if ($USER->can_edit_water_sources) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="sold_by">Select the attendant</label>
                                    <select name="sold_by" class="selecter_3" id="sold_by" data-selecter-options='{"cover":"true"}'>
                                        <?php foreach ($attendants as $attendant) {
                                            ?>
                                            <option value="<?php echo $attendant['idu'] ?>" <?php echo $sale['sold_by'] === $attendant['idu'] ? 'selected="selected"' : ''; ?>><?php echo $attendant['fname'] . " " . $attendant['lname'] ?></option>
                                        <?php }
                                        ?>
                                    </select>                        
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="sold_to">Select the customer</label>
                                <select name="sold_to" class="selecter_3" id="sold_to" data-selecter-options='{"cover":"true"}'>
                                    <option value="0" selected="selected">Daily Sale</option>
                                    <?php foreach ($water_users as $customer) {
                                        ?>
                                        <option value="<?php echo $customer['id_user'] ?>" <?php echo $sale['sold_to'] === $customer['id_user'] ? 'selected="selected"' : ''; ?> ><?php echo $customer['fname'] . " " . $customer['lname'] ?></option>
                                    <?php }
                                    ?>
                                </select>                        
                            </div>
                        </div>
                        <div class="row sale_ugx_div">
                            <div class="col-md-12">
                                <label class="control-label" for="sale_ugx">Sale UGX</label>
                                <input type="text" name="sale_ugx" id="water_source_location" class="form-control" placeholder="UGX" value="<?php echo floatval($sale['sale_ugx']) < 0 ? trim($sale['sale_ugx'], "-") : getArrayVal($sale, 'sale_ugx'); ?>">
                            </div>
                        </div>
                        <!--div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="sale_date">Date</label>
                                <input type="text" name="sale_date" id="sale_date" class="form-control datetimepicker" data-date-format="DD/MM/YYYY hh:mm A" value="<?php echo getArrayVal($sale, 'sale_date'); ?>">
                            </div>                    
                        </div-->
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Update Sale" name="submit" class="btn btn-primary pull-right">
                            </div>                    
                        </div>
                    </div>  
                </form>
            </div>
        </div>
        <?php
    }
}

function the_add_user_form() {
    global $dbhandle;
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Add system user</h3>
        </div>
        <div class="panel-body">
            <div class="row"> 
                <form method="post" action="?a=add-user" autocomplete="off"> 
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="email">First name</label>
                                <input type="text" name="fname" id="fname" class="form-control" placeholder="User first name" value="<?php echo getArrayVal($_POST, 'fname'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="email">Last name</label>
                                <input type="text" name="lname" id="lname" class="form-control" placeholder="User last name" value="<?php echo getArrayVal($_POST, 'lname'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="username">username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="username" value="<?php echo getArrayVal($_POST, 'username'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="email">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="email`" value="<?php echo getArrayVal($_POST, 'email'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">                               
                                <label class="control-label" for="active">Select the user's account status</label>
                                <select name="active" class="selecter_3" id="group_id" data-selecter-options='{"cover":"true"}'>
                                    <option value="0">Inactive</option>
                                    <option value="1">Active</option>
                                </select>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="pnumber">Phone number (Format 256777123456)</label>
                                <input type="text" name="pnumber" id="pnumber" class="form-control" placeholder="Phone number" value="<?php echo getArrayVal($_POST, 'pnumber'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $ug = $dbhandle->Fetch("user_groups", "*");
                                if (!isset($ug[0]) && is_array($ug)) {
                                    $user_groups[] = $ug;
                                } elseif (is_array($ug)) {
                                    $user_groups = $ug;
                                }
                                ?>
                                <label class="control-label" for="group_id">Select the user's group</label>
                                <select name="group_id" class="selecter_3" id="group_id" data-selecter-options='{"cover":"true"}'>
                                    <?php
                                    foreach ($user_groups as $group) {
                                        ?>
                                        <option value="<?php echo$group['id_group']; ?>"><?php echo$group['group_name']; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="password">Confirm Password</label>
                                <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">        
                                <input type="submit" value="Add user" name="submit" class="btn btn-primary pull-right">
                            </div>
                        </div>
                    </div>            
                </form>
            </div> 
        </div>
    </div>
    <?php
}

function the_edit_user_form() {
    global $dbhandle, $USER;
    $uid = getArrayVal($_GET, 'id');
    $query = "SELECT idu,group_id,username,pnumber,email,fname,lname,last_login,active FROM " . TABLE_PREFIX . "users WHERE idu=$uid";
    $result = $dbhandle->RunQueryForResults($query);
    $account = $result->fetch_assoc();
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Edit system user</h3>
        </div>
        <div class="panel-body">
            <?php
            if (!isset($account['idu'])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            User does not exist
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <form method="post" action="?a=edit-user&id=<?php echo $uid; ?>" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="?a=add-user" class="btn btn-primary pull-right">Add User</a>
                        </div>                    
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="email">First name</label>
                                    <input type="text" name="fname" id="fname" class="form-control" placeholder="User first name" value="<?php echo getArrayVal($account, 'fname'); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="email">Last name</label>
                                    <input type="text" name="lname" id="lname" class="form-control" placeholder="User last name" value="<?php echo getArrayVal($account, 'lname'); ?>">
                                </div>
                            </div>   
                            <?php if ($USER->can_edit_system_users) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="username">username</label>
                                        <input type="text" name="username" id="username" class="form-control" placeholder="username" value="<?php echo getArrayVal($account, 'username'); ?>">
                                    </div>
                                </div>     
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="email" value="<?php echo getArrayVal($account, 'email'); ?>">
                                </div>
                            </div>
                            <?php if ($USER->can_edit_system_users) { ?>
                                <div class="row">
                                    <div class="col-md-12">                               
                                        <label class="control-label" for="active">Select the user's account status</label>
                                        <select name="active" class="selecter_3" id="active" data-selecter-options='{"cover":"true"}'>
                                            <option value="0" <?php echo $account["active"] === "0" ? 'selected="selected"' : ''; ?>>Inactive</option>
                                            <option value="1" <?php echo $account["active"] === "1" ? 'selected="selected"' : ''; ?>>Active</option>
                                        </select>
                                    </div>
                                </div> 
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="pnumber">Phone number (Format 256777123456)</label>
                                    <input type="text" name="pnumber" id="pnumber" class="form-control" placeholder="Phone number" value="<?php echo getArrayVal($account, 'pnumber'); ?>">
                                </div>
                            </div>  
                            <?php if ($USER->can_edit_system_users) { ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php
                                        $ug = $dbhandle->Fetch("user_groups", "*");
                                        if (!isset($ug[0]) && is_array($ug)) {
                                            $user_groups[] = $ug;
                                        } elseif (is_array($ug)) {
                                            $user_groups = $ug;
                                        }
                                        ?>
                                        <label class="control-label" for="group_id">Select the user's group</label>
                                        <select name="group_id" class="selecter_3" id="group_id" data-selecter-options='{"cover":"true"}'>
                                            <?php
                                            foreach ($user_groups as $group) {
                                                ?>
                                                <option value="<?php echo$group['id_group']; ?>" <?php echo $account['group_id'] === $group['id_group'] ? 'selected="selected"' : ''; ?>><?php echo$group['group_name']; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>                
                            <?php } ?>
                            <div class = "row">
                                <div class = "col-md-12">
                                    <label class = "control-label" for = "password">New password</label>
                                    <input type = "password" name = "password" id = "password" class = "form-control" placeholder = "Password">
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "col-md-12">
                                    <label class = "control-label" for = "password">Confirm new password</label>
                                    <input type = "password" name = "cpassword" id = "cpassword" class = "form-control" placeholder = "Confirm Password">
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "col-md-12">
                                    <input type = "submit" value = "Update" name = "submit" class = "btn btn-primary pull-right">
                                </div>
                            </div>
                        </div>                
                    </div>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

function the_add_water_source_form() {
    global $dbhandle;
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Add new water source</h3>
        </div>
        <div class="panel-body">                     
            <form method="post" action="?a=add-water-source" autocomplete="off">                
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="email">The map below helps you pin the exact position of the water source</label>
                                <p>Feel free to type in the map coordinates if you're certain of them.</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="addRouteCanvas" style="width: 100%;
                                     height: 400px;"></div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="email">Fill in the water source details</label>
                                <p>If a water source has no name or ID, the system will allocate one automatically</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_id">Water Source ID</label>
                                <input type="text" name="water_source_id" id="water_source_id" class="form-control" placeholder="Water Source ID" value="<?php echo getArrayVal($_POST, 'water_source_id'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_name">Water Source Name</label>
                                <input type="text" name="water_source_name" id="water_source_name" class="form-control" placeholder="Water Source Name" value="<?php echo getArrayVal($_POST, 'water_source_name'); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_location">Water Source Location</label>
                                <input type="text" name="water_source_location" id="water_source_location" class="form-control" placeholder="Water Source Location" value="<?php echo getArrayVal($_POST, 'water_source_location'); ?>">
                            </div>
                        </div>                
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_coordinates">Water Source Coordinates</label>
                                <input type="text" name="water_source_coordinates" id="water_source_coordinates" class="form-control" placeholder="Water Source Coordinates" value="<?php echo getArrayVal($_POST, 'water_source_coordinates'); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="monthly_charges">Monthly charges</label>
                                <input type="text" name="monthly_charges" id="monthly_charges" class="form-control" placeholder="Percentage Submitted" value="<?php echo getArrayVal($_POST, 'monthly_charges'); ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="percentage_saved">Percentage Submitted</label>
                                <input type="text" name="percentage_saved" id="percentage_saved" class="form-control" placeholder="Percentage Submitted" value="<?php echo getArrayVal($_POST, 'percentage_saved'); ?>">
                            </div>
                        </div>

                        <hr class="dashed">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_name">Care Takers</label>
                                <p class="help-block">Select where appropriate to assign.</p>
                            </div>
                        </div>
                        <?php
                        $attendants = array();
                        $attendants_ids = array();
                        $a_ids = array();

                        $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM users "
                                . "LEFT JOIN user_groups ON group_id=id_group "
                                . "WHERE active=1 ORDER BY fname";

                        $result = $dbhandle->RunQueryForResults($query);

                        while ($row = $result->fetch_assoc()) {
                            $attendants[] = $row;
                        }
                        if (!empty($attendants)) {
                            foreach ($attendants as $attendant) {
                                ?>                            
                                <div class="checkbox">
                                    <input type="checkbox" name="attendants[]" value="<?php echo $attendant['idu']; ?>" id="attendants_<?php echo $attendant['idu']; ?>" <?php echo in_array($attendant['idu'], $attendants_ids) ? 'checked="checked"' : '' ?>>
                                    <label for="attendants_<?php echo $attendant['idu']; ?>"><?php echo $attendant['fname'] . " " . $attendant['lname']; ?></label>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        No attendants have been added so far. Click <strong><a href="?a=add-user">here</a></strong>
                                        to add an attendant.
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>   
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="water_source_name">Treasurers</label>
                                <p class="help-block">Select where appropriate to assign.</p>
                            </div>
                        </div>
                        <?php
                        $treasurers = array();

                        $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM users "
                                . "LEFT JOIN user_groups ON group_id=id_group "
                                . "WHERE active=1 ORDER BY fname";

                        $result = $dbhandle->RunQueryForResults($query);


                        while ($row = $result->fetch_assoc()) {
                            $treasurers[] = $row;
                        }
                        if (!empty($treasurers)) {
                            foreach ($treasurers as $treasurer) {
                                ?>                            
                                <div class="checkbox">
                                    <input type="checkbox" name="treasurers[]" value="<?php echo $treasurer['idu']; ?>" id="treasurer_<?php echo $treasurer['idu']; ?>">
                                    <label for="treasurer_<?php echo $treasurer['idu']; ?>"><?php echo $treasurer['fname'] . " " . $treasurer['lname']; ?></label>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        No treasurers have been added so far. Click <strong><a href="?a=add-user">here</a></strong>
                                        to add an treasurer.
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>    
                        <div class="row">
                            <div class="col-md-12">        
                                <input type="submit" value="Add Water Source" name="submit" class="btn btn-primary pull-right">
                            </div>
                        </div>
                    </div>
                </div>   
            </form>  
        </div>
    </div>
    <?php
}

function the_edit_water_source_form() {
    global $dbhandle;
    $water_source = array();
    $id_water_source = getArrayVal($_GET, 'id');
    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
    $result = $dbhandle->RunQueryForResults($query);
    if (!empty($result)) {
        $water_source = $result->fetch_assoc();
    }
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Edit water source details</h3>
        </div>
        <div class="panel-body"> 
            <div class="row">
                <div class="col-md-12">        
                    <a href="?a=add-water-source" class="btn btn-primary pull-right">Add water source</a>                       
                </div>
            </div>
            <hr>
            <?php
            if (!isset($water_source['id_water_source'])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            Water source does not exist
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="row"> 
                    <form method="post" action="?a=edit-water-source&id=<?php echo $id_water_source; ?>" autocomplete="off"> 
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="email">The map below helps you pin the exact position of the water source</label>
                                    <p>Feel free to type in the map coordinates if you're certain of them.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="addRouteCanvas" style="width: 100%;
                                         height: 400px;"></div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="email">Fill in the water source details</label>
                                    <p>If a water source has no name or ID, the system will allocate one automatically</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_id">Water Source ID</label>
                                    <input type="text" name="water_source_id" id="water_source_id" class="form-control" placeholder="Water Source ID" value="<?php echo getArrayVal($water_source, 'water_source_id'); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_name">Water Source Name</label>
                                    <input type="text" name="water_source_name" id="water_source_name" class="form-control" placeholder="Water Source Name" value="<?php echo getArrayVal($water_source, 'water_source_name'); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_location">Water Source Location</label>
                                    <input type="text" name="water_source_location" id="water_source_location" class="form-control" placeholder="Water Source Location" value="<?php echo getArrayVal($water_source, 'water_source_location'); ?>">
                                </div>
                            </div>                
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_coordinates">Water Source Coordinates</label>
                                    <input type="text" name="water_source_coordinates" id="water_source_coordinates" class="form-control" placeholder="Water Source Coordinates" value="<?php echo getArrayVal($water_source, 'water_source_coordinates'); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="monthly_charges">Monthly charges</label>
                                    <input type="text" name="monthly_charges" id="monthly_charges" class="form-control" placeholder="Percentage Submitted" value="<?php echo getArrayVal($water_source, 'monthly_charges'); ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="percentage_saved">Percentage Submitted</label>
                                    <input type="text" name="percentage_saved" id="percentage_saved" class="form-control" placeholder="Percentage Submitted" value="<?php echo getArrayVal($water_source, 'percentage_saved'); ?>">
                                </div>
                            </div>

                            <hr class="dashed">

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_name">Care Takers</label>
                                    <p class="help-block">Select where appropriate to assign.</p>
                                </div>
                            </div>
                            <?php
                            $attendants = array();
                            $attendants_ids = array();

                            $a_ids = $dbhandle->Fetch("water_source_caretakers", "uid", array('water_source_id' => $id_water_source), 'water_source_id');
                            if (is_array($a_ids) && !empty($a_ids) && !isset($a_ids[0])) {
                                $a_ids = array($a_ids);
                            }

                            if (is_array($a_ids)) {
                                foreach ($a_ids as $a_id) {
                                    $attendants_ids[] = $a_id['uid'];
                                }
                            }

                            $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . TABLE_PREFIX . "users "
                                    . "LEFT JOIN user_groups ON group_id=id_group "
                                    . "WHERE active=1 ORDER BY fname";

                            $result = $dbhandle->RunQueryForResults($query);

                            while ($row = $result->fetch_assoc()) {
                                $attendants[] = $row;
                            }
                            if (!empty($attendants)) {
                                foreach ($attendants as $attendant) {
                                    ?>                            
                                    <div class="checkbox">
                                        <input type="checkbox" name="attendants[]" value="<?php echo $attendant['idu']; ?>" id="attendants_<?php echo $attendant['idu']; ?>" <?php echo in_array($attendant['idu'], $attendants_ids) ? 'checked="checked"' : '' ?>>
                                        <label for="attendants_<?php echo $attendant['idu']; ?>"><?php echo $attendant['fname'] . " " . $attendant['lname']; ?></label>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning">
                                            No attendants have been added so far. Click <strong><a href="?a=add-user">here</a></strong>
                                            to add an attendant.
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>   

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_name">Treasurers</label>
                                    <p class="help-block">Select where appropriate to assign.</p>
                                </div>
                            </div>
                            <?php
                            $treasurers = array();
                            $treasurers_ids = array();

                            $a_ids = $dbhandle->Fetch("water_source_treasurers", "uid", array('water_source_id' => $id_water_source));
                            if (is_array($a_ids) && !empty($a_ids) && !isset($a_ids[0])) {
                                $a_ids = array($a_ids);
                            }

                            if (is_array($a_ids)) {
                                foreach ($a_ids as $a_id) {
                                    $treasurers_ids[] = $a_id['uid'];
                                }
                            }

                            $query = "SELECT idu,group_id,fname,lname,can_add_water_users FROM " . TABLE_PREFIX . "users "
                                    . "LEFT JOIN user_groups ON group_id=id_group "
                                    . "WHERE active=1 ORDER BY fname";

                            $result = $dbhandle->RunQueryForResults($query);

                            while ($row = $result->fetch_assoc()) {
                                $treasurers[] = $row;
                            }
                            if (!empty($treasurers)) {
                                foreach ($treasurers as $treasurer) {
                                    ?>                            
                                    <div class="checkbox">
                                        <input type="checkbox" name="treasurers[]" value="<?php echo $treasurer['idu']; ?>" id="treasurers_<?php echo $treasurer['idu']; ?>" <?php echo in_array($treasurer['idu'], $treasurers_ids) ? 'checked="checked"' : '' ?>>
                                        <label for="treasurers_<?php echo $treasurer['idu']; ?>"><?php echo $treasurer['fname'] . " " . $treasurer['lname']; ?></label>
                                    </div>
                                    <?php
                                }
                            } else {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-warning">
                                            No treasurers have been added so far. Click <strong><a href="?a=add-user">here</a></strong>
                                            to add an treasurer.
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>   

                            <div class="row">
                                <div class="col-md-12">        
                                    <input type="submit" value="Update" name="submit" class="btn btn-primary pull-right">
                                </div>
                            </div>
                        </div>            
                    </form>
                </div> 
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

function the_compose_sms() {
    global $dbhandle, $USER;
    //var_dump($USER);
    $water_users = array();
    $system_users = array();

    if ($USER->can_view_water_source_savings) {
        $query = "SELECT idu,fname,lname FROM " . TABLE_PREFIX . "users WHERE pnumber <>'' ORDER BY fname";
        $result = $dbhandle->RunQueryForResults($query);

        while ($row = $result->fetch_assoc()) {
            $system_users[] = $row;
        }
    }



    if ($USER->can_view_water_source_savings) {
        $query = "SELECT id_user,fname,lname FROM " . TABLE_PREFIX . "water_users WHERE pnumber <>'' ORDER BY fname";
    } else {
        $query = "SELECT id_user,fname,lname FROM " . TABLE_PREFIX . "water_users WHERE pnumber <>'' AND added_by=" . $USER->idu . " ORDER BY fname";
    }

    $result = $dbhandle->RunQueryForResults($query);

    while ($row = $result->fetch_assoc()) {
        $water_users[] = $row;
    }
    // $water_users = array_merge($water_users, $water_users);
    //var_dump($water_users);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Compose SMS</h3>
        </div>
        <div class="panel-body"> 
            <!--div class="row">
                <div class="col-md-12">        
                    <a href="?a=add-water-source" class="btn btn-primary pull-right">Add water source</a>                       
                </div>
            </div>
            <hr-->
            <?php
            if (empty($water_users) && empty($system_users)) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            You don't have users with phone numbers
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>

                <form method="post" action="?a=send-sms" autocomplete="off">
                    <div class="row"> 
                        <div class="col-md-12">

                            <?php
                            if (!empty($system_users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Select system users</h5>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    foreach ($system_users as $system_user) {
                                        ?>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <input type="checkbox" id="system_user_<?php echo $system_user['idu']; ?>" name="system_users_recepients[]" value="<?php echo $system_user['idu']; ?>">
                                                <label for="system_user_<?php echo $system_user['idu']; ?>"><?php echo $system_user['fname']; ?> <?php echo $system_user['lname']; ?></label>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>                                
                                <?php
                            }
                            ?> 
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-12">
                            <?php
                            if (!empty($water_users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Select water users</h5>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    foreach ($water_users as $water_user) {
                                        ?>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <input type="checkbox" id="water_user_<?php echo $water_user['id_user']; ?>" name="water_user_recepients[]" value="<?php echo $water_user['id_user']; ?>">
                                                <label for="water_user_<?php echo $water_user['id_user']; ?>"><?php echo $water_user['fname']; ?> <?php echo $water_user['lname']; ?></label>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Type your sms</h5>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea class="form-control" rows="3" style="resize: none;" name="msg_content" id="msg_content"></textarea>
                        </div>
                    </div>          
                    <div class="row">
                        <div class="col-md-12">        
                            <input type="submit" value="Send" name="submit" class="btn btn-primary pull-right">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <?php } ?>
    <?php
}

function the_compose_notification() {
    global $dbhandle, $USER;
    //var_dump($USER);
    $water_users = array();
    $system_users = array();

    if ($USER->can_view_water_source_savings) {
        $query = "SELECT idu,fname,lname FROM " . TABLE_PREFIX . "users WHERE gcm_regid <>''";
        $result = $dbhandle->RunQueryForResults($query);

        while ($row = $result->fetch_assoc()) {
            $system_users[] = $row;
        }
    }

    //var_dump($water_users);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Compose Notification</h3>
        </div>
        <div class="panel-body"> 
            <!--div class="row">
                <div class="col-md-12">        
                    <a href="?a=add-water-source" class="btn btn-primary pull-right">Add water source</a>                       
                </div>
            </div>
            <hr-->
            <?php
            if (empty($water_users) && empty($system_users)) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            Devices are not yet registered on the Google Cloud Messaging
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>

                <form method="post" action="?a=send-notification" autocomplete="off">
                    <div class="row"> 
                        <div class="col-md-12">

                            <?php
                            if (!empty($system_users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Select system users</h5>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    foreach ($system_users as $system_user) {
                                        ?>
                                        <div class="col-md-3">
                                            <div class="checkbox">
                                                <input type="checkbox" id="system_user_<?php echo $system_user['idu']; ?>" name="system_users_recepients[]" value="<?php echo $system_user['idu']; ?>">
                                                <label for="system_user_<?php echo $system_user['idu']; ?>"><?php echo $system_user['fname']; ?> <?php echo $system_user['lname']; ?></label>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>                                
                                <?php
                            }
                            ?> 
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-12">

                            <?php
                            if (!empty($water_users)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Select water users</h5>
                                        <hr>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                    foreach ($water_users as $water_user) {
                                        ?>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <input type="checkbox" id="water_user_<?php echo $water_user['id_user']; ?>" name="water_user_recepients[]" value="<?php echo $water_user['id_user']; ?>">
                                                <label for="water_user_<?php echo $water_user['id_user']; ?>"><?php echo $water_user['fname']; ?> <?php echo $water_user['lname']; ?></label>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Type your message</h5>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea class="form-control" rows="3" style="resize: none;" name="msg_content" id="msg_content"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">        
                            <input type="submit" value="Send" name="submit" class="btn btn-primary pull-right">
                        </div>
                    </div>
                </form>
            <?php } 
        }
        