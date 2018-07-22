<?php

    if(  $sgpe_taxonomy !== '' ) {
        
        $sgpe_post_categories = get_the_terms( get_the_ID(), $sgpe_taxonomy );
    
        if ( $sgpe_post_categories ) {
            echo '<ul class="sgpe-listgroup__category">';

                if( $sgpe_terms_show == 'children' ) {
                    foreach($sgpe_post_categories as $cate) {
                        if ( $cate->parent != 0 )
                            echo '<li><a href="' . $field_type . $cate->$field_value . '" style="' . $cate->description . '">' . $cate->name . '</a></li>';;
                    }
                } elseif( $sgpe_terms_show == 'parent' ) {
                    foreach($sgpe_post_categories as $cate) {
                        if ( $cate->parent == 0 )
                        echo '<li><a href="' . $field_type . $cate->$field_value . '" style="' . $cate->description . '">' . $cate->name . '</a></li>';;
                    }
                } else {
                    foreach($sgpe_post_categories as $cate) {
                        echo '<li><a href="' . $field_type . $cate->$field_value . '" style="' . $cate->description . '">' . $cate->name . '</a></li>';;
                    }
                }

            echo '</ul>';
        }
    }
    
?>