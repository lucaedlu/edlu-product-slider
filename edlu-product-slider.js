(function ($) {
    'use strict';

    function initEdluProductSlider($wrapper) {
        var $pages = $wrapper.find('.edlu-product-page');

        if (!$pages.length || $pages.length <= 1) {
            return; // niente slider se c'è solo una pagina
        }

        var totalPages = $pages.length;
        var current = 0;
        var isAnimating = false;

        // Mostra solo la pagina attiva (la 0 all'inizio)
        $pages.removeClass('is-active');
        $pages.eq(current).addClass('is-active');

        function goTo(index) {
            if (index < 0 || index >= totalPages) {
                return;
            }
            if (index === current || isAnimating) {
                return;
            }

            isAnimating = true;

            $pages.removeClass('is-active');
            $pages.eq(index).addClass('is-active');

            setTimeout(function () {
                isAnimating = false;
            }, 400); // match con transition CSS
        }

        // NAV: frecce
        $wrapper.on('click', '.edlu-prev', function (e) {
            e.preventDefault();
            goTo(current - 1);
            if (current > 0) {
                current--;
            }
        });

        $wrapper.on('click', '.edlu-next', function (e) {
            e.preventDefault();
            goTo(current + 1);
            if (current < totalPages - 1) {
                current++;
            }
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

        $wrapper.on('mousedown.edluSlider touchstart.edluSlider', function (e) {
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
            // evita selezione testo
            e.preventDefault();
        });

        $(document).on('mouseup.edluSlider touchend.edluSlider touchcancel.edluSlider', function (e) {
            if (!dragging) {
                return;
            }

            var endX = getPageX(e);
            var deltaX = endX - startX;
            dragging = false;

            var threshold = 50; // px minimo per considerare swipe

            if (Math.abs(deltaX) > threshold) {
                if (deltaX < 0 && current < totalPages - 1) {
                    // swipe verso sinistra → pagina successiva
                    goTo(current + 1);
                    current++;
                } else if (deltaX > 0 && current > 0) {
                    // swipe verso destra → pagina precedente
                    goTo(current - 1);
                    current--;
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
