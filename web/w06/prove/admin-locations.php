<?php

require_once('includes/bootstrap.php');

require_once('templates/admin-header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Locations</h1>
</div>

<?php 

$db = Database::getInstance()->connection();

$query  = "SELECT l.* 
           FROM locations as l
           INNER JOIN location_user lu 
            ON (l.id = lu.location_id AND lu.user_id = ?)";

$params     = [ getLoggedInUserId() ];
$statement  = $db->prepare($query);
$result     = $statement->execute($params);
$locations  = $statement->fetchAll(PDO::FETCH_ASSOC);

echo '<pre>';
print_r($locations);
echo '</pre>';

?>

<?php require_once('templates/admin-footer.php'); ?>