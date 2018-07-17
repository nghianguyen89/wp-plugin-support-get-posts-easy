<?php
/*
Plugin Name: Support Get Posts Easy
Plugin URI: http://www.fb.com/nghianguyen1989
Description: Support get post easy
Version: 1.0
Author: Nghia Nguyen
Author URI: http://www.fb.com/nghianguyen1989
License: GPL
Copyright: Nghia Nguyen
Text Domain: sgpe
*/


/**
 * Function 	: remove_jquery_masonry_default()
 * Description  : Remove defaut masonry
 * @return 		: null
 */
function remove_jquery_masonry_default( ){
	wp_dequeue_script( 'masonry' );
	wp_deregister_script( 'masonry');
    wp_dequeue_script( 'imagesloaded' );
	wp_deregister_script( 'imagesloaded');	
	wp_dequeue_script( 'jquery-masonry' );
    wp_deregister_script( 'jquery-masonry');
}
add_action( 'wp_enqueue_scripts', 'remove_jquery_masonry_default' );

/**
 * Enqueue styles & scripts in front-end.
 */
function sgpe_scripts_styles() {
    /* sgpe styles */
    wp_enqueue_style( 'sgpe-styles', plugins_url( 'css/style.css', __FILE__ ), array(), null );
	/* masonary */
	wp_enqueue_script( 'sgpe-masonry', plugins_url( 'js/masonry.pkgd.min.js', __FILE__ ), array(), null, true );
    /* sgpe scripts ajax-getmore */
    wp_enqueue_script( 'sgpe-ajax-getmore', plugins_url( 'js/ajax-getmore.js', __FILE__ ), array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'sgpe_scripts_styles' );

/**
 * Function 	: limit_post_content()
 * Description  : Limit content post
 * @return 		: $string
 */
function limit_post_content( $content, $limit, $more = null ){
	if ( $limit != '0'){
		$trimmed = wp_trim_words( $content, $num_words = $limit, $more = $more );
	}
	return $trimmed;
}

/**
 * Function 	: pagination_customize()
 * Description  : Custome & style for pagination
 * @return 		: pagination
 */
function pagination_customize( $numpages = '', $pagerange = '', $paged='', $show_first_last = true ) {
	/*set default page mid size*/
	if (empty($pagerange)) { $pagerange = 2; }
	/*set default page current*/
	global $paged;
	if (empty($paged)) { $paged = 1; }
	/*set default numpages total*/
	if ($numpages == '') {
		global $wp_query;
		$numpages = $wp_query->max_num_pages;
		if(!$numpages) { $numpages = 1; }
	}
	/*fix url param of salon*/
	if( isset( $_GET['term_id'] ) && is_page( 'case' ) )
		$paramURL = '?term_id=' . $_GET['term_id'];
	else $paramURL = '';
	
	$strBase = get_pagenum_link(1) . '%_%';
	/*config*/
	$pagination_args = array(
		'base'            => str_replace( $paramURL, '', $strBase ),
		'format'          => 'page/%#%',
		'total'           => $numpages,
		'current'         => $paged,
		'show_all'        => false,
		'end_size'        => 1,
		'mid_size'        => $pagerange,
		'prev_next'       => true,
		'prev_text'       => __('〈'),
		'next_text'       => __('〉'),
		'type'            => 'plain',
		'add_args'        => false,
		'add_fragment'    => '',
		'before_page_number' => '',
		'after_page_number'  => ''
	);
	/*create page link*/
	$paginate_links = paginate_links($pagination_args);
	
	/*layout*/
	if ($paginate_links) {
		echo "<div class='st-pagelink' data='". $paged . "'>";
			if( $show_first_last ){
				if( $paged > 1 ){
					echo '<a class="first page-numbers" href="../1/' . $paramURL . '">&laquo;</a>';
				}
				echo $paginate_links;
				if( $paged == 1 ){
					echo '<a class="last page-numbers" href="page/' . $numpages . $paramURL . '">&raquo;</a>';
				}else if( $paged < $numpages ){
					echo '<a class="last page-numbers" href="../' . $numpages . $paramURL . '">&raquo;</a>';
				}
			}else{
				echo $paginate_links;
			}
		echo "</div>";
	}
}

/**
 * Function 	: sgpe_shortcode_getposts()
 * Description  : Get list posts & custom posts
 * @return 		: array post
 */
function sgpe_shortcode_getposts( $atts ) {
	
	/* default params */
	$atts = shortcode_atts(
		array(
			'pagination'	   => 'true',
			'limit_text'	   => 0,
			'taxonomy'		   => '',
			'term_id'		   => '',
            'template'		   => '',
            'nopost'       => 'Sorry, no post found.',
			'paged'		   	   => 1,
			'posts_per_page'   => -1,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'post_type'        => 'post',
			'post_parent'      => '',
			'author'	   	   => '',
			'author_name'	   => '',
			'post_status'      => 'publish'
		), $atts
	);
	
	/* filter post by URL */
		// by category
	$cateID = isset( $_GET['cate_id'] ) ? $_GET['cate_id'] : $atts['category'];
		//by term_id 
	if( isset( $_GET['term_id'] ) ){
		$termID = $_GET['term_id'];
		$param_detail_url = '?term_id=' . $termID;
		$tax_query_custom = array(
			array(
				'taxonomy' => $atts['taxonomy'],
				'field' => 'term_id',
				'terms' => $termID
			)
		);
	}elseif( $atts['term_id'] != '' ){
		$tax_query_custom = array(
			array(
				'taxonomy' => $atts['taxonomy'],
				'field' => 'term_id',
				'terms' => $atts['term_id']
			)
		);
	}else{
		$param_detail_url = '';
		$tax_query_custom = '';
	}
	
	/* query posts */
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : $atts['paged'];
	$sgpe_getposts = new WP_Query( array(		
		'paged'			 => $paged,
		'posts_per_page' => $atts['posts_per_page'],
		'cat'       	 => $cateID,
		'category_name'  => $atts['category_name'],
		'orderby'        => $atts['orderby'],
		'order'          => $atts['order'],
		'include'        => $atts['include'],
		'exclude'        => $atts['exclude'],
		'post_type'      => $atts['post_type'],
		'post_parent'    => $atts['post_parent'],
		'author'	   	 => $atts['author'],
		'author_name'	 => $atts['author_name'],
		'post_status'    => $atts['post_status'],
		'tax_query' 	 => $tax_query_custom,
		'pagination'	 => $atts['pagination']
	) );
	
    ob_start();
	if( $sgpe_getposts->have_posts() ) {
        
        /* get template post list */        
        $template_default = dirname(__FILE__) . '/templates/default/post.php';
        $template_customs = dirname(__FILE__) . '/templates/' . $atts['template'] . '.php';

        echo '<div id="sgpe-' . $atts['post_type'] . '" class="sgpe-list" data-taxonomy="' . $atts['taxonomy'] . '" data-paged="' . $paged . '" data-pagerall="' . $sgpe_getposts->max_num_pages . '">';
            if( $atts['template'] != '' && file_exists( $template_customs ) ) {
                include( $template_customs );
            } else {
                include( $template_default );
			}
			echo '<p class="sgpe-loadmore" data-post_type="' . $atts['post_type'] . '"><a href="#">load more ...</a></p>';
		echo '</div>';

		wp_reset_postdata();
		
		/* pagination */
		if( $atts['pagination'] == 'true' ){
			echo '<div class="nav-links">';
				if ( function_exists( pagination_customize ) ) {
					pagination_customize( $sgpe_getposts->max_num_pages, "2", $paged, true );
				}
			echo '</div>';
		}
		
	}else{
		/* no post */
		echo '<p class="sgpe-no-post">' . $atts['nopost'] . '</p>';
	}
	return ob_get_clean();
}
add_shortcode( 'sgpe', 'sgpe_shortcode_getposts' );

/**
 * Function 	: sgpe_loadmore_post()
 * Description  : Load more post
 * @return 		: post
 */
add_action( 'wp_ajax_sgpe_loadmore_post', 'sgpe_loadmore_post' );
add_action( 'wp_ajax_nopriv_sgpe_loadmore_post', 'sgpe_loadmore_post' );
function sgpe_loadmore_post() {
	$next_page = isset( $_POST['next_paged'] ) ? ' paged="' . $_POST['next_paged'] . '" ' : '';
	$category = isset( $_POST['cate_id'] ) ? ' term_id="' . $_POST['cate_id'] . '" ' : '';
	echo do_shortcode( '[GET_LIST post_type="practice_news" taxonomy="cate_practice_news"' . $next_page . $category . ' posts_per_page="12" pagination="false" template="template-list/news_archive.php" limit_text="70"]' );
	die();
}

?>