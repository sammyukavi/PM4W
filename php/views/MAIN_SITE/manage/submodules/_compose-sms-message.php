<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Compse SMS</h3>
                            <p>Send a message to users with phone number</p>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="">
                                <div class="panel-group" id="accordion">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                    <label>
                                                        Select system users to send SMS message to 
                                                    </label> 
                                                    <i class="indicator glyphicon  pull-right glyphicon-chevron-up"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="row">                                                      
                                                    <div class="col-sm-3 col-md-3 col-lg-3 margin-bottom-8">
                                                        <label for="checkAll_system_users" class="checkbox-inline">
                                                            <input type="checkbox" name="checkAll_system_users[]" class="check" id="checkAll_system_users" >
                                                            Select All System Users
                                                        </label>                                             
                                                    </div>                                   
                                                </div>
                                                <hr class="dashed"/>
                                                <?php
                                                $App->con->where('pnumber', '', '<>');
                                                $App->con->groupBy('pnumber');
                                                $system_users = $App->con->get('users');
                                                ?>
                                                <div class="row system_users_container">
                                                    <?php foreach ($system_users as $system_user) {
                                                        ?>                           
                                                        <div class="col-sm-3 col-md-3 col-lg-3 margin-bottom-8">
                                                            <label for="system_users_<?php echo $system_user['idu']; ?>" class="checkbox-inline">
                                                                <input type="checkbox" name="system_users[]" <?php echo in_array($system_user['idu'], (isset($_POST['system_users']) ? $_POST['system_users'] : array())) ? 'checked="checked"' : ""; ?> value="<?php echo $system_user['idu']; ?>" class="check" id="system_users_<?php echo $system_user['idu']; ?>" >
                                                                <?php echo $system_user['fname'] . " " . $system_user['lname']; ?>
                                                            </label>                                             
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                    <label>
                                                        Select water users to send SMS message to 
                                                    </label>
                                                    <i class="indicator glyphicon  pull-right glyphicon-chevron-up"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="panel-collapse collapse">
                                            <div class="panel-body">                              
                                                <div class="row">                                                      
                                                    <div class="col-sm-3 col-md-3 col-lg-3 margin-bottom-8">
                                                        <label for="checkAll_water_users" class="checkbox-inline">
                                                            <input type="checkbox" name="checkAll_water_users[]" class="check" id="checkAll_water_users" >
                                                            Select All Water Users
                                                        </label>                                             
                                                    </div>                                   
                                                </div>
                                                <hr class="dashed"/>  
                                                <?php
                                                $id_water_source = 0;
                                                $sql = " SELECT id_water_source,water_source_name,id_user,fname,lname,pnumber FROM " . DB_TABLE_PREFIX . "water_sources LEFFT JOIN " . DB_TABLE_PREFIX . "water_users "
                                                        . " ON " . DB_TABLE_PREFIX . "water_users.water_source_id=id_water_source WHERE pnumber<>'' GROUP BY pnumber ORDER BY water_source_name,fname ASC ";
//echo $sql;
                                                $water_users = $App->con->rawQuery($sql);

                                                foreach ($water_users as $water_user) {
                                                    if ($water_user['id_water_source'] != $id_water_source) {
                                                        $id_water_source = $water_user['id_water_source'];
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <label class="control-label">
                                                                    <?php echo $water_user['water_source_name']; ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                    <div class="col-sm-3 col-md-3 col-lg-3 margin-bottom-8 water_users_container">
                                                        <label for="water_users_<?php echo $water_user['id_user']; ?>" class="checkbox-inline">
                                                            <input type="checkbox" name="water_users[]" <?php echo in_array($water_user['id_user'], (isset($_POST['water_users']) ? $_POST['water_users'] : array())) ? 'checked="checked"' : ""; ?> value="<?php echo $water_user['id_user']; ?>" class="check" id="water_users_<?php echo $water_user['id_user']; ?>" >
                                                            <?php echo $water_user['fname'] . " " . $water_user['lname']; ?>
                                                        </label>                                             
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="collapsed" href="javascript:;">
                                                    Type SMS Message
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseThree" class="panel-collapse">
                                            <div class="panel-body">

                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="scheduled" class="control-label col-md-2">Schedule SMS for </label>
                                                        <div class="col-md-10">
                                                            <select name="scheduled" id="scheduled" class="form-control selectpicker">
                                                                <option value="now" <?php echo isset($_POST['scheduled']) && $_POST['scheduled'] == 'now' ? 'selected="selected"' : ''; ?>>Now</option>
                                                                <option value="setDate"<?php echo isset($_POST['scheduled']) && $_POST['scheduled'] == 'setDate' ? 'selected="selected"' : ''; ?>>Set date</option>
                                                                <option value="noSend" <?php echo isset($_POST['scheduled']) && $_POST['scheduled'] == 'noSend' ? 'selected="selected"' : ''; ?>>Don't send</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row margin-top-10">
                                                    <div id="scheduledDateContainer" class="form-group <?php echo isset($_POST['scheduled']) && $_POST['scheduled'] == 'setDate' ? '' : 'hiddend'; ?>">
                                                        <label for="scheduledDate" class="control-label col-md-2">Date</label>
                                                        <div class="col-md-10">
                                                            <input type="text" class="form-control datetimepickerNoPast" id="scheduledDate" name="scheduledDate" data-date-format="DD-MM-YYYY hh:mm A" data-minDate="<?php echo date('d-m-Y h:i A'); ?>" value="<?php echo isset($_POST['scheduledDate']) ? date('d-m-Y h:i A', strtotime($App->postValue('scheduledDate'))) : date('d-m-Y h:i A'); ?>" placeholder="DD-MM-YYYY hh:mm A">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row margin-top-10">
                                                    <div class="form-group">
                                                        <label for="msg_content" class="control-label col-md-2">Content</label>
                                                        <div class="col-md-10">
                                                            <div class="msg_content_container">
                                                                <textarea class="form-control resize-none" name="msg_content" style="resize: none;" id="msg_content"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row margin-top-20">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary pull-right" type="submit" name="submit">
                                                            Queue for sending
                                                        </button>
                                                        <span class="btn pull-right margin-right-8" id="smscharacterCount">
                                                            <?php echo SMS_CHARACTERS_LIMIT; ?>
                                                        </span> 
                                                    </div>                                                
                                                </div>                            
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
<script type="text/javascript">
    var SMS_CHARACTERS_LIMIT = <?php echo SMS_CHARACTERS_LIMIT ?>;
</script>