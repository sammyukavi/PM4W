<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Statistics <small>Visual system data</small>
            </h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Monetary Transactions per Month</h3>
                            <p>Count of transactions involving money</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            $cols = array('water_source_name', 'COUNT(id_sale) sales_count', 'DATE_FORMAT(sale_date,\'%Y-%m\') sale_date');
                            $App->con->orderBy("sale_date", "asc");
                            $App->con->orderBy("water_source_name", "asc");
                            $App->con->groupBy("sales.water_source_id");
                            $App->con->groupBy("Month(sale_date)");
                            $App->con->join('sales sales', 'sales.water_source_id=id_water_source', 'LEFT');
                            $transactions = $App->con->get('water_sources', null, $cols);
                            $grouped_transactions = array();
                            foreach ($transactions as $key => $transaction) {
                                $grouped_transactions[$transaction['sale_date']]['sale_date'] = $App->getCurrentDateTime($transaction['sale_date']);
                                $grouped_transactions[$transaction['sale_date']][$transaction['water_source_name']] = $transaction['sales_count'];
                            }
                            $data = array();
                            foreach ($grouped_transactions as $value) {
                                $data[] = $value;
                            }
                            ?>
                            <div class="graph" id="transactions-graph"></div>
                        </div>                       
                    </div>                
                </div> 
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Sales per Month</h3>
                            <p>Amount collected per month</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            /* $cols = array('SUM(sale_ugx) amount', 'DATE_FORMAT(sale_date,\'%Y-%m\') sale_date');
                              $App->con->orderBy("sale_date", "asc");
                              $App->con->groupBy("MONTH(sale_date)");
                              $sales = $App->con->get('sales', null, $cols); */

                            $cols = array('water_source_name', 'SUM(sale_ugx) amount', 'DATE_FORMAT(sale_date,\'%Y-%m\') sale_date');
                            $App->con->orderBy("sale_date", "asc");
                            $App->con->orderBy("water_source_name", "asc");
                            $App->con->groupBy("sales.water_source_id");
                            $App->con->groupBy("Month(sale_date)");
                            $App->con->join('sales sales', 'sales.water_source_id=id_water_source', 'LEFT');
                            $sales = $App->con->get('water_sources', null, $cols);
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
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>User Registrations per Month</h3>
                            <p>Number of water users added into the system per month</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            /* $cols = array('COUNT(id_user) total', 'DATE_FORMAT(date_added,\'%Y-%m\') date_added');
                              $App->con->orderBy("date_added", "asc");
                              $App->con->groupBy("MONTH(date_added)");
                              $water_users = $App->con->get('water_users', null, $cols); */

                            $cols = array('water_source_name', 'COUNT(id_user) total', 'DATE_FORMAT(date_added,\'%Y-%m\') date_added');
                            $App->con->orderBy("date_added", "asc");
                            $App->con->orderBy("water_source_name", "asc");
                            $App->con->groupBy("water_users.water_source_id");
                            $App->con->groupBy("Month(date_added)");
                            $App->con->join('water_users water_users', 'water_users.water_source_id=id_water_source', 'LEFT');
                            $water_users = $App->con->get('water_sources', null, $cols);
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
                            <h3>Syncs per Month</h3>
                            <p>Flow of incoming and outgoing data</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            $cols = array('COUNT(id_event) occurences,event_time', 'DATE_FORMAT(event_time,\'%Y-%m\') event_time');
                            $App->con->where("event", $App->event->EVENT_SYNC_COMPLETE);
                            $App->con->orderBy("event_time", "asc");
                            $App->con->groupBy("MONTH(event_time)");
                            $event_logs = $App->con->get('event_logs', 10, $cols);
                            ?>
                            <div id="syncs"></div>
                        </div>
                    </div>                
                </div>  
            </div>           

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3>Events</h3>
                            <p>Most Used Features of The System</p>
                        </div>
                        <div class="panel-body">
                            <?php
                            $events = array(
                                $App->event->EVENT_ATTEMPTED_LOGIN,
                                $App->event->EVENT_ADDED_SALE,
                                $App->event->EVENT_VIEWED_CARETAKER_SALES,
                                $App->event->EVENT_SUBMITTED_CARETAKER_SALES,
                                $App->event->EVENT_APPROVED_CARETAKER_SALES,
                                $App->event->EVENT_CANCELED_CARETAKER_SALES,
                                $App->event->EVENT_SUBMITTED_TREASURER_SAVINGS,
                                $App->event->EVENT_VIEWED_WATER_SOURCE_SAVINGS,
                                $App->event->EVENT_LOGGED_EXPENDITURE,
                                $App->event->EVENT_VIEWED_ACCOUNT_BALANCE,
                                $App->event->EVENT_VIEWED_MINISTATEMENT,
                                $App->event->EVENT_LISTED_WATER_USERS,
                                $App->event->EVENT_DEACTIVATED_WATER_USER_ACCOUNT
                            );
                            //$events = implode("','", array_unique($events));
                            //$sql = " SELECT COUNT(id_event) total,event FROM `" . TABLE_PREFIX . "event_logs` WHERE event IN('" . $events . "') GROUP BY event ";
                            $cols = array('COUNT(id_event) total', 'event');
                            $App->con->orderBy("total", "asc");
                            $App->con->where('event', $events, 'IN');
                            $App->con->groupBy("event");
                            $events = $App->con->get('event_logs', 10, $cols);
                            foreach ($events as $key => $event) {
                                $events[$key] = array(
                                    'total' => $event['total'],
                                    'event' => ucwords(str_replace("_", " ", $event['event']))
                                );
                            }
                            ?>
                            <div id="eventsPieChart"></div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>        
    </div>    
