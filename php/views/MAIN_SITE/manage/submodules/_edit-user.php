<?php global $account; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Edit User</h3>
                            <p>Update a system user's account</p>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="" autocomplete="off">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="email">First name</label>
                                                <input type="text" name="fname" id="fname" class="form-control" placeholder="User first name" value="<?php echo $App->sanitizeVar($account, 'fname'); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="email">Last name</label>
                                                <input type="text" name="lname" id="lname" class="form-control" placeholder="User last name" value="<?php echo $App->sanitizeVar($account, 'lname'); ?>">
                                            </div>
                                        </div>   
                                        <?php if ($App->can_edit_system_users) { ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="control-label" for="username">Username</label>
                                                    <input type="text" name="username" id="username" class="form-control" placeholder="username" value="<?php echo $App->sanitizeVar($account, 'username'); ?>">
                                                </div>
                                            </div>     
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="email">Email</label>
                                                <input type="text" name="email" id="email" class="form-control" placeholder="email" value="<?php echo $App->sanitizeVar($account, 'email'); ?>">
                                            </div>
                                        </div>
                                        <?php if ($App->can_edit_system_users) { ?>
                                            <div class="row">
                                                <div class="col-md-12">                               
                                                    <label class="control-label" for="active">Select the user's account status</label>
                                                    <select name="active" class="form-control selectpicker" id="active" data-selecter-options='{"cover":"true"}'>
                                                        <option value="0" <?php echo $account["active"] == "0" ? 'selected="selected"' : ''; ?>>Inactive</option>
                                                        <option value="1" <?php echo $account["active"] == "1" ? 'selected="selected"' : ''; ?>>Active</option>
                                                    </select>
                                                </div>
                                            </div> 
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="pnumber">Phone number (Format 256777123456)</label>
                                                <input type="text" name="pnumber" id="pnumber" class="form-control" placeholder="Phone number" value="<?php echo $App->sanitizeVar($account, 'pnumber'); ?>">
                                            </div>
                                        </div>  
                                        <?php if ($App->can_edit_system_users) { ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                    $user_groups = $App->con->get('user_groups');
                                                    ?>
                                                    <label class="control-label" for="group_id">Select the user's group</label>
                                                    <select name="group_id" class="form-control selectpicker-with-search" id="group_id" data-selecter-options='{"cover":"true"}'>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="password">New password</label>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="password">Confirm new password</label>
                                                <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">
                                                <input type="submit" value="Update" name="submit" class="btn btn-primary pull-right">
                                            </div>
                                        </div>
                                    </div>                
                                </div>
                            </form>
                        </div>                       
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
