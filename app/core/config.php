<?php

set_exception_handler( 'logger::exception_handler' );
set_error_handler( 'logger::error_handler' );

// Set timezone
date_default_timezone_set( 'America/New_York' );

// Site address
define( 'DIR', 'localhost' );

// Database details
define( 'DB_TYPE', 'mysql' );
define( 'DB_HOST', 'localhost' );
define( 'DB_NAME', 'canopy' );
define( 'DB_USER', '' );
define( 'DB_PASS', '' );
define( 'PREFIX', '' );

// API details
define( 'WU_KEY', '' );

// Set prefix for sessions
define( 'SESSION_PREFIX','canopy' );

// Optional create a constant for the name of the site
define( 'SITETITLE', 'Canopy' );

// Set the default template
Session::set( 'template', 'default' );