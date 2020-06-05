<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

spl_autoload_register( function( $class ) {
    $file = 'class-' . str_replace( '_', '-', strtolower( $class ) ) . '.php';
    require_once( INCLUDE_PATH . $file );
});