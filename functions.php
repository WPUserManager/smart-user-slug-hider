<?php

/**
 * The Smart User Slug Hider Plugin Functions
 *
 * @since 1.0
 * functions.php since 4.0.0
 *
 **/
 
 
function get_smart_user_slug( $user_id = false ) {
   
  return pp_smart_user_slug_hider()->get_smart_user_slug( $user_id );
  
}


function the_smart_user_slug( $user_id = false ) {

  pp_smart_user_slug_hider()->the_smart_user_slug( $user_id );
  
}


?>