<?php

echo 'HERE';
exit;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BOOTSTRAPPED', true);

if(file_exists('local-config.php')) {
    require_once('local-config.php');
}

define('BASE_PATH', '/w05/prove/');

include_once( 'autoloader.php' );

function path( $path ) {
    return BASE_PATH . $path;
}

function redirect( $path ) {
    header('Location: ' . path( $path ));
    exit;
}