(function ($) {
    'use strict';

    function initEdluProductSlider($wrapper) {
        var $swiperEl = $wrapper.find('.edlu-product-swiper');

        if (!$swiperEl.length) {
            return;
        }

        var totalPages = parseInt($wrapper.attr('data-total-pages') || '1', 10);

        // Se c'è solo una pagina, niente frecce
        var nextEl = null;
        var prevEl = null;

        if (totalPages > 1) {
            nextEl = $wrapper.find('.edlu-next')[0] || null;
            prevEl = $wrapper.find('.edlu-prev')[0] || null;
        }

        var options = {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: false,
            speed: 450,
        };

        if (nextEl && prevEl) {
            options.navigation = {
                nextEl: nextEl,
                prevEl: prevEl,
            };
        }

        // Swiper gestisce già drag / swipe (mouse + touch)
        // eslint-disable-next-line no-undef
        new Swiper($swiperEl[0], options);
    }

    $(function () {
        $('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
            initEdluProductSlider($(this));
        });
    });

})(jQuery);
