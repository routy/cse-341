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

if (isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {

    $session = Session::getInstance();

    switch ($_REQUEST['action']){

        case 'add_user_to_queue':
            if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

                $location_id = $_REQUEST['location_id'];

                $messages = $session->get('messages');
                $messages[] = [
                    'message' => 'You have been added to the queue.',
                    'type' => 'success'
                ];
                $session->store('messages', $messages);

                redirect('index.php?location_id=' . $location_id );
                
            }

            redirect('index.php');
            
            break;

        default:

        break;

    }

}


/*
 * Initiate Session
 */ 
Session::getInstance();
