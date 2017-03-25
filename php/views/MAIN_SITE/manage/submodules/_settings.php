<?php global $tab, $CONFIG, $index;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Settings</h3>
                            <p>System configuration and preferences</p>
                        </div>
                        <div class="panel-body">
                            <ul id="settingsTab" class="nav nav-tabs nav-justified">
                                <li <?php echo empty($tab) ? 'class="active"' : ''; ?>>
                                    <a href="#seo" data-toggle="tab">
                                        <i class="fa fa-globe"></i> SEO
                                    </a>
                                </li>
                                <li <?php echo $tab == 'templates' ? 'class="active"' : ''; ?>>
                                    <a href="#templates" data-toggle="tab">
                                        <i class="fa fa-envelope"></i> SMS & Email Templates
                                    </a>
                                </li>
                                <li class="dropdown <?php echo $tab == 'basic-configuration' || $tab == 'advanced-configuration' ? 'active' : ''; ?>">
                                    <a href="#" id="configuration-tab" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> Configuration<b class="caret"></b></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="configuration-tab">
                                        <li class="<?php echo $tab == 'basic-configuration' && empty($index) ? 'active' : ''; ?>">
                                            <a href="#basic-configuration" tabindex="-1" data-toggle="tab"><i class="fa fa-cog"></i> Basic</a>
                                        </li>
                                        <li class="<?php echo $tab == 'advanced-configuration' && $index == 'advanced' ? 'active' : ''; ?>">
                                            <a href="#advanced-configuration" tabindex="-1" data-toggle="tab"><i class="fa fa-wrench"></i> Advanced</a>
                                        </li>                                
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
                                            <form method="post" action="/manage/settings/" autocomplete="off">  
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
                                                            <input type="radio" name="robots" value="index-follow" id="index-follow" class="check" <?php echo $CONFIG["robots"] == "index-follow" ? 'checked="checked"' : ''; ?>>
                                                            <label for="index-follow">Index, Follow</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="radio">
                                                            <input type="radio" name="robots" value="index-nofollow" id="index-nofollow" class="check" <?php echo $CONFIG["robots"] == "index-nofollow" ? 'checked="checked"' : ''; ?>>
                                                            <label for="index-nofollow">Index, Nofollow</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="radio">
                                                            <input type="radio" name="robots" value="noindex-follow" id="noindex-follow" class="check" <?php echo $CONFIG["robots"] == "noindex-follow" ? 'checked="checked"' : ''; ?>>
                                                            <label for="noindex-follow">No Index, Follow</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="radio">
                                                            <input type="radio" name="robots" value="noindex-nofollow" id="noindex-nofollow" class="check" <?php echo $CONFIG["robots"] == "noindex-nofollow" ? 'checked="checked"' : ''; ?>>
                                                            <label for="noindex-nofollow">No Index, No Follow</label>
                                                        </div>
                                                    </div>
                                                </div>                                        
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="site_description" class="control-label">Site Description</label>
                                                        <textarea name="site_description" id="site_description" class="form-control" rows="5" style="resize: none;"><?php echo $App->sanitizeVar($CONFIG, 'site_description'); ?></textarea>
                                                    </div>                                       
                                                </div>                                                
                                                <div class="row margin-top-10">
                                                    <div class="col-md-12">  
                                                        <label for="site_keywords" class="control-label">Site Keywords</label>
                                                        <textarea name="site_keywords" id="site_keywords" class="form-control" row margin-top-10s="5" style="resize: none;"><?php echo $App->sanitizeVar($CONFIG, 'site_keywords'); ?></textarea>
                                                    </div>                                       
                                                </div>
                                                <div class="row margin-top-10">
                                                    <div class="col-md-6">    
                                                        <label for="googleSiteVerification" class="control-label">Google Site Verification Key</label>
                                                        <input name="googleSiteVerification" id="googleSiteVerification" class="form-control" placeholder="Google Site Verification Key" value="<?php echo $App->sanitizeVar($CONFIG, 'googleSiteVerification'); ?>"/>
                                                    </div>                                       
                                                    <div class="col-md-6">    
                                                        <label for="bingSiteVerification" class="control-label">Bing Site Verification Key</label>
                                                        <input name="bingSiteVerification" id="bingSiteVerification" class="form-control" placeholder="Bing Site Verification Key" value="<?php echo $App->sanitizeVar($CONFIG, 'bingSiteVerification'); ?>"/>
                                                    </div>                                       
                                                </div>
                                                <div class="row margin-top-10">
                                                    <div class="col-md-6">    
                                                        <label for="alexaSiteVerification" class="control-label">Alexa Site Verification Key</label>
                                                        <input name="alexaSiteVerification" id="alexaSiteVerification" class="form-control" placeholder="Alexa Site Verification Key" value="<?php echo $App->sanitizeVar($CONFIG, 'alexaSiteVerification'); ?>"/>
                                                    </div>                                       

                                                    <div class="col-md-6">    
                                                        <label for="yahooSiteVerification" class="control-label">Yahoo Site Verification Key</label>
                                                        <input name="yahooSiteVerification" id="yahooSiteVerification" class="form-control" placeholder="Yahoo Site Verification Key" value="<?php echo $App->sanitizeVar($CONFIG, 'yahooSiteVerification'); ?>"/>
                                                    </div>                                       
                                                </div>

                                                <div class="row margin-top-10">
                                                    <div class="col-md-4">    
                                                        <label for="androidAppName" class="control-label">Android App Name</label>
                                                        <input type="text" name="androidAppName" id="androidAppName" class="form-control" placeholder="Android App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG, 'androidAppName'); ?>"/>
                                                    </div>                                       
                                                    <div class="col-md-4">    
                                                        <label for="androidAppID" class="control-label">Android App ID</label>
                                                        <input name="androidAppID" id="androidAppID" class="form-control" placeholder="Android App ID" value="<?php echo $App->sanitizeVar($CONFIG, 'androidAppID'); ?>"/>
                                                    </div> 
                                                    <div class="col-md-4">    
                                                        <label for="androidAppURL" class="control-label">Android App URL</label>
                                                        <input name="androidAppURL" id="androidAppURL" class="form-control" placeholder="Android App URL" value="<?php echo $App->sanitizeVar($CONFIG, 'androidAppURL'); ?>"/>
                                                    </div> 
                                                </div>

                                                <div class="row margin-top-10">
                                                    <div class="col-md-4">    
                                                        <label for="iphoneAppName" class="control-label">iPhone App Name</label>
                                                        <input type="text" name="iphoneAppName" id="iphoneAppName" class="form-control" placeholder="iPhone App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG, 'iphoneAppName'); ?>"/>
                                                    </div>                                       
                                                    <div class="col-md-4">    
                                                        <label for="iphoneAppID" class="control-label">iPhone App ID</label>
                                                        <input name="iphoneAppID" id="iphoneAppID" class="form-control" placeholder="iPhone App ID" value="<?php echo $App->sanitizeVar($CONFIG, 'iphoneAppID'); ?>"/>
                                                    </div> 
                                                    <div class="col-md-4">    
                                                        <label for="iphoneAppURL" class="control-label">iPhone App URL</label>
                                                        <input name="iphoneAppURL" id="iphoneAppURL" class="form-control" placeholder="iPhone App URL" value="<?php echo $App->sanitizeVar($CONFIG, 'iphoneAppURL'); ?>"/>
                                                    </div> 
                                                </div>

                                                <div class="row margin-top-10">
                                                    <div class="col-md-4">    
                                                        <label for="ipadAppName" class="control-label">iPad App Name</label>
                                                        <input type="text" name="ipadAppName" id="ipadAppName" class="form-control" placeholder="iPad App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG, 'ipadAppName'); ?>"/>
                                                    </div>                                       
                                                    <div class="col-md-4">    
                                                        <label for="ipadAppID" class="control-label">iPad App ID</label>
                                                        <input name="ipadAppID" id="ipadAppID" class="form-control" placeholder="iPad App ID" value="<?php echo $App->sanitizeVar($CONFIG, 'ipadAppID'); ?>"/>
                                                    </div> 
                                                    <div class="col-md-4">    
                                                        <label for="ipadAppURL" class="control-label">iPad App URL</label>
                                                        <input name="ipadAppURL" id="ipadAppURL" class="form-control" placeholder="iPad App URL" value="<?php echo $App->sanitizeVar($CONFIG, 'ipadAppURL'); ?>"/>
                                                    </div> 
                                                </div>  

                                                <div class="row margin-top-10">
                                                    <div class="col-md-6">    
                                                        <label for="site_w3c_itemprop_url" class="control-label">Site W3C Itemprop Url</label>
                                                        <input name="site_w3c_itemprop_url" id="site_w3c_itemprop_url" class="form-control" placeholder="Site W3C Itemprop Url" value="<?php echo $App->sanitizeVar($CONFIG, 'site_w3c_itemprop_url'); ?>"/>
                                                    </div>                                                                      
                                                </div>

                                                <div class="row margin-top-10">
                                                    <div class="col-md-4">    
                                                        <label for="OgDescription" class="control-label">Open Graph Description</label>
                                                        <textarea name="OgDescription" id="OgDescription" class="form-control" placeholder="Open Graph Description" row margin-top-10s="5" style="resize: none;"><?php echo $App->sanitizeVar($CONFIG, 'OgDescription'); ?></textarea>
                                                    </div>                                       
                                                    <div class="col-md-4">    
                                                        <label for="OgImageUrl" class="control-label">Open Graph Image URL</label>
                                                        <input name="OgImageUrl" id="OgImageUrl" class="form-control" placeholder="Open Graph Image URL" value="<?php echo $App->sanitizeVar($CONFIG, 'OgImageUrl'); ?>"/>
                                                    </div> 
                                                    <div class="col-md-4">    
                                                        <label for="og_title" class="control-label">Open Graph Title</label>
                                                        <input name="og_title" id="og_title" class="form-control" placeholder="Open Graph Title" value="<?php echo $App->sanitizeVar($CONFIG, 'og_title'); ?>"/>
                                                    </div>
                                                </div>

                                                <div class="row margin-top-10">
                                                    <div class="col-md-6">    
                                                        <label for="twitterHandle" class="control-label">Twitter Handle</label>
                                                        <input type="text" name="twitterHandle" id="twitterHandle" class="form-control" placeholder="Twitter Handle" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG, 'twitterHandle'); ?>"/>
                                                    </div>                                       
                                                    <div class="col-md-6">    
                                                        <label for="twitterDescription" class="control-label">Twitter Description</label>
                                                        <textarea name="twitterDescription" id="twitterDescription" class="form-control" placeholder="Twitter Description" row margin-top-10s="5" style="resize: none;"><?php echo $App->sanitizeVar($CONFIG, 'twitterDescription'); ?></textarea>
                                                    </div>                                        
                                                </div>

                                                <div class="row margin-top-10">
                                                    <div class="col-md-12">
                                                        <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>                   
                                <div class="tab-pane fade <?php echo $tab == 'basic-configuration' ? 'active in' : ''; ?>" id="basic-configuration">                            

                                    <form method="post" action="/manage/settings/basic-configuration" autocomplete="off" class="form form-horizontal" enctype="multipart/form-data"> 

                                        <div class="form-group margin-top-20">
                                            <label for="site_name" class="col-md-3 control-label">System name</label>
                                            <div class="col-md-6">
                                                <input type="text" name="site_name" id="site_name" class="form-control" placeholder="System name" value="<?php echo $App->sanitizeVar($CONFIG, 'site_name'); ?>">
                                            </div>
                                        </div>

                                        <div class="form-group margin-top-20">
                                            <label for="system_status" class="col-md-3 control-label">System status</label>
                                            <div class="col-md-6">
                                                <select name="system_status" class="form-control selectpicker" id="system_status">
                                                    <option value="0" <?php echo $CONFIG["system_status"] == "0" ? 'selected="selected"' : ''; ?>>Offline</option>
                                                    <option value="1" <?php echo $CONFIG["system_status"] == "1" ? 'selected="selected"' : ''; ?>>Online</option>
                                                    <option value="2" <?php echo $CONFIG["system_status"] == "2" ? 'selected="selected"' : ''; ?>>Server Upgrading</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="enable_water_user_registrations" class="control-label">Allow Water User Registrations</label>
                                                <select name="enable_water_user_registrations" class="form-control selectpicker" id="enable_water_user_registrations">
                                                    <option value="0" <?php echo $CONFIG["enable_water_user_registrations"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["enable_water_user_registrations"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="default_locale_coordinates" class="control-label">Default Locale Coordinates</label>
                                                <input type="text" name="default_locale_coordinates" id="default_locale_coordinates" class="form-control" placeholder="System name" value="<?php echo $App->sanitizeVar($CONFIG, 'default_locale_coordinates'); ?>">
                                            </div>
                                        </div>                                        

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" for="enable_emails">Enable emails</label>
                                                <select name="enable_emails" class="form-control selectpicker" id="enable_emails">
                                                    <option value="0" <?php echo $CONFIG["enable_emails"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["enable_emails"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="enable_sms">Enable SMS messages alerts</label>
                                                <select name="enable_sms" class="form-control selectpicker" id="enable_sms">
                                                    <option value="0" <?php echo $CONFIG["enable_sms"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["enable_sms"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>
                                        </div>

                                        <!--div class="row">
                                            <div class="col-md-6">
                                            </div>
                                            <div class="col-md-6">
                                            </div>
                                        </div-->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" for="enable_acountablility_sms">Send Accountability Messages</label>
                                                <select name="enable_acountablility_sms" class="form-control selectpicker" id="enable_acountablility_sms">
                                                    <option value="0" <?php echo $CONFIG["enable_acountablility_sms"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["enable_acountablility_sms"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="acountablility_cycle">Send Cycle (days)</label>
                                                <input type="text" name="acountablility_cycle" id="acountablility_cycle" class="form-control" placeholder="Send Cycle (days)" value="<?php echo $App->sanitizeVar($CONFIG, 'acountablility_cycle'); ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" for="batch_schedule_date">Next Batch Schedule Date</label>
                                                <input type="text" name="batch_schedule_date" id="batch_schedule_date" class="form-control datetimepicker" placeholder="Next Batch Schedule Date" data-date-format="DD-MM-YYYY hh:mm A" value="<?php echo date('d-m-Y h:i A', strtotime($App->sanitizeVar($CONFIG, 'batch_schedule_date'))); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="acountablility_recipients">Select Recipients</label>
                                                <select name="acountablility_recipients" class="form-control selectpicker" id="acountablility_recipients">
                                                    <option value="all" <?php echo $CONFIG["acountablility_recipients"] == "all" ? 'selected="selected"' : ''; ?>>All</option>
                                                    <option value="system_users" <?php echo $CONFIG["acountablility_recipients"] == "system_users" ? 'selected="selected"' : ''; ?>>System Users</option>                               
                                                    <option value="water_users" <?php echo $CONFIG["acountablility_recipients"] == "water_users" ? 'selected="selected"' : ''; ?>>Water Users</option> 
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" for="sms_api_username">SMS API username</label>
                                                <input type="text" name="sms_api_username" id="sms_api_username" class="form-control" placeholder="API username" value="<?php echo $App->sanitizeVar($CONFIG, 'sms_api_username'); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="sms_api_key">SMS API key</label>
                                                <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" placeholder="API key" value="<?php echo $App->sanitizeVar($CONFIG, 'sms_api_key'); ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" for="recaptcha_site_key">Recaptcha Site Key</label>
                                                <input type="text" name="recaptcha_site_key" id="recaptcha_site_key" class="form-control" placeholder="Recaptcha Site Key" value="<?php echo $App->sanitizeVar($CONFIG, 'recaptcha_site_key'); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="recaptcha_secret_key">Recaptcha Secret Key</label>
                                                <input type="text" name="recaptcha_secret_key" id="recaptcha_secret_key" class="form-control" placeholder="Recaptcha Secret Key" value="<?php echo $App->sanitizeVar($CONFIG, 'recaptcha_secret_key'); ?>">
                                            </div>
                                        </div>

                                        <div class="row">                             
                                            <div class="col-md-6">
                                                <label class="control-label" for="enable_push_notifications">Enable Google Cloud Push Notifications</label>
                                                <select name="enable_push_notifications" class="form-control selectpicker" id="enable_push_notifications">
                                                    <option value="0" <?php echo $CONFIG["enable_push_notifications"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["enable_push_notifications"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="google_api_key">Google Cloud API key</label>
                                                <input type="text" name="google_api_key" id="sms_api_key" class="form-control" placeholder="Google API key" value="<?php echo $App->sanitizeVar($CONFIG, 'google_api_key'); ?>">
                                            </div>                                                              
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="control-label" for="launch_date">Launch Date</label>
                                                <input type="text" name="launch_date" id="launch_date" class="form-control datetimepicker" placeholder="Next Batch Schedule Date" data-date-format="DD-MM-YYYY hh:mm A" value="<?php echo date('d-m-Y h:i A', strtotime($App->sanitizeVar($CONFIG, 'launch_date'))); ?>">
                                            </div>
                                        </div>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">        
                                                <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade <?php echo $tab == 'advanced-configuration' ? 'active in' : ''; ?>" id="advanced-configuration">                            
                                    <form method="post" action="/manage/settings/advanced-configuration" autocomplete="off" class="form form-horizontal" enctype="multipart/form-data"> 
                                        <div class="row">
                                            <div class="col-md-6">    
                                                <label for="error_log_emails" class="control-label">Error log emails</label>
                                                <input name="error_log_emails" id="googleSiteVerification" class="form-control" placeholder="Error log emails" value="<?php echo $App->sanitizeVar($CONFIG, 'error_log_emails'); ?>"/>
                                            </div>
                                            <div class="col-md-6">    
                                                <label for="support_email" class="control-label">Support emails</label>
                                                <input name="support_email" id="support_email" class="form-control" placeholder="Error log emails" value="<?php echo $App->sanitizeVar($CONFIG, 'support_email'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">    
                                                <label for="support_phone_number" class="control-label">Error log emails</label>
                                                <input name="support_phone_number" id="support_phone_number" class="form-control" placeholder="Error log emails" value="<?php echo $App->sanitizeVar($CONFIG, 'error_log_emails'); ?>"/>
                                            </div>
                                            <div class="col-md-6">    
                                                <label for="notifications_email" class="control-label">Notifications emails</label>
                                                <input name="notifications_email" id="notifications_email" class="form-control" placeholder="Notifications emails" value="<?php echo $App->sanitizeVar($CONFIG, 'notifications_email'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">    
                                                <label for="write_emails_to_log" class="control-label">Write outgoing emails to logs folder</label>
                                                <select name="write_emails_to_log" id="write_emails_to_log" class="form-control selectpicker">
                                                    <option value="1" <?php echo isset($CONFIG['write_emails_to_log']) && $CONFIG['write_emails_to_log'] == 1 ? 'selected="selected"' : ''; ?>>Yes</option>
                                                    <option value="0" <?php echo isset($CONFIG['write_emails_to_log']) && $CONFIG['write_emails_to_log'] == 0 ? 'selected="selected"' : ''; ?>>No</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">    
                                                <label for="allowed_chars_in_username" class="control-label">Allowed characters in username</label>
                                                <input name="allowed_chars_in_username" id="allowed_chars_in_username" class="form-control" placeholder="Allowed characters in username" value="<?php echo $App->sanitizeVar($CONFIG, 'allowed_chars_in_username'); ?>"/>
                                            </div>
                                        </div>
                                        <!--hr>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">
                                                <label>Main Site Settings</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">    
                                                <label for="main_site_domain_scheme" class="control-label">Scheme</label>
                                                <input name="domains[main_site_domain][scheme]" id="main_site_domain_scheme" class="form-control" placeholder="iPad App ID" value="<?php echo $App->sanitizeVar($CONFIG['domains']['main_site_domain'], 'scheme'); ?>"/>
                                            </div> 
                                            <div class="col-md-4">    
                                                <label for="main_site_domain_domain" class="control-label">Domain</label>
                                                <input type="text" name="domains[main_site_domain][domain]" id="main_site_domain_domain" class="form-control" placeholder="iPad App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG['domains']['main_site_domain'], 'domain'); ?>"/>
                                            </div>                                      
                                            <div class="col-md-4">    
                                                <label for="main_site_domain_folder" class="control-label">Folder</label>
                                                <input name="domains[main_site_domain][folder]" id="main_site_domain_folder" class="form-control" placeholder="iPad App URL" value="<?php echo $App->sanitizeVar($CONFIG['domains']['main_site_domain'], 'folder'); ?>"/>
                                            </div> 
                                        </div>
                                        <hr>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">
                                                <label>Mobile Site Settings</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">    
                                                <label for="mobile_site_domain_scheme" class="control-label">Scheme</label>
                                                <input name="domains[mobile_site_domain][scheme]" id="mobile_site_domain_scheme" class="form-control" placeholder="iPad App ID" value="<?php echo $App->sanitizeVar($CONFIG['domains']['mobile_site_domain'], 'scheme'); ?>"/>
                                            </div> 
                                            <div class="col-md-4">    
                                                <label for="mobile_site_domain_domain" class="control-label">Domain</label>
                                                <input type="text" name="domains[mobile_site_domain][domain]" id="mobile_site_domain_domain" class="form-control" placeholder="iPad App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG['domains']['mobile_site_domain'], 'domain'); ?>"/>
                                            </div>                                      
                                            <div class="col-md-4">    
                                                <label for="mobile_site_domain_folder" class="control-label">Folder</label>
                                                <input name="domains[mobile_site_domain][folder]" id="mobile_site_domain_folder" class="form-control" placeholder="iPad App URL" value="<?php echo $App->sanitizeVar($CONFIG['domains']['mobile_site_domain'], 'folder'); ?>"/>
                                            </div> 
                                        </div>

                                        <hr>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">
                                                <label>Touch Site Settings</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">    
                                                <label for="touch_site_domain_scheme" class="control-label">Scheme</label>
                                                <input name="domains[touch_site_domain][scheme]" id="touch_site_domain_scheme" class="form-control" placeholder="iPad App ID" value="<?php echo $App->sanitizeVar($CONFIG['domains']['touch_site_domain'], 'scheme'); ?>"/>
                                            </div> 
                                            <div class="col-md-4">    
                                                <label for="touch_site_domain_domain" class="control-label">Domain</label>
                                                <input type="text" name="domains[touch_site_domain][domain]" id="touch_site_domain_domain" class="form-control" placeholder="iPad App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG['domains']['touch_site_domain'], 'domain'); ?>"/>
                                            </div>                                      
                                            <div class="col-md-4">    
                                                <label for="touch_site_domain_folder" class="control-label">Folder</label>
                                                <input name="domains[touch_site_domain][folder]" id="touch_site_domain_folder" class="form-control" placeholder="iPad App URL" value="<?php echo $App->sanitizeVar($CONFIG['domains']['touch_site_domain'], 'folder'); ?>"/>
                                            </div> 
                                        </div>

                                        <hr>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">
                                                <label>Admin Site Settings</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">    
                                                <label for="admin_site_domain_scheme" class="control-label">Scheme</label>
                                                <input name="domains[admin_site_domain][scheme]" id="admin_site_domain_scheme" class="form-control" placeholder="iPad App ID" value="<?php echo $App->sanitizeVar($CONFIG['domains']['admin_site_domain'], 'scheme'); ?>"/>
                                            </div> 
                                            <div class="col-md-4">    
                                                <label for="admin_site_domain_domain" class="control-label">Domain</label>
                                                <input type="text" name="domains[admin_site_domain][domain]" id="admin_site_domain_domain" class="form-control" placeholder="iPad App Name" row margin-top-10s="5" value="<?php echo $App->sanitizeVar($CONFIG['domains']['admin_site_domain'], 'domain'); ?>"/>
                                            </div>                                      
                                            <div class="col-md-4">    
                                                <label for="admin_site_domain_folder" class="control-label">Folder</label>
                                                <input name="domains[admin_site_domain][folder]" id="admin_site_domain_folder" class="form-control" placeholder="iPad App URL" value="<?php echo $App->sanitizeVar($CONFIG['domains']['admin_site_domain'], 'folder'); ?>"/>
                                            </div> 
                                        </div>

                                        <div class="row">                             
                                            <div class="col-md-6">
                                                <label class="control-label" for="force_mobile_redirect">Force Mobile Redirect</label>
                                                <select name="force_mobile_redirect" class="form-control selectpicker" id="force_mobile_redirect">
                                                    <option value="0" <?php echo $CONFIG["force_mobile_redirect"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["force_mobile_redirect"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="control-label" for="delete_photo_on_region_remove">Delete Photo On Region Remove</label>
                                                <select name="delete_photo_on_region_remove" class="form-control selectpicker" id="delete_photo_on_region_remove">
                                                    <option value="0" <?php echo $CONFIG["delete_photo_on_region_remove"] == "0" ? 'selected="selected"' : ''; ?>>No</option>
                                                    <option value="1" <?php echo $CONFIG["delete_photo_on_region_remove"] == "1" ? 'selected="selected"' : ''; ?>>Yes</option>                               
                                                </select>
                                            </div>                                                              
                                        </div-->

                                        <div class="row margin-top-10">
                                            <div class="col-md-12">        
                                                <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane fade <?php echo $tab == 'templates' && empty($index) ? 'active in' : ''; ?>" id="templates">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Message Templates</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-group" id="accordion">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" style="display: block;" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                                Template variables 
                                                                <i class="indicator glyphicon glyphicon-chevron-down  pull-right"></i>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse">
                                                        <div class="panel-body">
                                                            <p>While you are allowed to freely create an email template even with the use of basic html with no scripting, please understand that <strong>a phone SMS is limited to 160 characters</strong>. 
                                                                An extra character after that will be charged as per your SMS provider's billing policy. </p>
                                                            <p>Please understand the following variables and their values when used in a template. Please note that not all variables apply in all templates for example a savings variable is useless in a passwords recovery template</p>
                                                            <table class="table table-striped table-bordered hover dt-responsive nowrap table-responsive" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr><th>Variable</th><th>Value</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>{$site_name}</td><td><?php echo $CONFIG['site_name']; ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>{$site_url}</td><td><?php echo $App->mainSiteURL(); ?></td>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--div class="row">
                                        <div class="col-md-12">
                                            
                                        </div>
                                    </div-->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="btn-group pull-right">
                                                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?php echo $App->lang('select_language') ?> <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <?php
                                                    $email_templates = $App->sanitizeVar($CONFIG, 'email_templates');
                                                    foreach ($email_templates as $key => $template) {
                                                        ?>
                                                        <li class="<?php echo $App->action == 'all' ? 'active' : ''; ?>">
                                                            <a href="/manage/settings/templates?lng=<?php echo $key; ?>">
                                                                <?php echo ucwords($key); ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>    
                                    <?php
                                    $lng = $App->getValue('lng');
                                    if (!empty($lng) && !isset($email_templates[$lng])) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12 margin-top-10">
                                                <div class="alert alert-danger text-capitalize text-center">
                                                    The language you selected does not exist
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } elseif (empty($lng)) {
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12 margin-top-10">
                                                <div class="alert alert-info text-capitalize text-center">
                                                    Select a language to edit the template
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        $email_template = $email_templates[$lng];
                                        ?>
                                        <form method="post" action="/manage/settings/templates?lng=<?php echo $lng; ?>" autocomplete="off">   
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p>
                                                        <label>Account Created Email Template</label>
                                                    </p>
                                                    <label for="account_created_email_template_title">Title</label>
                                                    <input type="text" name="email_templates[account_created_email_template][title]" id="account_created_email_template_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'account_created_email_template', "title"); ?>" class="form-control"/>
                                                    <label for="account_created_email_template_body">Body</label>
                                                    <textarea name="email_templates[account_created_email_template][body]" id="account_created_email_template_body" class="form-control" placeholder="Body" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'account_created_email_template', "body"); ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <p>
                                                        <label>Account Created SMS Template</label>
                                                    </p>
                                                    <label for="account_created_sms_template_title">Title</label>
                                                    <input type="text" name="email_templates[account_created_sms_template][title]" id="account_created_sms_template_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'account_created_sms_template', "title"); ?>" class="form-control"/>
                                                    <label for="account_created_sms_template_body">Body</label>
                                                    <textarea name="email_templates[account_created_sms_template][body]" id="account_created_sms_template_body" class="form-control" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'account_created_sms_template', "body"); ?></textarea>
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p>
                                                        <label>Recovery Email template</label>
                                                    </p>
                                                    <label for="recovery_email_template_title">Title</label>
                                                    <input type="text" name="email_templates[recovery_email_template][title]" id="recovery_email_template_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'recovery_email_template', "title"); ?>" class="form-control"/>
                                                    <label for="recovery_email_template_body">Body</label>
                                                    <textarea name="email_templates[recovery_email_template][body]" id="recovery_email_template_body" class="form-control" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'recovery_email_template', "body"); ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <p>
                                                        <label>Recovery SMS template</label>
                                                    </p>
                                                    <label for="recovery_sms_template_title">Title</label>
                                                    <input type="text" name="email_templates[recovery_sms_template][title]" id="recovery_sms_template_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'recovery_sms_template', "title"); ?>" class="form-control"/>
                                                    <label for="recovery_sms_template_body">Body</label>
                                                    <textarea name="email_templates[recovery_sms_template][body]" id="recovery_sms_template_body" class="form-control" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'recovery_sms_template', "body"); ?></textarea>
                                                </div>
                                            </div>
                                            <hr/>  

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p>
                                                        <label>Funds Accountability Email Template</label>
                                                    </p>
                                                    <label for="funds_accountability_email_template_title">Title</label>
                                                    <input type="text" name="email_templates[funds_accountability_email_template][title]" id="funds_accountability_email_template_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'funds_accountability_email_template', "title"); ?>" class="form-control"/>
                                                    <label for="funds_accountability_email_template_body">Body</label>
                                                    <textarea name="email_templates[funds_accountability_email_template][body]" id="funds_accountability_email_template_body" class="form-control" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'funds_accountability_email_template', "body"); ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <p>
                                                        <label>Funds Accountability SMS Template</label>
                                                    </p>
                                                    <label for="funds_accountability_sms_template_title">Title</label>
                                                    <input type="text" name="email_templates[funds_accountability_sms_template][title]" id="funds_accountability_sms_template_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'funds_accountability_sms_template', "title"); ?>" class="form-control"/>
                                                    <label for="funds_accountability_sms_template_body">Body</label>
                                                    <textarea name="email_templates[funds_accountability_sms_template][body]" id="funds_accountability_sms_template_body" class="form-control" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'funds_accountability_sms_template', "body"); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>
                                                        <label>Funds Accountability Email Template</label>
                                                    </p>
                                                    <label for="contact_form_message_title">Title</label>
                                                    <input type="text" name="email_templates[contact_form_message][title]" id="contact_form_message_title" placeholder="Title" value="<?php echo $App->getEmailTemplate($lng, 'contact_form_message', "title"); ?>" class="form-control"/>
                                                    <label for="contact_form_message_body">Body</label>
                                                    <textarea name="email_templates[contact_form_message][body]" id="contact_form_message_body" class="form-control" rows="5" style="resize: none;"><?php echo $App->getEmailTemplate($lng, 'contact_form_message', "body"); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row margin-top-10">
                                                <div class="col-md-12">        
                                                    <input type="submit" value="Save settings" name="submit" class="btn btn-primary pull-right">
                                                </div>
                                            </div>
                                        </form>
                                    <?php } ?>
                                </div>                                
                            </div>
                        </div>                       
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
