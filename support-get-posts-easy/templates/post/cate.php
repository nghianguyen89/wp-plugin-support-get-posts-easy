<?php
    if( $atts['taxonomy'] != '' ) {
        $sgpe_post_categories = get_terms( array( 
            'taxonomy' => $atts['taxonomy'],
            'parent'   => 0
        ) );
    } else {
        $sgpe_post_categories = get_the_category();
    }
    if ( $sgpe_post_categories ) {
        echo '<ul class="sgpe-listgroup__category">';
        foreach($sgpe_post_categories as $cate) {
            if($cate->parent != 0)
                echo '<li class="post-cate-id-' . $cate->term_taxonomy_id . '"><a href="?cate_id=' . $cate->term_taxonomy_id . '" style="' . $cate->description . '">' . $cate->name . '</a></li>';
        }
        echo '</ul>';
    }
?>