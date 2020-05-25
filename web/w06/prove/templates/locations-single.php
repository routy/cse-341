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

?>

<main role="main">

  <div class="jumbotron">
    <div class="container">
      <h1 class="display-3">Welcome to <?php echo $location->name; ?></h1>
      <p>We're all about making life easier for our customers. In an effort to do just that, we've created a digital line for you to wait in. We don't want you out in the summer heat! When your number comes up, simply enter the store and we're ready to service your needs.</p>
      <p>
        <a href="<?php echo path('?location_id=' . $location->id . '&action=add_user_to_queue'); ?>" class="btn btn-lg btn-primary">
            Enter Queue &raquo;
        </a>
      </p>
    </div>
  </div>

  <div class="container">
    <!-- Example row of columns -->
    <div class="row">
      <div class="col-md-4">
        <h2>Address</h2>
        <p><?php echo $location->address1; ?></p>
        <p><?php echo $location->address2; ?></p>
        <p><?php echo $location->city . ', ' . $location->state . ' ' . $location->zip; ?></p>
      </div>
      <div class="col-md-4">
        <h2>Currently Waiting</h2>
        <p><?php echo $location->getCurrentQueuePosition(); ?></p>
      </div>
      <div class="col-md-4">
        <h2>Est. Wait Time</h2>
        <p><?php 

        $waitTime = $location->getEstimatedWaitTime();

        echo (($waitTime > 0) ? $location->getEstimatedWaitTime() / 60 : '0') . ' minutes'; 
        
        ?></p>
      </div>
    </div>

    <hr>

  </div> <!-- /container -->

</main>