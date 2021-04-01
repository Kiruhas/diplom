<?
require_once "blocks/databaseConnect.php";
require_once "blocks/functions.php";

if ($_GET['clear'] == 'true') {
    unset($_COOKIE['products']);
    setcookie('products', '', -1, '/');
}

$products = unserialize($_COOKIE['products']);
$palets = 0;


if ($products) {
    $revenue = 100;
    foreach ($products as $key => $product) {
        $query_text = "SELECT size FROM sizes WHERE product_id=$key";
        $query = pg_query($db_connection, $query_text);
        $res = pg_fetch_object($query);

        $products[$key]['size'] = $res->size;
        $prod = checkFill($products[$key]['size'], $products[$key]["amount"], "100x100x$revenue", 0);
        $products[$key]['palets'] = $prod[1];
        $palets += $prod[1];
        $revenue = 100 - $prod[0];
        pg_free_result($query);
    }
    setcookie('products', serialize($products), time() + 3600, '/');
    setcookie('palets', $palets, time() + 3600, '/');
}
?>

<?php require_once "blocks/html_structure_open.php" ?>
<?php require_once "blocks/header.php" ?>

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
                    <? foreach ($products as $key=>$product) :?>
                        <tr>
                            <td><?= $product['name']?></td>
                            <td><?= $product['size']?></td>
                            <td><?= $product['color']?></td>
                            <td><?= $product['amount']?></td>
                            <td>
                                <button class="delete_prod button_style nav_btn" data-id="<?=$key?>">X</button>
                            </td>
                        </tr>
                    <? endforeach; ?>
                </table>
                <div>Количество используемых палетов для доставки: <span class="count_palets"><?= $palets ?></span></div>
            <? else: ?>
                Корзина пуста
            <? endif; ?>
        </div>
        <div class="basket_buttons">
            <form type="post" action="/basket.php">
                <input hidden name="clear" value="true">
                <button class="clear_basket button_style nav_btn" type="submit">Очистить корзину</button>
            </form>
            <? if ($products): ?>
                <form type="post" action="/blocks/confirmOrder.php">
                    <input hidden name="complete" value="true">
                    <button class="order_basket button_style nav_btn" type="submit">Оформить заказ</button>
                </form>
            <? endif ?>
        </div>
        
    </div>
</div>

<?php require "blocks/html_structure_close.php" ?>