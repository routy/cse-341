<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Action_Handler {

    /**
     * 
     */
    public function __construct()
    {
        if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
            $action = toCamelCase($_REQUEST['action']);
            if (method_exists($this, $action)){
                $this->$action;
            }
        }
    }

    /**
     * 
     */
    public function cancelItemInQueue()
    {
        Message::add('This action has not been implemented.', 'danger');

        redirect('index.php' );
    }

    /**
     * 
     */
    public function nextInQueue()
    {
        if (!User::isLoggedIn()) {
            redirect('index.php');
        }

        if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

            $locationId = $_REQUEST['location_id'];

            try {

                $location = new Location( $locationId );
            
            }  catch( Exception $e ) {

                Message::add('We were unable to find the location you specified.', 'danger');

                redirect('index.php' );

            }

            try {

                $result = $location->setNextInQueueActive();

                if ( $result ) {
                    Message::add('Queue has been advanced successfully.');
                } else {
                    Message::add('We were unable to advance the queue.', 'danger');
                }

                redirect('index.php?location_id=' . $location->id );

            } catch( Exception $e ) {

                Message::add('We were unable to progress the queue. An error has occurred.', 'danger');

                redirect('index.php' );

            }
        }

        redirect('index.php');
    }

    /**
     * 
     */
    public function addItemToQueue()
    {
        if ( isset( $_REQUEST['location_id'] ) && is_numeric( $_REQUEST['location_id'] ) ) {

            $locationId = $_REQUEST['location_id'];

            try {

                $location = new Location( $locationId );
            
            }  catch( Exception $e ) {

                Message::add('We were unable to find the location you specified.', 'danger');

                redirect('index.php' );

            }

            try {

                $session = Session::getInstance();

                // Store the user's token into their session
                if (!$session->has($location->id .'_token')) {

                    $token    = $location->addNewQueueItem();
                    $position = $location->getCurrentQueuePositionByToken($token);

                    $session->store( $location->id .'_token', $token );

                    Message::add('You have been added to the queue. Your queue position is ' . $position . '.');

                } else {

                    $token = $session->get( $location->id .'_token' );
                    $position = $location->getCurrentQueuePositionByToken($token);

                    if ( !$position ) {
                        $session->remove( $location->id .'_token' );

                        Message::add('Your token has expired. Please try again.', 'danger');

                    } else {

                        Message::add('You already have an active queue position. Your queue position is ' . $position, 'danger');
                    
                    }

                }

                redirect('index.php?location_id=' . $location->id );

            } catch( Exception $e ) {

                Message::add('We were unable to add you to the queue. An error has occurred.', 'danger');

                redirect('index.php' );

            }
        }

        redirect('index.php');
    }
}