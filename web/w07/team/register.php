<?php

require_once('includes/bootstrap.php');

$session  = Session::getInstance();
$messages = $session->get('messages');

require_once('templates/header.php');

if (isset($_POST['username']) && !empty($_POST['username']) 
    && isset($_POST['name']) && !empty($_POST['name'])
    && isset($_POST['password']) && !empty($_POST['password'])
    && isset($_POST['password2']) && !empty($_POST['password2'])) {

    $name      = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $password1 = $_POST['password'];
    $password2 = $_POST['password2'];
    $username  = filter_var( $_POST['username'], FILTER_VALIDATE_EMAIL );

    if ($password1 === $password2 && preg_match('/\d/', $password1) === 1 && strlen($password1) > 7) {

        $password1 = password_hash($password1, PASSWORD_BCRYPT);

    } else {

        $messages[] = [
            'message' => 'Your password must be at least 8 characters in length and include a number, and your passwords must match.',
            'type' => 'error'
        ];
        $session->store('messages', $messages);

        redirect('register.php');
    }

    try {

        $db = Database::getInstance()->connection();
        $query = "INSERT INTO users (email, name, password) VALUES(?, ?, ?)";
        $statement = $db->prepare($query);
        $result = $statement->execute([$username, $name, $password1]);

        if ($result) {            
            $messages[] = [
                'message' => 'You are registered! Now, go forth and login.',
                'type' => 'success'
            ];
            $session->store('messages', $messages);
            redirect('login.php');
        }

    } catch(Exception $e) {

        $messages[] = [
            'message' => 'An error occurred with your registration.',
            'type' => 'error'
        ];
        $session->store('messages', $messages);

        redirect('register.php');

    }

} else if (isset($_POST['username'])) {

    $messages[] = [
        'message' => 'An error occurred with your registration.',
        'type' => 'error'
    ];
    $session->store('messages', $messages);

}

?>

<h1>Hi, register.</h1>
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
    <label for="name">Name:</label><br>
    <input type="text" name="name" id="name" required><br>

    <label for="username">Username:</label><br>
    <input type="email" name="username" id="username" required><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}" required>

    <label for="password2">Confirm Password:</label><br>
    <input type="password" name="password2" id="password2" pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}" required>

    <button type="submit">Sign Up</button>
</form>

<?php require_once('templates/footer.php'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script type="text/javascript">

   $("#password2").blur(function(){
        if ( $(this).val() !== $('#password').val() ) {
            $(this).val(null);
            $(this).css('backgroundColor', 'red');
        } else {
            $(this).css('backgroundColor', 'green');
        }
   });

  </script>