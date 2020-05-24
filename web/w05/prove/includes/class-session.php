<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Session
{

    /**
     * Instance of the Session
     */
    private static $_instance = null;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retrieve instance of the Session class
     */
    public static function getInstance()
    {

        if (self::$_instance == null) {
            self::$_instance = new Session();
        }

        return self::$_instance;
    }

    /**
     * 
     */
    public function store($name, $value)
    {
        $_SESSION[$name] = $value;
        return true;
    }

    /**
     * 
     */
    public function remove($name)
    {
        unset($_SESSION[$name]);
        return true;
    }

    /**
     * 
     */
    public function get($name)
    {
        return (isset($_SESSION[$name])) ? $_SESSION[$name] : null;
    }

    /**
     * 
     */
    public function has($name)
    {
        return (isset($_SESSION[$name]));
    }
}
