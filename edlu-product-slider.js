(function ($) {
    'use strict';

    function initEdluProductSlider($wrapper) {
        // Pagine (slide)
        var $inner = $wrapper.find('.edlu-product-slider-inner');
        var $pages = $wrapper.find('.edlu-product-page');

        if (!$inner.length || !$pages.length || $pages.length <= 1) {
            return; // niente slider se c'è solo una pagina
        }

        var totalPages = $pages.length;
        var current = 0;
        var isAnimating = false;

        // Impostiamo il layout slider (flex con translateX)
        $inner.addClass('edlu-slider-flex');
        $pages.addClass('edlu-slider-page');

        function updatePosition() {
            var offset = -current * 100;
            $inner.css('transform', 'translateX(' + offset + '%)');
            $pages.removeClass('is-active');
            $pages.eq(current).addClass('is-active');
        }

        function goTo(index) {
            if (index < 0 || index >= totalPages) {
                return;
            }
            if (index === current || isAnimating) {
                return;
            }

            isAnimating = true;
            current = index;
            updatePosition();

            // durata animazione (match con CSS transition)
            setTimeout(function () {
                isAnimating = false;
            }, 450);
        }

        // Inizializza posizione
        updatePosition();

        // NAV: frecce
        $wrapper.on('click', '.edlu-prev', function (e) {
            e.preventDefault();
            goTo(current - 1);
        });

        $wrapper.on('click', '.edlu-next', function (e) {
            e.preventDefault();
            goTo(current + 1);
        });

        // DRAG / SWIPE con mouse + touch
        var dragging = false;
        var startX = 0;

        function getPageX(event) {
            if (event.originalEvent && event.originalEvent.touches && event.originalEvent.touches.length) {
                return event.originalEvent.touches[0].pageX;
            }
            if (event.originalEvent && event.originalEvent.changedTouches && event.originalEvent.changedTouches.length) {
                return event.originalEvent.changedTouches[0].pageX;
            }
            return event.pageX;
        }

        $inner.on('mousedown.edluSlider touchstart.edluSlider', function (e) {
            if (isAnimating) {
                return;
            }
            dragging = true;
            startX = getPageX(e);
        });

        $(document).on('mousemove.edluSlider touchmove.edluSlider', function (e) {
            if (!dragging) {
                return;
            }
            // Evita selezione testo / scroll strani
            e.preventDefault();
        });

        $(document).on('mouseup.edluSlider touchend.edluSlider touchcancel.edluSlider', function (e) {
            if (!dragging) {
                return;
            }

            var endX = getPageX(e);
            var deltaX = endX - startX;
            dragging = false;

            var threshold = 50; // px minimo per considerare uno swipe

            if (Math.abs(deltaX) > threshold) {
                if (deltaX < 0) {
                    // swipe verso sinistra → prossima pagina
                    goTo(current + 1);
                } else {
                    // swipe verso destra → pagina precedente
                    goTo(current - 1);
                }
            }
        });
    }

    $(function () {
        $('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
            initEdluProductSlider($(this));
        });
    });

})(jQuery);
