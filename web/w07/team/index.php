<?php

require_once('includes/bootstrap.php');

$username = false;
if (isLoggedIn()) {
    $user = getUser();
    $username = $user['email'];
}

require_once('templates/header.php');

?>

<h1>Hi<?php echo ($username) ? ' ' . $username : null; ?>, welcome.</h1>
<?php if(!$username) { ?>
    <a href="<?php echo path('login.php'); ?>">Login</a> |
    <a href="<?php echo path('register.php'); ?>">Register</a>
<?php } ?>

<?php require_once('templates/footer.php'); ?>