<?php require '../config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Downloads | <?php echo SYSTEM_NAME; ?></title>     
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
    <body style="background-color: #f1f2f6;">
        <div class="docs-header">
            <!--nav-->
            <nav class="navbar navbar-default navbar-custom" role="navigation">
                <div class="container">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/">
                            <img src="/apple-touch-icon-60x60.png" height="40">
                            <?php echo SYSTEM_NAME; ?>
                        </a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a class="nav-link current" href="/downloads/">Download</a></li>
                            <li><a class="nav-link" href="/manage/" target="_blank">Login</a></li>                          
                        </ul>
                    </div>
                </div>
            </nav>
            <!--header-->
            <div class="topic">
                <div class="container">
                    <div class="col-md-8">
                        <h3>Downloads</h3>
                        <h4>Download the compiled binaries and install on your phone</h4>
                    </div>                    
                </div>                
            </div>
        </div>
        <?php
        $extensionsToDisplay = array(
            'apk'
        );

        $files = array();

        foreach (new DirectoryIterator(dirname(__FILE__)) as $fileInfo) {
            if (!$fileInfo->isDot() && in_array(strtolower($fileInfo->getExtension()), $extensionsToDisplay)) {
                $files[] = array(
                    strtolower($fileInfo->getFilename()),
                    $fileInfo->getMTime()
                );
            }
        }

        arsort($files);

        //var_dump($files);
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">                    
                    <ul>
                        <?php
                        foreach ($files as $key => $file) {
                            if ($key == (count($files)) - 1) {
                                echo '<li class="list-group-item"><span class="badge badge-success">Latest</span><strong><a href="' . $file[0] . '">' . $file[0] . '</a></strong> <small> created: ' . getCurrentDate($file[1], true) . '</small></li>';
                            } else {
                                echo '<li class="list-group-item"><a href="' . $file[0] . '">' . $file[0] . '</a> <small>created: ' . getCurrentDate($file[1], true) . '</small></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="site-footer">
            <div class="container">                
                <div class="copyright clearfix">
                    <p>
                        <b><?php echo SYSTEM_NAME; ?></b>                            
                    </p>
                    <p>&copy; <?php echo date("Y"); ?></p>
                </div>
            </div>
        </div>
    </body>
</html>
