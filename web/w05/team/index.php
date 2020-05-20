<?php

require_once('includes/bootstrap.php');

$db = Database::getInstance()->connection();

$query = 'SELECT * FROM Scriptures WHERE 1';

if (isset($_GET['book']) && !empty($_GET['book'])) {
    $query  .= ' AND `book` LIKE ?';
    $params[] = filter_var( $_GET['book'], FILTER_SANITIZE_STRING);
}
if (isset($_GET['chapter']) && !empty($_GET['chapter'])) {
    $query  .= ' AND `chapter` LIKE ?';
    $params[] = filter_var( $_GET['chapter'], FILTER_SANITIZE_STRING);
}
if (isset($_GET['verse']) && !empty($_GET['verse'])) {
    $query  .= ' AND `verse` LIKE ?';
    $params[] = filter_var( $_GET['verse'], FILTER_SANITIZE_STRING);
}
if (isset($_GET['content']) && !empty($_GET['content'])) {
    $query  .= ' AND `content` LIKE ?';
    $params[] = filter_var( $_GET['content'], FILTER_SANITIZE_STRING);
}

$statement = $db->prepare( $query );
$statement->execute( $params );
$results = $statement->fetchAll(PDO::FETCH_ASSOC);

/*$results = [
    [
        'id' => 1,
        'book' => 'Mosiah',
        'chapter' => 1,
        'verse' => 2,
        'content' => 'Content here'
    ],
    [
        'id' => 2,
        'book' => 'D&C',
        'chapter' => 3,
        'verse' => 17,
        'content' => 'Content here'
    ]
];*/

// print_r($results);

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

    <title>Week 05 - Team Activity</title>

    </head>

    <form>
        <p>
            <label for="inputBook">Book</label>
            <input id="inputBook" type="text" name="book" value="<?php echo ( (isset($_GET['book'])) ? $_GET['book'] : ''); ?>" placeholder="Mosiah" />
        </p>
        <p>
            <label for="inputChapter">Chapter</label>
            <input id="inputChapter" type="text" name="chapter" value="<?php echo ( (isset($_GET['chapter'])) ? $_GET['chapter'] : ''); ?>" placeholder="1" />
        </p>
        <p>
            <label for="inputVerse">Verse</label>
            <input id="inputVerse" type="text" name="verse" value="<?php echo ( (isset($_GET['verse'])) ? $_GET['verse'] : ''); ?>" placeholder="1" />
        </p>
        <p>
            <label for="inputContent">Content</label>
            <input id="inputContent" type="text" name="content" value="<?php echo ( (isset($_GET['content'])) ? $_GET['content'] : ''); ?>" placeholder="Scripture verse text..." />
        </p>
        <button type="submit">Submit</button>
    </form>

    <ul>
        <?php foreach($results as $result) : ?>
        <li>
            <?php echo '<strong>' . $result['book'] . ' ' . $result['chapter'] . ':' . $result['verse'] . '</strong> - "' . $result['content'] . '"'; ?>
        </li>
        <?php endforeach; ?>
    </ul>

</body>
</html>