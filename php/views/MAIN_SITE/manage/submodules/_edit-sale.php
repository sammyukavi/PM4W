<?php
global $sale;
$App->postValues = $sale;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Edit water sale</h3>
                            <p>Update a water sale transaction</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            $App->con->orderBy('water_source_name', 'ASC');
                            $water_sources = $App->con->get("water_sources");
                            ?>
                            <form method="post" action="" autocomplete="off">     
                                <div class="col-md-6 col-md-offset-3">                       
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="water_source_id">Select the Water Source</label>
                                            <select name="water_source_id" class="selectpicker-with-search form-control" id="water_source_id">
                                                <?php foreach ($water_sources as $water_source) {
                                                    ?>
                                                    <option <?php echo isset($App->postValues['water_source_id']) && $App->postValues['water_source_id'] == $water_source['id_water_source'] ? 'selected="selected"' : ''; ?> value="<?php echo $water_source['id_water_source'] ?>"><?php echo $water_source['water_source_name'] ?></option>
                                                <?php }
                                                ?>
                                            </select>                        
                                        </div>
                                    </div>     
                                    <?php if ($App->can_edit_water_sources) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="control-label" for="sold_by">Select the attendant</label>
                                                <div id="sold_by_target">
                                                    <select name="sold_by" class="selectpicker-with-search form-control" id="sold_by" data-selectpicker-options='{"cover":"true"}'>
                                                        <option value="">-----</option>                                           
                                                    </select>                        
                                                </div>
                                            </div>  
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label" for="sold_to">Select the customer</label>
                                            <div id="sold_to_target">
                                                <select name="sold_to" class="selectpicker-with-search form-control" id="sold_to" data-selectpicker-options='{"cover":"true"}'>
                                                    <option value="0">Daily Sale</option>
                                                </select>   
                                            </div>
                                        </div>
                                    </div>                    
                                    <div class="row sale_ugx_div">
                                        <div class="col-md-12">
                                            <label class="control-label" for="sale_ugx">Sale UGX</label>
                                            <input type="text" name="sale_ugx" id="water_source_location" class="form-control" placeholder="UGX" value="<?php echo $App->postValue('sale_ugx'); ?>">
                                        </div>
                                    </div>                
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="submit" value="Add Sale" name="submit" class="btn btn-primary pull-right margin-top-10">
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
