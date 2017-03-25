<?php
global $build;
if (!isset($App->postValues['submit'])) {
    $App->postValues = $build;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Update build</h3>
                            <p>Update and uploaded compiled app build for download</p>
                        </div>
                        <div class="panel-body">
                            <form method="post"  action="" autocomplete="off" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="build_name">Build name</label>
                                        <input type="text" name="build_name" id="build_name" class="form-control" placeholder="Build name" value="<?php echo $App->postValue('build_name'); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="build_version">Build version</label>
                                        <input type="text" name="build_version" id="build_version" class="form-control" placeholder="Build version" value="<?php echo $App->postValue('build_version'); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="compatible_devices">Compatible devices</label>
                                        <textarea name="compatible_devices" id="compatible_devices" class="form-control" placeholder="Build version"><?php echo $App->postValue('compatible_devices'); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="build_features">Build features</label>
                                        <textarea name="build_features" id="build_features" class="form-control" placeholder="Build features"><?php echo $App->postValue('build_features'); ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="build_date">Build date</label>
                                        <input type="text" name="build_date" id="build_date" class="form-control datetimepicker" data-date-format="DD-MM-YYYY hh:mm A" placeholder="Build date" value="<?php echo date('d-m-Y h:i A', strtotime($App->postValue('build_date'))); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4"> 
                                        <label for="preferred" class="checkbox">
                                            <input type="checkbox" name="preferred" id="preferred" <?php echo isset($App->postValues['preferred']) && $App->postValues['preferred'] == 1 ? 'checked="checked"' : ""; ?> value="1" class="check">
                                            Preferred
                                        </label> 
                                    </div>
                                    <div class="col-md-4">
                                        <label for="is_stable" class="checkbox">
                                            <input type="checkbox" name="is_stable" id="is_stable" <?php echo isset($App->postValues['is_stable']) && $App->postValues['is_stable'] == 1 ? 'checked="checked"' : ""; ?> value="1" class="check">
                                            Stable
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="published" class="checkbox">
                                            <input type="checkbox" name="published" id="published" <?php echo isset($App->postValues['published']) && $App->postValues['published'] == 1 ? 'checked="checked"' : ""; ?> value="1" class="check">
                                            Published
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label" for="build_file">Build File</label>
                                        <span class="help-block text-danger">
                                            Upload a new file to overwrite the current existing one. Leave blank if you donot want to overwrite the existing
                                        </span>
                                        <input type="file" name="build_file" id="build_file" class="form-control">
                                        <span class="help-block">
                                            Maximum file size is <?php echo ini_get("upload_max_filesize"); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="row margin-top-10">
                                    <div class="col-md-12">
                                        <input type="submit" value="Save Build" name="submit" class="btn btn-primary pull-right">
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
<script type="text/javascript" src="/assets/libs/trumbowyg-2.0.0-beta.6/dist/trumbowyg.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#build_features').trumbowyg({
            fullscreenable: false,
            closable: false,
        });
    });
</script>