/*
*  ajax-getmore.js
*
*  All javascript needed to get more posts by ajax
*
*  @type	JS
*  @date	15/07/2018
*/ 

(function($){
    var sgpe_list = '.sgpe-listgroup';
    var total_pages = jQuery(sgpe_list).data('pagerall');
    var current_page = jQuery(sgpe_list).data('paged');

    var $grid_sgpe;

    /* layout default */
    $grid_sgpe = new Masonry( sgpe_list, {
        columnWidth : '.sgpe-grid-sizer',
        itemSelector: '.sgpe-grid-item',
        gutter: '.sgpe-gutter-sizer',
        percentPosition: true,
        horizontalOrder: true,
        initLayout: false
    });

    /* add event listener for initial  */
    $grid_sgpe.on( 'layoutComplete', function( items ) {
        console.log( items.length );
    });
    /* trigger initial layout */
    setTimeout(function () {
        $grid_sgpe.layout();
    }, 500);
    



    /* hide buton loadmore if 1 page */
    /*if (total_pages <= 1) {
        jQuery('#sgpe-loadmore a').hide();
    }*/

    /* set category active */
    /*jQuery('.sgpe-cate li').removeClass('active');
    if (typeof getUrlParameter('cate_id') !== 'undefined')
        var current_cate = getUrlParameter('cate_id');
    else
        var current_cate = getUrlParameter('term_id');

    if (typeof current_cate !== 'undefined') {
        cate_id = current_cate;
        jQuery('.sgpe-cate li').each(function () {
            if (jQuery(this).data('cate') == current_cate) {
                jQuery(this).addClass('active');
            }
        });
    } else {
        jQuery('.sgpe-cate li:first-child').addClass('active');
    }*/

    /* load more click */
    /*jQuery('#sgpe-loadmore a').click(function (e) {
        e.preventDefault();

        if (jQuery(this).parent().hasClass('practice_loadmore')) {
            var action_name = 'getmore_practice';
        } else if (jQuery(this).parent().hasClass('graduation_loadmore')) {
            var action_name = 'getmore_graduation';
        } else if (jQuery(this).parent().hasClass('student_loadmore')) {
            var action_name = 'getmore_student';
        } else if (jQuery(this).parent().hasClass('sport_loadmore')) {
            var action_name = 'getmore_sport';
        } else {
            var action_name = 'getmore_blog';
        }
        jQuery.post(
            site_url + "/wp-admin/admin-ajax.php", {
                action: action_name,
                cate_id: cate_id,
                next_paged: current_page
            }
        ).done(function (data) {
            if (data != '') {
                current_page++;
                var jQueryitems = jQuery(data);
                $grid_sgpe.append(jQueryitems).masonry('appended', jQueryitems);
                setTimeout(function () {
                    $grid_sgpe.masonry('layout');
                }, 1000);
            }
            var total_item_current = topic.find('.item').length;
            var current_page = topic.find('.item').eq(total_item_current - 1).data('page');
            if (current_page == total_pages) {
                jQuery('#sgpe-loadmore a').hide();
            }
        });
    });*/

})(jQuery);