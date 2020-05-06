<?php

$name  = ( isset( $_POST['name'] ) )  ? filter_var( $_POST['name'], FILTER_SANITIZE_STRING ) : '';
$email = ( isset( $_POST['email'] ) ) ? filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL ) : '';
$major = ( isset( $_POST['major'] ) && in_array( $_POST['major'], array_keys( $majors ) ) ) ? $majors[$_POST['major']] : '';
$comments = ( isset( $_POST['comments'] ) ) ? filter_var( $_POST['comments'], FILTER_SANITIZE_STRING ) : '';

if ( isset( $_POST['continents'] ) && count( $_POST['continents'] ) > 0 ) {
    $matches = array_intersect_key( $continents, array_flip( $_POST['continents'] ) );
    $continents = implode( ', ', $matches);
} else {
    $continents = '';
}

?>

<h4 class="display-4">Results</h4>
<hr>
<dl class="row">
    <dt class="col-sm-3"><label>Name</label></dt>
    <dd class="col-sm-9"><?php echo $name; ?></dd>

    <dt class="col-sm-3"><label>Email</label></dt>
    <dd class="col-sm-9"><?php echo $email; ?></dd>

    <dt class="col-sm-3"><label>Major</label></dt>
    <dd class="col-sm-9"><?php echo $major; ?></dd>

    <dt class="col-sm-3"><label>Continents Visited</label></dt>
    <dd class="col-sm-9"><?php echo $continents; ?></dd>

    <dt class="col-sm-3"><label>Comments</label></dt>
    <dd class="col-sm-9"><?php echo $comments; ?></dd>
</dl>