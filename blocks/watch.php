<?php
    $conn_string = "host=localhost port=5438 dbname=ukkeMovies user=postgres password=dg4ao9hv";
    $db_connection = pg_connect($conn_string);
    $query_text = 'SELECT * FROM "filmsRus" WHERE id=' . $_GET['watch_id'];
    $res = pg_fetch_object(pg_query($db_connection, $query_text));
?>

<?php require "html_structure_open.php" ?>
<?php require "header.php" ?>

<div class="container">
    <div class="watch">
        <div><?php echo $res -> title?></div>
        <div><img src="../<?php echo $res -> poster ?>" alt=""></div>
        <div><?php echo $res -> description ?></div>
        <div><?php echo $res -> agerate ?></div>
    </div>
</div>

<?php pg_close($db_connection) ?>
<?php require "html_structure_close.php" ?>