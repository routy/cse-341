<?php

require_once('includes/bootstrap.php');

$session  = Session::getInstance();
$messages = $session->get('messages');

if ( isset($_POST['form']) && $_POST['form'] === 'register' ) {

    $register = [];

    $required = [
        'email', 'password', 'confirm_password', 'first_name', 'last_name', 'name', 'address', 'city', 'state', 'zip', 'phone'
    ];
    $errors = false;

    foreach( $_POST as $field => $value ) {
        if ( in_array( $field, $required ) ) {
            if ( isset( $_POST[$field] ) && !empty( $_POST[$field] ) ) {
                if ($field === 'phone') {
                    $register[$field] = preg_replace('/[^0-9]/s', '', $_POST[$field]);
                } else {
                    $register[$field] = filter_var( $_POST[$field],FILTER_SANITIZE_STRING );
                }
            } else {
                $errors = true;
                $messages[] = [
                    'message' => ucwords(str_replace('_', ' ', $field)) . ' is a required field.',
                    'type' => 'error'
                ];
                $session->store('messages', $messages);
            }
        } else {
            $field_value = filter_var( $_POST[$field],FILTER_SANITIZE_STRING );
            $register[$field] = (!empty($field_value)) ? $field_value : null;
        }

        if ($field === 'password' && $_POST[$field] !== $_POST['confirm_password'] ) {
            $errors = true;
                $messages[] = [
                    'message' => 'Your passwords do not match.',
                    'type' => 'error'
                ];
                $session->store('messages', $messages);
        }

        if ($field === 'phone' && strlen($register[$field]) !== 10 ) {
            $errors = true;
                $messages[] = [
                    'message' => 'Please enter a valid phone number. Digits only, 10 numbers in length.',
                    'type' => 'error'
                ];
                $session->store('messages', $messages);
        }
    }

    if (!$errors) {

        $db = Database::getInstance()->connection();

        try {

            $db->beginTransaction();

            $query     = "SELECT * FROM users WHERE email = ?";
            $statement = $db->prepare($query);
            $result    = $statement->execute([$register['email']]);
            $user      = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $messages[] = [
                    'message' => 'Your email address is already registered with our service. Please login.',
                    'type' => 'error'
                ];
                $session->store('messages', $messages);
                redirect('login.php');
            }

            $query = "INSERT INTO users (first_name, last_name, email, password) 
                    VALUES (?, ?, ?, ?)";
            $params = [
                $register['first_name'],
                $register['last_name'],
                $register['email'],
                password_hash($register['password'], PASSWORD_BCRYPT)
            ];
        
            $statement = $db->prepare($query);
            $result    = $statement->execute($params);
            $userId    = $db->lastInsertId();

            $query = "INSERT INTO locations (name, address1, address2, city, state, zip, phone, status_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $register['name'],
                $register['address'],
                $register['address2'],
                $register['city'],
                $register['state'],
                $register['zip'],
                $register['phone'],
                Location::STATUS_ACTIVE
            ];

            $statement  = $db->prepare($query);
            $result     = $statement->execute($params);
            $locationId = $db->lastInsertId();

            $query = "INSERT INTO location_user (location_id, user_id) VALUES (?, ?)";
            $params = [$locationId, $userId];

            $statement  = $db->prepare($query);
            $result     = $statement->execute($params);

            $db->commit();

            $messages[] = [
                'message' => 'Your account has been created successfully.',
                'type' => 'success'
            ];
            $session->store('messages', $messages);

            redirect('login.php');

        } catch(Exception $e) {

            $db->rollback();

            $messages[] = [
                'message' => 'An error occurred while processing your request. Please try again later.',
                'type' => 'error'
            ];
            $session->store('messages', $messages);

        }
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
    <link rel="stylesheet" href="css/register-styles.css" />

    <title>QueueMe - Register</title>

</head>

