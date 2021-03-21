<?
if ($_GET['clear'] == 'true') {
    unset($_COOKIE['products']);
    setcookie('products', '', -1, '/');
}
?>
<?php require_once "blocks/html_structure_open.php" ?>
<?php require_once "blocks/header.php" ?>
<?php require_once "blocks/databaseConnect.php" ?>
<?php 
    // $query_text = 'SELECT * FROM "products" ';
    // $query = pg_query($db_connection, $query_text);
    $products = unserialize($_COOKIE['products']);
?>

<div class="container">
    <div class="catalog">
        <div class="catalog_header">Корзина</div>
        <div class="basket_products">
            <? if ($products): ?>
                <table>
                    <tr>
                        <td>Наименование</td>
                        <td>Размер</td>
                        <td>Цвет</td>
                        <td>Количество</td>
                    </tr>
                    <? foreach ($products as $product) :?>
                        <tr>
                            <td><?= $product['name']?></td>
                            <td><?= $product['size']?></td>
                            <td><?= $product['color']?></td>
                            <td><?= $product['amount']?></td>
                        </tr>
                    <? endforeach; ?>
                </table>
            <? else: ?>
                Корзина пуста
            <? endif; ?>
        </div>
        <form type="post" action="/basket.php">
            <input hidden name="clear" value="true">
            <button class="clear_basket button_style nav_btn" type="submit">Очистить корзину</button>
        </form>
    </div>
</div>

<?php require "blocks/html_structure_close.php" ?>