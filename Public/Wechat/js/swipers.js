(function ($) {
    "use strict";
    /* Swiper declaration (index.html) */
    var sliderSwiper = new Swiper('#slider-swiper', {
        loop: true,
        grabCursor: true,
        calculateHeight: true,
        autoResize: true,
        autoplay: 5000
    });
    /* Height fix for fluid swipers when orieantation changes */
    $(window).resize(function () {
        if ($('#slider-swiper').length) { // Check if the targetted element exists (avoiding console errors)
            sliderSwiper.reInit();
        }
    });

    /* Swiper declaration (gallery.html) */
    var gallerySwiper = new Swiper('#gallery-swiper', {
        grabCursor: true,
        calculateHeight: true,
        freeMode: true,
        freeModeFluid: true,
        slidesPerView: 'auto',
        scrollbar: {
            container: '#gallery-swiper-scrollbar',
            hide: false,
            draggable: true
        }
    });
})(jQuery);