<?php

$majors = [
  'CE' => 'Computer Engineering',
  'CIT' => 'Computer Information Technology',
  'CS' => 'Computer Science',
  'WDD' => 'Web Design & Development'
];

$continents = [
  'AR' => 'Africa',
  'AN' => 'Antarctica',
  'AU' => 'Australia',
  'AA' => 'Asia',
  'EU' => 'Europe',
  'NA' => 'North America',
  'SA' => 'South America'
];

$display = 'form';

/**
 * Validate and clean the submitted data for display
 */
if ( isset( $_POST['name'] ) && !empty( $_POST['name'] ) ) {
  $display = 'results';
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

  <link rel="stylesheet" href="css/styles.css" />

  <title>Week 03 - Team Activity</title>

</head>

<body class="bg-light">
  <main role="main">
    <div class="container py-5">
      <div class="row">
        <div class="col-12">

        <?php 
        
        if ( $display === 'form' ) :

          include_once( 'templates/form.php');

        elseif ( $display === 'results' ) :

          include_once( 'templates/results.php');

        endif;

        ?>

        </div>
      </div>
    </div>
    </div>
  </main>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="js/script.js" type="text/javascript"></script>

</body>

</html>