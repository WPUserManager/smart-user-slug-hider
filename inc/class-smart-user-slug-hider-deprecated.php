<?php

/**
 * The Smart User Slug Hider deprecated class
 *
 * @since  4.0.0
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The deprecated plugin class
 */
if ( !class_exists( 'PP_Smart_User_Slug_Hider_Deprecated' ) ) {
  
  class PP_Smart_User_Slug_Hider_Deprecated extends PPF09_SubClass {  
    
    /**
	   * Do Init
     *
     * @since 4.0.0
     * @access public
     */
    public function init() {

      // removed mcrypt in 4.0.0
      // always use openssl_encrypt
      // so we do not need the option key anymore
      
      delete_option( $this->core()->get_plugin_slug() . '_openssl' );
    
    }

  }
  
}

?>