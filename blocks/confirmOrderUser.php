<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/functions.php";

$query = pg_query_params($db_connection, 'SELECT id FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
$id_user = pg_fetch_object($query) -> id;
pg_free_result($query);

$products = unserialize($_COOKIE['products']);

$border_id = $_GET['border'];
$palet_size = $_GET['palet_size'];

if ($products) {
    foreach ($products as $key => $product) {
        $query_text = "SELECT size FROM sizes WHERE product_id=$key";
        $query = pg_query($db_connection, $query_text);
        $res = pg_fetch_object($query);
        $products[$key]['package_size'] = $res->size;
        pg_free_result($query);
    }
    $contain['border'] = $border_id;
    $contain['palet_size'] = $palet_size;
    $contain['products'] =  $products;
    $contain = serialize($contain);
    
    $query_text = "INSERT INTO orders (id, contain, active, ready, id_user, agreed) VALUES (DEFAULT, '$contain', true, false, '$id_user', false)";
    $query = pg_query($db_connection, $query_text);
    pg_free_result($query);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/clearBasket.php";
}

?>
<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<div class="container">
    <? if ($contain): ?>
        <span>Заказ оформлен</span>
    <? elseif ($no_order): ?>
        <span>Заказ не оформлен
              Причина: недостаток места на складе</span>
    <? else: ?>
        <span>Корзина была пуста</span>
    <? endif ?>
    <a href="/">На главную</a>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . "/blocks/html_structure_close.php" ?>
<?


