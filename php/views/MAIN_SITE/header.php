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
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->      
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


        <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Covered+By+Your+Grace' rel='stylesheet' type='text/css'>  


        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <?php echo '<style type="text/css">' ?> 
        <?php
        $App->parseStylesheet(array(
            "css/flatly.css",
            "libs/flexslider/flexslider.css",
            "libs/animate-css/animate.min.css",
            "fonts/font-awesome-4.5.0/css/font-awesome.css",
            "libs/icheck-1.0.2/skins/flat/green.css",
            "css/styles.css",
            "css/default.css",
        ));
        ?>
        <?php echo '</style>' ?>       
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->  
    </head> 
    <body data-spy="scroll">
        <header id="top" class="header navbar-fixed-top">  
            <div class="container">           
                <nav id="main-nav" class="main-nav" role="navigation">
                    <div class="navbar-header">
                        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only"><?php echo $App->lang('toggle_nav'); ?></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" title="Home" href="<?php echo $App->siteURL(); ?>">                        
                            <img src="/assets/images/logo.png" alt="<?php //echo $App->getSiteName();                                                                                                    ?>"/>
                        </a>
                    </div>
                    <div class="navbar-collapse collapse navbar-right" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item">
                                <a class="scrollto" title="Features" href="/#features">Features</a>
                            </li> 
                            <li class="nav-item">
                                <a class="scrollto" title="Story" href="/#story">Story</a>
                            </li>
                            <li class="nav-item">
                                <a class="scrollto" title="Contact Us" href="/#contact-us">Contact Us</a>
                            </li>
                            <li class="nav-item <?php echo $App->controller == 'downloads' ? 'active' : ''; ?>">
                                <a title="Downloads" href="/downloads">Downloads</a>
                            </li>
                            <?php if ($App->isAuthenticated) { ?>
                                <li class="nav-item last">
                                    <a title="Dashboard" href="/manage">Dashboard</a>
                                </li>
                            <?php } else {
                                ?>
                                <li class="nav-item last <?php echo $App->controller == 'login' ? 'active' : ''; ?>">
                                    <a title="Login" href="/login">Login</a>
                                </li>
                            <?php }
                            ?>
                        </ul>
                    </div>
                </nav>     
            </div>
        </header>

