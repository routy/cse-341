<?php

require_once('includes/bootstrap.php');

echo 'Database Connection Test.<br/>';

$db = Database::getInstance()->connection();
$statement = $db->query( 'select * from users' );

$results = $statement->fetchAll(PDO::FETCH_ASSOC);

print_r($results);


