<?php

echo 'Database Connection Test.';

$uri = getenv('DATABASE_URL');

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

    $query = "SELECT * FROM users";

    $statement = $db->query( $query );
    $results   = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch( PDOException $e ) {

    echo $e->getMessage();

}
