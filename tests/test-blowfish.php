<?php

/**
 * Checks PP_Smart_User_Slug_Hider_Blowfish against OpenSSL's BF-ECB.
 *
 * Needs a PHP whose OpenSSL can still do Blowfish. On OpenSSL 3, load the legacy provider:
 *
 *   OPENSSL_CONF=tests/openssl-legacy.cnf OPENSSL_MODULES=<openssl>/lib/ossl-modules php tests/test-blowfish.php
 *
 * Without Blowfish in OpenSSL it only checks that the class round-trips.
 */

define( 'WPINC', 'wp-includes' );
require dirname( __DIR__ ) . '/inc/class-smart-user-slug-hider-blowfish.php';

$openssl = false !== @openssl_encrypt( '', 'BF-ECB', 'key', OPENSSL_RAW_DATA );
echo $openssl ? "Comparing with OpenSSL\n" : "OpenSSL has no Blowfish here: round trips only\n";

$failures = 0;
$checks   = 0;

function check( $ok, $what ) {
  global $failures, $checks;
  $checks++;
  if ( ! $ok ) {
    $failures++;
    echo 'FAIL: ' . $what . "\n";
  }
}

// random_bytes() refuses a length of 0 on PHP 8
function bytes( $length ) {
  return $length ? random_bytes( $length ) : '';
}

mt_srand( 4007 );

// keys like the plugin's (md5 hex, 32 bytes) and random keys of every length OpenSSL accepts
$keys = array( md5( '127.0.0.1/var/www/html/wp-content/plugins/smart-user-slug-hider/smart-user-slug-hider.php' ), md5( 'ceb2d699fa' ) );
for ( $len = 1; $len <= 80; $len++ ) {
  $keys[] = random_bytes( $len );
}

foreach ( $keys as $key ) {

  $bf = new PP_Smart_User_Slug_Hider_Blowfish( $key );

  // the plugin encrypts user IDs in base 36
  $plains = array();
  foreach ( array( 1, 2, 9, 10, 35, 36, 1295, 1296, 78364164095, 78364164096, PHP_INT_MAX ) as $id ) {
    $plains[] = base_convert( $id, 10, 36 );
  }
  for ( $i = 0; $i < 40; $i++ ) {
    $plains[] = base_convert( mt_rand( 1, 5000000 ), 10, 36 );
    $plains[] = bytes( mt_rand( 0, 40 ) );
  }

  foreach ( $plains as $plain ) {

    $cipher = $bf->encrypt( $plain );
    check( $plain === $bf->decrypt( $cipher ), 'round trip ' . bin2hex( $plain ) );

    if ( $openssl ) {
      check( openssl_encrypt( $plain, 'BF-ECB', $key, OPENSSL_RAW_DATA ) === $cipher, 'encrypt matches OpenSSL, key length ' . strlen( $key ) );
    }

  }

  // anything a visitor can put in an author URL: the plugin only accepts hex
  for ( $i = 0; $i < 40; $i++ ) {

    $bytes = bytes( mt_rand( 0, 24 ) );
    $ours  = $bf->decrypt( $bytes );

    if ( $openssl ) {
      check( @openssl_decrypt( $bytes, 'BF-ECB', $key, OPENSSL_RAW_DATA ) === $ours, 'decrypt of junk matches OpenSSL: ' . bin2hex( $bytes ) );
    }

  }

}

echo $checks . ' checks, ' . $failures . " failures\n";
exit( $failures ? 1 : 0 );
