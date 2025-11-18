(function ($) {
    'use strict';

    function parseOrDefault(val, def) {
        var n = parseInt(val, 10);
        return isNaN(n) || n <= 0 ? def : n;
    }

    /**
     * Device mode dall'API di Elementor.
     */
    function getElementorDeviceMode() {
        if (typeof elementorFrontend !== 'undefined' &&
            typeof elementorFrontend.getCurrentDeviceMode === 'function') {
            return elementorFrontend.getCurrentDeviceMode(); // 'desktop' | 'tablet' | 'mobile'
        }
        return 'desktop';
    }

    /**
     * True se siamo in edit mode Elementor.
     */
    function isElementorEditMode() {
        return (typeof elementorFrontend !== 'undefined' &&
            typeof elementorFrontend.isEditMode === 'function' &&
            elementorFrontend.isEditMode());
    }

    function initEdluProductSlider($wrapper) {
        var $swiperEl = $wrapper.find('.edlu-product-swiper');

        if (!$swiperEl.length) {
            return;
        }

        // Evita doppia inizializzazione sullo stesso wrapper
        if ($wrapper.data('edluSwiperInitialized')) {
            return;
        }
        $wrapper.data('edluSwiperInitialized', true);

        var enableSlider = ($wrapper.attr('data-enable-slider') === 'yes');
        if (!enableSlider) {
            return;
        }

        // Config da PHP
        var colsDesktop = parseOrDefault($wrapper.attr('data-cols-desktop'), 4);
        var colsTablet  = parseOrDefault($wrapper.attr('data-cols-tablet'), colsDesktop);
        var colsMobile  = parseOrDefault($wrapper.attr('data-cols-mobile'), 1);

        var rowsDesktop = parseOrDefault($wrapper.attr('data-rows-desktop'), 2);
        var rowsTablet  = parseOrDefault($wrapper.attr('data-rows-tablet'), rowsDesktop);
        var rowsMobile  = parseOrDefault($wrapper.attr('data-rows-mobile'), 1);

        var nextEl = $wrapper.find('.edlu-next')[0] || null;
        var prevEl = $wrapper.find('.edlu-prev')[0] || null;

        var editMode = isElementorEditMode();
        var swiperOptions;

        if (editMode) {
            // 🟣 EDITOR: usiamo sempre il layout legato al device selezionato
            var device = getElementorDeviceMode();
            var c = colsDesktop;
            var r = rowsDesktop;

            if (device === 'tablet') {
                c = colsTablet;
                r = rowsTablet;
            } else if (device === 'mobile') {
                c = colsMobile;
                r = rowsMobile;
            }

            var group = Math.max(1, c * r);

            swiperOptions = {
                slidesPerView: c,
                spaceBetween: 20,
                grid: {
                    rows: r,
                    fill: 'row'
                },
                slidesPerGroup: group,
                loop: false,
                speed: 450
            };
        } else {
            // 🟢 FRONTEND: breakpoints normali
            var groupDesktop = Math.max(1, colsDesktop * rowsDesktop);
            var groupTablet  = Math.max(1, colsTablet * rowsTablet);
            var groupMobile  = Math.max(1, colsMobile * rowsMobile);

            swiperOptions = {
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
                    768: {
                        slidesPerView: colsTablet,
                        grid: {
                            rows: rowsTablet,
                            fill: 'row'
                        },
                        slidesPerGroup: groupTablet
                    },
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
        }

        if (nextEl && prevEl) {
            swiperOptions.navigation = {
                nextEl: nextEl,
                prevEl: prevEl
            };
        }

        // eslint-disable-next-line no-undef
        new Swiper($swiperEl[0], swiperOptions);
    }

    /**
     * Inizializza tutti gli slider presenti nel DOM.
     */
    function initAllSliders() {
        $('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
            initEdluProductSlider($(this));
        });
    }

    // FRONTEND NORMALE
    $(function () {
        initAllSliders();
    });

    // EDITOR ELEMENTOR
    $(window).on('elementor/frontend/init', function () {

        // Quando un widget viene renderizzato / ricaricato
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.hooks) {
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
                $scope.find('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
                    initEdluProductSlider($(this));
                });
            });
        }

        // Inizializzazione di sicurezza
        initAllSliders();
    });

})(jQuery);
