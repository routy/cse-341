<?php

require_once( dirname(__FILE__) . '/includes/bootstrap.php');

if (isset($_REQUEST['logout'])) {
    User::logout();
}

if (isset($_POST['email']) && !empty($_POST['email']) 
    && isset($_POST['password']) && !empty($_POST['password'])) {

    $email    = filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL );
    $password = $_POST['password'];

    $result = User::login($email, $password);

    if ( $result === true ) {
        redirect('admin.php');
    } else {
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
    <link rel="stylesheet" href="<?php echo ASSET_URL . 'css/login-styles.css'; ?>" />

    <title>QueueMe - Login</title>

</head>

<body class="text-center">

    <?php include( TEMPLATE_PATH . 'forms/form-login.php'); ?> 

</body>

</html>