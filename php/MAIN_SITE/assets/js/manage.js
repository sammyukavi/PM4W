//Google analytics
(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-60032029-1', 'auto');
ga('send', 'pageview');



////Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {
    $(window).bind("load resize", function () {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }
        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1)
            height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
    var url = window.location;
    var element = $('ul.nav a').filter(function () {
        return this.href === url;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});

Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

Object.size = function (obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key))
            size++;
    }
    return size;
};

function getHighestIndex(object) {
    var highest = 0;
    var index_point = 0;
    $(object).each(function (index, element) {
        var count = Object.size(element);
        if (count > highest) {
            highest = count;
            index_point = index;
        }
    });
    return index_point;
}

function countColumns(table) {
    return $(table + " tr:first th").length;
}

function initilizeSelectPicker() {
    $('.selectpicker-with-search').selectpicker({
        style: 'btn-default',
        size: 8,
        liveSearch: true
    });

    $('.selectpicker').selectpicker({
        style: 'btn-default',
        size: 8
    });
}

$(function () {
    $('#side-menu').metisMenu();
    initilizeSelectPicker();

    $('.datetimepicker').datetimepicker();
    $('.datetimepickerNoPast').datetimepicker({
        minDate: new Date()
    });

});


$(function () {
    var elem = $("select.alter-pump-id");
    var target = '#water_source_id_target';
    if (elem.length < 1) {
        return;
    }

    var uid = elem.val();
    var water_source_id = $("#water_source_id").val();
    doMore(uid, water_source_id);
    elem.change(function () {
        uid = $(this).val();
        doMore(uid);
    });
    function doMore(uid, water_source_id) {
        if (typeof water_source_id === 'undefined') {
            water_source_id = "";
        }
        $.ajax({
            type: "POST",
            url: "/manage/water-users/?a=water-sources&uid=" + uid + "&water_source_id=" + water_source_id,
            dataType: "text",
            beforeSend: function () {
                $(target).html('<div class="text-center" style="font-size: 20px;">Loading</div>')
            },
            success: function (data) {
                $(target).html(data);
            },
            error: function (xhr) {
                //.log(xhr);
                alert(" An error occured, please try again later");
            }, complete: function (xhr) {
                initilizeSelectPicker();
            }
        });
    }
});

$(function () {
    var elem = "#water_source_id";
    var water_source_id;
    if ($(elem).length < 1) {
        return;
    }

    doStuff();
    $(elem).change(function () {
        doStuff();
    });
    function doStuff() {
        water_source_id = $(elem).val();
        var sold_by = $("#sold_by").val();
        var target = "#sold_by_target";
        $.ajax({
            type: "POST",
            url: "/manage/sales?a=fetch-attendants&water_source_id=" + water_source_id + "&sold_by=" + sold_by,
            dataType: "text",
            beforeSend: function () {
                $(target).html('<div class="text-center" style="font-size: 20px;">Loading</div>')
            },
            success: function (data) {
                $(target).html(data);
            },
            error: function (xhr) {
                //console.log(xhr);
                alert(" An error occured, please try again later");
            }, complete: function (xhr) {
                initilizeSelectPicker();
                doMore();
            }
        });
    }

    function doMore() {
        var elem = "#sold_to";
        var target = "#sold_to_target";
        var sold_to = $(elem).val();
        doEvenMore(target, elem);
        $.ajax({
            type: "POST",
            url: "/manage/sales/?a=fetch-water-users&water_source_id=" + water_source_id + "&sold_to=" + sold_to,
            dataType: "text",
            beforeSend: function () {
                $(target).html('<div class="text-center" style="font-size: 20px;">Loading</div>')
            },
            success: function (data) {
                $(target).html(data);
            },
            error: function (xhr) {
                //console.log(xhr);
                alert(" An error occured, please try again later");
            }, complete: function (xhr) {
                initilizeSelectPicker();
                doEvenMore(target, elem);
            }
        });
    }

    function doEvenMore(target, elem) {
        if ($(target).find(elem).val() != 0) {
            $(".sale_ugx_div").fadeOut(300);
        } else {
            $(".sale_ugx_div").fadeIn(300);
        }

        $(target).find(elem).change(function () {
            if ($(this).val() != 0) {
                $(".sale_ugx_div").fadeOut(300);
            } else {
                $(".sale_ugx_div").fadeIn(300);
            }
        });
    }
});

