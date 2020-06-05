<form class="form-signin" method="POST">
    <a href="<?php echo url('index.php'); ?>">
        <img class="mb-4" src="<?php echo ASSET_URL . 'images/qm-logo.png'; ?>" alt="QueueMe" width="110" height="110">
    </a>
    <h3 class="h3 mb-3 font-weight-normal">Business Login</h3>

    <?php Message::display(); ?>

    <label for="inputEmail" class="sr-only">Email address</label>
    <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email Address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    <input type="hidden" name="form_handler" value="login" />
    <p class="mt-3">Not registered? <a href="<?php echo url('register.php'); ?>">Register</a>
    <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y'); ?>QueueMe</p>
</form>