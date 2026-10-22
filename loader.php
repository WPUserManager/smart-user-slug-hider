<?php

/**
 * smart User Slug Hider Plugin Loader
 *
 * @since 3
 *
 **/
 
// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Load Plugin Foundation
 * @since 4.0.0
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/ppf/loader.php' );
 


/**
 * Load file
 */
require_once( plugin_dir_path( __FILE__ ) . '/inc/class-smart-user-slug-hider.php' );


/**
 * Load Plugin Functions
 */
require_once( plugin_dir_path( __FILE__ ) . '/functions.php' );


/**
 * Load Plugin Shortcodes
 */
require_once( plugin_dir_path( __FILE__ ) . '/shortcodes.php' );


/**
 * Main Function
 */
function pp_smart_user_slug_hider() {

  return PP_Smart_User_Slug_Hider::getInstance( array(
    'file'      => dirname( __FILE__ ) . '/smart-user-slug-hider.php',
    'slug'      => pathinfo( dirname( __FILE__ ) . '/smart-user-slug-hider.php', PATHINFO_FILENAME ),
    'name'      => 'Smart User Slug Hider',
    'shortname' => 'Smart User Slug Hider',
    'version'   => '4.0.6'
  ) );
    
}


/**
 * Run the plugin
 */
pp_smart_user_slug_hider();

?>