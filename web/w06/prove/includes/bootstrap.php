<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BOOTSTRAPPED', true);

if(!getenv('BASE_PATH')) {
    define('BASE_PATH', '/w06/prove/'); 
} else {
    define('BASE_PATH', getenv('BASE_PATH')); 
}

include_once( 'autoloader.php' );

if (isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {

    $session = Session::getInstance();

    switch ($_REQUEST['action']){

        case 'progress_queue':

            $userId = getLoggedInUserId();

            // Check if the user is allowed to progress the queue
            if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

                $locationId = $_REQUEST['location_id'];

                $messages = $session->get('messages');

                try {

                    $location = new Location( $locationId );
                    
                    if ( $location->isUserLocationAdmin($userId) ) {

                        $location->setNextInQueueActive();

                        $messages[] = [
                            'message' => 'Queue has advanced to the next user.',
                            'type' => 'success'
                        ];
                        $session->store('messages', $messages);

                    }

                } catch (Exception $e) {

                    $messages[] = [
                        'message' => 'You are not allowed to progress the queue for this location.',
                        'type' => 'danger'
                    ];
                    $session->store('messages', $messages);

                }
            }

        break;

        case 'cancel_item_in_queue':

        break;

        case 'add_item_to_queue':

            if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

                $locationId = $_REQUEST['location_id'];

                $messages = $session->get('messages');

                try {

                    $location = new Location( $locationId );

                    // Store the user's token into their session
                    if (!$session->has($locationId .'_token')) {

                        $token    = $location->addNewQueueItem();
                        $position = $location->getCurrentQueuePositionByToken($token);

                        $session->store( $locationId .'_token', $token );

                        $messages[] = [
                            'message' => 'You have been added to the queue. Your queue position is ' . $position . '.',
                            'type' => 'success'
                        ];
                        $session->store('messages', $messages);

                    } else {

                        $token = $session->get( $locationId .'_token' );
                        $position = $location->getCurrentQueuePositionByToken($token);

                        $messages[] = [
                            'message' => 'You already have an active queue position. Your queue position is ' . $position . '.',
                            'type' => 'error'
                        ];
                        $session->store('messages', $messages);

                    }

                    redirect('index.php?location_id=' . $location_id );

                } catch( Exception $e ) {

                    $messages = $session->get('messages');
                    $messages[] = [
                        'message' => 'We were unable to find the location you specified.',
                        'type' => 'success'
                    ];
                    $session->store('messages', $messages);

                    redirect('index.php' );

                }
            }

            redirect('index.php');
            
            break;

        default:

        break;

    }

}

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
