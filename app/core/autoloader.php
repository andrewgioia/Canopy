<?php
// Turn on output buffering
ob_start();

function autoloader( $class ) {

   $filename = "app/controllers/".strtolower( $class ).".php";
   if ( file_exists( $filename ) ) {
      require $filename;
   }

   $filename = "app/core/".strtolower( $class ).".php";
   if ( file_exists( $filename ) ) {
      require $filename;
   }

   $filename = "app/helpers/".strtolower( $class ).".php";
   if ( file_exists( $filename ) ) {
      require $filename;
   }

}

// Run autoloader
spl_autoload_register('autoloader');

// Start sessions
Session::init();

// Load the config (local first, if it's there)
if ( file_exists( 'app/core/config.local.php' ) ) {
   require( 'app/core/config.local.php' );
} else {
   require( 'app/core/config.php' );  
}

