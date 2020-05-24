<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

spl_autoload_register( function( $class ) {
    $file = 'class-' . strtolower($class) . '.php';
    require_once( $file );
});