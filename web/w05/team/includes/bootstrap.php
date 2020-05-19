<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BOOTSTRAPPED', true);
define('BASE_PATH', '/w05/prove/'); // Production
//define('BASE_PATH', '/'); // Development

include_once( 'autoloader.php' );

function path( $path ) {
    return BASE_PATH . $path;
}

function redirect( $path ) {
    header('Location: ' . path( $path ));
    exit;
}