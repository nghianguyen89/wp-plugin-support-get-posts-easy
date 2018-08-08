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
	/* pagination */
	wp_enqueue_script( 'sgpe-pagination', plugins_url( 'js/pagination.min.js', __FILE__ ), array(), null, true );
    /* pager */
    wp_enqueue_script( 'sgpe-pager', plugins_url( 'js/pager.js', __FILE__ ), array(), null, true );
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
			'template'    	   => '',
			'nopost_message'   => 'Sorry, no post found.',
			'paged'		   	   => 1,
			'posts_per_page'   => -1,
			'category_id'      => '',
			'category_name'    => '',
			'order'            => 'DESC',
			'orderby'          => 'date',
			'include'          => '',
			'exclude'          => '',
			'post_type'        => 'post',
			'post_status'	   => 'publish',
			'post_parent'      => '',
			'author'	   	   => '',
			'author_name'	   => '',
			'taxonomy'		   => '',
			'include_children' => true,
			'hide_empty' 	   => '1',
			'terms_show' 	   => '',
		), $atts
	);
	
	$sgpe_pagination 		= $atts['pagination'];
	$sgpe_ilimit_text 		= $atts['limit_text'];
	$sgpe_template 			= $atts['template'];
	$sgpe_nopost_message 	= $atts['nopost_message'];
	$sgpe_paged 			= $atts['paged'];
	$sgpe_posts_per_page 	= $atts['posts_per_page'];
	$sgpe_category_id 		= $atts['category_id'];
	$sgpe_category_name 	= $atts['category_name'];
	$sgpe_order 			= $atts['order'];
	$sgpe_orderby 			= $atts['orderby'];
	$sgpe_include 			= $atts['include'];
	$sgpe_exclude 			= $atts['exclude'];
	$sgpe_post_type 		= $atts['post_type'];
	$sgpe_post_status 		= $atts['post_status'];
	$sgpe_post_parent 		= $atts['post_parent'];
	$sgpe_author 			= $atts['author'];
	$sgpe_author_name 		= $atts['author_name'];
	$sgpe_taxonomy 			= $atts['taxonomy'];
	$sgpe_include_children 	= filter_var( $atts['include_children'], FILTER_VALIDATE_BOOLEAN );
	$sgpe_hide_empty 		= $atts['hide_empty'];
	$sgpe_terms_show 		= $atts['terms_show'];
	
	/* filter post by category */
	if ( $sgpe_category_name !== '' ) {
		$field_type     = '?ucna=';
		$field_value    = 'slug';
	} else {
		$field_type     = '?ucid=';
		$field_value    = 'term_taxonomy_id';
	}

	if( 
		(
			isset( $_GET['ucid'] ) ||
			isset( $_GET['ucna'] ) ||
			$sgpe_category_id !== '' ||
			$sgpe_category_name !== ''
		) && $sgpe_taxonomy !== ''
	){
		if( isset( $_GET['ucid'] ) || $sgpe_category_id !== '' ) {
			$field = 'term_id';
			$terms = isset( $_GET['ucid'] ) ? $_GET['ucid'] : $sgpe_category_id;
		} elseif ( isset( $_GET['ucna'] ) || $sgpe_category_name !== '' )  {
			$field = 'slug';
			$terms = isset( $_GET['ucna'] ) ? $_GET['ucna'] : $sgpe_category_name;
		}
		
		$tax_query_string = array(
			array(
				'taxonomy' 	=> $sgpe_taxonomy,
				'field' 	=> $field,
				'terms'		=> $terms,
				'include_children' => $sgpe_include_children
			)
		);
	} else $tax_query_string = '';

	/* link filter by category */
	
	/* query posts */
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : $sgpe_paged;
	$sgpe_getposts = new WP_Query( array(		
		'paged'			 => $paged,
		'posts_per_page' => $sgpe_posts_per_page,
		'order'          => $sgpe_order,
		'orderby'        => $sgpe_orderby,
		'include'        => $sgpe_include,
		'exclude'        => $sgpe_exclude,
		'post_type'      => $sgpe_post_type,
		'post_status'    => $sgpe_post_status,
		'post_parent'    => $sgpe_post_parent,
		'author'	   	 => $sgpe_author,
		'author_name'	 => $sgpe_author_name,
		'tax_query' 	 => $tax_query_string
	) );
	
    ob_start();
	if( $sgpe_getposts->have_posts() ) {
        
        /* get template post list */        
		$template_default_post_content 	= dirname(__FILE__) . '/templates/post/list-main.php';
		$template_default_post_loop 	= dirname(__FILE__) . '/templates/post/list-loop.php';

		//$template_customs = dirname(__FILE__) . '/templates/' . $sgpe_template . '.php';
		
		/* options */
		$sgpe_post_options .= 	'"postType":"' . $sgpe_post_type . '",' .
								'"postTaxonomy":"' . $sgpe_taxonomy . '",' .
								'"postPerPage":"' . $sgpe_posts_per_page . '",' .
								'"pageAll":"' . $sgpe_getposts->max_num_pages . '",' .
								'"termsShow":"' . $sgpe_terms_show . '",' .
								'"siteUrl":"' . get_site_url() . '"';
		
		if( !empty( $_POST[ 'options' ] ) ) {
			include( $template_default_post_loop );
		} else {
			echo '<div class="sgpe-list" data-options=\'{' . $sgpe_post_options . '}\'>';           
				include( $template_default_post_content );
				echo '<p class="sgpe-loadmore" data-current="' . $paged . '" id="sgpe-loadmore_' . $sgpe_post_type . '"><a href="#">load more ...</a></p>';
			echo '</div>';
		}
		wp_reset_postdata();

		/*if( $sgpe_template != '' && file_exists( $template_customs ) ) {
			include( $template_customs );
		} else {
			include( $template_default_post_content );
		}*/
		
	}else{
		/* no post */
		echo '<p class="sgpe-no-post">' . $sgpe_nopost_message . '</p>';
	}
	return ob_get_clean();
}
add_shortcode( 'sgpe', 'sgpe_shortcode_getposts' );



/**
 * Function 	: sgpe_pager()
 * Description  : Load more post
 * @return 		: post
 */
add_action( 'wp_ajax_sgpe_pager', 'sgpe_pager' );
add_action( 'wp_ajax_nopriv_sgpe_pager', 'sgpe_pager' );
function sgpe_pager() {
	/* get POST option */
	$paged = $_POST['page'];
	$param = $_POST[ 'options' ];

	/* assign parameter options */
	$post_type		= $param['postType'];
	$taxonomy		= $param['postTaxonomy'];	
	$posts_per_page	= $param['postPerPage'];
	$terms_show		= $param['termsShow'];

	/* get post of next page */
	$post_loop 	= dirname(__FILE__) . '/templates/post/list-loop.php';
	echo do_shortcode( '[sgpe post_type="' . $post_type . '" taxonomy="' . $taxonomy . '" terms_show="' . $terms_show . '" posts_per_page="' . $posts_per_page . '" paged="' . $paged . '" template="' . $post_loop . '" pagination="false"]' );
	die();
}

/*
	echo '<pre><code>';
	var_dump( get_post_types() );
	echo '</code></pre>';
*/

?>