</div>
<script type="text/javascript" src="/assets/libs/amcharts/amcharts.js"></script>
<script type="text/javascript" src="/assets/libs/amcharts/serial.js"></script>
<script type="text/javascript" src="/assets/libs/amcharts/pie.js"></script>
<script type="text/javascript" src="/assets/libs/amcharts/themes/light.js"></script>
<script type="text/javascript" src="/assets/libs/morris/morris.js"></script>
<script type="text/javascript" src="/assets/libs/raphael/raphael.js"></script>
<script type="text/javascript">
    $(function () {
        var lineColors = [
            '#0b62a4',
            '#7A92A3',
            '#4da74d',
            '#afd8f8',
            '#edc240',
            '#cb4b4b',
            '#9440ed',
            '#e41a1c',
            '#377eb8',
            '#4daf4a',
            '#984ea3',
            '#ff7f00',
            '#ffff33',
            '#a65628',
            '#f781bf'
        ];
        var transactions_data = <?php echo json_encode($data); ?>;
        var keys = Object.keys(transactions_data[getHighestIndex(transactions_data)]).remove('sale_date');
        Morris.Line({
            element: 'transactions-graph',
            data: transactions_data,
            //smooth: true,
            xkey: 'sale_date',
            ykeys: keys,
            labels: keys,
            lineColors: lineColors,
            resize: true
        });
        var sales_data = <?php echo json_encode($sales_data); ?>;
        var keys = Object.keys(sales_data[getHighestIndex(sales_data)]).remove('sale_date');
        Morris.Line({
            element: 'sales-graph',
            data: sales_data,
            //smooth: true,
            xkey: 'sale_date',
            ykeys: keys,
            labels: keys,
            lineColors: lineColors,
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
            lineColors: lineColors,
            resize: true
        });
        var syc_data = <?php echo json_encode($event_logs); ?>;
        Morris.Line({
            element: 'syncs',
            data: syc_data,
            //smooth: true,
            xkey: 'event_time',
            ykeys: ['occurences'],
            labels: ['Sync Count'],
            lineColors: lineColors,
            resize: true
        });
        var data = <?php echo json_encode($events); ?>;
        var chart = AmCharts.makeChart("eventsPieChart", {
            "type": "pie",
            "theme": "light",
            // "path": "../../assets/global/plugins/amcharts/ammap/images/",
            "dataProvider": data,
            "valueField": "total",
            "titleField": "event",
            "outlineAlpha": 0.4,
            "depth3D": 15,
            "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> counts ([[percents]]%)</span>",
            "angle": 30,
            "export": {
                "enabled": true
            }
        });
    });
</script>
