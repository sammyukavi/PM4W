<?php require 'config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Home | <?php echo SYSTEM_NAME; ?></title>     
        <meta name="robots" content="<?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'robots')); ?>"> 
        <meta name="description" content="<?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'site_desc')); ?>">
        <meta name="keywords" content="<?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'site_keywords')); ?>">
        <meta name="author" content="Sammy N Ukavi Jr">
        <!-- Sets initial viewport load and disables zooming  -->
        <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">       
        <meta name="google-site-verification" content="m_GJfXZa9_FiKQLJ5Pa1iyHDIHeiWk39XNxifn_dQZE" />

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
        <meta name="apple-mobile-web-app-title" content="<?php echo SYSTEM_NAME; ?>">
        <link rel="icon" type="image/png" href="/favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <meta name="application-name" content="<?php echo SYSTEM_NAME; ?>">

        <link rel="bookmark" href="/favicon-16x16.png"/>  

        <!-- site css -->
        <link rel="stylesheet" href="/assets/css/front.css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,700,400italic,600italic,700italic,800italic,300italic" rel="stylesheet" type="text/css">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="/assets/js/html5shiv.js"></script>
          <script src="/assets/js/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript" src="/assets/js/site.min.js"></script>
    </head>
    <body class="home">
        <div class="docs-header header--noBackground">
            <!--nav-->
            <nav class="navbar navbar-default navbar-custom" role="navigation">
                <div class="container">
                    <div class="navbar-header"> 
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">
                            <img src="apple-touch-icon-60x60.png" height="40">
                            <?php echo SYSTEM_NAME; ?>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-link" href="/downloads/">Download</a></li>
                            <li><a class="nav-link" href="/manage/" target="_blank">Login</a></li>                          
                        </ul>
                    </div>
                </div>
            </nav>
            <!--index-->
            <div class="index">
                <h1>
                    <img src="apple-touch-icon-180x180.png" alt="<?php echo SYSTEM_NAME; ?>: Advanced HTML5 Hybrid Mobile App Framework">
                </h1>
                <h2><?php echo str_replace('-', ', ', getArrayVal($SYSTEM_CONFIG, 'site_desc')); ?></h2>
                <h2><?php echo SYSTEM_NAME; ?> is a water sales management android app based on Android 2.2 and Bootstrap 3.3.0 CSS framework. <!--br/>And, for the coders, we offer a <a href="free-psd.html">Code Download</a>.--> <br />It provides a faster, easier and less repetitive way for managing sales. <?php echo SYSTEM_NAME; ?> is on test and currently being used in Uganda</h2>
                <h3>Compatible Devices: Android 2.2 and above <br/> Compatible Browsers: IE8, IE9, IE10, IE11, Firefox, Safari, Opera, Chrome.</h3>
                <p class="download-link">
                    <a class="btn btn-primary" href="/downloads/">Download</a>
                </p>               
                <p class="learn-more <?php echo SYSTEM_NAME; ?>">
                    <a href="#learn-more">Learn more <i class="icon" data-icon="&#xe035"></i></a>
                </p>
            </div>
            <div id="learn-more" class="desc container">
                <div class="desc__introduces">
                    <h3>Designed for everyone, everywhere.</h3>
                    <p><?php echo SYSTEM_NAME; ?> is built on the foundations of Bootstrap, visioned in a stunning flat design. Bootstrap itself is a trusted, reliable and proven tool for managers. Built with <a href="http://sass-lang.com/" target="_blank" rel="external nofollow">Sass 3.3.9</a>.</p>
                </div>
                <div class="desc__features">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="features__photo"><img src="/assets/img/feature-mobile.png" /></div>
                            <h4>Mobile first</h4>
                            <p><?php echo SYSTEM_NAME; ?> runs from Android &trade; 2.2 and  it's web version is fully responsive, built for mobile-first in mind. It provides off screen navigation, and almost all the widgets are compatible with all screen sizes.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="features__photo"><img src="/assets/img/feature-bootstrap.png" /></div>
                            <h4>Bootstrap 3.3.0</h4>
                            <p><?php echo SYSTEM_NAME; ?> is built on Bootstrap 3.3.0: the sleek, intuitive, and powerful mobile-first front-end framework for faster and easier web development.</p>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="features__photo"><img src="/assets/img/feature-lightweight.png" /></div>
                            <h4>Lightweight</h4>
                            <p><?php echo SYSTEM_NAME; ?> uses lightweight high-function plugins for maximum performance, keeping CSS and JS file sizes down.</p>
                        </div>
                        <div class="col-md-6">
                            <div class="features__photo"><img src="/assets/img/feature-css.png" /></div>
                            <h4>HTML5 &amp; CSS3</h4>
                            <p><?php echo SYSTEM_NAME; ?>'s components are built with HTML5 and CSS3. The pages use `header`, `nav` and `section` to build the layout. <?php echo SYSTEM_NAME; ?> also comes with several splendid color schemes built-in, and allows for easy customization.</p>
                        </div>

                    </div>
                </div>               
            </div>
            <!--footer-->
            <div class="site-footer">
                <div class="container">
                    <div class="download text-capitalize text-center">
                        <span class="download__infos">You simply have to <b>try it</b>.</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary" href="/downloads/">Download <?php echo SYSTEM_NAME; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <hr class="dashed" />
                    <div class="row">
                        <div class="col-md-4">
                            <h3>Get involved</h3>
                            <p><?php echo SYSTEM_NAME; ?> is <!--hosted on <a href="https://github.com/sammyukavi/PM4W" target="_blank" rel="external nofollow">GitHub</a> and--> open for everyone to contribute. Please give us some feedback and join the development!</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Contribute</h3>
                            <p>You want to help us and participate in the development or the documentation? Just fork <?php echo SYSTEM_NAME; ?> on <a href="https://github.com/sammyukavi/PM4W" target="_blank" rel="external nofollow">GitHub</a> and send us a pull request.</p>
                        </div>
                        <div class="col-md-4">
                            <h3>Found a bug?</h3>
                            <p>Open a <a href="https://github.com/sammyukavi/PM4W/issues" target="_blank" rel="external nofollow">new issue</a> on GitHub. Please search for existing issues first and make sure to include all relevant information.</p>
                        </div>
                    </div>
                    <hr class="dashed" />
                    <div class="row">                       
                        <div class="col-md-6">
                            <div id="mc_embed_signup">
                                <h3 style="margin-bottom: 15px;">Newsletter</h3>
                                <form action="mailto:support@zikiza.com" method="post">
                                    <input style="margin-bottom: 10px;" type="email" value="" name="email" class="email form-control" placeholder="email address" required>
                                    <span class="clear">
                                        <input type="submit" value="Subscribe" name="subscribe" class="btn btn-primary">
                                    </span>
                                </form>
                            </div>                           
                        </div>
                    </div>
                    <hr class="dashed" />
                    <div class="copyright clearfix">
                        <p>
                            <b><?php echo SYSTEM_NAME; ?></b>                            
                        </p>
                        <p>&copy; <?php echo date("Y"); ?></p>
                    </div>
                </div>
            </div>

        </div>
        <script type="text/javascript">

        </script>
    </body>
</html>
