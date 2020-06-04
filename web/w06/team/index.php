<?php

require_once('includes/bootstrap.php');

$db = Database::getInstance()->connection();

if ( isset($_POST['action'] ) && $_POST['action'] === 'add_scripture' ) {

    $db->beginTransaction();

    try {

        $query = "INSERT INTO scriptures (book, chapter, verse, content) VALUES (?, ?, ?, ?)";
        
        $params[] = $_POST['book'];
        $params[] = $_POST['chapter'];
        $params[] = $_POST['verse'];
        $params[] = $_POST['content'];

        $statement    = $db->prepare($query);
        $result       = $statement->execute($params);
        $scriptureId  = $db->lastInsertId();

        $topics = $_POST['topics'];

        if ( isset($_POST['other']) && isset($_POST['other_field']) && !empty( $_POST['other_field']) ) {
            $params     = [];
            $query      = "INSERT INTO topics (name) VALUES (?)";
            $params[]   = $_POST['other_field'];
            $statement  = $db->prepare($query);
            $result     = $statement->execute($params);  
            $topicId    = $db->lastInsertId();
            $topics[]   = $topicId;
        }

        if ( count( $topics ) > 0 ) {

            $params = [];

            $query = "INSERT INTO scripture_topic (scripture_id, topic_id) VALUES ";
            foreach( $topics as $topicId ) {
                $query_values[] = '(?, ?)';
                $params[] = $scriptureId;
                $params[] = $topicId;
            }
            $query     .= implode( ', ', $query_values ); 
            $statement  = $db->prepare($query);
            $result     = $statement->execute($params);
        }

        $db->commit();

    } catch (Exception $e) {

        echo $e->getLine() . ': ' . $e->getMessage();

        $db->rollback();

    }

}

$query  = "SELECT * FROM topics";
$params = [];

$statement  = $db->prepare($query);
$result     = $statement->execute($params);
$topics     = $statement->fetchAll(PDO::FETCH_ASSOC);

$query  = "SELECT s.*, t.name
           FROM scriptures s
           LEFT JOIN scripture_topic st
            ON (st.scripture_id = s.id)
           LEFT JOIN topics t
            ON (t.id = st.topic_id)";

$params = [];

$statement  = $db->prepare($query);
$result     = $statement->execute($params);
$values = $statement->fetchAll(PDO::FETCH_ASSOC);

$scriptures = [];

foreach( $values as $value ) :
    if ( !isset( $scriptures[$value['id']]) ) {
        $scriptures[$value['id']]['book'] = $value['book'];
        $scriptures[$value['id']]['chapter'] = $value['chapter'];
        $scriptures[$value['id']]['verse'] = $value['verse'];
        $scriptures[$value['id']]['content'] = $value['content'];
        $scriptures[$value['id']]['topics'][] = $value['name'];
    } else {
        $scriptures[$value['id']]['topics'][] = $value['name'];
    }
endforeach;

print_r($scriptures, true);

echo '<pre>' . print_r($scriptures, true) . '</pre>';

require_once('templates/header.php'); ?>

<form>
    <label for="book">Book</label>
   <input type="text" id="book" name="book" value="" required>
   <br>
   <label for="chapter">Chapter</label> 
   <input type="text" id="chapter" name="chapter" value="" required>
   <br>
   <label for="verse">Verse</label> 
   <input type="text" id="verse" name="verse" value="" required>
   <br>
   <label for="content">Verse Content</label>
   <textarea id="content" name="content" required></textarea>
   <br>
    <?php foreach( $topics as $topic ) : ?>
        <input type="checkbox" name="topics[]" value="<?php echo $topic['id']; ?>"/><label><?php echo $topic['name']; ?></label>
    <?php endforeach; ?>
   
   <input type="checkbox" name="other"><input type="text" name="other_field" id="other_field">


    <input type="hidden" name="action" value="add_scripture" />
    <br>
    <button type="submit">Submit Scripture</button>
</form>


<?php require_once('templates/footer.php'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

    $( "form" ).submit(function( event ) {

        event.preventDefault();

        var data = $("form").serialize();

        $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
        });
    });

  </script>
