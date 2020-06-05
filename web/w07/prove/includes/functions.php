<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

function isCurrentPage( $page ) {
    return (isset($_SERVER['REQUEST_URI']) && '/' . $page === $_SERVER['REQUEST_URI']);
}

function path( $path ) {
    return BASE_PATH . $path;
}

function url( $path ) {
    return BASE_URL . $path;
}

function redirect( $path ) {
    header('Location: ' . url( $path ));
    exit;
}

function toCamelCase($string) {
    return lcfirst(str_replace(' ', '',ucwords(str_replace('_', ' ', $string))));
}