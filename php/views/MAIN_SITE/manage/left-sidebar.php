<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>                
            </li>
            <li class="<?php echo $App->action == '' ? 'open active' : ''; ?>">
                <a href="/manage">
                    <i class="fa fa-dashboard fa-fw"></i> Dashboard
                </a>
            </li>
            <li class="<?php echo $App->action == 'statistics' || $App->action == 'access-logs' || $App->action == 'sync-logs' ? 'open active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Analytics
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'statistics' ? 'active' : ''; ?>">
                        <a href="/manage/statistics">Statistics</a>
                    </li>
                    <li class="<?php echo $App->action == 'access-logs' ? 'active' : ''; ?>">
                        <a href="/manage/access-logs">Access Logs</a>
                    </li>
                    <li class="<?php echo $App->action == 'sync-logs' ? 'active' : ''; ?>">
                        <a href="/manage/sync-logs">Sync Logs</a>
                    </li>
                </ul>                
            </li>
            <li class="<?php echo $App->action == 'water-users' || $App->action == 'add-water-user' || $App->action == 'edit-water-user' || $App->action == 'water-user-transactions' || $App->action == 'added-by-attendant' || $App->action == 'water-source-users' ? 'open active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Water Users
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'water-users' ? 'active' : ''; ?>">
                        <a href="/manage/water-users">All water users</a>
                    </li>
                    <li class="<?php echo $App->action == 'add-water-user' || $App->action == 'edit-water-user' ? 'active' : ''; ?>">
                        <a href="/manage/add-water-user">Add water user</a>
                    </li>                    
                </ul>                
            </li>
            <li class="<?php echo $App->action == 'sales' || $App->action == 'add-sale' || $App->action == 'edit-sale' || $App->action == 'attendants-sales' || $App->action == 'water-source-sales' ? 'open active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Sales
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'sales' ? 'active' : ''; ?>">
                        <a href="/manage/sales">All water sales</a>
                    </li>
                    <li class="<?php echo $App->action == 'add-sale' ? 'active' : ''; ?>">
                        <a href="/manage/add-sale">Add water sale</a>
                    </li>                    
                </ul>                
            </li>

            <li class="<?php echo $App->action == 'caretakers-collections' || $App->action == 'treasurers-collections' || $App->action == 'water-source-savings' || $App->action == 'water-source-approved' || $App->action == 'water-source-mini-statement' || $App->action == 'expenditures' || $App->action == 'add-expenditure' || $App->action == 'edit-expenditure' ? 'open active' : ''; ?>">
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Savings & Expenses<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'caretakers-collections' || $App->action == 'treasurers-collections' ? 'open active' : ''; ?>">
                        <a href="#">Awaiting approval <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li class="<?php echo $App->action == 'caretakers-collections' ? 'active' : ''; ?>">
                                <a href="/manage/caretakers-collections" >
                                    Caretaker's Collections
                                </a>
                            </li>
                            <li class="<?php echo $App->action == 'treasurers-collections' ? 'active' : ''; ?>">
                                <a href="/manage/treasurers-collections" >
                                    Treasurer's Collections
                                </a>
                            </li>
                        </ul>                               
                    </li>                            
                    <li class="<?php echo $App->action == 'water-source-savings' || $App->action == 'water-source-approved' || $App->action == 'water-source-mini-statement' ? 'open active' : ''; ?>">
                        <a href="#">Water Sources <span class="fa arrow"></span></a>                                
                        <ul class="nav nav-third-level scrollable">
                            <?php
                            $id_water_source = $App->getValue('id');
                            if ($App->can_view_water_source_savings) {
                                $App->con->orderBy('water_source_name', 'ASC');
                                $water_sources = $App->con->get("water_sources");
                                foreach ($water_sources as $ws) {
                                    ?>
                                    <li>
                                        <a href="/manage/water-source-savings/?id=<?php echo $ws['id_water_source']; ?>" <?php //echo $REQUEST_URI === '/' . ADMIN_FOLDER . '/savings/water-source/' && $id_water_source == $ws['id_water_source'] ? 'class="active"' : '';                                            ?>><?php echo $ws['water_source_name']; ?></a>
                                    </li> 
                                    <?php
                                }
                            }
                            ?>  
                        </ul>                                        
                    </li>
                    <li class="<?php echo $App->action == 'expenditures' || $App->action == 'add-expenditure' || $App->action == 'edit-expenditure' ? 'open active' : ''; ?>">
                        <a href="#">Expenditures<span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level scrollable">
                            <li>
                                <a href="/manage/expenditures"  >All Expenditures</a>
                            </li>
                            <li>
                                <a href="/manage/add-expenditure"  >Add Expenditure</a>
                            </li>

                            <?php
                            $ids = $App->con->subQuery();
                            $ids->groupBy("water_source_id");
                            $App->con->orderBy('water_source_name', 'ASC');
                            $ids->get("expenditures", null, "water_source_id");

                            $App->con->where("id_water_source", $ids, ' IN ');
                            $water_sources = $App->con->get("water_sources", null, "id_water_source,water_source_name");
                            foreach ($water_sources as $ws) {
                                ?>
                                <li>
                                    <a href="/manage/expenditures/?id=<?php echo $ws['id_water_source']; ?>" <?php //echo $REQUEST_URI === '/' . ADMIN_FOLDER . '/savings/water-source/' && $id_water_source == $ws['id_water_source'] ? 'class="active"' : '';                                            ?>><?php echo $ws['water_source_name']; ?></a>
                                </li> 
                                <?php
                            }
                            ?>

                        </ul>                                
                    </li>
                </ul>                        
            </li>

            <li class="<?php echo $App->action == 'water-sources' || $App->action == 'add-water-source' || $App->action == 'edit-water-source' ? 'open active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Water Sources
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'water-sources' ? 'active' : ''; ?>">
                        <a href="/manage/water-sources" >Water Sources</a>
                    </li>
                    <li class="<?php echo $App->action == 'add-water-source' ? 'active' : ''; ?>">
                        <a href="/manage/add-water-source" >Add Water Source</a>
                    </li>
                </ul>

            </li>

            <li class="<?php echo $App->action == 'repair-types' || $App->action == 'add-repair-type' || $App->action == 'edit-repair-type' ? 'open active' : ''; ?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Repair Types
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'repair-types' ? 'active' : ''; ?>">
                        <a href="/manage/repair-types" >All Repair Types</a>
                    </li>
                    <li class="<?php echo $App->action == 'add-repair-type' ? 'active' : ''; ?>">
                        <a href="/manage/add-repair-type" >Add Repair Types</a>
                    </li>
                </ul>                        
            </li>



            <li class="<?php echo $App->action == 'sms-messages' || $App->action == 'compose-sms-message' || $App->action == 'view-message' ? 'open active' : ''; ?>">
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Communication<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'sms-messages' || $App->action == 'compose-sms-message' || $App->action == 'view-message' ? 'open active' : ''; ?>">
                        <a href="#">SMS <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="/manage/sms-messages" >SMS Messages</a>
                            </li>
                            <li>
                                <a href="/manage/compose-sms-message" >Compose SMS Message</a>
                            </li>                                    
                        </ul>                               
                    </li>                            
                    <!--li>
                        <a href="#">Push Notifications <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="http://pm4w.uct.ac.za/nu/pending">Outbox</a>
                            </li>
                            <li>
                                <a href="http://pm4w.uct.ac.za/nu/pending">SMS Message</a>
                            </li>                                   
                        </ul>                                
                    </li-->                            
                </ul>                        
            </li>

            <li class="<?php echo $App->action == 'users' || $App->action == 'add-user' || $App->action == 'edit-user' || $App->action == 'user-groups' || $App->action == 'add-user-group' || $App->action == 'edit-user-group' ? 'open active' : ''; ?>">
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Users & Groups<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'users' || $App->action == 'add-user' || $App->action == 'edit-user' ? 'open active' : ''; ?>">
                        <a href="#">Users <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="/manage/users" >All Users</a>
                            </li>
                            <li>
                                <a href="/manage/add-user" >Add User</a>
                            </li>
                        </ul>                               
                    </li>                            
                    <li class="<?php echo $App->action == 'user-groups' || $App->action == 'add-user-group' || $App->action == 'edit-user-group' ? 'open active' : ''; ?>">
                        <a href="#">User Groups <span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="/manage/user-groups" >All User Groups</a>
                            </li>
                            <li>
                                <a href="/manage/add-user-group" >Add User Group</a>
                            </li>                                   
                        </ul>                                
                    </li>                            
                </ul>                        
            </li>

            <li class="<?php echo $App->action == 'builds' || $App->action == 'add-build' || $App->action == 'edit-build' || $App->action == 'view-message' ? 'open active' : ''; ?>">
                <a href="#"><i class="fa fa-sitemap fa-fw"></i> Builds<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li class="<?php echo $App->action == 'builds' ? 'active' : ''; ?>">
                        <a href="/manage/builds">Builds</a>
                    </li>
                    <li class="<?php echo $App->action == 'add-build' ? 'active' : ''; ?>">
                        <a href="/manage/add-build">Add build</a>
                    </li>                                   
                </ul>
            </li>

            <li class="<?php echo $App->action == 'settings' ? 'open active' : ''; ?>">
                <a href="/manage/settings" >
                    <i class="fa fa-cogs fa-fw"></i> Settings
                </a>
            </li>
            <li class="<?php echo $App->action == 'backup' ? 'open active' : ''; ?>">
                <a href="/manage/backup" >
                    <i class="fa fa-hdd-o fa-fw"></i> Database Backup
                </a>
            </li>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>