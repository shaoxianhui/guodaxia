jQuery(document).ready(function ($) {
    "use strict";
    /* Load PhotoSwipes (Image galleries) */
    var options = {}; // Set the options to a variable
    if ($('#gallery-swiper a').length) { // Check if the targetted element exists (avoiding console errors)
        $('#gallery-swiper a').photoSwipe(options); // Call the initializer function
    }
    if ($('.gallery a').length) {
        $('.gallery a').photoSwipe(options);
    }
    if ($('.photography a').length) {
        $('.photography a').photoSwipe(options);
    }

    /* Notification Box events */
    $('.notification').append('<div class="close">X</div>');
    $('.notification .close').click(function () {
        $(this).parent().animate({ opacity: "0" });
        $(this).parent().slideUp();
    });

    /* Vertical aligner (used in signin.html) */
    $(window).resize(function () {
        var mt = ($('body').height() / 2) - ($('.content').height() / 2) - 30;
        if (mt < 8) mt = 8;
        $('.vertical-centered').css({
            marginTop: mt + "px"
        });
    });
    $(window).resize();
});

