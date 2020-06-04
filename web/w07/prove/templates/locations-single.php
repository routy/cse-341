<?php

$location_id = (int) $_GET['location_id'];

try {
    $location = new Location( $location_id );
} catch ( Exception $e) {

    $messages = $session->get('messages');
    $messages[] = [
        'message' => 'The location you requested was not found.',
        'type' => 'error'
    ];
    $session->store('messages', $messages);

    redirect('index.php');
}

$user     = getUser();
$isAdmin  = false;
$position = false;

if ( $user ) {
  $isAdmin  = $location->isUserLocationAdmin($user['id']);
}

$token = $session->get( $location->id . '_token');

if ( $token ) {
  $position = $location->getCurrentQueuePositionByToken($token); 
}

?>

<main role="main">

  <div class="jumbotron">
    <div class="container">
      <h1 class="display-3">Welcome to <?php echo $location->name; ?></h1>
      <p>We're all about making life easier for our customers. In an effort to do just that, we've created a digital line for you to wait in. We don't want you out in the summer heat! When your number comes up, simply enter the store and we're ready to service your needs.</p>
      
      <?php if ($isAdmin) : ?>
        <p>
        <a href="<?php echo path('?location_id=' . $location->id . '&action=next_in_queue'); ?>" class="btn btn-lg btn-primary">
            Serve Next In Queue &raquo;
        </a>
      </p>
      <?php elseif ( !$position ) : ?>
      <p>
        <a href="<?php echo path('?location_id=' . $location->id . '&action=add_item_to_queue'); ?>" class="btn btn-lg btn-primary">
            Enter Queue &raquo;
        </a>
      </p>
      <?php endif; ?>

    </div>
  </div>

  <div class="container">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-4">
        <h3>Address</h3>
        <?php echo $location->getFormattedAddress(); ?>
      </div>
      <div class="col-md-4">
<<<<<<< HEAD
        <h2>Currently Waiting</h2>
=======
        <h3>Total Waiting</h3>
>>>>>>> 52a45365ba82e1e71e5b4b0852f18722c8ebb573
        <p><?php echo $location->getQueueItemCountByStatus(); ?></p>
      </div>
      <div class="col-md-4">
        <h3>Est. Wait Time</h3>
        <p><?php 

        $waitTime = $location->getEstimatedWaitTime( 'minutes', ($token) ? $token : null );

        echo $waitTime . ' minutes'; 
        
        ?></p>
        <?php if ( $position ) : ?>
          <h3>Your Position</h3>
          <p><?php echo $position; ?></p>
        <?php endif; ?>
      </div>
    </div>

    <hr>

  </div> <!-- /container -->

</main>