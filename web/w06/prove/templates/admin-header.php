<?php

if (!isLoggedIn()) {
    redirect('login.php');
}

$session  = Session::getInstance();
$messages = $session->get('messages');

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="css/admin-styles.css" />

    <title>QueueMe - Admin</title>

</head>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" style="font-size: 20px;" href="<?php echo path('admin.php'); ?>">
            <img src="images/qm-logo-sm.png" alt="QueueMe" height="35" class="mr-3" />QueueMe
        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?php echo path('login.php?logout'); ?>">Logout</a>
            </li>
        </ul>
    </nav>

    <?php require_once('admin-sidebar.php'); ?>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

        <div class="container">
            <?php

            if ($messages && count($messages) > 0) :

                foreach ($messages as $message) : ?>

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