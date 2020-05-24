<?php

require_once('includes/bootstrap.php');

require_once('templates/header.php'); ?>

<?php if ( isset( $_GET['location_id'] ) && is_numeric( $_GET['location_id'] ) ) : 

    require_once('templates/locations-single.php');

else :

    require_once('templates/locations-all.php');

endif; ?>

<?php require_once('templates/footer.php'); ?>