/*
 *  ajax-getmore.js
 *
 *  All javascript needed to get more posts by ajax
 *
 *  @type	JS
 *  @date	15/07/2018
 */

(function ($) {

    var sgpe_grid = $(".sgpe-listgroup");
    var total_grid = sgpe_grid.length;
    var _space = $(window).width() > 800 ? 25 : 15;

    if (total_grid > 0) {

        sgpe_grid.each(function (i) {
            var sgpe_options = $(this).parents('.sgpe-list').data('options');
            var _btn_loadmore = $(this).parents('.sgpe-list').find('.sgpe-loadmore');

            /* init masonry */
            var _thisGrid = $(this).masonry({
                itemSelector: sgpe_options.itemSelector,
                gutter: _space,
                percentPosition: true,
                horizontalOrder: true
            });

            /* re-layout masonry */
            setTimeout(function () {
                _thisGrid.masonry('layout');
            }, 500);

            /* hide buton loadmore if 1 page */
            if ( sgpe_options.pagerall <= 1) {
                _btn_loadmore.hide();
            }




        });





    }



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
                  sgpe_grid.append(jQueryitems).masonry('appended', jQueryitems);
                  setTimeout(function () {
                      sgpe_grid.masonry('layout');
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