<footer class="footer offset-header">
    <div class="container">
        <small class="copyright pull-left">Copyright &copy; 2014 - <?php echo date("Y"); ?></small>
        <div class="pull-right">                          
            <img style="margin-top: 15px;" src="/assets/images/partners/uct.png" width="250" title="In partnership with the University of Cape Town"/>
            <a href="http://eyeeza.com" target="_blank">
                <img style="margin-top: 15px;" src="/assets/images/partners/eyeeza.png" width="130" title="Powered by Eyeeza ICT Solutions"/>
            </a>
        </div>
    </div>
</footer>
<script type="text/javascript">
    var SITE_URL = '<?php echo $App->siteURL(); ?>';
    var Lang = <?php echo json_encode($App->getLangfile()); ?>;
    var INFORMATION_STATUS_CODE = <?php echo INFORMATION_STATUS_CODE; ?>;
    var SUCCESS_STATUS_CODE = <?php echo SUCCESS_STATUS_CODE; ?>;
    var WARNING_STATUS_CODE = <?php echo WARNING_STATUS_CODE; ?>;
    var ERROR_STATUS_CODE = <?php echo ERROR_STATUS_CODE; ?>;
</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="/assets/libs/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/assets/libs/isMobile/isMobile.min.js"></script>       
<script type="text/javascript" src="/assets/libs/jquery.easing.1.3.js"></script>   
<script type="text/javascript" src="/assets/libs/bootstrap/js/bootstrap.min.js"></script>     
<script type="text/javascript" src="/assets/libs/jquery-inview/jquery.inview.min.js"></script>
<script type="text/javascript" src="/assets/libs/FitVids/jquery.fitvids.js"></script>
<script type="text/javascript" src="/assets/libs/jquery-scrollTo/jquery.scrollTo.min.js"></script>    
<script type="text/javascript" src="/assets/libs/jquery-placeholder/jquery.placeholder.js"></script>
<script type="text/javascript" src="/assets/libs/flexslider/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="/assets/libs/jquery-match-height/jquery.matchHeight-min.js"></script>
<script type="text/javascript" src="/assets/libs/bootstrap-notify-3.1.3/bootstrap-notify.min.js"></script>
<script type="text/javascript" src="/assets/js/main.js"></script>
<!--[if !IE]>--> 
<script type="text/javascript" src="/assets/js/animations.js"></script> 
<!--<![endif]-->         
</body>    
</html> 