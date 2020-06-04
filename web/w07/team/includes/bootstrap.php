<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BOOTSTRAPPED', true);

if(!getenv('BASE_PATH')) {
    define('BASE_PATH', '/w07/team/'); 
} else {
    define('BASE_PATH', getenv('BASE_PATH')); 
}

include_once( 'autoloader.php' );

function isLoggedIn() {
    $session = Session::getInstance();
    return $session->has('userId');
}

function getLoggedInUserId() {
    $session = Session::getInstance();
    return $session->get('userId');
}

function getUser() {
    
    if ( isLoggedIn() ) {
        $userId = getLoggedInUserId();

        $database = Database::getInstance();
        $query = "SELECT u.*
                  FROM users as u
                  WHERE u.id = ?
                  LIMIT 1";

        $params = [ $userId ];

        $db = Database::getInstance()->connection();
        $statement = $db->prepare($query);
        $result    = $statement->execute($params);
        $user      = $statement->fetch(PDO::FETCH_ASSOC);

        return $user;
    } 

    return false;
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
