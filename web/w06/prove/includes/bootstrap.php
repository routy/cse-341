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

        case 'cancel_item_in_queue':

        break;

        case 'next_in_queue':

            if (!isLoggedIn()) {
                redirect('index.php');
            }

            if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

                $locationId = $_REQUEST['location_id'];

                $messages = $session->get('messages');

                try {

                    $location = new Location( $locationId );
                
                }  catch( Exception $e ) {

                    $messages = $session->get('messages');
                    $messages[] = [
                        'message' => 'We were unable to find the location you specified.',
                        'type' => 'danger'
                    ];
                    $session->store('messages', $messages);

                    redirect('index.php' );

                }

                try {

                    $result = $location->setNextInQueueActive();

                    if ( $result ) {
                        $messages[] = [
                            'message' => 'Queue has been advanced successfully.',
                            'type' => 'success'
                        ];
                        $session->store('messages', $messages);
                    } else {
                        $messages[] = [
                            'message' => 'We were unable to advance the queue.',
                            'type' => 'danger'
                        ];
                        $session->store('messages', $messages);
                    }

                    redirect('index.php?location_id=' . $location->id );

                } catch( Exception $e ) {

                    $messages = $session->get('messages');
                    $messages[] = [
                        'message' => 'We were unable to progress the queue. An error has occurred.',
                        'type' => 'danger'
                    ];
                    $session->store('messages', $messages);

                    redirect('index.php' );

                }
            }

            redirect('index.php');
            
            break;

        break;

        case 'add_item_to_queue':

            if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

                $locationId = $_REQUEST['location_id'];

                $messages = $session->get('messages');

                try {

                    $location = new Location( $locationId );
                
                }  catch( Exception $e ) {

                    $messages = $session->get('messages');
                    $messages[] = [
                        'message' => 'We were unable to find the location you specified.',
                        'type' => 'danger'
                    ];
                    $session->store('messages', $messages);

                    redirect('index.php' );

                }

                try {

                    // Store the user's token into their session
                    if (!$session->has($location->id .'_token')) {

                        $token    = $location->addNewQueueItem();
                        $position = $location->getCurrentQueuePositionByToken($token);

                        $session->store( $location->id .'_token', $token );

                        $messages[] = [
                            'message' => 'You have been added to the queue. Your queue position is ' . $position . '.',
                            'type' => 'success'
                        ];
                        $session->store('messages', $messages);

                    } else {

                        $token = $session->get( $location->id .'_token' );
                        $position = $location->getCurrentQueuePositionByToken($token);

                        if ( !$position ) {
                            $session->remove( $location->id .'_token' );

                            $messages[] = [
                                'message' => 'Your token has expired. Please try again.',
                                'type' => 'danger'
                            ];
                            $session->store('messages', $messages);

                        } else {

                            $messages[] = [
                                'message' => 'You already have an active queue position. Your queue position is ' . $position,
                                'type' => 'danger'
                            ];
                            $session->store('messages', $messages);
                        
                        }

                    }

                    redirect('index.php?location_id=' . $location->id );

                } catch( Exception $e ) {

                    echo $e->getLine() . ' :: ' . $e->getMessage();

                    $messages = $session->get('messages');
                    $messages[] = [
                        'message' => 'We were unable to add you to the queue. An error has occurred.',
                        'type' => 'danger'
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
