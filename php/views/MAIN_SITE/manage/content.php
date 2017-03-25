<!-- Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center margin-top-10">
            <?php $App->printMessage(); ?>
        </div>
    </div>
    <?php
    if (empty($App->action)) {
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3>Dashboard</h3>
                                <p>All controls in one place</p>
                            </div>
                            <div class="panel-body">
                                <p>The current server time is 
                                    <?php echo $App->getCurrentDateTime("", true); ?>
                                </p>
                                <p>The current timezone is 
                                    <?php echo date_default_timezone_get(); ?>
                                </p>
                            </div>                       
                        </div>                
                    </div>                
                </div>                             
            </div>        
        </div>
        <?php
    } elseif (file_exists(__DIR__ . DS . "submodules" . DS . "_$App->action.php")) {
        require_once __DIR__ . DS . "submodules" . DS . "_$App->action.php";
    } else {
        $App->the_404_text(false);
    }
    ?>
</div>