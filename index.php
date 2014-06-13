<?php
require( 'app/core/autoloader.php' );

// define routes
Router::get( '/', 'dashboard@index' );

// if no route found
Router::error( 'error@index' );

// execute matched routes
Router::dispatch();
ob_flush();
