<?php

/**
 * Checks PP_Smart_User_Slug_Hider::replace_name_in_url(): only the user name at the end
 * of an author or member URL is replaced (issue #3).
 *
 *   php tests/test-author-link.php
 */

define( 'WPINC', 'wp-includes' );
require dirname( __DIR__ ) . '/inc/ppf/loader.php';
require dirname( __DIR__ ) . '/inc/class-smart-user-slug-hider.php';

$slug  = 'e9e716def73f76ac';
$cases = array(
  // url, nicename, expected
  array( 'https://example.com/author/jane/', 'jane', "https://example.com/author/$slug/" ),
  array( 'https://example.com/author/jane', 'jane', "https://example.com/author/$slug" ),
  array( 'https://example.com/author/jane/?lang=de', 'jane', "https://example.com/author/$slug/?lang=de" ),
  array( 'https://example.com/author/jane/#posts', 'jane', "https://example.com/author/$slug/#posts" ),
  // the domain matches the user name (forum: "A username matches domain")
  array( 'https://jane.example/author/jane/', 'jane', "https://jane.example/author/$slug/" ),
  array( 'https://jane/author/jane/', 'jane', "https://jane/author/$slug/" ),
  // a path segment matches the user name
  array( 'https://example.com/author/author/', 'author', "https://example.com/author/$slug/" ),
  array( 'https://example.com/wp/author/wp/', 'wp', "https://example.com/wp/author/$slug/" ),
  array( 'https://example.com/en/author/en/', 'en', "https://example.com/en/author/$slug/" ),
  // a longer segment that only starts with the name is not the name
  array( 'https://example.com/author/janet/', 'jane', 'https://example.com/author/janet/' ),
  // plain permalinks have no name in the URL: leave them alone, even if the domain matches
  array( 'https://jane.example/?author=2', 'jane', 'https://jane.example/?author=2' ),
  array( 'https://example.com/?author=2', 'jane', 'https://example.com/?author=2' ),
  // characters that mean something in a regular expression
  array( 'https://example.com/author/jane.doe/', 'jane.doe', "https://example.com/author/$slug/" ),
  array( 'https://example.com/author/janexdoe/', 'jane.doe', 'https://example.com/author/janexdoe/' ),
  array( 'https://example.com/author/j+d/', 'j+d', "https://example.com/author/$slug/" ),
  // BuddyPress member URLs, with and without the members base
  array( 'https://example.com/members/jane/', 'jane', "https://example.com/members/$slug/" ),
  array( 'https://jane.example/jane/', 'jane', "https://jane.example/$slug/" ),
  // no name: nothing to replace
  array( 'https://example.com/author/', '', 'https://example.com/author/' ),
);

$failures = 0;
foreach ( $cases as $case ) {
  list( $url, $nicename, $expected ) = $case;
  $got = PP_Smart_User_Slug_Hider::replace_name_in_url( $url, $nicename, $slug );
  if ( $got !== $expected ) {
    $failures++;
    echo "FAIL: $url ($nicename)\n  expected $expected\n  got      $got\n";
  }
}

echo count( $cases ) . ' checks, ' . $failures . " failures\n";
exit( $failures ? 1 : 0 );
