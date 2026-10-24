<?php

/**
 * The Smart User Slug Hider core plugin class
 */


 // If this file is called directly, abort
 if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The core plugin class
 */
if ( !class_exists( 'PP_Smart_User_Slug_Hider' ) ) {

  class PP_Smart_User_Slug_Hider extends WPUMPPF1_Plugin {
    
    
    /**
     * Admin Class
     *
     * @see    class-smart-user-slug-hider-admin.php
     * @since  4.0.0
     * @var    object
     * @access private
     */
    private $admin;
    
    
    /**
     * Deprecated Class
     *
     * @see    class-smart-user-slug-hider-deprecated.php
     * @since  4.0.0
     * @var    object
     * @access private
     */
    private $deprecated;

    
    
    private $_key;


    /**
     * Blowfish in plain PHP, used where OpenSSL has no BF-ECB (OpenSSL 3)
     *
     * @see    class-smart-user-slug-hider-blowfish.php
     * @since  4.0.7
     * @var    object|false|null null until first use, false while OpenSSL does the work
     * @access private
     */
    private $_blowfish = null;


    /**
     * did the main query ask for an author we have to hide?
     *
     * @since  4.0.8
     * @var    bool
     * @access private
     */
    private $_not_found = false;


	/**
	 * do plugin init
	 */
    function plugin_init() {
		
	  // v 4.0.2
	  // do not rely on SERVER_ADDR only
	  
	  $enc = 'ceb2d699fa'; // use this as default if everything fails...
      
	  if ( ! empty( $_SERVER ) && array_key_exists( 'SERVER_ADDR', $_SERVER ) && ! empty ( $_SERVER['SERVER_ADDR'] ) ) {
		  $enc = $_SERVER['SERVER_ADDR'];
	  } elseif ( ! empty( $_SERVER ) && array_key_exists( 'LOCAL_ADDR', $_SERVER ) && ! empty ( $_SERVER['LOCAL_ADDR'] ) ) {
		  $enc = $_SERVER['LOCAL_ADDR'];
	  } elseif ( ! empty( $_SERVER ) && array_key_exists( 'HTTP_HOST', $_SERVER ) && ! empty ( $_SERVER['HTTP_HOST'] ) ) {
		  $enc = $_SERVER['HTTP_HOST'];
	  } elseif ( ! empty( $_SERVER ) && array_key_exists( 'SERVER_NAME', $_SERVER ) && ! empty ( $_SERVER['SERVER_NAME'] ) ) {
		  $enc = $_SERVER['SERVER_NAME'];
	  } elseif ( ! empty( get_home_url() ) ) {
		  $enc = get_home_url();
	  }
	
      $this->_key = md5( $enc . $this->get_plugin_file() );
        
     
      
      $this->add_actions( array( 
        'init'
      ) );

			
      add_action( 'pre_get_posts', array( $this, 'alter_query' ), 99 );
      add_action( 'wp', array( $this, 'send_not_found_status' ) );
      add_action( 'bp_include', array( $this, 'activate_buddypress_support' ) );
      
      add_filter( 'author_link', array( $this, 'alter_link' ), 99, 3 );
      add_filter( 'body_class', array( $this, 'alter_body_class' ), 99, 2 );

    }
    
    
    /**
     * init action
     */
    function action_init() {
      
      load_plugin_textdomain( 'smart-user-slug-hider' );
      
      // since v 4.0.0
      $this->admin      = $this->add_sub_class_backend( 'PP_Smart_User_Slug_Hider_Admin',     'class-smart-user-slug-hider-admin', $this );
      $this->deprecated = $this->add_sub_class_always( 'PP_Smart_User_Slug_Hider_Deprecated', 'class-smart-user-slug-hider-deprecated', $this );

    }
    

    /**
		 * Add support for BudyPress
     * used by bp_include action, which only fires if BuddyPress is active
		 */
    function activate_buddypress_support() {
      add_filter( 'bp_core_get_user_domain', array( $this, 'alter_link_buddypress' ), 99, 4 );
      add_filter ( 'bp_core_get_userid', array( $this, 'get_user_buddypress' ), 99, 2 );
      add_filter ( 'bp_core_get_userid_from_nicename', array( $this, 'get_user_buddypress' ), 99, 2 );
      add_filter ( 'bp_core_set_uri_globals_member_slug', array( $this, 'alter_query_buddypress' ), 99, 1 );
      remove_action( 'pre_get_posts', array( $this, 'alter_query' ), 99 );
    }
    
    
    /**
		 * replace author name in author link to encrypted value
     * used by author_link filter
		 */
    function alter_link( $link, $author_id, $author_nicename ) {

      // since 4.0.8 only the name at the end of the URL, not a domain or folder that matches it
      return self::replace_name_in_url( $link, $author_nicename, $this->encrypt( $author_id ) );

    }


    /**
     * replace the user name at the end of an author or member URL with the coded slug
     *
     * str_replace() on the whole URL also replaced a domain or folder matching the user name,
     * e.g. jane.example/author/jane/. URLs that don't end in the name, such as plain
     * permalinks (?author=1), come back unchanged.
     *
     * @since  4.0.8
     * @access public
     * @param  string $url      author or member URL
     * @param  string $nicename the user's nicename
     * @param  string $slug     the coded slug
     * @return string
     */
    public static function replace_name_in_url( $url, $nicename, $slug ) {

      if ( '' === (string) $nicename ) {
        return $url;
      }

      return preg_replace( '#/' . preg_quote( $nicename, '#' ) . '(?=/?(?:[?\#].*)?$)#', '/' . $slug, $url, 1 );

    }
    
    
    /**
		 * replace buddypress member name in member link to encrypted value
     * used by bp_core_get_user_domain filter
		 */
    function alter_link_buddypress ( $domain, $user_id, $user_nicename, $user_login ) {
      
      $user = false;
      
      if ( $user_id  == 0 && ! empty( $user_nicename ) ) {
        
        $user = get_user_by( 'slug', $user_nicename );
        
      } elseif ( $user_id  == 0 && ! empty( $user_login ) ) {
       
        $user = get_user_by( 'login', $user_login );
        
      } elseif ( $user_id != 0 ) {
        
        $user = get_user_by( 'id', $user_id );
      
      }
      
      if ( $user ) {
        
        // since 4.0.8 only the name at the end of the URL
        $domain = self::replace_name_in_url( $domain, $user->user_nicename, $this->encrypt( $user->ID ) );
        
      }
      
      return $domain;
      
    }
    
    /**
		 * if a author name is queried we have to decrypt it
     * used by pre_get_posts action
		 */
    function alter_query( $query ) {
           
      if ( $query->is_author() && $query->query_vars['author_name'] != '' ) {
        
        if ( ctype_xdigit( $query->query_vars['author_name'] ) ) {
          
          $user = get_user_by( 'id', $this->decrypt( $query->query_vars['author_name'] ) );
          
          if ( $user ) {

            $query->set( 'author_name', $user->user_nicename );

          } else {

            $this->not_found( $query );
          }

        } else {

          $this->not_found( $query );

        }

      }

      return;

    }


    /**
     * show the 404 template for an author we have to hide
     *
     * @since  4.0.8 (was part of alter_query)
     * @access private
     * @param  WP_Query $query
     */
    private function not_found( $query ) {

      $query->is_404 = true;
      $query->is_author = false;
      $query->is_archive = false;

      if ( $query->is_main_query() ) {
        $this->_not_found = true;
      }

    }


    /**
     * send HTTP status 404 along with the 404 template
     * used by wp action
     *
     * WP::handle_404() sends no status for a query that's already a 404, so the page
     * said "not found" but answered 200.
     *
     * @since  4.0.8
     * @access public
     */
    function send_not_found_status() {

      if ( $this->_not_found ) {

        status_header( 404 );
        nocache_headers();

      }

    }
    
    
    /**
		 * decrypt userslug for BuddyPress
     * used by bp_core_set_uri_globals_member_slug filter
     * WP core pre_get_posts does not work if BuddyPress Root Profiles are enabled
		 */
    function alter_query_buddypress( $userslug ) {
      
      if ( ctype_xdigit( $userslug ) ) {
        
        $userslug = $this->decrypt( $userslug );
        
        if ( ! bp_is_username_compatibility_mode() ) {
          
          $user = get_user_by( 'id', $userslug );
          
          if ( $user ) {
            
            $userslug = $user->user_nicename;
          }
          
        } 
        
      }
      
      return $userslug;
      
    }
    
    
    /*
     * Alter the <body> classes for author pages
     * @since 4.0.0
     * @see https://wordpress.org/support/topic/author-still-in-source-code/
     */
    function alter_body_class( $classes, $class ) {
      
      if ( is_author() ) {
        
        global $wp_query;
        
        $author = $wp_query->get_queried_object();
        
        if ( isset( $author->user_nicename ) ) {
        
          $authorclass = array ( 'author-' . sanitize_html_class( $author->user_nicename, $author->ID ) );
          
          $classes = array_diff( $classes, $authorclass );
          
        }
        
      }
        
      return $classes;
      
    }
    
    
    /**
		 * get user id from encrypted value for buddypress
     * used by bp_core_get_userid filter and bp_core_get_userid_from_nicename filter
		 */
    function get_user_buddypress( $user_id, $user_name ) {
      
      if ( empty( $user_id) && ctype_xdigit( $user_name ) ) {
        
        $user = get_user_by( 'id', $this->decrypt( $user_name ) );
        
        if ( $user ) {
          
          $user_id = $user->ID;
          
        }
        
      }
      
      return $user_id;
      
    }

    
    /**
		 * helper function to encrypt author name
		 */
    private function encrypt( $id ) {

      // since 4.0.0 mcrypt_encrypt is no longer supported, always use openssl_encrypt
      // since 4.0.7 fall back to plain PHP where OpenSSL has no Blowfish
      if ( $this->blowfish() ) {
        return bin2hex( $this->blowfish()->encrypt( base_convert( $id, 10, 36 ) ) );
      }

      return bin2hex( openssl_encrypt( base_convert( $id, 10, 36 ), 'BF-ECB', $this->_key, OPENSSL_RAW_DATA ) );

    }


    /**
		 * helper function to decrypt author name
		 */
    private function decrypt( $encid ) {

      // since 4.0.0 mcrypt_decrypt is no longer supported, always use openssl_decrypt
      // since 4.0.7 fall back to plain PHP where OpenSSL has no Blowfish
      if ( $this->blowfish() ) {
        return base_convert( $this->blowfish()->decrypt( pack('H*', $encid) ), 36, 10 );
      }

      return base_convert( openssl_decrypt( pack('H*', $encid), 'BF-ECB', $this->_key, OPENSSL_RAW_DATA ), 36, 10 );

    }


    /**
     * get the plain PHP Blowfish if OpenSSL can't do BF-ECB
     *
     * OpenSSL 3 only has Blowfish in its legacy provider, which most servers don't load,
     * so openssl_encrypt() returned false and every author link lost its slug.
     * The plain PHP version gives the same slugs OpenSSL did.
     *
     * @since  4.0.7
     * @access private
     * @return object|false false if OpenSSL can do it
     */
    private function blowfish() {

      if ( null === $this->_blowfish ) {

        $this->_blowfish = false;

        if ( ! function_exists( 'openssl_encrypt' ) || false === @openssl_encrypt( '', 'BF-ECB', $this->_key, OPENSSL_RAW_DATA ) ) {

          // don't leave our failure in OpenSSL's error queue for other code to find
          while ( function_exists( 'openssl_error_string' ) && openssl_error_string() ) {}

          require_once plugin_dir_path( $this->get_plugin_file() ) . 'inc/class-smart-user-slug-hider-blowfish.php';
          $this->_blowfish = new PP_Smart_User_Slug_Hider_Blowfish( $this->_key );

        }

      }

      return $this->_blowfish;

    }

    
		/**
		 * public functions
		 */
     
    // get the encrypted user slug for a given user id
		public function get_smart_user_slug( $user_id = false ) {
      
			$slug = '';
      
			if ( ! $user_id ) {
        
				if ( in_the_loop() ) {
          
					$user_id = get_the_author_meta( 'ID' );
          
				} else {
          
					$user_id = get_current_user_id();
          
				}
        
			}
      
			if ( $user_id ) {
        
				$slug = $this->encrypt( $user_id );
        
			}
      
			return $slug;
      
		}

    
    // print the encrypted user slug for a given user id
		public function the_smart_user_slug( $user_id = false ) {
			
      echo get_smart_user_slug( $user_id );
      
		}

	}

}