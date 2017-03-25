<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Add User Group</h3>
                            <p>Create a user access group</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            $group = array(
                                'group_name' => $App->postValue('group_name'),
                                'group_is_enabled' => $App->postValue('group_is_enabled'),
                                'can_access_system_config' => $App->postValue('can_access_system_config'),
                                'can_receive_emails' => $App->postValue('can_receive_emails'),
                                'can_access_app' => $App->postValue('can_access_app'),
                                'can_send_sms' => $App->postValue('can_send_sms'),
                                'can_receive_push_notifications' => $App->postValue('can_receive_push_notifications'),
                                'can_send_sms' => $App->postValue('can_send_sms'),
                                'can_submit_attendant_daily_sales' => $App->postValue('can_submit_attendant_daily_sales'),
                                'can_approve_attendants_submissions' => $App->postValue('can_approve_attendants_submissions'),
                                'can_approve_treasurers_submissions' => $App->postValue('can_approve_treasurers_submissions'),
                                'can_cancel_attendant_daily_sales' => $App->postValue('can_cancel_attendant_daily_sales'),
                                'can_cancel_attendants_submissions' => $App->postValue('can_cancel_attendants_submissions'),
                                'can_cancel_treasurers_submissions' => $App->postValue('can_cancel_treasurers_submissions'),
                                'can_add_water_users' => $App->postValue('can_add_water_users'),
                                'can_edit_water_users' => $App->postValue('can_edit_water_users'),
                                'can_delete_water_users' => $App->postValue('can_delete_water_users'),
                                'can_view_water_users' => $App->postValue('can_view_water_users'),
                                'can_add_sales' => $App->postValue('can_add_sales'),
                                'can_edit_sales' => $App->postValue('can_edit_sales'),
                                'can_delete_sales' => $App->postValue('can_delete_sales'),
                                'can_view_sales' => $App->postValue('can_view_sales'),
                                'can_view_personal_savings' => $App->postValue('can_view_personal_savings'),
                                'can_view_water_source_savings' => $App->postValue('can_view_water_source_savings'),
                                'can_add_water_sources' => $App->postValue('can_add_water_sources'),
                                'can_edit_water_sources' => $App->postValue('can_edit_water_sources'),
                                'can_delete_water_sources' => $App->postValue('can_delete_water_sources'),
                                'can_view_water_sources' => $App->postValue('can_view_water_sources'),
                                'can_add_repair_types' => $App->postValue('can_add_repair_types'),
                                'can_edit_repair_types' => $App->postValue('can_edit_repair_types'),
                                'can_delete_repair_types' => $App->postValue('can_delete_repair_types'),
                                'can_view_repair_types' => $App->postValue('can_view_repair_types'),
                                'can_add_expenses' => $App->postValue('can_add_expenses'),
                                'can_edit_expenses' => $App->postValue('can_edit_expenses'),
                                'can_delete_expenses' => $App->postValue('can_delete_expenses'),
                                'can_view_expenses' => $App->postValue('can_view_expenses'),
                                'can_add_system_users' => $App->postValue('can_add_system_users'),
                                'can_edit_system_users' => $App->postValue('can_edit_system_users'),
                                'can_delete_system_users' => $App->postValue('can_delete_system_users'),
                                'can_view_system_users' => $App->postValue('can_view_system_users'),
                                'can_add_user_permissions' => $App->postValue('can_add_user_permissions'),
                                'can_edit_user_permissions' => $App->postValue('can_edit_user_permissions'),
                                'can_delete_user_permissions' => $App->postValue('can_delete_user_permissions'),
                                'can_view_user_permissions' => $App->postValue('can_view_user_permissions')
                            );
                            ?>
                            <form method="post" action="" autocomplete="off"> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label" for="group_name">Group name</label>
                                        <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Group name" value="<?php echo $App->postValue('group_name'); ?>">
                                    </div>
                                </div>
                                <hr class="dashed">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label" for="group_is_enabled">Active</label>
                                        <select name="group_is_enabled" class="form-control selectpicker" id="group_is_enabled">
                                            <option value="0" <?php echo $group["group_is_enabled"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["group_is_enabled"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label" for="can_access_system_config">Can access System settings</label>
                                        <select name="can_access_system_config" class="form-control selectpicker" id="can_access_system_config">
                                            <option value="0" <?php echo $group["can_access_system_config"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_access_system_config"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                </div> 
                                <hr class="dashed">
                                <div class="row">  
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_receive_emails">Emails</label>
                                        <select name="can_receive_emails" class="form-control selectpicker" id="can_receive_emails">
                                            <option value="0" <?php echo $group["can_receive_emails"] == "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                            <option value="1" <?php echo $group["can_receive_emails"] == "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_access_app">App Access</label>
                                        <select name="can_access_app" class="form-control selectpicker" id="can_access_app">
                                            <option value="0" <?php echo $group["can_access_app"] == "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                            <option value="1" <?php echo $group["can_access_app"] == "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_send_sms">SMS Messages</label>
                                        <select name="can_send_sms" class="form-control selectpicker" id="can_send_sms">
                                            <option value="0" <?php echo $group["can_send_sms"] == "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                            <option value="1" <?php echo $group["can_send_sms"] == "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_receive_push_notifications">Push notifications</label>
                                        <select name="can_receive_push_notifications" class="form-control selectpicker" id="can_receive_push_notifications">
                                            <option value="0" <?php echo $group["can_receive_push_notifications"] == "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                            <option value="1" <?php echo $group["can_receive_push_notifications"] == "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                                        </select>                        
                                    </div>                                
                                </div> 
                                <hr class="dashed">
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <label class="control-label" for="can_submit_attendant_daily_sales">Can submit daily attendant sales</label>
                                        <select name="can_submit_attendant_daily_sales" class="form-control selectpicker" id="can_submit_attendant_daily_sales">
                                            <option value="0" <?php echo $group["can_submit_attendant_daily_sales"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_submit_attendant_daily_sales"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 

                                    <div class="col-md-4">
                                        <label class="control-label" for="can_approve_attendants_submissions">Can approve attendants submissions</label>
                                        <select name="can_approve_attendants_submissions" class="form-control selectpicker" id="can_approve_attendants_submissions">
                                            <option value="0" <?php echo $group["can_approve_attendants_submissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_approve_attendants_submissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label" for="can_approve_treasurers_submissions">Can approve treasurers submissions</label>
                                        <select name="can_approve_treasurers_submissions" class="form-control selectpicker" id="can_approve_treasurers_submissions">
                                            <option value="0" <?php echo $group["can_approve_treasurers_submissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_approve_treasurers_submissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label" for="can_cancel_attendant_daily_sales">Can cancel daily attendant sales</label>
                                        <select name="can_cancel_attendant_daily_sales" class="form-control selectpicker" id="can_cancel_attendant_daily_sales">
                                            <option value="0" <?php echo $group["can_cancel_attendant_daily_sales"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_cancel_attendant_daily_sales"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label" for="can_cancel_attendants_submissions">Can cancel attendants submissions</label>
                                        <select name="can_cancel_attendants_submissions" class="form-control selectpicker" id="can_cancel_attendants_submissions">
                                            <option value="0" <?php echo $group["can_cancel_attendants_submissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_cancel_attendants_submissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>


                                    <div class="col-md-4">
                                        <label class="control-label" for="can_cancel_treasurers_submissions">Can cancel treasurers submissions</label>
                                        <select name="can_cancel_treasurers_submissions" class="form-control selectpicker" id="can_cancel_treasurers_submissions">
                                            <option value="0" <?php echo $group["can_cancel_treasurers_submissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_cancel_treasurers_submissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                </div>
                                <hr class="dashed">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_water_users">Can add water users</label>
                                        <select name="can_add_water_users" class="form-control selectpicker" id="can_add_water_users">
                                            <option value="0" <?php echo $group["can_add_water_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_water_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_water_users">Can edit water users</label>
                                        <select name="can_edit_water_users" class="form-control selectpicker" id="can_edit_water_users">
                                            <option value="0" <?php echo $group["can_edit_water_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_water_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_water_users">Can delete water users</label>
                                        <select name="can_delete_water_users" class="form-control selectpicker" id="can_delete_water_users">
                                            <option value="0" <?php echo $group["can_delete_water_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_water_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_water_users">Can view water users</label>
                                        <select name="can_view_water_users" class="form-control selectpicker" id="can_view_water_users">
                                            <option value="0" <?php echo $group["can_view_water_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_water_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_sales">Can add sales</label>
                                        <select name="can_add_sales" class="form-control selectpicker" id="can_add_sales">
                                            <option value="0" <?php echo $group["can_add_sales"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_sales"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_sales">Can edit sales</label>
                                        <select name="can_edit_sales" class="form-control selectpicker" id="can_edit_sales">
                                            <option value="0" <?php echo $group["can_edit_sales"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_sales"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_sales">Can delete sales</label>
                                        <select name="can_delete_sales" class="form-control selectpicker" id="can_delete_sales">
                                            <option value="0" <?php echo $group["can_delete_sales"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_sales"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_sales">Can view sales</label>
                                        <select name="can_view_sales" class="form-control selectpicker" id="can_view_sales">
                                            <option value="0" <?php echo $group["can_view_sales"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_sales"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">

                                <div class="row">

                                    <div class="col-md-6">
                                        <label class="control-label" for="can_view_personal_savings">Can view personal savings</label>
                                        <select name="can_view_personal_savings" class="form-control selectpicker" id="can_view_personal_savings">
                                            <option value="0" <?php echo $group["can_view_personal_savings"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_personal_savings"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 

                                    <div class="col-md-6">
                                        <label class="control-label" for="can_view_water_source_savings">Can view water source savings</label>
                                        <select name="can_view_water_source_savings" class="form-control selectpicker" id="can_view_water_source_savings">
                                            <option value="0" <?php echo $group["can_view_water_source_savings"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_water_source_savings"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 

                                </div>
                                <hr class="dashed">


                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_water_sources">Can add water sources</label>
                                        <select name="can_add_water_sources" class="form-control selectpicker" id="can_add_water_sources">
                                            <option value="0" <?php echo $group["can_add_water_sources"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_water_sources"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_water_sources">Can edit water sources</label>
                                        <select name="can_edit_water_sources" class="form-control selectpicker" id="can_edit_water_sources">
                                            <option value="0" <?php echo $group["can_edit_water_sources"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_water_sources"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_water_sources">Can delete water sources</label>
                                        <select name="can_delete_water_sources" class="form-control selectpicker" id="can_delete_water_sources">
                                            <option value="0" <?php echo $group["can_delete_water_sources"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_water_sources"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_water_sources">Can view water sources</label>
                                        <select name="can_view_water_sources" class="form-control selectpicker" id="can_view_water_sources">
                                            <option value="0" <?php echo $group["can_view_water_sources"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_water_sources"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_repair_types">Can add repair types</label>
                                        <select name="can_add_repair_types" class="form-control selectpicker" id="can_add_repair_types">
                                            <option value="0" <?php echo $group["can_add_repair_types"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_repair_types"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_repair_types">Can edit repair types</label>
                                        <select name="can_edit_repair_types" class="form-control selectpicker" id="can_edit_repair_types">
                                            <option value="0" <?php echo $group["can_edit_repair_types"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_repair_types"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_repair_types">Can delete repair types</label>
                                        <select name="can_delete_repair_types" class="form-control selectpicker" id="can_delete_repair_types">
                                            <option value="0" <?php echo $group["can_delete_repair_types"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_repair_types"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_repair_types">Can view repair types</label>
                                        <select name="can_view_repair_types" class="form-control selectpicker" id="can_view_repair_types">
                                            <option value="0" <?php echo $group["can_view_repair_types"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_repair_types"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_expenses">Can add expenses</label>
                                        <select name="can_add_expenses" class="form-control selectpicker" id="can_add_expenses">
                                            <option value="0" <?php echo $group["can_add_expenses"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_expenses"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_expenses">Can edit expenses</label>
                                        <select name="can_edit_expenses" class="form-control selectpicker" id="can_edit_expenses">
                                            <option value="0" <?php echo $group["can_edit_expenses"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_expenses"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_expenses">Can delete expenses</label>
                                        <select name="can_delete_expenses" class="form-control selectpicker" id="can_delete_expenses">
                                            <option value="0" <?php echo $group["can_delete_expenses"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_expenses"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_expenses">Can view expenses</label>
                                        <select name="can_view_expenses" class="form-control selectpicker" id="can_view_expenses">
                                            <option value="0" <?php echo $group["can_view_expenses"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_expenses"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_system_users">Can add system users</label>
                                        <select name="can_add_system_users" class="form-control selectpicker" id="can_add_system_users">
                                            <option value="0" <?php echo $group["can_add_system_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_system_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_system_users">Can edit system users</label>
                                        <select name="can_edit_system_users" class="form-control selectpicker" id="can_edit_system_users">
                                            <option value="0" <?php echo $group["can_edit_system_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_system_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_system_users">Can delete system users</label>
                                        <select name="can_delete_system_users" class="form-control selectpicker" id="can_delete_system_users">
                                            <option value="0" <?php echo $group["can_delete_system_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_system_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_system_users">Can view system users</label>
                                        <select name="can_view_system_users" class="form-control selectpicker" id="can_view_system_users">
                                            <option value="0" <?php echo $group["can_view_system_users"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_system_users"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">


                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_add_user_permissions">Can add user permissions</label>
                                        <select name="can_add_user_permissions" class="form-control selectpicker" id="can_add_user_permissions">
                                            <option value="0" <?php echo $group["can_add_user_permissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_add_user_permissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_edit_user_permissions">Can edit user permissions</label>
                                        <select name="can_edit_user_permissions" class="form-control selectpicker" id="can_edit_user_permissions">
                                            <option value="0" <?php echo $group["can_edit_user_permissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_edit_user_permissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_delete_user_permissions">Can delete user permissions</label>
                                        <select name="can_delete_user_permissions" class="form-control selectpicker" id="can_delete_user_permissions">
                                            <option value="0" <?php echo $group["can_delete_user_permissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_delete_user_permissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div> 
                                    <div class="col-md-3">
                                        <label class="control-label" for="can_view_user_permissions">Can view user permissions</label>
                                        <select name="can_view_user_permissions" class="form-control selectpicker" id="can_view_user_permissions">
                                            <option value="0" <?php echo $group["can_view_user_permissions"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                            <option value="1" <?php echo $group["can_view_user_permissions"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                                        </select>                        
                                    </div>  
                                </div>
                                <hr class="dashed">

                                <div class="row">
                                    <div class="col-md-12">        
                                        <input type="submit" value="Add group" name="submit" class="btn btn-primary pull-right">
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
