<form class="form-register needs-validation" method="POST" novalidate>

    <div class="text-center">
        <a href="<?php echo url('index.php'); ?>">
            <img class="mb-4" src="<?php echo ASSET_URL . 'images/qm-logo.png'; ?>" alt="QueueMe" width="110" height="110">
        </a>
        <h3 class="h3 mb-5 font-weight-normal">Business Registration</h3>
    </div>

    <?php Message::display(); ?>

    <h4 class="mb-3">Account Details</h4>
    <div class="row">

        <div class="col-md-6 mb-3">
            <label for="firstName">First Name</label>
            <input name="first_name" type="text" class="form-control" id="firstName" placeholder="" value="<?php echo (isset($_POST['first_name'])) ? $_POST['first_name'] : ''; ?>" required>
            <div class="invalid-feedback">
                Valid first name is required.
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="lastName">Last Name</label>
            <input name="last_name" type="text" class="form-control" id="lastName" placeholder="" value="<?php echo (isset($_POST['last_name'])) ? $_POST['last_name'] : ''; ?>" required>
            <div class="invalid-feedback">
                Valid last name is required.
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="email">Email</label>
        <input name="email" type="email" class="form-control" id="email" placeholder="you@example.com" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter a valid email address.
        </div>
    </div>

    <div class="mb-3">
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter a valid password.
        </div>
    </div>
    <div class="mb-3">
        <label for="confirm_password">Confirm Password</label>
        <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" value="<?php echo (isset($_POST['confirm_password'])) ? $_POST['confirm_password'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter a valid password.
        </div>
    </div>

    <h4 class="mb-3 mt-5">Location Address</h4>

    <div class="mb-3">
        <label for="name">Business Name</label>
        <input name="name" type="text" class="form-control" id="name" placeholder="Your Business Name" value="<?php echo (isset($_POST['name'])) ? $_POST['name'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter a valid email address.
        </div>
    </div>

    <div class="mb-3">
        <label for="phone">Business Phone</label>
        <input name="phone" type="text" maxlength="10" class="form-control" id="phone" placeholder="Phone Number" value="<?php echo (isset($_POST['phone'])) ? $_POST['phone'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter a valid phone number.
        </div>
    </div>

    <div class="mb-3">
        <label for="address">Address</label>
        <input name="address" type="text" class="form-control" id="address" placeholder="1234 Main St" value="<?php echo (isset($_POST['address'])) ? $_POST['address'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter your location's address.
        </div>
    </div>

    <div class="mb-3">
        <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
        <input name="address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite" value="<?php echo (isset($_POST['address2'])) ? $_POST['address2'] : ''; ?>">
    </div>

    <div class="mb-3">
        <label for="city">City</label>
        <input name="city" type="text" class="form-control" id="city" placeholder="Testville" value="<?php echo (isset($_POST['city'])) ? $_POST['city'] : ''; ?>" required>
        <div class="invalid-feedback">
            Please enter your city.
        </div>
    </div>

    <div class="row">

        <div class="col-md-9 mb-3">
            <label for="state">State</label>
            <select name="state" class="custom-select d-block w-100" id="state" required>
                <option value="">Choose...</option>
                <option value="AZ" <?php echo (isset($_POST['state']) && $_POST['state'] === 'AZ') ? 'selected' : ''; ?>>Arizona</option>
            </select>
            <div class="invalid-feedback">
                Please provide a valid state.
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label for="zip">Zip</label>
            <input name="zip" type="text" class="form-control" id="zip" placeholder="" required value="<?php echo (isset($_POST['zip'])) ? $_POST['zip'] : ''; ?>">
            <div class="invalid-feedback">
                Zip code required.
            </div>
        </div>
    </div>
    <input type="hidden" name="form_handler" value="register" />
    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
    <p class="mt-3">Already registered? <a href="<?php echo url('login.php'); ?>">Login</a>
        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y'); ?> QueueMe</p>
</form>

<script type="text/javascript">
    // Yanked from Bootstrap examples
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>