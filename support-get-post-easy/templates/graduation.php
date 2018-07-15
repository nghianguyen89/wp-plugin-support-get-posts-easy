<?php
/**
 * The template content
 * Post type : graduation_news
 * Page : List
 * URL : /alumni/
 * Shortcode : [GET_LIST post_type="graduation_news" template="template-list/graduation.php" posts_per_page="10" pagination="false"]
 **/
?>

<?php
	
	echo '<h3 class="alumni_title">お知らせ</h3>';
	echo '<div class="alu_bl1">';
		echo '<table class="alu_table" summary="お知らせ" cellspacing="0" cellpadding="0">';
			while( $sgpe_getposts->have_posts() ) {
				$sgpe_getposts->the_post();
				echo '<tr>';
					echo '<th>'. get_the_date( 'Y.m.d' ) .'</th>';
					echo '<td><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></td>';
				echo '</tr>';
			}
		echo '</table>';
	echo '</div>';
	wp_reset_postdata();
	
?>