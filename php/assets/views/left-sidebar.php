<?php if ($App->isAuthenticated) { ?>
    <div id="sidebar-wrapper" class="nav-swipe">
        <div class="card hovercard">
            <div class="cardheader"></div>
            <div class="avatar">
                <a href="/me">
                    <img alt="<?php echo $App->user->first_name; ?>" id="avatar" src="<?php echo $App->user->url; ?>?w=70&h=70" width="70" height="70">
                </a>
            </div>
            <div class="info">
                <div class="title">
                    <a href="/me">
                        <?php echo $App->user->first_name; ?>
                    </a>
                </div>
                <div class="desc"><?php echo $App->user->age; ?></div>
                <div class="desc">Kampala</div>            
            </div>        
        </div>

        <div class="sidebar-nav">
            <div>
                <i class="fa fa-wrench"></i>
                <a href="/preferences">
                    <?php echo $App->lang("discovery_preferences"); ?>
                    <span><?php echo $App->lang("discovery_preferences_desc"); ?></span>
                </a>
            </div>

            <div>
                <i class="fa fa-cogs"></i>
                <a href="/settings">
                    <?php echo $App->lang("settings"); ?>
                    <span><?php echo $App->lang("settings_desc"); ?></span>                                
                </a>
            </div>

            <div>
                <i class="fa fa-life-buoy"></i>
                <a href="/help">
                    <?php echo $App->lang("help"); ?>
                    <span><?php echo $App->lang("help_desc"); ?></span> 
                </a>
            </div>

            <div>
                <i class="fa fa-share-alt"></i>
                <a href="/share">
                    <?php echo $App->lang("share"); ?>
                    <span><?php echo $App->lang("share_desc"); ?></span>                
                </a>
            </div>

            <div>
                <i class="fa fa-lock"></i>
                <a href="/logout">
                    <?php echo $App->lang("logout"); ?>
                    <span><?php echo $App->lang("logout_desc"); ?></span>                
                </a>
            </div>        
        </div>    
    </div>    
    <div class="nav-swipe sidebar-nav-trigger"></div>
<?php } ?>