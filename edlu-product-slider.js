(function ($) {
    'use strict';

    function initEdluProductSlider($wrapper) {
        var $swiperEl = $wrapper.find('.edlu-product-swiper');

        if (!$swiperEl.length) {
            return;
        }

        var enableSlider = ($wrapper.attr('data-enable-slider') === 'yes');
        if (!enableSlider) {
            return;
        }

        // Colonne & righe dai data-attributes
        function parseOrDefault(val, def) {
            var n = parseInt(val, 10);
            return isNaN(n) || n <= 0 ? def : n;
        }

        var colsDesktop = parseOrDefault($wrapper.attr('data-cols-desktop'), 4);
        var colsTablet  = parseOrDefault($wrapper.attr('data-cols-tablet'), colsDesktop);
        var colsMobile  = parseOrDefault($wrapper.attr('data-cols-mobile'), 1);

        var rowsDesktop = parseOrDefault($wrapper.attr('data-rows-desktop'), 2);
        var rowsTablet  = parseOrDefault($wrapper.attr('data-rows-tablet'), rowsDesktop);
        var rowsMobile  = parseOrDefault($wrapper.attr('data-rows-mobile'), 1);

        var groupDesktop = Math.max(1, colsDesktop * rowsDesktop);
        var groupTablet  = Math.max(1, colsTablet * rowsTablet);
        var groupMobile  = Math.max(1, colsMobile * rowsMobile);

        var nextEl = $wrapper.find('.edlu-next')[0] || null;
        var prevEl = $wrapper.find('.edlu-prev')[0] || null;

        var options = {
            // base: mobile
            slidesPerView: colsMobile,
            spaceBetween: 20,
            grid: {
                rows: rowsMobile,
                fill: 'row'
            },
            slidesPerGroup: groupMobile,
            loop: false,
            speed: 450,
            breakpoints: {
                // Tablet: >= 768px
                768: {
                    slidesPerView: colsTablet,
                    grid: {
                        rows: rowsTablet,
                        fill: 'row'
                    },
                    slidesPerGroup: groupTablet
                },
                // Desktop: >= 1025px
                1025: {
                    slidesPerView: colsDesktop,
                    grid: {
                        rows: rowsDesktop,
                        fill: 'row'
                    },
                    slidesPerGroup: groupDesktop
                }
            }
        };

        if (nextEl && prevEl) {
            options.navigation = {
                nextEl: nextEl,
                prevEl: prevEl
            };
        }

        // eslint-disable-next-line no-undef
        new Swiper($swiperEl[0], options);
    }

    $(function () {
        $('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
            initEdluProductSlider($(this));
        });
    });

})(jQuery);
