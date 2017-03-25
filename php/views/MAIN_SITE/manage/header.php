<?php
$title = $App->getPageTitle();
$site_name = $App->getSiteName();
$site_description = $App->getSiteDescription();
if (empty($title)) {
    if (!empty($site_description)) {
        $title = $site_description . ' | ' . $site_name;
    } else {
        $title = $site_name;
    }
} else {
    $title = $title . ' | ' . $site_name;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">       
        <title><?php echo $title; ?></title>
        <meta name="y_key" value="<?php echo $App->getYahooSiteVerification(); ?>">
        <meta name="google-site-verification" content="<?php echo $App->getGoogleSiteVerification(); ?>"/>
        <meta name="msvalidate.01" content="<?php echo $App->getBingSiteVerification(); ?>"/>
        <meta name="alexaVerifyID" content="<?php echo $App->getAlexaSiteVerification(); ?>"/>
        <meta name="keywords" content="<?php echo $App->getSiteKeywords(); ?>">
        <meta name="description" content="<?php echo $App->getSiteDescription(); ?>">
        <meta property="og:url" content="<?php echo $App->siteURL(); ?>">
        <link rel="canonical" href="<?php echo $App->siteURL(); ?>"/>        
        <meta property="itemprop" content="<?php echo $App->getItemPropUrl(); ?>">       
        <meta property="og:title" content="<?php echo $App->getOgTitle(); ?>">
        <meta property="og:description" content="<?php echo $App->getOgDescription(); ?>">
        <meta property="og:site_name" content="<?php echo $App->getSiteName(); ?>">
        <meta property="og:image" content="<?php echo $App->getOgImageUrl(); ?>">
        <meta property="fb:app_id" content="<?php echo $App->getFBAppID(); ?>">       

        <meta name="twitter:card" content="app">
        <meta name="twitter:site" content="<?php echo $App->getTwitterHandle(); ?>">
        <meta name="twitter:description" content="<?php echo $App->getTwitterDescription(); ?>">
        <!--meta name="twitter:app:country" content="US"-->
        <meta name="twitter:app:name:iphone" content="<?php echo $App->getIphoneAppName(); ?>">
        <meta name="twitter:app:id:iphone" content="<?php echo $App->getIphoneAppID(); ?>">
        <meta name="twitter:app:url:iphone" content="<?php echo $App->getIphoneAppURL(); ?>">
        <meta name="twitter:app:name:ipad" content="<?php echo $App->getIpadAppName(); ?>">
        <meta name="twitter:app:id:ipad" content="<?php echo $App->getIpadAppID(); ?>">
        <meta name="twitter:app:url:ipad" content="<?php echo $App->getIpadAppURL(); ?>">
        <meta name="twitter:app:name:googleplay" content="<?php echo $App->getAndroidAppName(); ?>">
        <meta name="twitter:app:id:googleplay" content="<?php echo $App->getAndroidAppID(); ?>">
        <meta name="twitter:app:url:googleplay" content="<?php echo $App->getAndroidAppURL(); ?>">


        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
        <meta name="format-detection" content="telephone=no">     

        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700,300italic' rel='stylesheet' type='text/css'>

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">       
        <link rel="manifest" href="/manifest.json">        

        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <?php echo '<style type="text/css">' ?> 
        <?php
        $App->parseStylesheet(array(
            "css/flatly.css",
            "fonts/font-awesome-4.5.0/css/font-awesome.css",
            "libs/icheck-1.0.2/skins/flat/green.css",
            "libs/metismenu/css/metismenu.min.css",
            "libs/morris/morris.css",
            "libs/jquery-datatables/css/jquery.dataTables.min.css",
            "libs/bootstrap-select-1.9.3/css/bootstrap-select.min.css",
            "libs/trumbowyg-2.0.0-beta.6/dist/ui/trumbowyg.min.css",
            "css/manage.css",
        ));
        ?>
        <?php echo '</style>' ?>       
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->  
        <script type="text/javascript" src="/assets/libs/jquery-1.11.3.min.js"></script>
    </head> 
    <body>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/manage/">                        
                        <img src="/assets/images/logo-backend.png" style="height: 50px;" alt="<?php //echo $App->getSiteName();               ?>"/>
                    </a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right">
                    <!--li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <a href="#">
                                    <div>
                                        <strong>John Smith</strong>
                                        <span class="pull-right text-muted">
                                            <em>Yesterday</em>
                                        </span>
                                    </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <strong>John Smith</strong>
                                        <span class="pull-right text-muted">
                                            <em>Yesterday</em>
                                        </span>
                                    </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <strong>John Smith</strong>
                                        <span class="pull-right text-muted">
                                            <em>Yesterday</em>
                                        </span>
                                    </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#">
                                    <strong>Read All Messages</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>                        
                    </li-->                    
                    <!--li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-tasks">
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong>Task 1</strong>
                                            <span class="pull-right text-muted">40% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                <span class="sr-only">40% Complete (success)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong>Task 2</strong>
                                            <span class="pull-right text-muted">20% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong>Task 3</strong>
                                            <span class="pull-right text-muted">60% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                <span class="sr-only">60% Complete (warning)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong>Task 4</strong>
                                            <span class="pull-right text-muted">80% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                                <span class="sr-only">80% Complete (danger)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#">
                                    <strong>See All Tasks</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>                        
                    </li-->                    
                    <!--li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-alerts">
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-comment fa-fw"></i> New Comment
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                        <span class="pull-right text-muted small">12 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-envelope fa-fw"></i> Message Sent
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-tasks fa-fw"></i> New Task
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                        <span class="pull-right text-muted small">4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </li>
                        </ul>                        
                    </li-->
                    <li>
                        <a class="text-center" href="/">
                            <i class="fa fa-globe" data-toggle="tooltipd" data-placement="top" title="Visit Front End"></i>
                        </a>
                    </li>
                    <!-- /.dropdown -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <!--li>
                                <a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                            </li-->
                            <li>
                                <a href="/manage/my-account">
                                    <i class="fa fa-gear fa-fw"></i> My account
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                            </li>
                        </ul>                        
                    </li>                   
                </ul>
        </div>
