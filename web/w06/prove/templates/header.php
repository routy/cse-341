<?php

$session  = Session::getInstance();
$messages = $session->get('messages');

$user = getUser();

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="css/styles.css"/>

    <title>QueueMe - Locations</title>

</head>

<body class="bg-light">

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">
        <a href="<?php echo path('index.php'); ?>" title="QueueMe">
            <img src="images/qm-logo.png" alt="QueueMe" height="35" class="mr-3" /> QueueMe
        </a>
    </h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <?php if ( !$user ) : ?>
            <a class="p-2 text-dark" href="<?php echo path('login.php'); ?>">Business Login</a>
        <?php else : ?>
            <a class="p-2 text-dark" href="<?php echo path('admin.php'); ?>">Dashboard</a>
            <a class="p-2 text-dark" href="<?php echo path('login.php?logout'); ?>">Logout</a>
        <?php endif; ?>    
    </nav>
</div>

<main role="main">

    <div class="container">
        <?php

        if ( $messages && count( $messages ) > 0 ) :

            foreach( $messages as $message ) : ?>

                <div class="alert alert-<?php echo $message['type']; ?>" role="alert">
                    <?php echo $message['message']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <?php

            endforeach;

            $session->remove('messages');

        endif;

        ?>
    </div>
