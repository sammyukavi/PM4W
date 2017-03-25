<?php global $CONFIG; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Backup</h3>
                            <p>Download a copy of the online database or restore the online database using a local copy</p>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="" autocomplete="off" enctype="multipart/form-data"> 
                                <div class="row">
                                    <div class="col-sm-6 col-md-6 col-lg-6">                                
                                        <label for="database" class="control-label">Upload an sql file or text file to restore the database. Your server allows a maximum of <?php echo ini_get('upload_max_filesize'); ?></label>
                                        <input type="file" name="database" id="database" class="form-control" placeholder="File name" value="<?php echo $App->sanitizeVar($CONFIG, 'database'); ?>">
                                        <div class="form-group margin-top-20">
                                            <button type="submit" name="submit" class="btn btn-success btn-lg">
                                                <i class="fa fa-upload"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6">                                
                                        <label for="download" class="control-label">Click download to get a copy of the database</label>
                                        <div>
                                            <a href="?a=export" id="download" class="btn btn-success btn-lg">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>                      
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
