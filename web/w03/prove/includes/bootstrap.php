<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BOOTSTRAPPED', true);
define('BASE_PATH', '/w03/prove/'); // Production
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
    $cart    = Cart::getInstance();

    switch ($_REQUEST['action']){

        case 'add_to_cart':
            if ( isset( $_REQUEST['quantity'] ) && is_numeric( $_REQUEST['quantity'] )
                && isset( $_REQUEST['item_id'] ) && is_numeric( $_REQUEST['item_id'] ) ) {
                $cart->add_to_cart( (int) $_REQUEST['item_id'], (int) $_REQUEST['quantity'] );
            }
            $messages = $session->get('messages');
            $messages[] = [
                'message' => 'Item has been added to your cart.',
                'type' => 'success'
            ];
            $session->store('messages', $messages);

            redirect('index.php');
            break;

        case 'remove_from_cart':
            if ( isset( $_REQUEST['item_id'] ) && is_numeric( $_REQUEST['item_id'] ) ) {
                $cart->remove_from_cart( (int) $_REQUEST['item_id'] );
            }
            redirect('cart.php');
            break;

        case 'empty_cart':
            $cart->empty_cart();

            $messages = $session->get('messages');
            $messages[] = [
                'message' => 'Your cart has been emptied',
                'type' => 'primary'
            ];
            $session->store('messages', $messages);

            redirect('cart.php');
            break;

        case 'update_cart':
            if ( isset( $_REQUEST['items'] ) && count( $_REQUEST['items'] ) > 0 ) {

                foreach ($_REQUEST['items'] as $item_id => $item) :

                    if (is_numeric($item_id) && isset($item['quantity']) && is_numeric($item['quantity'])) {
                        $cart->update_cart((int)$item_id, (int)$item['quantity']);
                    }

                endforeach;
            }

            $messages = $session->get('messages');
            $messages[] = [
                'message' => 'Your cart has been updated',
                'type' => 'success'
            ];
            $session->store('messages', $messages);

            redirect('cart.php');
            break;

        case 'set_checkout_details':

            $session = Session::getInstance();

            if (
                isset( $_POST['first_name'] ) && !empty( $_POST['first_name'] ) &&
                isset( $_POST['last_name'] ) && !empty( $_POST['last_name'] ) &&
                isset( $_POST['address'] ) && !empty( $_POST['address'] ) &&
                isset( $_POST['city'] ) && !empty( $_POST['city'] ) &&
                isset( $_POST['state'] ) && !empty( $_POST['state'] ) &&
                isset( $_POST['country'] ) && !empty( $_POST['first_name'] ) &&
                isset( $_POST['zip'] ) && !empty( $_POST['zip'] ) &&
                isset( $_POST['email'] ) && !empty( $_POST['email'] )
            ) :

                $checkout = [
                    'first_name' => filter_var( $_POST['first_name'],FILTER_SANITIZE_STRING ),
                    'last_name' => filter_var( $_POST['last_name'],FILTER_SANITIZE_STRING ),
                    'address' => filter_var( $_POST['address'],FILTER_SANITIZE_STRING ),
                    'address2' => filter_var( $_POST['address2'],FILTER_SANITIZE_STRING ),
                    'city' => filter_var( $_POST['city'],FILTER_SANITIZE_STRING ),
                    'state' => filter_var( $_POST['state'],FILTER_SANITIZE_STRING ),
                    'country' => filter_var( $_POST['country'],FILTER_SANITIZE_STRING ),
                    'email' => filter_var( $_POST['email'],FILTER_SANITIZE_EMAIL ),
                    'zip' => filter_var( $_POST['zip'],FILTER_SANITIZE_STRING )
                ];

                $session->store('checkout', $checkout );

                redirect('confirmation.php');

            endif;

            redirect('checkout.php');

            break;

        default:

    }

}


/*
 * Initiate Session
 */ 
Session::getInstance();
