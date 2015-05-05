//Scroll Containers
$('.swiper-nested').each(function () {
    var swipernested = $(this).swiper({
        mode: 'vertical',
        scrollContainer: true,
        mousewheelControl: true,
        scrollbar: {
            container: $(this).find('.swiper-scrollbar')[0]
        }
    });

    var h = $('.pages_container').css("height");

    h = parseInt(h.replace("px", ""))*1.4;
//    alert(h);
    $('.pages_container').css("height", h + "px");

})

