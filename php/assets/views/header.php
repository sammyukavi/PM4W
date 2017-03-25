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
<html lang="en" dir="ltr">
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
            "libs/bootstrap/css/bootstrap.css",
            "fonts/font-awesome-4.5.0/css/font-awesome.css",
            "css/default.css"
        ));
        ?>
        <?php echo '</style>' ?> 
    </head>    
    <body>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <?php
                    if ($App->isAuthenticated) {
                        ?>
                        <button type="button" class="navbar-toggle" id="menu-toggle">                       
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    <?php } ?>
                    <a class="navbar-brand" href="<?php echo $App->siteURL(); ?>">
                        <img src="/assets/images/logo-mobile-50x50.gif"/>
                    </a>
                    <?php
                    if ($App->isAuthenticated) {
                        ?>
                        <a class="top-nav-icon" href="<?php echo $App->siteURL(); ?>/messages/">
                            <i class="fa fa-envelope"></i>
                        </a>
                        <a class="top-nav-icon" href="<?php echo $App->siteURL(); ?>/feeds/">
                            <i class="fa fa-bullhorn"></i>
                        </a>
                    <?php } ?>
                </div>             
            </div>
        </nav>
        <div id="wrapper" class="toggled">