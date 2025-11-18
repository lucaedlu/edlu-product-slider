(function ($) {
    'use strict';

    /**
     * Legge il device mode corrente nell'editor Elementor
     * in base alle classi sul <body> dell'iframe.
     */
    function getEditorDeviceMode() {
        var $body = $('body');

        if ($body.hasClass('elementor-device-mobile')) {
            return 'mobile';
        }
        if ($body.hasClass('elementor-device-tablet')) {
            return 'tablet';
        }

        return 'desktop';
    }

    function parseOrDefault(val, def) {
        var n = parseInt(val, 10);
        return isNaN(n) || n <= 0 ? def : n;
    }

    function initEdluProductSlider($wrapper) {
        var $swiperEl = $wrapper.find('.edlu-product-swiper');

        if (!$swiperEl.length) {
            return;
        }

        var enableSlider = ($wrapper.attr('data-enable-slider') === 'yes');
        if (!enableSlider) {
            return;
        }

        // Dati da PHP
        var colsDesktop = parseOrDefault($wrapper.attr('data-cols-desktop'), 4);
        var colsTablet  = parseOrDefault($wrapper.attr('data-cols-tablet'), colsDesktop);
        var colsMobile  = parseOrDefault($wrapper.attr('data-cols-mobile'), 1);

        var rowsDesktop = parseOrDefault($wrapper.attr('data-rows-desktop'), 2);
        var rowsTablet  = parseOrDefault($wrapper.attr('data-rows-tablet'), rowsDesktop);
        var rowsMobile  = parseOrDefault($wrapper.attr('data-rows-mobile'), 1);

        var nextEl = $wrapper.find('.edlu-next')[0] || null;
        var prevEl = $wrapper.find('.edlu-prev')[0] || null;

        var isEditMode = (typeof elementorFrontend !== 'undefined' &&
                          elementorFrontend.isEditMode &&
                          elementorFrontend.isEditMode());

        var swiperOptions;

        if (isEditMode) {
            /**
             * 🟣 EDITOR MODE
             * Usiamo sempre e solo il layout del device selezionato,
             * ignorando la larghezza reale del canvas.
             */
            var device = getEditorDeviceMode();

            var c = colsDesktop, r = rowsDesktop;

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
            /**
             * 🟢 FRONTEND (SITO REALE)
             * Breakpoints classici: mobile / tablet / desktop.
             */
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
                    768: { // tablet
                        slidesPerView: colsTablet,
                        grid: {
                            rows: rowsTablet,
                            fill: 'row'
                        },
                        slidesPerGroup: groupTablet
                    },
                    1025: { // desktop
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

        // Navigazione se presente
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
     * Inizializza:
     * - Frontend normale
     * - Editor Elementor (preview)
     */
    function initAllSliders() {
        $('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
            initEdluProductSlider($(this));
        });
    }

    // Frontend standard
    $(function () {
        initAllSliders();
    });

    // Sicurezza extra: se Elementor ricarica il contenuto in edit mode,
    // agganciamo anche all'hook di Elementor.
    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode && elementorFrontend.isEditMode()) {
            initAllSliders();
        }
    });

})(jQuery);
