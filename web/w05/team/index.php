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

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        echo 'User: ' . $row['email'] . ' Password: ' . $row['password'] . '<br/>';
    }

} catch( PDOException $e ) {

    echo $e->getMessage();

}
