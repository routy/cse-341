<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(!getenv('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__FILE__)) . '/');
} else {
    define('BASE_PATH', getenv('BASE_PATH')); 
}
if(!getenv('BASE_URL')) {
    $protocol = (!isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == '')) ? 'http://' : 'https://';
    $path     = $protocol . $_SERVER['HTTP_HOST'] . '/';
    define('BASE_URL', $path);
} else {
    define('BASE_URL', getenv('BASE_URL')); 
}
if(!getenv('TEMPLATE_PATH')) {
    define('TEMPLATE_PATH', BASE_PATH . 'templates/');
} else {
    define('TEMPLATE_PATH', BASE_PATH . getenv('TEMPLATE_PATH')); 
}
if(!getenv('INCLUDE_PATH')) {
    define('INCLUDE_PATH', BASE_PATH . 'includes/');
} else {
    define('INCLUDE_PATH', BASE_PATH . getenv('INCLUDE_PATH')); 
}
if(!getenv('ASSET_URL')) {
    define('ASSET_URL', BASE_URL . 'assets/');
} else {
    define('ASSET_URL', BASE_URL . getenv('ASSET_URL')); 
}

define('BOOTSTRAPPED', true);

require_once( INCLUDE_PATH . 'autoloader.php' );
require_once( INCLUDE_PATH . 'functions.php' );

/*
 * Initiate Session
 */ 
Session::getInstance();

/*
 * Handle any actions that may be included in the request.
 */
new Action_Handler();