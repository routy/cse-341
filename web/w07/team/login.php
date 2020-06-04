<?php

require_once('includes/bootstrap.php');

$session  = Session::getInstance();
$messages = $session->get('messages');

if (isset($_GET['logout'])) {
    $session->remove('userId');
}

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
        redirect('index.php');
    } else {
        $messages[] = [
            'message' => 'Your email or password are incorrect. Please try again.',
            'type' => 'error'
        ];
        $session->store('messages', $messages);
        redirect('login.php');
    }
}

require_once('templates/header.php');

?>

<h1>Hi, login.</h1>
<a href="<?php echo path('login.php'); ?>">Login</a> |
<a href="<?php echo path('register.php'); ?>">Register</a>

<?php if ( $messages ) : ?>
    <ul>
        <?php foreach( $messages as $message ) : ?>
            <li><?php echo $message['message']; ?></li>
        <?php endforeach; ?>
    </ul>
    <?php $session->remove('messages'); ?>
<?php endif; ?>

<form method="POST">
   <label for="email">Email or User Name: </label><br>
   <input type="email" name="email" id="email" required><br>
   
   <label for="password">Password: </label><br>
   <input type="password" name="password" id="password" required><br>

   <button type="sumbmit">Login</button>
</form>


<?php require_once('templates/footer.php'); ?>