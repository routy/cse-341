<?php

$session  = Session::getInstance();
$messages = $session->get('messages');

if ( $messages && count( $messages ) > 0 ) :
    foreach( $messages as $message ) : ?>
        <div class="alert alert-<?php echo $message['type']; ?>" role="alert">
            <?php echo $message['message']; ?>
        </div>
    <?php
    endforeach;
    $session->remove('messages');
endif;