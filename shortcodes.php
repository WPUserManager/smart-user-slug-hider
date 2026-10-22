<?php

/**
 * The Smart User Slug Hider Plugin Shortcodes
 *
 * @since 1.0
 * shortcodes.php since 4.0.0
 *
 **/
 

// the user slug of the current post author
add_shortcode( 'smart_user_slug', function( $atts ) { return pp_smart_user_slug_hider()->get_smart_user_slug(); } );


// the url of the current post author’s profile page 
add_shortcode( 'smart_user_url', function( $atts ) { return esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); } );


// link to the current post author’s profile page
add_shortcode( 'smart_user_link', function( $atts ) { return '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a>'; } );


?>