<?php

require_once('includes/bootstrap.php');

$cart  = Cart::getInstance();
$items = $cart->get_items_in_cart();

?>

<?php require_once('templates/header.php'); ?>


<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Your Cart</h1>
</div>
<hr class="mb-4">
<div class="container">

    <div class="row">
        <div class="col-md-12">

            <?php if ( count($items) > 0 ) : ?>

            <form method="POST">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col" style="text-align: center;">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                        /**
                         * @var $item ['product'] Product
                         */
                        foreach ($items as $item_id => $item) : ?>

                            <tr>
                                <td scope="row"><?php echo $item['product']->name; ?></td>
                                <td style="text-align: center;">
                                    <input type="number" name="items[<?php echo $item_id; ?>][quantity]"
                                           value="<?php echo $item['quantity']; ?>" placeholder="0"
                                           style="text-align: center;"/>
                                </td>
                                <td><?php echo $item['product']->get_price(true); ?></td>
                                <td><?php echo $cart->get_cart_item_total($item_id, true); ?></td>
                            </tr>

                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align:right;font-weight:bold;">Cart Total</td>
                            <td style="font-weight:bold;"><?php echo $cart->get_cart_total(true); ?></td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="action" value="update_cart"/>

                <a class="btn btn-primary btn-lg float-right" href="<?php echo path('checkout.php'); ?>">Continue to
                    Checkout</a>
                <button class="btn btn-secondary btn-lg float-right mr-2" type="submit">Update Cart</button>
                <a class="btn btn-secondary btn-lg float-right mr-2" href="<?php echo path('index.php'); ?>">Continue Shopping</a>
                <a class="btn btn-danger btn-lg float-left" href="<?php echo path('cart.php'); ?>?action=empty_cart">Empty Cart</a>
            </form>

            <?php else : ?>

                <p style="text-align:center;">Your cart is empty, you better do some <a href="<?php echo path('index.php'); ?>">shopping</a>!</p>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php require_once('templates/footer.php'); ?>

