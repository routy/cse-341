<?php

require_once( dirname(__FILE__) . '/includes/bootstrap.php');

require_once( TEMPLATE_PATH .  'header.php'); ?>

<?php if ( isset( $_GET['location_id'] ) && is_numeric( $_GET['location_id'] ) ) : 

    require_once( TEMPLATE_PATH . 'locations/locations-single.php');

else :

    require_once( TEMPLATE_PATH . 'locations/locations-all.php');

endif; ?>

<?php require_once( TEMPLATE_PATH . 'footer.php'); ?>