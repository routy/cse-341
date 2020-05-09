<?php

require_once('includes/bootstrap.php');

$cart  = Cart::getInstance();
$items = $cart->get_items();

?>

<?php require_once('templates/header.php'); ?>


<div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Electronics</h1>
    <p class="lead">We have all kinds of amazing electronics, check them out!</p>
</div>

<div class="container">
    <div class="card-deck mb-3 text-center">

        <?php foreach ($items as $item) : ?>
            <form action="<?php echo path('cart.php'); ?>">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal"><?php echo $item->name; ?></h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">
                            $<?php echo number_format($item->price, 2); ?></small></h1>
                        <div>
                            <?php echo $item->description; ?>
                        </div>

                        <div class="input-group mb-2 mt-4">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Quantity</div>
                            </div>
                            <input name="quantity" type="text" class="form-control"
                                   id="inlineFormInputGroup"
                                   placeholder="Quantity" value="1">
                        </div>

                        <button type="submit"
                                class="btn btn-lg btn-block btn-outline-primary">
                            Add to Cart
                        </button>

                        <input type="hidden" name="item_id" value="<?php echo $item->id; ?>"/>
                        <input type="hidden" name="action" value="add_to_cart"/>

                    </div>
                </div>
            </form>

        <?php endforeach; ?>

    </div>

</div>

<?php require_once('templates/footer.php'); ?>

