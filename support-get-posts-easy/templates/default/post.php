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
			echo '<div class="sgpe-listgroup__block">';
				/* post-image */				
				echo '<div class="sgpe-listgroup__image">';
					echo '<a href="' . get_the_permalink() . '">';
						if( !empty( get_the_post_thumbnail( get_the_ID() ) ) ) {
							echo get_the_post_thumbnail( get_the_ID(), 'full' );
						} else {
							echo '<img src="' . plugins_url( '../../images/no-image.png' , __FILE__ ) . '" alt="Image Not Avalable" />';
						}
					echo '</a>';
				echo '</div>';
				/* post-content */
				echo '<div class="sgpe-listgroup__text">';
					/* date */
					echo '<p class="sgpe-listgroup__date">'. get_the_date( 'Y.m.d (D)' ) .'</p>';
					/* title */
					echo '<h3 class="sgpe-listgroup__title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h3>';
					/* content */
					echo '<div class="sgpe-listgroup__content">' . limit_post_content( get_the_content(), 70 ) . '</div>';
					/* category */
					include( 'cate.php' );
				echo '</div>';				
			echo '</div>';
		}
		wp_reset_postdata();
		
	echo '</div>';
?>