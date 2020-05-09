<?php

require_once('includes/bootstrap.php');

$cart  = Cart::getInstance();
$items = $cart->get_items_in_cart();


$session  = Session::getInstance();
$checkout = $session->get('checkout');

if ( !count($items) > 0 ) {
    redirect('cart.php');
}

?>

<?php require_once('templates/header.php'); ?>


<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Checkout</h1>
</div>
<hr class="mb-4">

<div class="container">

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your Cart</span>
                <span class="badge badge-secondary badge-pill"><?php echo $cart->get_cart_item_count(); ?></span>
            </h4>
            <ul class="list-group mb-3">

                <?php

                /**
                 * @var $item['product'] Product
                 */
                foreach ($items as $item) : ?>

                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0"><?php echo $item['product']->name; ?></h6>
                            <small class="text-muted"><?php echo $item['product']->description; ?></small>
                        </div>
                        <span class="text-muted"><?php echo $item['quantity']; ?> x <?php echo $item['product']->get_price( true ); ?></span>
                    </li>

                <?php endforeach; ?>

                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (USD)</span>
                    <strong><?php echo $cart->get_cart_total( true ); ?></strong>
                </li>
            </ul>
            <a class="btn btn-secondary btn-lg btn-block" href="<?php echo path('cart.php'); ?>">Edit Cart</a>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Shipping Address</h4>
            <form class="needs-validation" novalidate method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">First Name</label>
                        <input name="first_name" type="text" class="form-control" id="firstName" placeholder="" value="<?php echo (isset($checkout['first_name'])) ? $checkout['first_name'] : ''; ?>" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Last Name</label>
                        <input name="last_name" type="text" class="form-control" id="lastName" placeholder="" value="<?php echo (isset($checkout['last_name'])) ? $checkout['last_name'] : ''; ?>" required>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="you@example.com" value="<?php echo (isset($checkout['email'])) ? $checkout['email'] : ''; ?>" required>
                    <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address">Address</label>
                    <input name="address" type="text" class="form-control" id="address" placeholder="1234 Main St" value="<?php echo (isset($checkout['address'])) ? $checkout['address'] : ''; ?>" required>
                    <div class="invalid-feedback">
                        Please enter your shipping address.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                    <input name="address2" type="text" class="form-control" id="address2" placeholder="Apartment or suite" value="<?php echo (isset($checkout['address2'])) ? $checkout['address2'] : ''; ?>">
                </div>

                <div class="mb-3">
                    <label for="city">City</label>
                    <input name="city" type="text" class="form-control" id="city" placeholder="Testville" value="<?php echo (isset($checkout['city'])) ? $checkout['city'] : ''; ?>" required>
                    <div class="invalid-feedback">
                        Please enter your city.
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="country">Country</label>
                        <select name="country" class="custom-select d-block w-100" id="country" required>
                            <option value="">Choose...</option>
                            <option value="US" <?php echo (isset($checkout['country']) && $checkout['country'] === 'US') ? 'selected' : ''; ?>>United States</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid country.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="state">State</label>
                        <select name="state" class="custom-select d-block w-100" id="state" required>
                            <option value="">Choose...</option>
                            <option value="AZ" <?php echo (isset($checkout['state']) && $checkout['state'] === 'AZ') ? 'selected' : ''; ?>>Arizona</option>
                        </select>
                        <div class="invalid-feedback">
                            Please provide a valid state.
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="zip">Zip</label>
                        <input name="zip" type="text" class="form-control" id="zip" placeholder="" required value="<?php echo (isset($checkout['zip'])) ? $checkout['zip'] : ''; ?>">
                        <div class="invalid-feedback">
                            Zip code required.
                        </div>
                    </div>
                </div>
                <input type="hidden" name="action" value="set_checkout_details"/>
                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Complete Order</button>
            </form>
        </div>
    </div>

</div>

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

<?php require_once('templates/footer.php'); ?>