function handleMap(ElementClicked, LatitudeLongitudeContainer) {
    var geocoder = new google.maps.Geocoder();
    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function (responses) {
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
        // $(ElementClicked).val(str);
    }

    function initialize() {
        var latLng = new google.maps.LatLng(WATER_SOURCE_COORDINATES[0], WATER_SOURCE_COORDINATES[1]);
        var map = new google.maps.Map(document.getElementById('addRouteCanvas'), {
            zoom: 13,
            center: latLng,
            mapTypeId: 'roadmap'
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
        google.maps.event.addListener(marker, 'dragstart', function () {
            updateMarkerAddress('Dragging...');
        });
        google.maps.event.addListener(marker, 'drag', function () {
            updateMarkerStatus('Dragging...');
            updateMarkerPosition(marker.getPosition());
        });
        google.maps.event.addListener(marker, 'dragend', function () {
            updateMarkerStatus('Drag ended');
            geocodePosition(marker.getPosition());
        });
    }
    initialize();
    // Onload handler to fire off the app.
    //google.maps.event.addDomListener(window, 'load', initialize);
}

$(function () {
    if ($("#addRouteCanvas").length >= 1) {
        try {
            handleMap("#water_source_location", "#water_source_coordinates");
        } catch (err) {
            console.log(err);
        }

        $('#water_source_location').click(function () {
            try {
                // handleMap('#water_source_location', "#water_source_coordinates");
            } catch (err) {

            }

        });
    }
});

function initCallbacks() {
    var checkAll;
    var checkboxes;
    $('input.check').iCheck('destroy');
    checkAll = $('.checkAll');
    checkboxes = $('input.check');
    checkboxes.iCheck('uncheck');
    checkAll.on('ifChecked ifUnchecked', function (event) {
        if (event.type === 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    checkboxes.on('ifChanged', function (event) {
        if (checkboxes.filter(':checked').length === checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
    checkboxes.on('ifChanged', function (event) {
        if (checkboxes.filter(':checked').length > 0) {
        }
    });

    $(".check").iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green',
        increaseArea: '20%'
    });
    $("a.delete-link").click(function (event) {
        if (confirm("Are you sure you want to delete? This action is irreversible")) {

        } else {
            event.preventDefault();
        }
    });

    $('[data-toggle="tooltip"]').tooltip();
}

function handleDatatables() {
    var tableName = '.ajax-powered-datatable';
    var URL = '?a=ajax';

    $(tableName).dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": URL,
        "lengthMenu": [[100, 250, 500, 1000, -1], [100, 250, 500, 1000, "All"]],
        "aoColumnDefs": [
            {
                'bSortable': false,
                //'aTargets': [0, (countColumns(tableName) - 1)]
            }
        ],
        //"dom": 'T<"clear">lfrtip',
        "tableTools": {
            "sSwfPath": "/assets/libs/jquery-datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
            , "aButtons": ["copy",
                "csv",
                "xls",
                {
                    "sExtends": "pdf",
                    //"sPdfMessage": SITE_URL,
                },
                "print"
            ]},
        "drawCallback": function () {

        }, "initComplete": function (settings, json) {

        }});
    $(tableName).on('draw.dt', function () {
        initCallbacks();
    });

    tableName = '.managed-table';

    $(tableName).dataTable({
        "processing": true,
        "lengthMenu": [[100, 250, 500, 1000, -1], [100, 250, 500, 1000, "All"]],
        "aoColumnDefs": [
            {
                'bSortable': false,
                'aTargets': [0, (countColumns(tableName) - 1)]
            }
        ],
        "drawCallback": function () {

        }, "initComplete": function (settings, json) {
            initCallbacks();
        }
    });

}

function handleComposeSMS() {

    //$("#accordion .collapse").collapse("show");

    var elem = '#msg_content';
    var smscharacterCount = '#smscharacterCount';

    $(elem).focus(function () {
        $(this).height(0);
        $(this).height(this.scrollHeight);
    });

    $(elem).keyup(function () {
        $(this).height(0);
        $(this).height(this.scrollHeight);
        if (SMS_CHARACTERS_LIMIT - $(this).val().length <= 20) {
            $(smscharacterCount).addClass('btn-danger');
        } else {
            $(smscharacterCount).removeClass('btn-danger');
        }
        $(smscharacterCount).html(SMS_CHARACTERS_LIMIT - $(this).val().length);
    });

    $(elem).bind('input propertychange', function () {
        $(this).height(0);
        $(this).height(this.scrollHeight);
        if (SMS_CHARACTERS_LIMIT - $(this).val().length <= 20) {
            $(smscharacterCount).addClass('btn-danger');
        } else {
            $(smscharacterCount).removeClass('btn-danger');
        }
        $(smscharacterCount).html(SMS_CHARACTERS_LIMIT - $(this).val().length);
    });

    var schedule = "#scheduled";
    var scheduleDateContainer = "#scheduledDateContainer";
    scheduleDateContainer = $(scheduleDateContainer);

    $(schedule).change(function () {
        if ($(this).val() === "setDate") {
            scheduleDateContainer.removeClass("hidden");
        } else {
            scheduleDateContainer.addClass("hidden");
        }
    });

    if ($(schedule).val() === "setDate") {
        scheduleDateContainer.removeClass("hidden");
    } else {
        scheduleDateContainer.addClass("hidden");
    }
}


$(function () {
    handleDatatables();
    $('.scrollable').niceScroll({
        cursorwidth: '10px',
        cursorborder: '0px',
        railalign: 'right'
    });

    var checkAll = $('#checkAll_system_users');
    var checkboxes = $('.system_users_container .check');

    checkAll.on('ifChecked ifUnchecked', function (event) {
        if (event.type === 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    checkboxes.on('ifChanged', function (event) {
        if (checkboxes.filter(':checked').length === checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
    checkboxes.on('ifChanged', function (event) {
        if (checkboxes.filter(':checked').length > 0) {
        }
    });

    var checkAll = $('#checkAll_water_users');
    var checkboxes = $('.water_users_container .check');

    checkAll.on('ifChecked ifUnchecked', function (event) {
        if (event.type === 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    checkboxes.on('ifChanged', function (event) {
        if (checkboxes.filter(':checked').length === checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
    checkboxes.on('ifChanged', function (event) {
        if (checkboxes.filter(':checked').length > 0) {
        }
    });


    $(".check").iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green',
        increaseArea: '20%'
    });

    function toggleChevron(e) {
        $(e.target)
                .prev('.panel-heading')
                .find("i.indicator")
                .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
    }
    $('#accordion').on('hidden.bs.collapse', toggleChevron);
    $('#accordion').on('shown.bs.collapse', toggleChevron);
    handleComposeSMS();


});