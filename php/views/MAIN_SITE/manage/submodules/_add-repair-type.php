<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Add a repair type</h3>
                            <p>Add an expenditure type</p>
                        </div>
                        <div class="panel-body"> 
                            <form method="post" action="" autocomplete="off">     
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="repair_type">Repair Type</label>
                                                <input type="text" name="repair_type" id="repair_type" class="form-control" placeholder="Repair Type" value="<?php echo $App->postValue('repair_type'); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="active">Status</label>                                                
                                                <select name="active" id="active" class="form-control selectpicker">
                                                    <option value="1" <?php echo isset($App->postValues['active']) && $App->postValues['active'] == 1 ? 'selected="selected"' : ''; ?>>Active</option>
                                                    <option value="0" <?php echo isset($App->postValues['active']) && $App->postValues['active'] == 0 ? 'selected="selected"' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row margin-top-10">
                                            <div class="col-md-12">
                                                <input type="submit" value="Add repair type" name="submit" class="btn btn-primary pull-right">
                                            </div>                    
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
