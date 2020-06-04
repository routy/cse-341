<?php

if (!defined('BOOTSTRAPPED')) {
    exit;
}

class Database {

    /**
     * Instance of the DB
     */
    private static $_instance = null;

    /**
     * Store the PDO instance
     */
    private $_connection = false;

    private function __construct() {

        $uri = getenv('HEROKU_POSTGRESQL_BROWN_URL');

        try {
            
            $db = parse_url( $uri );

            $db = new PDO("pgsql:" . sprintf(
                "host=%s;port=%s;user=%s;password=%s;dbname=%s;sslmode=require",
                $db["host"],
                $db["port"],
                $db["user"],
                $db["pass"],
                ltrim($db["path"], "/")
            ));

            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->_connection = $db;

        } catch( PDOException $e ) {

            echo $e->getMessage();

        }

    }

    /**
     * Retrieve instance of the Database class
     *
     * @return Database|null
     */
    public static function getInstance() {

        if (self::$_instance == null) {
            self::$_instance = new Database();
        }

        return self::$_instance;
    }

    public function connection() {
        return $this->_connection;
    }



}