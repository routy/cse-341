<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Message {

    public static function get()
    {
        $session  = Session::getInstance();
        $messages = $session->get('messages');
        if ( $messages && is_array( $messages ) ) {
            $response = $messages;
        } else {
            $response = [];
        }

        return $response;
    }

    public static function add($message, $type = 'success')
    {
        $session  = Session::getInstance();
        $messages = $session->get('messages');

        $messages[] = [
            'message' => $message,
            'type' => $type
        ];

        $session->store('messages', $messages);

        return true;

    }

    public static function reset()
    {
        $session  = Session::getInstance();
        $session->remove('messages');
    }

    public static function display() 
    {
        include( TEMPLATE_PATH . 'messages.php');
    }

}