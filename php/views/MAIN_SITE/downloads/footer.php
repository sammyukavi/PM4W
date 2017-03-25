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
<script type="text/javascript" src="/assets/libs/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="/assets/libs/isMobile/isMobile.min.js"></script>       
<script type="text/javascript" src="/assets/libs/jquery.easing.1.3.js"></script>   
<script type="text/javascript" src="/assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
    function toggleChevron(e) {
        $(e.target)
                .prev('.panel-heading')
                .find("i.indicator")
                .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
    }
    $('#accordion').on('hidden.bs.collapse', toggleChevron);
    $('#accordion').on('shown.bs.collapse', toggleChevron);
</script>