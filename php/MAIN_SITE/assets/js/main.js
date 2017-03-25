
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


postData = function (data, url, callback, dataType, requestType) {
    if (!dataType) {
        dataType = 'json';
    }

    if (!requestType) {
        requestType = 'POST';
    }

    $.ajax({
        type: requestType,
        async: true,
        cache: false,
        url: url,
        dataType: dataType,
        data: data,
        beforeSend: function () {

        },
        success: function (data) {
            if (typeof callback === 'function') {
                callback(data);
            }
        },
        error: function (xhr) {
            //new Exception("An error occured", xhr);
            if (typeof callback === 'function') {
                callback(Lang.error_occured_fetching_ajax);
            }
        }, complete: function (xhr) {

        }
    });
};

jQuery(document).ready(function ($) {
    $('body').scrollspy({target: '#top', offset: 400});
    $('a.scrollto').on('click', function (e) {
        var target = this.hash;
        e.preventDefault();
        $('body').scrollTo(target, 800, {offset: -80, 'axis': 'y', easing: 'easeOutQuad'});
        if ($('.navbar-collapse').hasClass('in')) {
            $('.navbar-collapse').removeClass('in').addClass('collapse');
        }
        document.title = $(this).text() + " | PM4W";
        $('.nav-item').removeClass("active");
        $(this).parent().addClass("active");
    });
    var curr_hash = window.location.hash;
    $("#navbar-collapse").each(function (index, item) {
        // console.log($(item).find($("a")).prop("href"))
        $(item).find($("a")).each(function (a, b) {
            var url = $(b).prop("href");
            var hash = url.substring(url.indexOf('#')); // '#foo'
            if (curr_hash === hash) {
                $(b).parent().addClass("active");
            }
        });
    });

    $('.flexslider').flexslider({
        animation: "fade",
        touch: true,
        directionNav: false
    });

    $('input, textarea').placeholder();
    $("#video-container").fitVids();
    $('#testimonials .quote-box').matchHeight();
    $('#config-trigger').on('click', function (e) {
        var $panel = $('#config-panel');
        var panelVisible = $('#config-panel').is(':visible');
        if (panelVisible) {
            $panel.hide();
        } else {
            $panel.show();
        }
        e.preventDefault();
    });

    $('#config-close').on('click', function (e) {
        e.preventDefault();
        $('#config-panel').hide();
    });


    $('#color-options a').on('click', function (e) {
        var $styleSheet = $(this).attr('data-style');
        var $logoImage = $(this).attr('data-logo');
        $('#theme-style').attr('href', $styleSheet);
        $('#logo-image').attr('src', $logoImage);
        var $listItem = $(this).closest('li');
        $listItem.addClass('active');
        $listItem.siblings().removeClass('active');
        e.preventDefault();
    });

    $("form").submit(function (e) {
        e.preventDefault();
        $.notify({
            message: 'Sending. Please wait'
        }, {
            type: 'info',
            delay: 1000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            }
        });
        var name = $("#name"), email = $("#email"), message = $("#message"), g_recaptcha_response = grecaptcha.getResponse().trim();
        var data = {
            'name': name.val().trim(),
            'email': email.val().trim(),
            'message': message.val().trim(),
            'g-recaptcha-response': g_recaptcha_response
        };
        postData(data, '?', function (server_reply) {
            var type = 'danger';
            if (server_reply.code === 2) {
                type = 'success';
            }
            if (typeof server_reply.msgs === 'object') {
                $(server_reply.msgs).each(function (index) {
                    $.notify({
                        message: server_reply.msgs[index]
                    }, {
                        type: type,
                        delay: 1000,
                        animate: {
                            enter: 'animated fadeInDown',
                            exit: 'animated fadeOutUp'
                        }
                    });
                });
            } else {
                $.notify({
                    message: server_reply
                }, {
                    type: type,
                    delay: 1000,
                    animate: {
                        enter: 'animated fadeInDown',
                        exit: 'animated fadeOutUp'
                    }
                });
            }
            name.val('');
            email.val('');
            message.val('');
            grecaptcha.reset();
        });
    });
});
