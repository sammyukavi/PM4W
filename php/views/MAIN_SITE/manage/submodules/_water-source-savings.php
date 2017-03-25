<?php global $water_source_data, $CONFIG; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Water Source Savings & Statistics</h3>
                            <p>Details pertaining a water source</p>
                        </div>
                        <div class="panel-body"> 
                            <div class="row">
                                <div class="col-md-12">                           
                                    <h4>Name: <?php echo ucwords($water_source_data['water_source_name']); ?></h4>
                                    <h4>ID: <?php echo ucwords($water_source_data['water_source_id']); ?></h4>
                                    <h4>Location: <?php echo ucwords($water_source_data['water_source_location']); ?></h4>
                                </div>
                            </div>

                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#stats">
                                        <i class="fa fa-bar-chart-o"></i> Stats
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#caretakers">
                                        <i class="fa fa-users"></i> Caretakers
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#treasurers">
                                        <i class="fa fa-user-md"></i>    Treasurers
                                    </a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#devices">
                                        <i class="fa fa-map"></i>    Map & Devices
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="stats" class="tab-pane fade in active">
                                    <div class="row margin-top-20"> 
                                        <div class="col-md-3">
                                            <div class="dashboard-stat fb rounded">
                                                <div class="visual">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                </div>
                                                <div class="details">
                                                    <div class="number">
                                                        <?php echo number_format($App->calculateWaterSourceTotalWaterUsers($water_source_data['id_water_source']), 0, '.', ','); ?>
                                                    </div>
                                                    <div class="desc">
                                                        Monthly Billed Water Users
                                                    </div>
                                                </div>
                                                <?php if ($App->can_view_water_users) { ?>
                                                    <a class="more" href="/manage/water-source-users/?id=<?php echo $water_source_data['id_water_source']; ?>">
                                                        View more <i class="fa fa-arrow-circle-o-right"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="dashboard-stat twitter rounded">
                                                <div class="visual">
                                                    <i class="glyphicon glyphicon-transfer"></i>
                                                </div>
                                                <div class="details">
                                                    <div class="number">
                                                        <?php echo number_format($App->calculateWaterSourceTotalTransactions($water_source_data['id_water_source']), 0, '.', ','); ?>
                                                    </div>
                                                    <div class="desc">
                                                        Total transactions
                                                    </div>
                                                </div>
                                                <?php if ($App->can_view_sales) { ?>
                                                    <a class="more" href="/manage/water-source-sales/?id=<?php echo $water_source_data['id_water_source']; ?>">
                                                        View more <i class="fa fa-arrow-circle-o-right"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="dashboard-stat gp rounded">
                                                <div class="visual">
                                                    <i class="glyphicon glyphicon-check"></i>
                                                </div>
                                                <div class="details">
                                                    <div class="number">
                                                        <?php echo number_format($App->calculateWaterSourceTotalApprovedTransactions($water_source_data['id_water_source']), 0, '.', ','); ?>
                                                    </div>
                                                    <div class="desc">
                                                        Verified transactions
                                                    </div>
                                                </div>
                                                <?php if ($App->can_view_sales) { ?>
                                                    <a class="more" href="/manage/water-source-approved/?id=<?php echo $water_source_data['id_water_source']; ?>">
                                                        View more <i class="fa fa-arrow-circle-o-right"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="dashboard-stat bb rounded">
                                                <div class="visual" style="color: #ffffff;">
                                                    UGX 
                                                </div>
                                                <div class="details">
                                                    <div class="number">
                                                        <?php echo number_format($App->calculateWaterSourceTotalSavings($water_source_data['id_water_source']), 0, '.', ','); ?>
                                                    </div>
                                                    <div class="desc">
                                                        Total Savings
                                                    </div>
                                                </div>
                                                <?php if ($App->can_view_sales) { ?>
                                                    <a class="more" href="/manage/water-source-mini-statement/?id=<?php echo $water_source_data['id_water_source']; ?>">
                                                        View more <i class="fa fa-arrow-circle-o-right"></i>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div> 

                                    <div class="row margin-top-20">
                                        <div class="col-md-6">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h3>User registrations per month</h3>
                                                    <p>Number of water users added into the system per month</p>
                                                </div>
                                                <div class="panel-body">
                                                    <?php
                                                    /* $cols = array('COUNT(id_user) total', 'DATE_FORMAT(date_added,\'%Y-%m\') date_added');
                                                      $App->con->orderBy("date_added", "asc");
                                                      $App->con->groupBy("MONTH(date_added)");
                                                      $water_users = $App->con->get('water_users', null, $cols); */

                                                    $cols = array('water_source_name', 'COUNT(id_user) total', 'DATE_FORMAT(date_added,\'%Y-%m\') date_added');
                                                    $App->con->where('id_water_source', $water_source_data['id_water_source']);
                                                    $App->con->orderBy("date_added", "asc");
                                                    $App->con->orderBy("water_source_name", "asc");
                                                    $App->con->groupBy("water_users.water_source_id");
                                                    $App->con->groupBy("Month(date_added)");
                                                    $App->con->join('water_users water_users', 'water_users.water_source_id=id_water_source', 'LEFT');
                                                    $water_users = $App->con->get('water_sources water_sources', null, $cols);
                                                    $grouped_water_users = array();
                                                    foreach ($water_users as $key => $water_user) {
                                                        $grouped_water_users[$water_user['date_added']]['date_added'] = $App->getCurrentDateTime($water_user['date_added']);
                                                        $grouped_water_users[$water_user['date_added']][$water_user['water_source_name']] = $water_user['total'];
                                                    }
                                                    $water_users_data = array();
                                                    foreach ($grouped_water_users as $value) {
                                                        $water_users_data[] = $value;
                                                    }
                                                    ?>      
                                                    <div id="new-registrations"></div>
                                                </div>
                                            </div>                
                                        </div>                                                                       

                                        <div class="col-md-6">
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h3>Sales per month</h3>
                                                    <p>Amount collected per month</p>
                                                </div>
                                                <div class="panel-body">
                                                    <?php
                                                    /* $cols = array('SUM(sale_ugx) amount', 'DATE_FORMAT(sale_date,\'%Y-%m\') sale_date');
                                                      $App->con->orderBy("sale_date", "asc");
                                                      $App->con->groupBy("MONTH(sale_date)");
                                                      $sales = $App->con->get('sales', null, $cols); */

                                                    $cols = array('water_source_name', 'SUM(sale_ugx) amount', 'DATE_FORMAT(sale_date,\'%Y-%m\') sale_date');
                                                    $App->con->where('id_water_source', $water_source_data['id_water_source']);
                                                    $App->con->orderBy("sale_date", "asc");
                                                    $App->con->orderBy("water_source_name", "asc");
                                                    $App->con->groupBy("sales.water_source_id");
                                                    $App->con->groupBy("Month(sale_date)");
                                                    $App->con->join('sales sales', 'sales.water_source_id=id_water_source', 'LEFT');
                                                    $sales = $App->con->get('water_sources water_sources', null, $cols);
                                                    $grouped_sales = array();
                                                    foreach ($sales as $key => $sale) {
                                                        $grouped_sales[$sale['sale_date']]['sale_date'] = $App->getCurrentDateTime($sale['sale_date']);
                                                        $grouped_sales[$sale['sale_date']][$sale['water_source_name']] = $sale['amount'];
                                                    }
                                                    $sales_data = array();
                                                    foreach ($grouped_sales as $value) {
                                                        $sales_data[] = $value;
                                                    }
                                                    ?>
                                                    <div class="graph" id="sales-graph"></div>
                                                </div>
                                            </div>                
                                        </div>    
                                    </div>

                                </div>
                                <div id="caretakers" class="tab-pane fade">
                                    <div class="row margin-top-20">                       
                                        <div class="col-md-12">                                                      
                                            <h4>Caretakers/Attendants</h4>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <?php
                                        //$sql = "SELECT idu,CONCAT_WS(' ',fname,lname) name FROM " . TABLE_PREFIX . "users WHERE idu IN(SELECT uid FROM " . TABLE_PREFIX . "water_source_caretakers WHERE water_source_id=" . $water_source_data['id_water_source'] . ") ORDER BY name";

                                        $ids = $App->con->subQuery();
                                        $ids->where("water_source_id", $water_source_data['id_water_source']);
                                        $ids->get("water_source_caretakers", null, "uid");

                                        $App->con->where("idu", $ids, ' IN ');
                                        $caretakers = $App->con->get("users", null, "idu,CONCAT_WS(' ',fname,lname) name");

                                        if (count($caretakers) > 0) {
                                            foreach ($caretakers as $caretaker) {
                                                ?>
                                                <div class="col-md-3">   
                                                    <label class="control-label">
                                                        <i class="fa fa-arrow-circle-right"></i>
                                                        <a href="/manage/attendants-sales/?id=<?php echo $caretaker['idu'] ?>">
                                                            <?php echo $caretaker['name'] ?>
                                                        </a>
                                                    </label>
                                                </div>    
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="col-md-offset-1 col-md-10 alert alert-info text-capitalize text-center">
                                                This water source does not have any caretakers/attendants added. 
                                            </div>
                                            <?php
                                        }
                                        ?>

                                    </div>
                                </div>
                                <div id="treasurers" class="tab-pane fade">
                                    <div class="row margin-top-20">                       
                                        <div class="col-md-12">
                                            <h4>Treasurers</h4>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <?php
                                        $ids = $App->con->subQuery();
                                        $ids->where("water_source_id", $water_source_data['id_water_source']);
                                        $ids->get("water_source_treasurers", null, "uid");

                                        $App->con->where("idu", $ids, ' IN ');
                                        $treasurers = $App->con->get("users", null, "idu,CONCAT_WS(' ',fname,lname) name");


                                        if (count($treasurers) > 0) {
                                            foreach ($treasurers as $treasurer) {
                                                ?>
                                                <div class="col-md-3">  
                                                    <label class="control-label">
                                                        <i class="fa fa-arrow-circle-right"></i>
                                                        <a href="/manage/treasurer-submissions/?id=<?php echo $treasurer['idu'] ?>">
                                                            <?php echo $treasurer['name'] ?>
                                                        </a>
                                                    </label>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="col-md-offset-1 col-md-10 alert alert-info text-capitalize text-center">
                                                This water source does not have any treasurers added. 
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div id="devices" class="tab-pane fade">
                                    <div class="row margin-top-20">
                                        <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                                            <input type="hidden" id="e_water_source_id" value="<?php echo $water_source_data['id_water_source']; ?>"/>
                                            <div id="devicesMapContainer">

                                            </div>
                                        </div>                                
                                    </div>
                                    <div id="missingDevicesMapContainer" class="margin-top-20">

                                    </div>
                                </div>
                            </div>
                        </div>                       
                    </div>                
                </div>                
            </div>                             
        </div>        
    </div>    
</div>
<script type="text/javascript" src="/assets/libs/morris/morris.js"></script>
<script type="text/javascript" src="/assets/libs/raphael/raphael.js"></script>
<script type="text/javascript" src="/assets/libs/jquery.blockUI/jquery.blockUI.js" type="text/javascript" ></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js"></script>
<script type="text/javascript">
    $(function () {
        var sales_data = <?php echo json_encode($sales_data); ?>;
        var keys = Object.keys(sales_data[getHighestIndex(sales_data)]).remove('sale_date');
        Morris.Line({
            element: 'sales-graph',
            data: sales_data,
            //smooth: true,
            xkey: 'sale_date',
            ykeys: keys,
            labels: keys,
            //lineColors: lineColors,
            resize: true
        });

        var water_users_data = <?php echo json_encode($water_users_data); ?>;
        var keys = Object.keys(water_users_data[getHighestIndex(water_users_data)]).remove('date_added');
        Morris.Line({
            element: 'new-registrations',
            data: water_users_data,
            //smooth: true,
            xkey: 'date_added',
            ykeys: keys,
            labels: keys,
            //lineColors: lineColors,
            resize: true
        });
        var elemName = "devicesMapContainer";
        if ($("#" + elemName).length >= 1) {

            WATER_SOURCE_COORDINATES = [<?php echo isset($water_source['water_source_coordinates']) && !empty($water_source['water_source_coordinates']) ? (is_numeric($water_source['water_source_coordinates'][0]) ? $water_source['water_source_coordinates'] : $CONFIG['default_locale_coordinates']) : $CONFIG['default_locale_coordinates']; ?>];

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href") // activated tab
                if (target === "#devices") {
                    var map = new google.maps.Map(document.getElementById(elemName), {
                        zoom: 8,
                        center: new google.maps.LatLng(WATER_SOURCE_COORDINATES[0], WATER_SOURCE_COORDINATES[1]),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    google.maps.event.addListenerOnce(map, 'tilesloaded', function () {
                        $("#" + elemName).unblock();
                    });

                    var infowindow = new google.maps.InfoWindow();
                    var marker;
                    var water_source_id = $("#e_water_source_id").val();
                    $.ajax({
                        type: "POST",
                        url: "/manage/water-source-savings/?a=ajax&v=devices-map&id=" + water_source_id,
                        dataType: "json",
                        beforeSend: function () {
                            $("#" + elemName).block({
                                message: '<h1>Loading</h1>',
                                css: {border: '3px solid #a00'}
                            });
                            // $(target).html('<div class="text-center" style="font-size: 20px;">Loading</div>')
                        },
                        success: function (data) {
                            //$(target).html(data);                           
                            var seen_devices = $(data.seen_devices);
                            var missing_devices = $(data.missing_devices);
                            if (seen_devices.length > 0) {
                                seen_devices.each(function (index, item) {
                                    var locations = item.last_known_location.split(',');
                                    marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(parseFloat(locations[0]), parseFloat(locations[1])),
                                        map: map
                                    });


                                    var html = "<div class=\"text-center\"><strong>" + item.fname + " " + item.lname + "</strong>\n\
                                        <br/>" + item.pnumber + "<br/>\n\
                                            Last Seen:" + item.last_login
                                            + "</div>";

                                    google.maps.event.addListener(marker, 'click', (function (marker, html) {
                                        return function () {
                                            infowindow.setContent(html);
                                            infowindow.open(map, marker);
                                        }
                                    })(marker, html));
                                });
                            }

                            if (missing_devices.length > 0) {
                                var str = '<div class="row">\n\
                                            <div class="col-sm-12">\n\
                                                <label class="control-lable">The last known location of the following devices is unknown.</label>\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="row">';
                                missing_devices.each(function (index, item) {
                                    str += '<div class="col-sm-3 margin-bottom-8"><label class="checkbox-inline">' + item.fname + ' ' + item.lname + ' - ' + item.pnumber + '</label></div>';
                                });
                                str += "</div>";
                                $("#missingDevicesMapContainer").html(str);
                            }

                        },
                        error: function (xhr) {
                            //.log(xhr);
                            alert(" An error occured, please try again later");
                        }, complete: function (xhr) {
                            //console.log(xhr); 
                        }
                    });
                }
            });
        }



    });

</script>