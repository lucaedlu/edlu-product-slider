jQuery(document).ready(function ($) {
    $('.edlu-product-slider-wrapper[data-enable-slider="yes"]').each(function () {
        var $wrapper = $(this);
        var $pages   = $wrapper.find('.edlu-product-page');

        if ($pages.length <= 1) {
            return; // niente slider se c'è solo una pagina
        }

        var current = 0;

        function showPage(index) {
            $pages.removeClass('is-active').attr('aria-hidden', 'true');
            $pages.eq(index).addClass('is-active').attr('aria-hidden', 'false');
        }

        showPage(current);

        $wrapper.on('click', '.edlu-next', function (e) {
            e.preventDefault();
            current = (current + 1) % $pages.length;
            showPage(current);
        });

        $wrapper.on('click', '.edlu-prev', function (e) {
            e.preventDefault();
            current = (current - 1 + $pages.length) % $pages.length;
            showPage(current);
        });
    });
});
