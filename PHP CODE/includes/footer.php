<div class="site-footer">
    <div class="container">               
        <div class="copyright clearfix">
            <p>
                <b><?php echo SYSTEM_NAME; ?> v 1.0</b>
            <p>&copy; <?php echo date("Y"); ?> All rights reserved. </p>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/assets/js/moment.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="/assets/js/flot/jquery.flot.js"></script>
<script type="text/javascript" src="/assets/js/flot/jquery.flot.time.js"></script>
<script type="text/javascript" src="/assets/js/date.format/date.format.js"></script>
<script type="text/javascript" src="/assets/js/jquery.dataTables/jquery.dataTables.js"></script>
<script type="text/javascript">


    function timeConverter(UNIX_timestamp) {
        UNIX_timestamp = parseInt(UNIX_timestamp);
        //return new Date(UNIX_timestamp).format('D d-M-Y h:i:s');
        return new Date(UNIX_timestamp).format('F Y');
    }

    function numberWithCommas(n) {
        var parts = n.toString().split(".");
        return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
    }

    $(function() {

        //var d = [[1196463600000, 0], [1196550000000, 0], [1196636400000, 0], [1196722800000, 77], [1196809200000, 3636], [1196895600000, 3575], [1196982000000, 2736], [1197068400000, 1086], [1197154800000, 676], [1197241200000, 1205], [1197327600000, 906], [1197414000000, 710], [1197500400000, 639], [1197586800000, 540], [1197673200000, 435], [1197759600000, 301], [1197846000000, 575], [1197932400000, 481], [1198018800000, 591], [1198105200000, 608], [1198191600000, 459], [1198278000000, 234], [1198364400000, 1352], [1198450800000, 686], [1198537200000, 279], [1198623600000, 449], [1198710000000, 468], [1198796400000, 392], [1198882800000, 282], [1198969200000, 208], [1199055600000, 229], [1199142000000, 177], [1199228400000, 374], [1199314800000, 436], [1199401200000, 404], [1199487600000, 253], [1199574000000, 218], [1199660400000, 476], [1199746800000, 462], [1199833200000, 448], [1199919600000, 442], [1200006000000, 403], [1200092400000, 204], [1200178800000, 194], [1200265200000, 327], [1200351600000, 374], [1200438000000, 507], [1200524400000, 546], [1200610800000, 482], [1200697200000, 283], [1200783600000, 221], [1200870000000, 483], [1200956400000, 523], [1201042800000, 528], [1201129200000, 483], [1201215600000, 452], [1201302000000, 270], [1201388400000, 222], [1201474800000, 439], [1201561200000, 559], [1201647600000, 521], [1201734000000, 477], [1201820400000, 442], [1201906800000, 252], [1201993200000, 236], [1202079600000, 525], [1202166000000, 477], [1202252400000, 386], [1202338800000, 409], [1202425200000, 408], [1202511600000, 237], [1202598000000, 193], [1202684400000, 357], [1202770800000, 414], [1202857200000, 393], [1202943600000, 353], [1203030000000, 364], [1203116400000, 215], [1203202800000, 214], [1203289200000, 356], [1203375600000, 399], [1203462000000, 334], [1203548400000, 348], [1203634800000, 243], [1203721200000, 126], [1203807600000, 157], [1203894000000, 288]];

<?php
global $dbhandle;
$sales = array();

$query = "SELECT id_sale,sale_date,time_stamp,SUM(inflow) AS total_savings FROM (SELECT *,EXTRACT(MONTH FROM sale_date) as month, 
EXTRACT(YEAR FROM sale_date) as year, sale_date AS time_stamp, (floor((sale_ugx) * (percentage_saved / 100) / 100) * 100) AS inflow FROM " . TABLE_PREFIX . "sales WHERE submitted_to_treasurer=1 AND status=1) AS T GROUP BY month";

$result = $dbhandle->RunQueryForResults($query);
if (isset($result->num_rows) && $result->num_rows > 0) {
    while ($sale = $result->fetch_assoc()) {
        if (!empty($sale['time_stamp'])) {
            $sales[] = array(
                strtotime($sale['time_stamp']) * 1000,
                intval($sale['total_savings']
                )
            );
        }
    }
}
?>
        var d = <?php echo json_encode($sales); ?>;
        // first correct the timestamps - they are recorded as the daily
        // midnights in UTC+0100, but Flot always displays dates in UTC
        // so we have to add one hour to hit the midnights in the plot

        for (var i = 0; i < d.length; ++i) {
            d[i][0] += 60 * 60 * 1000;
        }

        // helper for returning the weekends in a period

        function weekendAreas(axes) {

            var markings = [], d = new Date(axes.xaxis.min);
            // go to the first Saturday
            d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7));
            d.setUTCSeconds(0);
            d.setUTCMinutes(0);
            d.setUTCHours(0);

            var i = d.getTime();

            // when we don't set yaxis, the rectangle automatically
            // extends to infinity upwards and downwards
            do {
                markings.push({xaxis: {from: i, to: i + 2 * 24 * 60 * 60 * 1000}});
                i += 7 * 24 * 60 * 60 * 1000;
            } while (i < axes.xaxis.max);
            return markings;
        }

        var options = {
            series: {
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            xaxis: {
                mode: "time",
                tickLength: 5
            },
            selection: {
                mode: "x"
            },
            grid: {
                markings: weekendAreas,
                hoverable: true,
                clickable: true
            }
        };
        if ($('#placeholder').length > 0) {
            var plot = $.plot("#placeholder", [
                {
                    data: d,
                    label: "Total Monthly Savings"
                }
            ], options);
        }

        $("<div id='tooltip'></div>").css({
            position: "absolute",
            display: "none",
            border: "1px solid #fdd",
            padding: "2px",
            "background-color": "#fee",
            opacity: 0.80
        }).appendTo("body");

        $("#placeholder").bind("plothover", function(event, pos, item) {
            if (item) {
                var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);

                $("#tooltip").html(item.series.label + " of " + timeConverter(x) + " = " + numberWithCommas(y))
                        .css({top: item.pageY + 5, left: item.pageX + 5})
                        .fadeIn(200);
            } else {
                $("#tooltip").hide();
            }

        });

        $("#placeholder").bind("plotclick", function(event, pos, item) {
            if (item) {
                $("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
                plot.highlight(item.series, item.datapoint);
            }
        });

    });

    function water_sources_map() {
        if (typeof google === 'undefined') {
            return;
        }
        // map options
        var options = {
            zoom: 12,
            center: new google.maps.LatLng(<?php echo $SYSTEM_CONFIG['default_locale_coordinates']; ?>),
            mapTypeControl: false
        };

        // init map
        if ($('#map-canvas').length > 0) {
            var map = new google.maps.Map(document.getElementById('map-canvas'), options);
        }


<?php
$water_sources = array();
global $dbhandle;
$p = $dbhandle->Fetch("water_sources");
if (is_array($p) && !empty($p) && !isset($p[0])) {
    $water_sources[] = $p;
} elseif (is_array($p)) {
    $water_sources = $p;
}

$counter = 0;
foreach ($water_sources as $water_source) {
    ?>
            // init markers
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(<?php echo $water_source['water_source_coordinates']; ?>),
                map: map,
                title: '<?php echo $water_source['water_source_name']; ?>'
            });

            // process multiple info windows
            (function(marker) {
                // add click event
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow = new google.maps.InfoWindow({
                        content: '<strong>Pump ID:</strong> <?php echo $water_source['water_source_id']; ?>'
                    });
                    infowindow.open(map, marker);
                });
            })(marker);
    <?php
    $counter+=1;
}
?>
    }
    ;

    function handleMap(ElementClicked, LatitudeLongitudeContainer) {
        var geocoder = new google.maps.Geocoder();
        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    updateMarkerAddress(responses[0].formatted_address);
                } else {
                    updateMarkerAddress('Cannot determine address at this location.');
                }
            });
        }

        function updateMarkerStatus(str) {
            //document.getElementById('markerStatus').innerHTML = str;
        }

        function updateMarkerPosition(latLng) {
            $(LatitudeLongitudeContainer).val([
                latLng.lat(),
                latLng.lng()
            ].join(', '));
        }

        function updateMarkerAddress(str) {
            $(ElementClicked).val(str);
        }

        function initialize() {

<?php
if ($action === 'edit-water-source') {
    $id_water_source = getArrayVal($_GET, 'id');
    $query = "SELECT * FROM " . TABLE_PREFIX . "water_sources WHERE id_water_source=$id_water_source";
    $result = $dbhandle->RunQueryForResults($query);
    $water_source = $result->fetch_assoc();
}
?>

            var latLng = new google.maps.LatLng(<?php echo isset($water_source['water_source_coordinates']) ? $water_source['water_source_coordinates'] : $SYSTEM_CONFIG['default_locale_coordinates']; ?>);
            var map = new google.maps.Map(document.getElementById('addRouteCanvas'), {
                zoom: 13,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var marker = new google.maps.Marker({
                position: latLng,
                title: 'Place on a point to get you the location',
                map: map,
                draggable: true,
                icon: 'http://maps.google.com/mapfiles/ms/icons/purple.png'
            });
            // Update current position info.
            updateMarkerPosition(latLng);
            geocodePosition(latLng);
            // Add dragging event listeners.
            google.maps.event.addListener(marker, 'dragstart', function() {
                updateMarkerAddress('Dragging...');
            });
            google.maps.event.addListener(marker, 'drag', function() {
                updateMarkerStatus('Dragging...');
                updateMarkerPosition(marker.getPosition());
            });
            google.maps.event.addListener(marker, 'dragend', function() {
                updateMarkerStatus('Drag ended');
                geocodePosition(marker.getPosition());
            });
        }
        initialize();
        // Onload handler to fire off the app.
        //google.maps.event.addDomListener(window, 'load', initialize);
    }


    $(document).ready(function() {

        if ($("#sold_to").val() === '0') {
            $('.sale_ugx_div').show(500);
        } else {
            $('.sale_ugx_div').hide(500);
        }

        $("#sold_to").change(function() {
            if ($(this).val() === '0') {
                $('.sale_ugx_div').show(500);
            } else {
                $('.sale_ugx_div').hide(500);
            }
        });

        water_sources_map();

        $('.managed-table').dataTable({
            "bStateSave": true,
            "aLengthMenu": [
                [10, 25, 50, 100, 250, 500, 750, 1000, -1],
                [10, 25, 50, 100, 250, 500, 750, 1000, "All"]
            ],
            "iDisplayLength": 10,
            "aaSorting": [[0, "desc"]], // Sort by first column descending
            "bProcessing": true,
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "fnDrawCallback": function(sSource) {
                $('.delete-link').click(function(event) {
                    var result = confirm("Delete this record?");
                    if (result !== true) {
                        event.preventDefault();
                    }
                });
            }
        });

        $('.ajax-managed-table').dataTable({
            "bStateSave": true,
            "aLengthMenu": [
                [10, 25, 50, 100, 250, 500, 750, 1000, -1],
                [10, 25, 50, 100, 250, 500, 750, 1000, "All"]
            ],
            "iDisplayLength": 10,
            "aaSorting": [[0, "desc"]], // Sort by first column descending
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": window.location.protocol + "//" + window.location.hostname + "/ajax.php",
            "bPaginate": true,
            "sPaginationType": "full_numbers",
            "fnDrawCallback": function(sSource) {
                $('.delete-link').click(function(event) {
                    var result = confirm("Delete this record?");
                    if (result !== true) {
                        event.preventDefault();
                    }
                });
            }
        });

        $('.datetimepicker').datetimepicker();

        /*
         $('.delete-link').click(function(event) {
         var result = confirm("Delete this record?");
         if (result !== true) {
         event.preventDefault();
         }
         });*/

        if ($("#addRouteCanvas").length >= 1) {
            try {
                handleMap("#water_source_location", "#water_source_coordinates");
            } catch (err) {

            }

            $('#water_source_location').click(function() {
                try {
                    // handleMap('#water_source_location', "#water_source_coordinates");
                } catch (err) {

                }

            });
        }
    });

</script>
</body>
</html>
