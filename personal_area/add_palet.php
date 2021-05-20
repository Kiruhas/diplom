<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?
    $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
    $res = pg_fetch_object($query);
    $dolzhn_active = $res -> staff;
?>

<?php if ($_COOKIE['log'] == 'Да' && $dolzhn_active == 'admin'): ?>

<div class="container">
    <?php 
        $query_text = "SELECT * FROM palets ORDER BY palet_size";
        $query = pg_query($db_connection, $query_text);
        while ($res = pg_fetch_object($query)) {
            $palet_sizes[$res->palet_size] = $res->palet_size;
        }
        pg_free_result($query);
    ?>
    <div>
        <form type="post" action="/blocks/confirmAddPalet.php">
            <div class="choice_button">
                <span>Выберите размер поддона: </span>
                <input type="text"  name="palet_size" placeholder="Введите размер поддона"><span> формат - 100х100х100 в мм</span>
            </div>
            <button class="order_basket button_style nav_btn" type="submit">Добавить поддон</button>
        </form>
    </div>
</div>

<?php endif ?>

<?php require "../blocks/html_structure_close.php" ?>