<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BOOTSTRAPPED', true);

if(!getenv('BASE_PATH')) {
    define('BASE_PATH', '/w06/team/'); 
} else {
    define('BASE_PATH', getenv('BASE_PATH')); 
}

include_once( 'autoloader.php' );

if (isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {

    $session = Session::getInstance();

}

function isCurrentPage( $page ) {
    return (isset($_SERVER['REQUEST_URI']) && '/' . $page === $_SERVER['REQUEST_URI']);
}

function path( $path ) {
    return BASE_PATH . $path;
}

function redirect( $path ) {
    header('Location: ' . path( $path ));
    exit;
}


/*
 * Initiate Session
 */ 
Session::getInstance();
