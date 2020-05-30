<?php

$searchText = false;

if ( isset($_GET['search']) && !empty($_GET['search']) ) {
    $searchText = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
}

$db = Database::getInstance()->connection();

$query = "SELECT l.id
          FROM locations as l
          WHERE l.status_id = ?";

$params = [ Location::STATUS_ACTIVE ];

if ( $searchText ) {
    $query .= ' AND LOWER(l.name) LIKE ?';
    $params[] = '%'. strtolower( filter_var($_GET['search'], FILTER_SANITIZE_STRING) ) . '%';
}

$db = Database::getInstance()->connection();
$statement  = $db->prepare($query);
$result     = $statement->execute($params);
$locations  = $statement->fetchAll(PDO::FETCH_COLUMN);

?>

<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Choose a Location</h1>
    <p class="lead">Select a location to enter the queue.</p>
    <div class="container" style="text-align:center;">
    <form class="form-inline mt-2 mt-md-0">
        <input class="form-control mr-sm-2" type="text" placeholder="Search by Name" aria-label="Search" name="search" value="<?php echo ($searchText) ? $searchText : ''; ?>"/>
        <button class="btn btn-primary my-2 my-sm-0" type="submit">Search</button>
    </form>
</div>
</div>

<div class="container">

    <div class="card-deck mb-3 text-center">

        <?php foreach ($locations as $location_id) : 
        
            $location = new Location( $location_id );
            
        ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal location-name"><?php echo $location->name; ?></h4>
                </div>
                <div class="card-body">
                        
                    <div class="location-address">
                        <?php echo $location->getFormattedAddress(); ?>
                    </div>

                    <a href="<?php echo path('?location_id=' . $location->id); ?>"
                            class="btn btn-lg btn-block btn-outline-primary">
                        View Location
                    </a>

                </div>
            </div>

        <?php endforeach; ?>

    </div>
</div>