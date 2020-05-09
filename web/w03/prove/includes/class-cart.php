<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Cart {

    /**
     * Instance of the Cart
     */
    private static $_instance = null;

    private $_items;

    private $_items_in_cart;

    /**
     * @var $_session Session
     */
    private $_session;

    private function __construct() {

        $this->_session = Session::getInstance();

        // Populate the items in cart with the saved session
        $this->retrieve_saved_cart();

        $this->_items = [
            1 => new Product( 1, 'Desktop Computer', 'This is the product description', 799.99 ),
            2 => new Product( 2, 'Laptop Computer', 'This is the product description', 1299.99 ),
            3 => new Product( 3, 'Cell Phone', 'This is the product description', 399.99 )
        ];

    }

    /**
     * Retrieve instance of the Cart class
     *
     * @return Cart|null
     */
    public static function getInstance() {

        if (self::$_instance == null) {
            self::$_instance = new Cart();
        }

        return self::$_instance;
    }

    /**
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function add_to_cart( $id, $quantity = 1 ) {
        
        if ( isset( $this->_items_in_cart[$id] ) ) {
            $this->_items_in_cart[$id]['quantity'] += $quantity;
        } else {
            $this->_items_in_cart[$id]['quantity'] = $quantity;
        }

        $this->save();

        return true;
    }

    /**
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function update_cart( int $id, int $quantity ) {

        if ( $quantity === 0 ) {
            $this->remove_from_cart( $id );
        } else if ( $quantity > 0) {
            $this->_items_in_cart[$id]['quantity'] = $quantity;
        }

        $this->save();

        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public function remove_from_cart( $id ) {
        if ( isset( $this->_items_in_cart[$id] ) ) {
            unset($this->_items_in_cart[$id]);
        }

        $this->save();

        return true;
    }

    /**
     * @return bool
     */
    public function empty_cart( ) {
        if ( count( $this->_items_in_cart ) > 0 ) {
            foreach( $this->_items_in_cart as $item_id => $item ) {
                unset($this->_items_in_cart[$item_id]);
            }
        }

        $this->save();

        return true;
    }

    /**
     * @return void
     */
    protected function retrieve_saved_cart() {
        $this->_items_in_cart = $this->_session->get( 'cart' );
    }

    /**
     * @return bool
     */
    protected function save() {
        return $this->_session->store( 'cart', $this->_items_in_cart );
    }

    /**
     * @return array
     */
    public function get_items() {
        return $this->_items;
    }

    /**
     * @return array
     */
    public function get_items_in_cart() {
        $items = [];
        foreach( $this->_items_in_cart as $key => $values ) {
            $items[$key] = array_merge( $values, [ 'product' => $this->_items[$key] ] );
        }
        return $items;
    }

    public function get_cart_total( $formatted = false ) {
        $items = $this->get_items_in_cart();
        $total = (count($items) > 0) ? array_reduce($items, function( $total, $item ) { return $total += $item['quantity'] * $item['product']->price; }) : 0;
        if ( $formatted ) {
            $total = '$' .  number_format($total, 2);
        }
        return $total;
    }

    public function get_cart_item_total( $item_id, $formatted = false ) {
        $items = $this->get_items_in_cart();
        $total = (isset($items[$item_id])) ? $items[$item_id]['quantity'] * $items[$item_id]['product']->price : 0;
        if ( $formatted ) {
            $total = '$' .  number_format($total, 2);
        }
        return $total;
    }

    public function get_cart_item_count() {
        $items = $this->get_items_in_cart();
        return (count($items) > 0) ? array_reduce($items, function( $total, $item ) { return $total += $item['quantity']; }) : 0;
    }

}