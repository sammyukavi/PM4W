<nav class="navbar navbar-default" role="navigation">
    <div class="container">
        <?php if (is_logged_in()) { ?>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                    <!--img src="img/logo.png" height="40"-->
                    <?php echo SYSTEM_NAME; ?>
                </a>
            </div>        
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <?php if ($USER->can_add_water_users || $USER->can_edit_water_users || $USER->can_delete_water_users || $USER->can_view_water_users) { ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Water Users <b class="caret"></b>                                    
                            </a>

                            <ul class="dropdown-menu" role="menu">   
                                <?php if ($USER->can_edit_water_users || $USER->can_delete_water_users || $USER->can_view_water_users) { ?>
                                    <li>
                                        <a class="nav-link" href="?a=water-users">All Water Users</a>
                                    </li>  
                                <?php } ?>
                                <?php if ($USER->can_add_water_users) { ?>
                                    <li>
                                        <a class="nav-link" href="?a=add-water-user">Add Water User</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($USER->can_add_sales || $USER->can_edit_sales || $USER->can_delete_sales || $USER->can_view_sales) { ?>
                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sales <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">    
                                <?php if ($USER->can_edit_sales || $USER->can_delete_sales || $USER->can_view_sales) { ?>
                                    <li>
                                        <a class="nav-link" href="?a=sales">All Sales</a>
                                    </li> 
                                <?php } ?>
                                <?php if ($USER->can_add_sales) { ?>
                                    <li>
                                        <a class="nav-link" href="?a=add-sale">Add sale</a>
                                    </li>
                                <?php } ?>
                            </ul>                        
                        </li>         
                    <?php } ?>
                    <?php if ($USER->can_submit_attendant_daily_sales || $USER->can_approve_attendants_submissions || $USER->can_approve_treasurers_submissions || $USER->can_view_water_source_savings) { ?>  
                        <li class="dropdown">  
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Savings                                    
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu"> 
                                <?php if ($USER->can_submit_attendant_daily_sales) { ?>  
                                    <li>
                                        <a class="nav-link" href="?a=attendants-submissions">Care Takers' Collections
                                            <?php //echo $pending_approvals > 0 ? ' <span class="badge">' . $pending_approvals . '</span>' : ''; ?>                                            
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($USER->can_approve_attendants_submissions) { ?>  
                                    <li>
                                        <a class="nav-link" href="?a=treasurers-submissions">Treasurers' Collections
                                            <?php //echo $pending_approvals > 0 ? ' <span class="badge">' . $pending_approvals . '</span>' : ''; ?>                                            
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php
                                if ($USER->can_view_water_source_savings) {
                                    $water_sources = array();

                                    $wc = $dbhandle->Fetch("water_sources", "*", NULL, "water_source_name");
                                    if (isset($wc[0]) && is_array($wc)) {
                                        $water_sources = $wc;
                                    } elseif (is_array($wc)) {
                                        $water_sources[] = $wc;
                                    }
                                    //var_dump($water_sources);
                                    echo count($water_sources) > 0 ? '<li class="dropdown-header">Select Water Source</li>' : '';
                                    foreach ($water_sources as $water_source) {
                                        ?>
                                        <li>
                                            <a class="nav-link" href="?a=savings&id=<?php echo $water_source['id_water_source']; ?>"><?php echo $water_source['water_source_name']; ?></a>
                                        </li> 
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($USER->can_add_water_sources || $USER->can_edit_water_sources || $USER->can_delete_water_sources || $USER->can_view_water_sources) { ?>  
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Water Sources <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">    
                                <?php if ($USER->can_add_water_sources || $USER->can_edit_water_sources || $USER->can_delete_water_sources || $USER->can_view_water_sources) { ?>  
                                    <li>
                                        <a class="nav-link" href="?a=water-sources">All Water Sources</a>
                                    </li>  
                                <?php } ?>
                                <?php if ($USER->can_add_water_sources) { ?>
                                    <li>
                                        <a class="nav-link" href="?a=add-water-source">Add Water Source</a>
                                    </li>  
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php
                    if ($USER->can_add_expenses || $USER->can_edit_expenses || $USER->can_delete_expenses || $USER->can_view_expenses ||
                            $USER->can_add_repair_types || $USER->can_edit_repair_types || $USER->can_delete_repair_types || $USER->can_view_repair_types) {
                        ?>  
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Expenditures <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">      
                                <?php
                                if ($USER->can_edit_expenses || $USER->can_delete_expenses || $USER->can_view_expenses) {
                                    ?>  

                                    <li>
                                        <a class="nav-link" href="?a=all-expenditures">All Expenditures</a>
                                    </li>  
                                <?php } ?>
                                <?php if ($USER->can_add_expenses) { ?>
                                    <li>
                                        <a class="nav-link" href="?a=add-expenditure">Add Expenditure</a>
                                    </li>
                                <?php } ?>
                                <?php
                                if ($USER->can_add_repair_types || $USER->can_edit_repair_types || $USER->can_delete_repair_types || $USER->can_view_repair_types) {
                                    ?> 
                                    <li class="divider"></li>
                                    <li class="dropdown-header">Repair types</li>
                                    <li>
                                        <?php
                                        if ($USER->can_edit_repair_types || $USER->can_delete_repair_types || $USER->can_view_repair_types) {
                                            ?> 
                                            <a class="nav-link" href="?a=repair-types">All Repair Types</a>
                                        <?php } ?>
                                        <?php
                                        if ($USER->can_add_repair_types) {
                                            ?> 
                                            <a class="nav-link" href="?a=add-repair-type">Add Repair Types</a>
                                        <?php } ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Messages <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-header">SMS Messages</li>
                            <li><a class="nav-link" href="?a=all-sms">All sent Messages</a></li>
                            <li><a class="nav-link" href="?a=send-sms">Send Message</a></li>
                            <li class="dropdown-header">Push Notifications</li>
                            <li><a class="nav-link" href="?a=all-notifications">All sent Notifications</a></li>
                            <li><a class="nav-link" href="?a=send-notification">Send Notifications</a></li>
                        </ul>
                    </li>
                    <?php
                    if ($USER->can_add_system_users || $USER->can_edit_system_users || $USER->can_delete_system_users || $USER->can_view_system_users ||
                            $USER->can_add_user_permissions || $USER->can_edit_user_permissions || $USER->can_delete_user_permissions || $USER->can_view_user_permissions) {
                        ?> 
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users <b class="caret"></b></a>
                            <ul class="dropdown-menu" role="menu">    
                                <?php
                                if ($USER->can_edit_system_users || $USER->can_delete_system_users || $USER->can_view_system_users) {
                                    ?> 
                                    <li>
                                        <a class="nav-link" href="?a=users">All users</a>
                                    </li>  
                                <?php } ?>
                                <?php
                                if ($USER->can_add_system_users) {
                                    ?> 
                                    <li>
                                        <a class="nav-link" href="?a=add-user">Add user</a>
                                    </li>     
                                <?php } ?>
                                <?php
                                if ($USER->can_add_user_permissions || $USER->can_edit_user_permissions || $USER->can_delete_user_permissions || $USER->can_view_user_permissions) {
                                    ?> 
                                    <li class="divider"></li>
                                    <li class="dropdown-header">User Groups</li>
                                    <?php
                                    if ($USER->can_edit_user_permissions || $USER->can_delete_user_permissions || $USER->can_view_user_permissions) {
                                        ?> 
                                        <li><a href="?a=user-groups">All User Groups</a></li>
                                    <?php } ?>
                                    <?php
                                    if ($USER->can_add_user_permissions) {
                                        ?> 
                                        <li><a href="?a=add-user-group">Add User Group</a></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </li>      
                    <?php } ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $USER->fname . ' ' . $USER->lname . ' (' . $USER->username . ')'; ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu"> 
                            <li>
                                <a class="nav-link" href="?a=edit-user&id=<?php echo $USER->idu; ?>">My Settings</a>
                            </li>    
                            <?php
                            if ($USER->can_access_system_config) {
                                ?> 
                                <li>
                                    <a class="nav-link" href="?a=configurations">System configuration</a>
                                </li> 
                            <?php } ?>
                            <li>
                                <a class="nav-link" href="?a=logout">Logout</a>
                            </li>                                        
                        </ul>
                    </li>
                </ul>
            </div>    
        <?php } ?>
    </div>
</nav>