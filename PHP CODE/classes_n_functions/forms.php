<?php

function the_config_form() {
    global $SYSTEM_CONFIG;

    $tab = getArrayVal($_GET, 'tab');
    $index = getArrayVal($_GET, 'i');
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <ul id="settingsTab" class="nav nav-tabs nav-justified">
                    <li <?php echo empty($tab) ? 'class="active"' : ''; ?>>
                        <a href="#seo" data-toggle="tab">SEO</a>
                    </li>                    
                    <li <?php echo $tab === 'configuration' ? 'class="active"' : ''; ?>>
                        <a href="#configuration" data-toggle="tab">Configuration</a>
                    </li>
                    <li class="dropdown <?php echo $tab === 'messages-and-templates' ? 'active' : ''; ?>">
                        <a href="#" id="messages-and-templates-tab" class="dropdown-toggle" data-toggle="dropdown">Message Settings & Templates <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="messages-and-templates-tab">
                            <li class="<?php echo $tab === 'messages-and-templates' && empty($index) ? 'active' : ''; ?>"><a href="#message-templates" tabindex="-1" data-toggle="tab">Message Templates</a></li>
                            <li class="<?php echo $tab === 'messages-and-templates' && $index === '1' ? 'active' : ''; ?>"><a href="#message-settings" tabindex="-1" data-toggle="tab">Message Settings</a></li>                                
                        </ul>
                    </li>
                </ul>
                <div id="settingsTabContent" class="tab-content">
                    <div class="tab-pane fade <?php echo empty($tab) ? 'active in' : ''; ?>" id="seo">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Search Engine Optimisation</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">                                    
                                <form method="post" action="?a=configurations&tab=seo" autocomplete="off">  
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <strong>Site Indexing</strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <input type="radio" name="robots" value="index-follow" id="index-follow" <?php echo $SYSTEM_CONFIG["robots"] === "index-follow" ? 'checked="checked"' : ''; ?>>
                                                <label for="index-follow">Index, Follow</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <input type="radio" name="robots" value="index-nofollow" id="index-nofollow" <?php echo $SYSTEM_CONFIG["robots"] === "index-nofollow" ? 'checked="checked"' : ''; ?>>
                                                <label for="index-nofollow">Index, Nofollow</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <input type="radio" name="robots" value="noindex-follow" id="noindex-follow" <?php echo $SYSTEM_CONFIG["robots"] === "noindex-follow" ? 'checked="checked"' : ''; ?>>
                                                <label for="noindex-follow">No Index, Follow</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="radio">
                                                <input type="radio" name="robots" value="noindex-nofollow" id="noindex-nofollow" <?php echo $SYSTEM_CONFIG["robots"] === "noindex-nofollow" ? 'checked="checked"' : ''; ?>>
                                                <label for="noindex-nofollow">No Index, No Follow</label>
                                            </div>
                                        </div>
                                    </div>                                        
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <strong><label for="site_desc">Site Description</label></strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <textarea name="site_desc" id="site_desc" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'site_desc'); ?></textarea>
                                        </div>                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                                <strong>
                                                    <label for="site_keywords">Site Keywords</label>
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">                                           
                                            <textarea name="site_keywords" id="site_keywords" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'site_keywords'); ?></textarea>
                                        </div>                                       
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>                   
                    <div class="tab-pane fade <?php echo $tab === 'configuration' ? 'active in' : ''; ?>" id="configuration">                            
                        <div class="row">
                            <div class="col-md-12">
                                <h3>System configuration</h3>
                            </div>
                        </div>
                        <form method="post" action="?a=configurations&tab=configuration" autocomplete="off">    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="system_name">System name</label>
                                            <input type="text" name="system_name" id="system_name" class="form-control" placeholder="System name" value="<?php echo getArrayVal($SYSTEM_CONFIG, 'system_name'); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="system_status">System status</label>
                                            <select name="system_status" class="selecter_3" id="system_status" data-selecter-options='{"cover":"true"}'>
                                                <option value="0" <?php echo $SYSTEM_CONFIG["system_status"] === "0" ? 'selected="selected"' : ''; ?>>Offline</option>
                                                <option value="1" <?php echo $SYSTEM_CONFIG["system_status"] === "1" ? 'selected="selected"' : ''; ?>>Online</option>
                                                <option value="2" <?php echo $SYSTEM_CONFIG["system_status"] === "2" ? 'selected="selected"' : ''; ?>>Server Upgrading</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="enable_water_user_registrations">Allow Water User Registrations</label>
                                            <select name="enable_water_user_registrations" class="selecter_3" id="enable_water_user_registrations" data-selecter-options='{"cover":"true"}'>
                                                <option value="0" <?php echo $SYSTEM_CONFIG["enable_water_user_registrations"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                <option value="1" <?php echo $SYSTEM_CONFIG["enable_water_user_registrations"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="default_locale_coordinates">Default Locale Coordinates</label>
                                            <input type="text" name="default_locale_coordinates" id="default_locale_coordinates" class="form-control" placeholder="Default Locale Coordinates" value="<?php echo getArrayVal($SYSTEM_CONFIG, 'default_locale_coordinates'); ?>">
                                        </div>
                                    </div>
                                </div>                                    
                            </div>
                            <div class="row">
                                <div class="col-md-12">        
                                    <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                </div>
                            </div>
                        </form>
                    </div>                        
                    <div class="tab-pane fade <?php echo $tab === 'messages-and-templates' && empty($index) ? 'active in' : ''; ?>" id="message-templates">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Message Templates</h3>
                            </div>
                        </div>
                        <form method="post" action="?a=configurations&tab=messages-and-templates" autocomplete="off">                                
                            <div class="row">
                                <div class="col-md-12">
                                    <p>While you are allowed to freely create an email template even with the use of basic html with no scripting, please understand that <strong>a phone SMS is limited to 160 characters</strong>. 
                                        An extra character after that will be charged as per your SMS provider's billing policy. </p>
                                    <p>Please understand the following variables and their values when used in a template. Please note that not all variables apply in all templates for example a savings variable is useless in a passwords recovery template</p>
                                    <table class="table table-responsive table-striped table-bordered">
                                        <thead>
                                            <tr><th>Variable</th><th>Value</th></tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{$system_name}</td><td><?php echo SYSTEM_NAME; ?></td>
                                            </tr>
                                            <tr>
                                                <td>{$site_url}</td><td><?php echo SITE_URL; ?></td>
                                            </tr>
                                            <tr>
                                                <td>{$email}</td><td>Recipient's email address value</td>
                                            </tr>
                                            <tr>
                                                <td>{$username}</td><td>Recipient's username value</td>
                                            </tr>
                                            <tr>
                                                <td>{$password}</td><td>Recipient's password's value</td>
                                            </tr>
                                            <tr>
                                                <td>{$pnumber}</td><td>Recipient's phone number value if it exists in the system</td>
                                            </tr>
                                            <tr>
                                                <td>{$water_source_name}</td><td>Water source name</td>
                                            </tr>
                                            <tr>
                                                <td>{$water_source_location}</td><td>Water source location</td>
                                            </tr>
                                            <tr>
                                                <td>{$monthly_charges}</td><td>Water source monthly charges</td>
                                            </tr>
                                            <tr>
                                                <td>{$percentage_saved}</td><td>Water source percentage saved</td>
                                            </tr>
                                            <tr>
                                                <td>{$total_sales}</td><td>Sum of water source sales done in the month</td>
                                            </tr>
                                            <tr>
                                                <td>{$total_savings}</td><td>Sum of water source savings</td>
                                            </tr>
                                            <tr>
                                                <td>{$total_expenditures}</td><td>Sum of water source expenditures</td>
                                            </tr>
                                            <tr>
                                                <td>{$acountablility_cycle}</td><td>Number of days within which an accountability report is generated</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="account_created_email_template">Account created Email Template</label>
                                    <textarea name="account_created_email_template" id="account_created_email_template" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'account_created_email_template'); ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="account_created_sms_template">Account created SMS Template</label>
                                    <textarea name="account_created_sms_template" id="account_created_sms_template" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'account_created_sms_template'); ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recovery_email_template">Recovery Email template</label>
                                    <textarea name="recovery_email_template" id="recovery_email_template" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'recovery_email_template'); ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="recovery_sms_template">Recovery SMS template</label>
                                    <textarea name="recovery_sms_template" id="recovery_sms_template" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'recovery_sms_template'); ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="funds_accountability_email_template">Funds Accountability Email Template</label>
                                    <textarea name="funds_accountability_email_template" id="funds_accountability_email_template" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'funds_accountability_email_template'); ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="funds_accountability_sms_template">Funds Accountability SMS Template</label>
                                    <textarea name="funds_accountability_sms_template" id="funds_accountability_sms_template" class="form-control" rows="5" style="resize: none;"><?php echo getArrayVal($SYSTEM_CONFIG, 'funds_accountability_sms_template'); ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">        
                                    <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo $tab === 'messages-and-templates' && $index === '1' ? 'active in' : ''; ?>" id="message-settings">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Message Settings</h3>
                            </div>
                        </div>
                        <form method="post" action="?a=configurations&tab=messages-and-templates&i=1" autocomplete="off">                                
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="enable_emails">Enable emails</label>
                                    <select name="enable_emails" class="selecter_3" id="enable_emails" data-selecter-options='{"cover":"true"}'>
                                        <option value="0" <?php echo $SYSTEM_CONFIG["enable_emails"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo $SYSTEM_CONFIG["enable_emails"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                    </select>
                                </div>
                            </div>
                            <hr class="dashed"/>
                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="enable_sms">Enable SMS messages alerts</label>
                                            <select name="enable_sms" class="selecter_3" id="enable_sms" data-selecter-options='{"cover":"true"}'>
                                                <option value="0" <?php echo $SYSTEM_CONFIG["enable_sms"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                <option value="1" <?php echo $SYSTEM_CONFIG["enable_sms"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="sms_api_username">SMS API username</label>
                                            <input type="text" name="sms_api_username" id="sms_api_username" class="form-control" placeholder="API username" value="<?php echo getArrayVal($SYSTEM_CONFIG, 'sms_api_username'); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="sms_api_key">SMS API key</label>
                                            <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" placeholder="API key" value="<?php echo getArrayVal($SYSTEM_CONFIG, 'sms_api_key'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6"> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="enable_acountablility_sms">Send Accountability Messages</label>
                                            <select name="enable_acountablility_sms" class="selecter_3" id="enable_acountablility_sms" data-selecter-options='{"cover":"true"}'>
                                                <option value="0" <?php echo $SYSTEM_CONFIG["enable_acountablility_sms"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                <option value="1" <?php echo $SYSTEM_CONFIG["enable_acountablility_sms"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="acountablility_cycle">Send Cycle (days)</label>
                                            <input type="text" name="acountablility_cycle" id="acountablility_cycle" class="form-control" placeholder="Send Cycle (days)" value="<?php echo getArrayVal($SYSTEM_CONFIG, 'acountablility_cycle'); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="batch_schedule_date">Next Batch Schedule Date</label>
                                            <input type="text" name="batch_schedule_date" id="batch_schedule_date" class="form-control datetimepicker" placeholder="Next Batch Schedule Date" data-date-format="DD-MM-YYYY hh:mm A" value="<?php echo date('d-m-Y h:i A',  strtotime(getArrayVal($SYSTEM_CONFIG, 'batch_schedule_date'))); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="acountablility_recipients">Select Recipients</label>
                                            <select name="acountablility_recipients" class="selecter_3" id="acountablility_recipients" data-selecter-options='{"cover":"true"}'>
                                                <option value="all" <?php echo $SYSTEM_CONFIG["acountablility_recipients"] === "all" ? 'selected="selected"' : ''; ?>>All</option>
                                                <option value="system_users" <?php echo $SYSTEM_CONFIG["acountablility_recipients"] === "system_users" ? 'selected="selected"' : ''; ?>>System Users</option>                               
                                                <option value="water_users" <?php echo $SYSTEM_CONFIG["acountablility_recipients"] === "water_users" ? 'selected="selected"' : ''; ?>>Water Users</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="dashed"/>
                            <div class="row">                             
                                <div class="col-md-6">
                                    <label class="control-label" for="enable_push_notifications">Enable Google Cloud Push Notifications</label>
                                    <select name="enable_push_notifications" class="selecter_3" id="enable_push_notifications" data-selecter-options='{"cover":"true"}'>
                                        <option value="0" <?php echo $SYSTEM_CONFIG["enable_push_notifications"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                        <option value="1" <?php echo $SYSTEM_CONFIG["enable_push_notifications"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="google_api_key">Google Cloud API key</label>
                                    <input type="text" name="google_api_key" id="sms_api_key" class="form-control" placeholder="Google API key" value="<?php echo getArrayVal($SYSTEM_CONFIG, 'google_api_key'); ?>">
                                </div>                                                              
                            </div>
                            <hr class="dashed"/>
                            <div class="row">
                                <div class="col-md-12">        
                                    <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                </div>
                            </div>
                        </form>           
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function the_add_user_group_form() {

    $group = array(
        'group_name' => getArrayVal($_POST, 'group_name'),
        'group_is_enabled' => getArrayVal($_POST, 'group_is_enabled'),
        'can_access_system_config' => getArrayVal($_POST, 'can_access_system_config'),
        'can_receive_emails' => getArrayVal($_POST, 'can_receive_emails'),
        'can_access_app' => getArrayVal($_POST, 'can_access_app'),
        'can_send_sms' => getArrayVal($_POST, 'can_send_sms'),
        'can_receive_push_notifications' => getArrayVal($_POST, 'can_receive_push_notifications'),
        'can_send_sms' => getArrayVal($_POST, 'can_send_sms'),
        'can_submit_attendant_daily_sales' => getArrayVal($_POST, 'can_submit_attendant_daily_sales'),
        'can_approve_attendants_submissions' => getArrayVal($_POST, 'can_approve_attendants_submissions'),
        'can_approve_treasurers_submissions' => getArrayVal($_POST, 'can_approve_treasurers_submissions'),
        'can_cancel_attendant_daily_sales' => getArrayVal($_POST, 'can_cancel_attendant_daily_sales'),
        'can_cancel_attendants_submissions' => getArrayVal($_POST, 'can_cancel_attendants_submissions'),
        'can_cancel_treasurers_submissions' => getArrayVal($_POST, 'can_cancel_treasurers_submissions'),
        'can_add_water_users' => getArrayVal($_POST, 'can_add_water_users'),
        'can_edit_water_users' => getArrayVal($_POST, 'can_edit_water_users'),
        'can_delete_water_users' => getArrayVal($_POST, 'can_delete_water_users'),
        'can_view_water_users' => getArrayVal($_POST, 'can_view_water_users'),
        'can_add_sales' => getArrayVal($_POST, 'can_add_sales'),
        'can_edit_sales' => getArrayVal($_POST, 'can_edit_sales'),
        'can_delete_sales' => getArrayVal($_POST, 'can_delete_sales'),
        'can_view_sales' => getArrayVal($_POST, 'can_view_sales'),
        'can_view_personal_savings' => getArrayVal($_POST, 'can_view_personal_savings'),
        'can_view_water_source_savings' => getArrayVal($_POST, 'can_view_water_source_savings'),
        'can_add_water_sources' => getArrayVal($_POST, 'can_add_water_sources'),
        'can_edit_water_sources' => getArrayVal($_POST, 'can_edit_water_sources'),
        'can_delete_water_sources' => getArrayVal($_POST, 'can_delete_water_sources'),
        'can_view_water_sources' => getArrayVal($_POST, 'can_view_water_sources'),
        'can_add_repair_types' => getArrayVal($_POST, 'can_add_repair_types'),
        'can_edit_repair_types' => getArrayVal($_POST, 'can_edit_repair_types'),
        'can_delete_repair_types' => getArrayVal($_POST, 'can_delete_repair_types'),
        'can_view_repair_types' => getArrayVal($_POST, 'can_view_repair_types'),
        'can_add_expenses' => getArrayVal($_POST, 'can_add_expenses'),
        'can_edit_expenses' => getArrayVal($_POST, 'can_edit_expenses'),
        'can_delete_expenses' => getArrayVal($_POST, 'can_delete_expenses'),
        'can_view_expenses' => getArrayVal($_POST, 'can_view_expenses'),
        'can_add_system_users' => getArrayVal($_POST, 'can_add_system_users'),
        'can_edit_system_users' => getArrayVal($_POST, 'can_edit_system_users'),
        'can_delete_system_users' => getArrayVal($_POST, 'can_delete_system_users'),
        'can_view_system_users' => getArrayVal($_POST, 'can_view_system_users'),
        'can_add_user_permissions' => getArrayVal($_POST, 'can_add_user_permissions'),
        'can_edit_user_permissions' => getArrayVal($_POST, 'can_edit_user_permissions'),
        'can_delete_user_permissions' => getArrayVal($_POST, 'can_delete_user_permissions'),
        'can_view_user_permissions' => getArrayVal($_POST, 'can_view_user_permissions')
    );
    //var_dump($group);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Add new user group</h3>
        </div>
        <div class="panel-body">  
            <form method="post" action="?a=add-user-group" autocomplete="off">    

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" for="group_name">Group name</label>
                        <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Group name" value="<?php echo getArrayVal($_POST, 'group_name'); ?>">
                    </div>
                </div>
                <hr class="dashed">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label" for="group_is_enabled">Active</label>
                        <select name="group_is_enabled" class="selecter_3" id="group_is_enabled" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["group_is_enabled"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["group_is_enabled"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="can_access_system_config">Can access System settings</label>
                        <select name="can_access_system_config" class="selecter_3" id="can_access_system_config" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_access_system_config"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_access_system_config"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                </div> 
                <hr class="dashed">
                <div class="row">  
                    <div class="col-md-3">
                        <label class="control-label" for="can_receive_emails">Emails</label>
                        <select name="can_receive_emails" class="selecter_3" id="can_receive_emails" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_receive_emails"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                            <option value="1" <?php echo $group["can_receive_emails"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_access_app">App Access</label>
                        <select name="can_access_app" class="selecter_3" id="can_access_app" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_access_app"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                            <option value="1" <?php echo $group["can_access_app"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_send_sms">SMS Messages</label>
                        <select name="can_send_sms" class="selecter_3" id="can_send_sms" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_send_sms"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                            <option value="1" <?php echo $group["can_send_sms"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_receive_push_notifications">Push notifications</label>
                        <select name="can_receive_push_notifications" class="selecter_3" id="can_receive_push_notifications" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_receive_push_notifications"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                            <option value="1" <?php echo $group["can_receive_push_notifications"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                        </select>                        
                    </div>                                
                </div> 
                <hr class="dashed">
                <div class="row"> 
                    <div class="col-md-4">
                        <label class="control-label" for="can_submit_attendant_daily_sales">Can submit daily attendant sales</label>
                        <select name="can_submit_attendant_daily_sales" class="selecter_3" id="can_submit_attendant_daily_sales" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_submit_attendant_daily_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_submit_attendant_daily_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 

                    <div class="col-md-4">
                        <label class="control-label" for="can_approve_attendants_submissions">Can approve attendants submissions</label>
                        <select name="can_approve_attendants_submissions" class="selecter_3" id="can_approve_attendants_submissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_approve_attendants_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_approve_attendants_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>

                    <div class="col-md-4">
                        <label class="control-label" for="can_approve_treasurers_submissions">Can approve treasurers submissions</label>
                        <select name="can_approve_treasurers_submissions" class="selecter_3" id="can_approve_treasurers_submissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_approve_treasurers_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_approve_treasurers_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>

                    <div class="col-md-4">
                        <label class="control-label" for="can_cancel_attendant_daily_sales">Can cancel daily attendant sales</label>
                        <select name="can_cancel_attendant_daily_sales" class="selecter_3" id="can_cancel_attendant_daily_sales" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_cancel_attendant_daily_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_cancel_attendant_daily_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>

                    <div class="col-md-4">
                        <label class="control-label" for="can_cancel_attendants_submissions">Can cancel attendants submissions</label>
                        <select name="can_cancel_attendants_submissions" class="selecter_3" id="can_cancel_attendants_submissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_cancel_attendants_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_cancel_attendants_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>


                    <div class="col-md-4">
                        <label class="control-label" for="can_cancel_treasurers_submissions">Can cancel treasurers submissions</label>
                        <select name="can_cancel_treasurers_submissions" class="selecter_3" id="can_cancel_treasurers_submissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_cancel_treasurers_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_cancel_treasurers_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                </div>
                <hr class="dashed">
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_water_users">Can add water users</label>
                        <select name="can_add_water_users" class="selecter_3" id="can_add_water_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_water_users">Can edit water users</label>
                        <select name="can_edit_water_users" class="selecter_3" id="can_edit_water_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_water_users">Can delete water users</label>
                        <select name="can_delete_water_users" class="selecter_3" id="can_delete_water_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_water_users">Can view water users</label>
                        <select name="can_view_water_users" class="selecter_3" id="can_view_water_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>  
                </div>
                <hr class="dashed">

                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_sales">Can add sales</label>
                        <select name="can_add_sales" class="selecter_3" id="can_add_sales" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_sales">Can edit sales</label>
                        <select name="can_edit_sales" class="selecter_3" id="can_edit_sales" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_sales">Can delete sales</label>
                        <select name="can_delete_sales" class="selecter_3" id="can_delete_sales" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_sales">Can view sales</label>
                        <select name="can_view_sales" class="selecter_3" id="can_view_sales" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>  
                </div>
                <hr class="dashed">

                <div class="row">

                    <div class="col-md-6">
                        <label class="control-label" for="can_view_personal_savings">Can view personal savings</label>
                        <select name="can_view_personal_savings" class="selecter_3" id="can_view_personal_savings" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_personal_savings"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_personal_savings"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 

                    <div class="col-md-6">
                        <label class="control-label" for="can_view_water_source_savings">Can view water source savings</label>
                        <select name="can_view_water_source_savings" class="selecter_3" id="can_view_water_source_savings" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_water_source_savings"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_water_source_savings"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 

                </div>
                <hr class="dashed">


                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_water_sources">Can add water sources</label>
                        <select name="can_add_water_sources" class="selecter_3" id="can_add_water_sources" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_water_sources">Can edit water sources</label>
                        <select name="can_edit_water_sources" class="selecter_3" id="can_edit_water_sources" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_water_sources">Can delete water sources</label>
                        <select name="can_delete_water_sources" class="selecter_3" id="can_delete_water_sources" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_water_sources">Can view water sources</label>
                        <select name="can_view_water_sources" class="selecter_3" id="can_view_water_sources" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>  
                </div>
                <hr class="dashed">

                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_repair_types">Can add repair types</label>
                        <select name="can_add_repair_types" class="selecter_3" id="can_add_repair_types" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_repair_types">Can edit repair types</label>
                        <select name="can_edit_repair_types" class="selecter_3" id="can_edit_repair_types" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_repair_types">Can delete repair types</label>
                        <select name="can_delete_repair_types" class="selecter_3" id="can_delete_repair_types" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_repair_types">Can view repair types</label>
                        <select name="can_view_repair_types" class="selecter_3" id="can_view_repair_types" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>  
                </div>
                <hr class="dashed">

                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_expenses">Can add expenses</label>
                        <select name="can_add_expenses" class="selecter_3" id="can_add_expenses" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_expenses">Can edit expenses</label>
                        <select name="can_edit_expenses" class="selecter_3" id="can_edit_expenses" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_expenses">Can delete expenses</label>
                        <select name="can_delete_expenses" class="selecter_3" id="can_delete_expenses" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_expenses">Can view expenses</label>
                        <select name="can_view_expenses" class="selecter_3" id="can_view_expenses" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>  
                </div>
                <hr class="dashed">

                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_system_users">Can add system users</label>
                        <select name="can_add_system_users" class="selecter_3" id="can_add_system_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_system_users">Can edit system users</label>
                        <select name="can_edit_system_users" class="selecter_3" id="can_edit_system_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_system_users">Can delete system users</label>
                        <select name="can_delete_system_users" class="selecter_3" id="can_delete_system_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_system_users">Can view system users</label>
                        <select name="can_view_system_users" class="selecter_3" id="can_view_system_users" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>  
                </div>
                <hr class="dashed">


                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="can_add_user_permissions">Can add user permissions</label>
                        <select name="can_add_user_permissions" class="selecter_3" id="can_add_user_permissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_add_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_add_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_edit_user_permissions">Can edit user permissions</label>
                        <select name="can_edit_user_permissions" class="selecter_3" id="can_edit_user_permissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_edit_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_edit_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="can_delete_user_permissions">Can delete user permissions</label>
                        <select name="can_delete_user_permissions" class="selecter_3" id="can_delete_user_permissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_delete_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_delete_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                        </select>                        
                    </div> 
                    <div class="col-md-3">
                        <label class="control-label" for="can_view_user_permissions">Can view user permissions</label>
                        <select name="can_view_user_permissions" class="selecter_3" id="can_view_user_permissions" data-selecter-options='{"cover":"true"}'>
                            <option value="0" <?php echo $group["can_view_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                            <option value="1" <?php echo $group["can_view_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
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
    <?php
}

function the_edit_user_group_form() {
    global $dbhandle;

    $group = array();

    $id_group = getArrayVal($_GET, 'id');

    if (!empty($id_group)) {

        $query = "SELECT * FROM " . TABLE_PREFIX . "user_groups WHERE id_group=$id_group";
        $result = $dbhandle->RunQueryForResults($query);
        while ($row = $result->fetch_assoc()) {
            $group = $row;
        }
    }

    //var_dump($group);
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title">Edit user group</h3>
        </div>
        <div class="panel-body"> 
            <?php
            if (!isset($group['id_group'])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            User group does not exist
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <form method="post" action="?a=edit-user-group&id=<?php echo $id_group; ?>" autocomplete="off">    

                    <div class="row">
                        <div class="col-md-12"> 
                            <a class="btn btn-primary pull-right" href="?a=add-user-group">Add group</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="group_name">Group name</label>
                            <input type="text" name="group_name" id="group_name" class="form-control" placeholder="Group name" value="<?php echo getArrayVal($group, 'group_name'); ?>">
                        </div>
                    </div>
                    <hr class="dashed">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="group_is_enabled">Active</label>
                            <select name="group_is_enabled" class="selecter_3" id="group_is_enabled" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["group_is_enabled"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["group_is_enabled"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="can_access_system_config">Can access System settings</label>
                            <select name="can_access_system_config" class="selecter_3" id="can_access_system_config" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_access_system_config"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_access_system_config"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                    </div> 
                    <hr class="dashed">
                    <div class="row">  
                        <div class="col-md-3">
                            <label class="control-label" for="can_receive_emails">Emails</label>
                            <select name="can_receive_emails" class="selecter_3" id="can_receive_emails" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_receive_emails"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                <option value="1" <?php echo $group["can_receive_emails"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_access_app">App Access</label>
                            <select name="can_access_app" class="selecter_3" id="can_access_app" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_access_app"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                <option value="1" <?php echo $group["can_access_app"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_send_sms">SMS Messages</label>
                            <select name="can_send_sms" class="selecter_3" id="can_send_sms" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_send_sms"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                <option value="1" <?php echo $group["can_send_sms"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_receive_push_notifications">Push notifications</label>
                            <select name="can_receive_push_notifications" class="selecter_3" id="can_receive_push_notifications" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_receive_push_notifications"] === "0" ? 'selected="selected"' : ''; ?>>Disabled</option>
                                <option value="1" <?php echo $group["can_receive_push_notifications"] === "1" ? 'selected="selected"' : ''; ?>>Enabled</option>                                    
                            </select>                        
                        </div>                                
                    </div> 
                    <hr class="dashed">
                    <div class="row"> 
                        <div class="col-md-4">
                            <label class="control-label" for="can_submit_attendant_daily_sales">Can submit daily attendant sales</label>
                            <select name="can_submit_attendant_daily_sales" class="selecter_3" id="can_submit_attendant_daily_sales" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_submit_attendant_daily_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_submit_attendant_daily_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 

                        <div class="col-md-4">
                            <label class="control-label" for="can_approve_attendants_submissions">Can approve attendants submissions</label>
                            <select name="can_approve_attendants_submissions" class="selecter_3" id="can_approve_attendants_submissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_approve_attendants_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_approve_attendants_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>

                        <div class="col-md-4">
                            <label class="control-label" for="can_approve_treasurers_submissions">Can approve treasurers submissions</label>
                            <select name="can_approve_treasurers_submissions" class="selecter_3" id="can_approve_treasurers_submissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_approve_treasurers_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_approve_treasurers_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>

                        <div class="col-md-4">
                            <label class="control-label" for="can_cancel_attendant_daily_sales">Can cancel daily attendant sales</label>
                            <select name="can_cancel_attendant_daily_sales" class="selecter_3" id="can_cancel_attendant_daily_sales" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_cancel_attendant_daily_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_cancel_attendant_daily_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>

                        <div class="col-md-4">
                            <label class="control-label" for="can_cancel_attendants_submissions">Can cancel attendants submissions</label>
                            <select name="can_cancel_attendants_submissions" class="selecter_3" id="can_cancel_attendants_submissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_cancel_attendants_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_cancel_attendants_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>


                        <div class="col-md-4">
                            <label class="control-label" for="can_cancel_treasurers_submissions">Can cancel treasurers submissions</label>
                            <select name="can_cancel_treasurers_submissions" class="selecter_3" id="can_cancel_treasurers_submissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_cancel_treasurers_submissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_cancel_treasurers_submissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                    </div>
                    <hr class="dashed">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_water_users">Can add water users</label>
                            <select name="can_add_water_users" class="selecter_3" id="can_add_water_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_water_users">Can edit water users</label>
                            <select name="can_edit_water_users" class="selecter_3" id="can_edit_water_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_water_users">Can delete water users</label>
                            <select name="can_delete_water_users" class="selecter_3" id="can_delete_water_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_water_users">Can view water users</label>
                            <select name="can_view_water_users" class="selecter_3" id="can_view_water_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_water_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_water_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">

                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_sales">Can add sales</label>
                            <select name="can_add_sales" class="selecter_3" id="can_add_sales" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_sales">Can edit sales</label>
                            <select name="can_edit_sales" class="selecter_3" id="can_edit_sales" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_sales">Can delete sales</label>
                            <select name="can_delete_sales" class="selecter_3" id="can_delete_sales" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_sales">Can view sales</label>
                            <select name="can_view_sales" class="selecter_3" id="can_view_sales" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_sales"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_sales"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">

                    <div class="row">

                        <div class="col-md-6">
                            <label class="control-label" for="can_view_personal_savings">Can view personal savings</label>
                            <select name="can_view_personal_savings" class="selecter_3" id="can_view_personal_savings" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_personal_savings"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_personal_savings"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 

                        <div class="col-md-6">
                            <label class="control-label" for="can_view_water_source_savings">Can view water source savings</label>
                            <select name="can_view_water_source_savings" class="selecter_3" id="can_view_water_source_savings" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_water_source_savings"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_water_source_savings"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  

                    </div>
                    <hr class="dashed">


                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_water_sources">Can add water sources</label>
                            <select name="can_add_water_sources" class="selecter_3" id="can_add_water_sources" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_water_sources">Can edit water sources</label>
                            <select name="can_edit_water_sources" class="selecter_3" id="can_edit_water_sources" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_water_sources">Can delete water sources</label>
                            <select name="can_delete_water_sources" class="selecter_3" id="can_delete_water_sources" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_water_sources">Can view water sources</label>
                            <select name="can_view_water_sources" class="selecter_3" id="can_view_water_sources" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_water_sources"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_water_sources"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">

                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_repair_types">Can add repair types</label>
                            <select name="can_add_repair_types" class="selecter_3" id="can_add_repair_types" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_repair_types">Can edit repair types</label>
                            <select name="can_edit_repair_types" class="selecter_3" id="can_edit_repair_types" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_repair_types">Can delete repair types</label>
                            <select name="can_delete_repair_types" class="selecter_3" id="can_delete_repair_types" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_repair_types">Can view repair types</label>
                            <select name="can_view_repair_types" class="selecter_3" id="can_view_repair_types" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_repair_types"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_repair_types"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">

                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_expenses">Can add expenses</label>
                            <select name="can_add_expenses" class="selecter_3" id="can_add_expenses" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_expenses">Can edit expenses</label>
                            <select name="can_edit_expenses" class="selecter_3" id="can_edit_expenses" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_expenses">Can delete expenses</label>
                            <select name="can_delete_expenses" class="selecter_3" id="can_delete_expenses" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_expenses">Can view expenses</label>
                            <select name="can_view_expenses" class="selecter_3" id="can_view_expenses" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_expenses"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_expenses"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">

                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_system_users">Can add system users</label>
                            <select name="can_add_system_users" class="selecter_3" id="can_add_system_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_system_users">Can edit system users</label>
                            <select name="can_edit_system_users" class="selecter_3" id="can_edit_system_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_system_users">Can delete system users</label>
                            <select name="can_delete_system_users" class="selecter_3" id="can_delete_system_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_system_users">Can view system users</label>
                            <select name="can_view_system_users" class="selecter_3" id="can_view_system_users" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_system_users"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_system_users"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">


                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="can_add_user_permissions">Can add user permissions</label>
                            <select name="can_add_user_permissions" class="selecter_3" id="can_add_user_permissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_add_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_add_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_edit_user_permissions">Can edit user permissions</label>
                            <select name="can_edit_user_permissions" class="selecter_3" id="can_edit_user_permissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_edit_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_edit_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="can_delete_user_permissions">Can delete user permissions</label>
                            <select name="can_delete_user_permissions" class="selecter_3" id="can_delete_user_permissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_delete_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_delete_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div> 
                        <div class="col-md-3">
                            <label class="control-label" for="can_view_user_permissions">Can view user permissions</label>
                            <select name="can_view_user_permissions" class="selecter_3" id="can_view_user_permissions" data-selecter-options='{"cover":"true"}'>
                                <option value="0" <?php echo $group["can_view_user_permissions"] === "0" ? 'selected="selected"' : ''; ?>>No</option>
                                <option value="1" <?php echo $group["can_view_user_permissions"] === "1" ? 'selected="selected"' : ''; ?>>Yes</option>                                    
                            </select>                        
                        </div>  
                    </div>
                    <hr class="dashed">

                    <div class="row">
                        <div class="col-md-12">        
                            <input type="submit" value="Save" name="submit" class="btn btn-primary pull-right">
                        </div>
                    </div>


                </form>
            <?php } ?>
        </div>
    </div> 
    </div>
    <?php
}

function the_add_repair_type_form() {
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title text-center">Add Repair Type</h3>
        </div>
        <div class="panel-body"> 
            <form method="post" action="?a=add-repair-type" autocomplete="off">     
                <div class="row">
                    <div class="col-md-4 col-md-offset-4"> 
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="repair_type">Repair Type</label>
                                <input type="text" name="repair_type" id="repair_type" class="form-control" placeholder="Repair Type" value="<?php echo getArrayVal($_POST, 'repair_type'); ?>">
                            </div>
                        </div>        


                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Add repair type" name="submit" class="btn btn-primary pull-right">
                            </div>                    
                        </div>
                    </div>  
                </div>
            </form>
        </div>  
    </div>

    <?php
}

function the_edit_repair_type_form() {
    global $dbhandle;
    $repair = array();
    $id_repair_type = getArrayVal($_GET, 'id');
    if (is_numeric($id_repair_type)) {
        $query = "SELECT * FROM " . TABLE_PREFIX . "repair_types WHERE id_repair_type=$id_repair_type";
        $result = $dbhandle->RunQueryForResults($query);
        while ($row = $result->fetch_assoc()) {
            $repair = $row;
        }
    }

    // var_dump($repair);
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title text-center">Edit Repair Type</h3>
        </div>
        <div class="panel-body"> 
            <?php
            if (!isset($repair['id_repair_type'])) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            Repair type does not exist
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <form method="post" action="?a=edit-repair-type&id=<?php echo $id_repair_type; ?>" autocomplete="off">     
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="repair_type">Repair Type</label>
                                    <input type="text" name="repair_type" id="repair_type" class="form-control" placeholder="Repair Type" value="<?php echo getArrayVal($repair, 'repair_type'); ?>">
                                </div>
                            </div>        


                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" value="Update repair type" name="submit" class="btn btn-primary pull-right">
                                </div>                    
                            </div>
                        </div>  
                    </div>
                </form>
            <?php } ?>
        </div>  
    </div>

    <?php
}

function the_add_expenditure_form() {
    global $dbhandle;



    $water_sources = array();


    $p = $dbhandle->Fetch("water_sources", '*', null, 'water_source_id');


    if (is_array($p) && !empty($p) && !isset($p[0])) {
        $water_sources[] = $p;
    } else {
        $water_sources = $p;
    }

    $repair_types = array();

    $c = $dbhandle->Fetch("repair_types", '*', null, 'repair_type');

    if (is_array($c) && !empty($c) && !isset($c[0])) {
        $repair_types[] = $c;
    } else {
        $repair_types = $c;
    }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title text-center">Add Expenditure</h3>
        </div>
        <div class="panel-body"> 
            <form method="post" action="?a=add-expenditure" autocomplete="off">     
                <div class="row">
                    <div class="col-md-6">                
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
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="repair_type_id">Select the repair type</label>
                                <select name="repair_type_id" class="selecter_3" id="repair_type_id" data-selecter-options='{"cover":"true"}'>
                                    <?php foreach ($repair_types as $repair_type) {
                                        ?>
                                        <option value="<?php echo $repair_type['id_repair_type'] ?>"><?php echo $repair_type['repair_type'] ?></option>
                                    <?php }
                                    ?>
                                    <option value="0">Other</option>
                                </select>                        
                            </div>
                        </div>                    

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="expenditure_date">Date</label>
                                <input type="text" name="expenditure_date" id="expenditure_date" class="form-control datetimepicker" data-date-format="DD-MM-YYYY hh:mm A" value="<?php echo date('d-m-Y h:i A',  strtotime(getArrayVal($_POST, 'expenditure_date'))); ?>">
                            </div>                    
                        </div> 

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="expenditure_cost">Repair cost</label>
                                <input type="text" name="expenditure_cost" id="water_source_location" class="form-control" placeholder="UGX" value="<?php echo getArrayVal($_POST, 'expenditure_cost'); ?>">
                            </div>
                        </div>

                    </div>    

                    <div class="col-md-6">                                                 

                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="benefactor">Benefactor</label>
                                <input type="text" name="benefactor" id="benefactor" class="form-control" placeholder="Mechanic" value="<?php echo getArrayVal($_POST, 'benefactor'); ?>">
                            </div>
                        </div>   
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label" for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="7" style="resize: none;"><?php echo getArrayVal($_POST, 'description'); ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Add Expenditure" name="submit" class="btn btn-primary pull-right">
                            </div>                    
                        </div>
                    </div>   
                    `</div>
            </form>
        </div>  
    </div>



    <?php
}

function the_edit_expenditure_form() {
    global $dbhandle;

    $id_expenditure = getArrayVal($_GET, 'id');

    $expenditure = $dbhandle->Fetch("expenditures", "*", array('id_expenditure' => $id_expenditure));

    $water_sources = array();


    $p = $dbhandle->Fetch("water_sources", '*', null, 'water_source_id');


    if (is_array($p) && !empty($p) && !isset($p[0])) {
        $water_sources[] = $p;
    } else {
        $water_sources = $p;
    }

    $repair_types = array();

    $c = $dbhandle->Fetch("repair_types", '*', null, 'repair_type');

    if (is_array($c) && !empty($c) && !isset($c[0])) {
        $repair_types[] = $c;
    } else {
        $repair_types = $c;
    }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="row-title text-center">Edit Expenditure</h3>
        </div>
        <div class="panel-body"> 
            <?php
            if (empty($expenditure)) {
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger text-center">
                            Expenditure does not exist
                        </div>
                    </div>
                </div>
            <?php } else {
                ?>
                <form method="post" action="?a=edit-expenditure&id=<?php echo $id_expenditure; ?>" autocomplete="off">     
                    <div class="row">
                        <div class="col-md-6">                
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="water_source_id">Select the Water Source</label>
                                    <select name="water_source_id" class="selecter_3" id="water_source_id" data-selecter-options='{"cover":"true"}'>
                                        <?php foreach ($water_sources as $water_source) {
                                            ?>
                                            <option value="<?php echo $water_source['id_water_source']; ?>" <?php echo $water_source['id_water_source'] === $expenditure['water_source_id'] ? 'selected="selected"' : ''; ?>><?php echo $water_source['water_source_name'] ?></option>
                                        <?php }
                                        ?>
                                    </select>                        
                                </div>
                            </div>                    
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="repair_type_id">Select the repair type</label>
                                    <select name="repair_type_id" class="selecter_3" id="repair_type_id" data-selecter-options='{"cover":"true"}'>
                                        <?php foreach ($repair_types as $repair_type) {
                                            ?>
                                            <option value="<?php echo $repair_type['id_repair_type'] ?>" <?php echo $repair_type['id_repair_type'] === $expenditure['repair_type_id'] ? 'selected="selected"' : ''; ?>><?php echo $repair_type['repair_type'] ?></option>
                                        <?php }
                                        ?>
                                        <option value="0" <?php echo $expenditure['repair_type_id'] == '0' ? 'selected="selected"' : ''; ?>>Other</option>
                                    </select>                        
                                </div>
                            </div>                    

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="expenditure_date">Date</label>
                                    <input type="text" name="expenditure_date" id="expenditure_date" class="form-control datetimepicker" data-date-format="DD-MM-YYYY hh:mm A" value="<?php echo date('d-m-Y h:i A', strtotime(getArrayVal($expenditure, 'expenditure_date'))); ?>">
                                </div>                    
                            </div> 

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="expenditure_cost">Repair cost</label>
                                    <input type="text" name="expenditure_cost" id="water_source_location" class="form-control" placeholder="UGX" value="<?php echo getArrayVal($expenditure, 'expenditure_cost'); ?>">
                                </div>
                            </div>

                        </div>    

                        <div class="col-md-6">                                                 

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="benefactor">Benefactor</label>
                                    <input type="text" name="benefactor" id="benefactor" class="form-control" placeholder="Mechanic" value="<?php echo getArrayVal($expenditure, 'benefactor'); ?>">
                                </div>
                            </div>   
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label" for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="7" style="resize: none;"><?php echo getArrayVal($expenditure, 'description'); ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <input type="submit" value="Update Expenditure" name="submit" class="btn btn-primary pull-right">
                                </div>                    
                            </div>
                        </div>   
                        `</div>
                </form>
            <?php } ?>
        </div>  
    </div>



    <?php
}
