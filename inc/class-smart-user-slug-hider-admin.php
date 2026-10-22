<?php

/**
 * The Smart User Slug Hider admin plugin class
 *
 * @since 4.0.0
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin plugin class
 */
if ( !class_exists( 'PP_Smart_User_Slug_Hider_Admin' ) ) {
  
  class PP_Smart_User_Slug_Hider_Admin extends PPF09_Admin {

    
    /**
	   * Do Init
     *
     * @since 4.0.0
     * @access public
     */
    public function init() {
      
      $this->add_actions( array( 
        'admin_init',
        'admin_menu'
      ) );
    
    }
    
    
    /**
     * init admin 
     * moved to PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function action_admin_init() {
      
      $this->add_setting_sections(
      
        array(
          
          array(
        
            'section' => 'info',
            'order'   => 10,
            'title'   => esc_html__( 'Info', 'smart-user-slug-hider' ),
             'icon'    => 'info',
            'html' => '<p><strong>' . esc_html__( 'This Plugin replaces user names with 16 digits coded strings.', 'smart-user-slug-hider' ) . '</strong></p><p>' . esc_html__('There are no settings.', 'smart-user-slug-hider' ) . '</p><hr /><p>' . esc_html__('Your coded user slug: ', 'smart-user-slug-hider' ) . $this->core()->get_smart_user_slug() . '</p>',
            'nosubmit' => true
        
          )
          
        )
        
      );
      
    }
    
    
    /**
     * create the menu entry
     * moved to PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function action_admin_menu() {
      
      $screen_id = add_options_page( $this->core()->get_plugin_shortname(), $this->core()->get_plugin_shortname(), 'manage_options', 'smartuserslughidersettings', array( $this, 'show_admin' ) );
      $this->set_screen_id( $screen_id );
      
    }
    
   
    /**
     * show admin page
     * moved to PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function show_admin() {
      
      
      $this->show( 'manage_options' );
      
    }
    
    
    /**
     * create nonce
     *
     * @since  4.0.0
     * @access private
     * @return string Nonce
     */
    private function get_nonce() {
      
      return wp_create_nonce( 'pp_smart_user_slug_hider_dismiss_admin_notice' );
      
    }
    
    
    /**
     * check nonce
     *
     * @since  4.0.0
     * @access private
     * @return boolean
     */
    private function check_nonce() {
      
      return check_ajax_referer( 'pp_smart_user_slug_hider_dismiss_admin_notice', 'securekey', false );
      
    }

  }
  
}

?>