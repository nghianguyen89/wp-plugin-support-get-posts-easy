<?php
/**
 * The template content
 * Post type : post (default)
 * Page : List
 * URL : ...
 * Shortcode : [sgpe posts_per_page="4" pagination="false"]
 **/
?>

<?php

	echo '<div class="sgpe-listgroup">';

		while( $sgpe_getposts->have_posts() ) {
			$sgpe_getposts->the_post();			
			/* loop content template */
			include( $template_default_post_loop );
		}
		wp_reset_postdata();
		
	echo '</div>';
	
?>