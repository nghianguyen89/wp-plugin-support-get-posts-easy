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
        $(sgpe_loadmore).on("click", function (e) {
            e.preventDefault();
            var obj = $(this).parents(sgpe_container).data("options");

            $.post(obj.siteUrl + "/wp-admin/admin-ajax.php", {
                action: 'sgpe_loadmore_post',
                getpost_option: obj
            })
            .done(function (data) {
                if (data != "") {
                    console.log(data);
                }
            });

            
        });
    }
})(jQuery);