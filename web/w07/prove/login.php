<?php

require_once('includes/bootstrap.php');

$session  = Session::getInstance();
$messages = $session->get('messages');

if (isset($_REQUEST['logout'])) {
    $session->remove('userId');
}
// routy@byui.edu
// thisisanamazingpassword
// $2y$10$f6ZX5fMK/JqyMDuCZYvwDOjHgcRLX2DutoJpvCSgpbmpR8wEsCPtO

if (isset($_POST['email']) && !empty($_POST['email']) 
    && isset($_POST['password']) && !empty($_POST['password'])) {

    $email    = filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL );
    $password = $_POST['password'];

    $query  = "SELECT * FROM users WHERE email = ?";
    $params = [$email];

    $db = Database::getInstance()->connection();
    $statement = $db->prepare($query);
    $result    = $statement->execute($params);
    $user      = $statement->fetch(PDO::FETCH_ASSOC);

    if ( $user && password_verify( $_POST['password'], $user['password'] ) ) {
        $session->store('userId', $user['id']);
        redirect('admin.php');
    } else {
        $messages[] = [
            'message' => 'Your email or password are incorrect. Please try again.',
            'type' => 'error'
        ];
        $session->store('messages', $messages);
        redirect('login.php');
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login-styles.css" />

    <title>QueueMe - Login</title>

</head>

<body class="text-center">

    <form class="form-signin" method="POST">
        <a href="<?php echo path('index.php'); ?>">
            <img class="mb-4" src="images/qm-logo.png" alt="QueueMe" width="110" height="110">
        </a>
        <h3 class="h3 mb-3 font-weight-normal">Business Login</h3>

        <?php

        if ( $messages && count( $messages ) > 0 ) :

            foreach( $messages as $message ) : ?>

                <div class="alert alert-<?php echo $message['type']; ?>" role="alert">
                    <?php echo $message['message']; ?>
                </div>

            <?php

            endforeach;

            $session->remove('messages');

        endif;

        ?>

        <label for="inputEmail" class="sr-only">Email address</label>
        <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <p class="mt-3">Not registered? <a href="<?php echo path('register.php'); ?>">Register</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2020 QueueMe</p>
    </form>
</body>

</html>