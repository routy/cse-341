<?php

require_once('includes/bootstrap.php');

$cart  = Cart::getInstance();
$items = $cart->get_items_in_cart();

$session  = Session::getInstance();
$checkout = $session->get('checkout');

if ( !count($items) > 0 || !$checkout ) {
    redirect('cart.php');
}

?>

<?php require_once('templates/header.php'); ?>


<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Thank You for your Order!</h1>
</div>
<hr class="mb-4">
<div class="container">

    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Your Order</span>
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
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Order Information</h4>

            <p>We'll be sending out your order to:</p> <br>
            <?php echo $checkout['first_name']; ?>
            <?php echo $checkout['last_name']; ?><br>
            <?php echo $checkout['address']; ?><br>
            <?php echo (!empty($checkout['address2'])) ? $checkout['address2'] . '<br>' : ''; ?>
            <?php echo $checkout['city'] . ', ' . $checkout['state'] . ' ' . $checkout['zip']  ?><br>
            <?php echo $checkout['country']; ?><br><br>
            <label class="mr-1">Email Address:</label><?php echo $checkout['email']; ?>
            <hr class="mb-4">
            <a class="btn btn-primary btn-lg float-right" href="<?php echo path('index.php'); ?>">Go Shopping!</a>

        </div>
    </div>

</div>

<?php require_once('templates/footer.php'); ?>

<?php

// This order is done, let's empty that cart!
$cart->empty_cart();

?>