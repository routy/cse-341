<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Product {

    /**
     * @var $id int
     */
    public $id;
    /**
     * @var $name string
     */
    public $name;

    /**
     * @var $description string
     */
    public $description;

    /**
     * @var $price float
     */
    public $price;

    public function __construct( int $id, string $name, string $description, float $price ) {

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;

    }

    public function get_price( $formatted = false ) {
        return ( $formatted ) ? '$' . number_format( $this->price, 2 ) : $this->price;
    }

}