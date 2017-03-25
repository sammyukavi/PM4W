<?php
global $expenditure;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Edit expenditure</h3>
                            <p>Update an expense log</p>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="" autocomplete="off">     
                                <div class="row">
                                    <div class="col-md-6">    
                                        <?php
                                        $App->con->orderBy('water_source_name', 'ASC');
                                        $water_sources = $App->con->get('water_sources');
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="water_source_id">Select the Water Source</label>
                                                <select name="water_source_id" class="form-control selectpicker-with-search" id="water_source_id" data-selecter-options='{"cover":"true"}'>
                                                    <?php foreach ($water_sources as $water_source) {
                                                        ?>
                                                        <option value="<?php echo $water_source['id_water_source']; ?>" <?php echo $water_source['id_water_source'] === $expenditure['water_source_id'] ? 'selected="selected"' : ''; ?>><?php echo $water_source['water_source_name'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>                        
                                            </div>
                                        </div>                    
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                $App->con->orderBy('repair_type', 'ASC');
                                                $repair_types = $App->con->get('repair_types');
                                                ?>
                                                <label class="control-label" for="repair_type_id">Select the repair type</label>
                                                <select name="repair_type_id" class="form-control selectpicker-with-search" id="repair_type_id" data-selecter-options='{"cover":"true"}'>
                                                    <?php foreach ($repair_types as $repair_type) {
                                                        ?>
                                                        <option value="<?php echo $repair_type['id_repair_type'] ?>" <?php echo $repair_type['id_repair_type'] === $expenditure['repair_type_id'] ? 'selected="selected"' : ''; ?>><?php echo $repair_type['repair_type'] ?></option>
                                                    <?php }
                                                    ?>
                                                    <option value="0" <?php echo $expenditure['repair_type_id'] == '0' ? 'selected="selected"' : ''; ?>>Other</option>
                                                </select>                        
                                            </div>
                                        </div>                    

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="expenditure_date">Date</label>
                                                <input type="text" name="expenditure_date" id="expenditure_date" class="form-control datetimepicker" data-date-format="DD-MM-YYYY hh:mm A" value="<?php echo date('d-m-Y h:i A', strtotime($App->sanitizeVar($expenditure, 'expenditure_date'))); ?>">
                                            </div>                    
                                        </div> 

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="expenditure_cost">Repair cost</label>
                                                <input type="text" name="expenditure_cost" id="water_source_location" class="form-control" placeholder="UGX" value="<?php echo $App->sanitizeVar($expenditure, 'expenditure_cost'); ?>">
                                            </div>
                                        </div>

                                    </div>    

                                    <div class="col-md-6">                                                 

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="benefactor">Benefactor</label>
                                                <input type="text" name="benefactor" id="benefactor" class="form-control" placeholder="Mechanic" value="<?php echo $App->sanitizeVar($expenditure, 'benefactor'); ?>">
                                            </div>
                                        </div>   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="description">Description</label>
                                                <textarea name="description" id="description" class="form-control" rows="7" style="resize: none;"><?php echo $App->sanitizeVar($expenditure, 'description'); ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row margin-top-20">
                                            <div class="col-md-12">
                                                <input type="submit" value="Update Expenditure" name="submit" class="btn btn-primary pull-right">
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
