<?php global $sms, $id_msg; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>View Message</h3>
                            <p>Read SMS Message</p>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="post" action="">


                                <div class="form-group">
                                    <label for="scheduled" class="control-label col-xs-2">Status</label>
                                    <div class="col-xs-10">
                                        <?php
                                        echo $sms['can_be_sent'] == 0 && $sms['sent'] == MESSAGE_STATUS_PENDING ? '<span class="label label-info">Draft</span>' : ($sms['can_be_sent'] == 1 && $sms['sent'] == MESSAGE_STATUS_PENDING ? '<span class="label label-primary">Pending</span>' : ($sms['can_be_sent'] == 1 && $sms['sent'] == MESSAGE_STATUS_SENT ? '<span class="label label-success">Sent</span>' : '<span class="label label-danger">Not Sent</span>'));
                                        ?>
                                    </div>
                                </div>

                                <?php
                                if ($sms['can_be_sent'] == 1) {
                                    ?>
                                    <div class="form-group">
                                        <label for="scheduled" class="control-label col-xs-2">Scheduled for: </label>
                                        <div class="col-xs-10">
                                            <?php echo $App->getCurrentDateTime($sms['scheduled_send_date'], true, true); ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div id="scheduledDateContainer" class="form-group <?php echo isset($sms['scheduled']) && $sms['scheduled'] == 'setDate' ? '' : 'hidden'; ?>">
                                    <label for="scheduledDate" class="control-label col-xs-2">Date: </label>
                                    <div class="col-xs-10">
                                        <input type="text" class="form-control datetimepickerNoPast" id="scheduledDate" name="scheduledDate" data-date-format="DD-MM-YYYY hh:mm A" data-minDate="<?php echo date('d-m-Y h:i A'); ?>" value="<?php echo isset($sms['scheduledDate']) ? date('d-m-Y h:i A', strtotime($App->$App->sanitizeVar($sms, 'scheduledDate'))) : date('d-m-Y h:i A'); ?>" placeholder="DD-MM-YYYY hh:mm A">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="msg_content" class="control-label col-xs-2">Content: </label>
                                    <div class="col-xs-10">
                                        <?php echo $App->sanitizeVar($sms, 'message_content'); ?>
                                    </div>
                                </div>    
                                <div class="form-group">
                                    <label for="msg_content" class="control-label col-xs-2">Recipients: </label>
                                    <div class="col-xs-10">                               
                                        <?php
                                        /* $where = " AND id_msg=$id_msg";

                                          $sql = " SELECT id_msg,label,message_content,scheduled_send_date,created_by,can_be_sent,sent,IF(CONCAT_WS(''," . TABLE_PREFIX . "users.fname," . TABLE_PREFIX . "users.lname)=''," . TABLE_PREFIX . "sms_messages_recipients.pnumber,CONCAT_WS(' '," . TABLE_PREFIX . "users.fname," . TABLE_PREFIX . "users.lname)) name FROM " . TABLE_PREFIX . "sms_messages "
                                          . " LEFT JOIN " . TABLE_PREFIX . "sms_messages_recipients ON msg_id=id_msg "
                                          . " LEFT JOIN " . TABLE_PREFIX . "users ON " . TABLE_PREFIX . "users.idu=" . TABLE_PREFIX . "sms_messages_recipients.idu "
                                          . " WHERE " . TABLE_PREFIX . "sms_messages_recipients.idu<>0 $where ";

                                          $sql .= " UNION ALL ";

                                          $sql .= " SELECT id_msg,label,message_content,scheduled_send_date,created_by,can_be_sent,sent,IF(CONCAT_WS(''," . TABLE_PREFIX . "water_users.fname," . TABLE_PREFIX . "water_users.lname)=''," . TABLE_PREFIX . "sms_messages_recipients.pnumber,CONCAT_WS(' '," . TABLE_PREFIX . "water_users.fname," . TABLE_PREFIX . "water_users.lname)) name FROM " . TABLE_PREFIX . "sms_messages "
                                          . " LEFT JOIN " . TABLE_PREFIX . "sms_messages_recipients ON msg_id=id_msg "
                                          . " LEFT JOIN " . TABLE_PREFIX . "water_users ON " . TABLE_PREFIX . "water_users.id_user=" . TABLE_PREFIX . "sms_messages_recipients.id_user "
                                          . " WHERE " . TABLE_PREFIX . "sms_messages_recipients.id_user<>0 $where ";

                                          $sql = " SELECT id_msg,label,message_content,scheduled_send_date,created_by,can_be_sent,sent,name, GROUP_CONCAT(A.name separator ', ') name, CONCAT_WS(' ',fname,lname) created_by FROM ($sql) A "
                                          . " LEFT JOIN " . TABLE_PREFIX . "users ON A.created_by=" . TABLE_PREFIX . "users.idu "
                                          . " GROUP BY id_msg ORDER BY name ";

                                          $data = array();

                                          //echo($sql);

                                          $results = $pm4w->RunQueryForResults($sql);
                                          while ($row = $results->fetch_assoc()) {
                                          echo $row['name'];
                                          } */

                                        $App->con->rawQuery("SET group_concat_max_len=2048");

                                        $columns = array(
                                            'id_msg',
                                            'label',
                                            'message_content',
                                            'scheduled_send_date',
                                            'seen',
                                            'wu.fname',
                                            'wu.lname',
                                            'wu.pnumber'
                                        );

                                        $columns[] = "GROUP_CONCAT(wu.pnumber,' (',wu.fname,' ',wu.lname,')' separator ', ') recipients";
                                        $columns[] = "CONCAT_WS(' ',u.fname,u.lname) created_by";

                                        $sql = "(SELECT  " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "sms_messages sm "
                                                . "LEFT JOIN " . DB_TABLE_PREFIX . "sms_messages_recipients sr ON msg_id=id_msg "
                                                . "LEFT JOIN " . DB_TABLE_PREFIX . "water_users wu ON wu.id_user=sr.id_user "
                                                . "LEFT JOIN " . DB_TABLE_PREFIX . "users u ON u.idu=created_by "
                                                . "WHERE account_type='water_user' "
                                                . "GROUP BY id_msg AND id_msg=$id_msg "
                                                . "LIMIT 1) ";

                                        $sql .= " UNION ALL ";

                                        $sql .= "(SELECT  " . implode(', ', $columns) . " FROM " . DB_TABLE_PREFIX . "sms_messages sm "
                                                . "LEFT JOIN " . DB_TABLE_PREFIX . "sms_messages_recipients sr ON msg_id=id_msg "
                                                . "LEFT JOIN " . DB_TABLE_PREFIX . "users wu ON sr.idu=wu.idu "
                                                . "LEFT JOIN " . DB_TABLE_PREFIX . "users u ON u.idu=created_by "
                                                . "WHERE account_type='user' AND id_msg=$id_msg "
                                                . "GROUP BY id_msg "
                                                . "LIMIT 1) ";

                                        $sql = "SELECT id_msg,label,message_content,scheduled_send_date,seen,GROUP_CONCAT(recipients separator ', ') recipients,created_by  FROM ($sql) A GROUP BY id_msg";

                                        $results = $App->con->rawQuery($sql);
                                        echo $results[0]['recipients'];
                                        ?>
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
