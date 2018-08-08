/*
 *  ajax-getmore.js
 *
 *  All javascript needed to get more posts by ajax
 *
 *  @type	JS
 *  @date	15/07/2018
 */

(function ($) {

    var sgpe_container = ".sgpe-list";
    var sgpe_grid = ".sgpe-listgroup";
    var sgpe_loadmore = ".sgpe-loadmore";
    var sgpe_grid_total = $(sgpe_grid).length;
    var sgpe_gutter = $(window).width() > 800 ? 25 : 15;

    if (sgpe_grid_total > 0) {

        /* masonry */
        $(sgpe_grid).each(function (i) {
            var sgpe_options = $(this)
                .parents(sgpe_container)
                .data("options");
            var btn_loadmore = $(this)
                .parents(sgpe_container)
                .find(sgpe_loadmore);

            /* init */
            var this_grid = $(this).masonry({
                itemSelector: sgpe_options.msnrItemSelector,
                gutter: sgpe_gutter,
                percentPosition: true,
                horizontalOrder: true
            });

            /* re-layout */
            setTimeout(function () {
                this_grid.masonry("layout");
            }, 500);

            /* hide buton loadmore if 1 page */
            if (sgpe_options.pageAll <= 1) {
                btn_loadmore.hide();
            }
        });

        /* button loadmore post by ajax */
        $(sgpe_loadmore).on('click', function (e) {
            e.preventDefault();
            var _this_container = $(this).parents(sgpe_container);
            var _this_grid = $(this).prev(sgpe_grid);
            var _options = _this_container.data('options');
            var next_page = $(this).data('current') + 1;

            $.post(_options.siteUrl + '/wp-admin/admin-ajax.php', {
                    action: 'sgpe_pager',
                    page: next_page,
                    options: _options
            })
            .done(function (data) {
                var _btn_loadmore = _this_container.find(sgpe_loadmore);
                /* set page current & update page */
                _btn_loadmore.attr('data-current', next_page);
                _btn_loadmore.data('current', next_page);

                /* hide button loadmore if last page */
                if(_options.pageAll == next_page)
                    _btn_loadmore.hide();
                    
                /* load more data */
                if (data != "") {
                    var items = $(data);
                    _this_grid.append(items).masonry('appended', items);
                    setTimeout(function () { $(_this_grid).masonry('layout'); }, 1000);
                }
            });
        });
    }
})(jQuery);