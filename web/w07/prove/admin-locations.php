<?php

require_once( dirname(__FILE__) . '/includes/bootstrap.php');

require_once( TEMPLATE_PATH . 'admin/admin-header.php'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Locations</h1>
</div>

<?php 

$db = Database::getInstance()->connection();

$query  = "SELECT l.id 
           FROM locations as l
           INNER JOIN location_user lu 
            ON (l.id = lu.location_id AND lu.user_id = ?)";

$params     = [ User::getCurrentUserId() ];
$statement  = $db->prepare($query);
$result     = $statement->execute($params);
$locations  = $statement->fetchAll(PDO::FETCH_ASSOC);

if( $locations && count($locations) > 0 ) : ?>

    <a href="<?php echo url('admin-locations.php?form=add_new_location'); ?>" class="btn btn-success mb-3">Add New Location</a>
    <table class="table">
        <thead class="thead-dark">
            <tr>
            <th scope="col">Location Status</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th> 
            <th scope="col">Queue Status</th>
            <th scope="col">In Queue</th>
            <th scope="col">Est. Wait Time</th>
            <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

        <?php foreach( $locations as $l ) : ?>

            <?php

            $location = new Location( $l['id'] );

            ?>

            <tr>
            <th scope="row"><?php echo $location->getFormattedStatus(); ?></th>
            <td><?php echo $location->name; ?></td>
            <td><?php echo $location->getFormattedAddress(); ?></td>
            <th><?php echo $location->getFormattedStatus(); ?></th>
            <td><?php echo $location->getQueueItemCountByStatus(); ?></td>
            <td><?php echo $location->getEstimatedWaitTime( $format = 'minutes' ); ?> mins</td>
            <td><a href="<?php echo url('index.php?location_id=' . $location->id) ?>" class="btn btn-sm btn-outline-primary">View Location</a></td>
            </tr>
            
        <?php endforeach; ?>
        
        </tbody>
    </table>

<?php else : ?>

    <p>You don't currently manage any locations.</p>

<?php endif; ?>

<?php require_once( TEMPLATE_PATH . 'admin/admin-footer.php'); ?>