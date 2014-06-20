<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);  

require( 'app/core/autoloader.php' );

// Define routes (route, controller@method)
Router::get( '/', 'dashboard@index' );
Router::get( '/home', 'dashboard@index' );
Router::post( '/home', 'dashboard@index' );
Router::get( '/settings', 'settings@index' );
Router::get( '/settings/weather/add_day', 'settings@add_weather_for_day' );
Router::post( '/settings/weather/add_day', 'settings@add_weather_for_day' );
Router::get( '/settings/vacation/add', 'settings@add_vacation' );
Router::post( '/settings/vacation/add', 'settings@add_vacation' );

// If no route is found, throw an error
Router::error( 'error@index' );

// Execute matched routes
Router::dispatch();
ob_flush();
