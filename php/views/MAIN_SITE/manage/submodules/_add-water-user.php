<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Add water user</h3>                            
                        </div>
                        <div class="panel-body">                           
                            <form method="post" action="" autocomplete="off">   
                                <?php
                                $App->con->where('active', 1);
                                $App->con->orderBy('fname,lname', 'ASC');
                                $App->con->join("user_groups", "group_id=id_group", "LEFT");
                                $users = $App->con->get("users", null, array('idu,group_id,fname,lname,can_add_water_users'));
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label" for="email">First name</label>
                                        <input type="text" name="fname" id="fname" class="form-control" placeholder="User first name" value="<?php echo $App->postValue('fname'); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label" for="email">Last name</label>
                                        <input type="text" name="lname" id="lname" class="form-control" placeholder="User last name" value="<?php echo $App->postValue('lname'); ?>">
                                    </div>
                                </div> 

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label" for="pnumber">Phone number (Format 256777123456, 0777123456)</label>
                                        <input type="text" name="pnumber" id="pnumber" class="form-control" placeholder="Phone number" value="<?php echo $App->postValue('pnumber'); ?>">
                                    </div>
                                </div>                              

                                <div class="row">
                                    <?php if ($App->can_edit_system_users) { ?>
                                        <div class="col-md-6">
                                            <label class="control-label" for="uid">Added by</label>
                                            <select name="uid" class="selectpicker-with-search alter-pump-id form-control" id="uid">
                                                <option value="" <?php echo isset($_POST['uid']) && $_POST['uid'] == '' ? 'selected="selected"' : ''; ?>>-----</option>
                                                <?php foreach ($users as $user) {
                                                    ?>
                                                    <option value="<?php echo $user['idu'] ?>" <?php echo isset($_POST['uid']) && $_POST['uid'] == $user['idu'] ? 'selected="selected"' : ''; ?>><?php echo $user['fname'] . " " . $user['lname'] ?></option>
                                                <?php }
                                                ?>
                                            </select>                        
                                        </div>                     
                                    <?php } ?>

                                    <?php if ($App->can_edit_system_users) { ?>
                                        <div class="col-md-6">                                       
                                            <label class="control-label" for="water_source_id">Select Water Source</label>
                                            <div  id="water_source_id_target">
                                                <select name="water_source_id" class="form-control selectpicker-with-search" id="water_source_id" data-selecter-options='{"cover":"true"}'>
                                                    <option value="" selected="selected">-----</option>
                                                </select>   
                                            </div>
                                        </div>                     
                                    <?php } ?>
                                </div>
                                <div class="row margin-top-10">
                                    <div class="col-md-12">        
                                        <input type="submit" value="Add Water User" name="submit" class="btn btn-primary pull-right">
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