<body>

    <form class="form-register needs-validation" method="POST" novalidate>

        <div class="text-center">
            <a href="<?php echo path('index.php'); ?>">
                <img class="mb-4" src="images/qm-logo.png" alt="QueueMe" width="110" height="110">
            </a>
            <h3 class="h3 mb-5 font-weight-normal">Business Registration</h3>
        </div>

        <?php

        if ($messages && count($messages) > 0) :

            foreach ($messages as $message) : ?>

                <div class="alert alert-<?php echo $message['type']; ?>" role="alert">
                    <?php echo $message['message']; ?>
                </div>

        <?php

            endforeach;

            $session->remove('messages');

        endif;

        ?>
        <h4 class="mb-3">Account Details</h4>
        <div class="row">
            
            <div class="col-md-6 mb-3">
                <label for="firstName">First Name</label>
                <input name="first_name" type="text" class="form-control" id="firstName" placeholder="" value="<?php echo (isset($register['first_name'])) ? $register['first_name'] : ''; ?>" required>
                <div class="invalid-feedback">
                    Valid first name is required.
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="lastName">Last Name</label>
                <input name="last_name" type="text" class="form-control" id="lastName" placeholder="" value="<?php echo (isset($register['last_name'])) ? $register['last_name'] : ''; ?>" required>
                <div class="invalid-feedback">
                    Valid last name is required.
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" id="email" placeholder="you@example.com" value="<?php echo (isset($register['email'])) ? $register['email'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter a valid email address.
            </div>
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input name="password" type="password" class="form-control" id="password" placeholder="Password" value="<?php echo (isset($register['password'])) ? $register['password'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter a valid password.
            </div>
        </div>
        <div class="mb-3">
            <label for="confirm_password">Confirm Password</label>
            <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" value="<?php echo (isset($register['confirm_password'])) ? $register['confirm_password'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter a valid password.
            </div>
        </div>

        <h4 class="mb-3 mt-5">Location Address</h4>

        <div class="mb-3">
            <label for="name">Business Name</label>
            <input name="name" type="text" class="form-control" id="name" placeholder="Your Business Name" value="<?php echo (isset($register['name'])) ? $register['name'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter a valid email address.
            </div>
        </div>

        <div class="mb-3">
            <label for="phone">Business Phone</label>
            <input name="phone" type="text" maxlength="10" class="form-control" id="phone" placeholder="Phone Number" value="<?php echo (isset($register['phone'])) ? $register['phone'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter a valid phone number.
            </div>
        </div>

        <div class="mb-3">
            <label for="address">Address</label>
            <input name="address" type="text" class="form-control" id="address" placeholder="1234 Main St" value="<?php echo (isset($register['address'])) ? $register['address'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter your location's address.
            </div>
        </div>

        <div class="mb-3">
            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
            <input name="address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite" value="<?php echo (isset($register['address2'])) ? $register['address2'] : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="city">City</label>
            <input name="city" type="text" class="form-control" id="city" placeholder="Testville" value="<?php echo (isset($register['city'])) ? $register['city'] : ''; ?>" required>
            <div class="invalid-feedback">
                Please enter your city.
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-9 mb-3">
                <label for="state">State</label>
                <select name="state" class="custom-select d-block w-100" id="state" required>
                    <option value="">Choose...</option>
                    <option value="AZ" <?php echo (isset($register['state']) && $register['state'] === 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                </select>
                <div class="invalid-feedback">
                    Please provide a valid state.
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="zip">Zip</label>
                <input name="zip" type="text" class="form-control" id="zip" placeholder="" required value="<?php echo (isset($register['zip'])) ? $register['zip'] : ''; ?>">
                <div class="invalid-feedback">
                    Zip code required.
                </div>
            </div>
        </div>
        <input type="hidden" name="form" value="register" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
        <p class="mt-3">Already registered? <a href="<?php echo path('login.php'); ?>">Login</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2020 QueueMe</p>
    </form>

    <script>
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
</body>
</html>