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
    setcookie('products', serialize($products), time() + 3600, '/');

    $query_text = "SELECT * FROM borders ORDER BY id";
    $query = pg_query($db_connection, $query_text);
    while ($res = pg_fetch_object($query)) {
        $borders[$res->id] = $res->title;
    }
}
?>

<?php require_once "blocks/html_structure_open.php" ?>
<?php require_once "blocks/header.php" ?>

<div class="container">
    <div class="catalog">
        <div class="catalog_header">Корзина</div>
        <div class="basket_products">
            <? if ($products): ?>
                <table class="orders_table" border=1 cellpadding=10>
                    <tr>
                        <td>Наименование</td>
                        <td>Размер</td>
                        <td>Цвет</td>
                        <td>Количество</td>
                        <td>Удалить</td>
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
                <!-- <div>Количество используемых палетов для доставки: <span class="count_palets"><?= $palets ?></span></div> -->
                <div>
                    <form type="post" action="/blocks/confirmOrder.php">
                        <input hidden name="complete" value="true">
                        <span>Выберите обрешётку палета: </span>
                        <select name=border>
                            <? foreach ($borders as $key=>$title) :?>
                                <option value="<?=$key?>"><?=$title?></option>
                            <? endforeach; ?>
                        </select>
                        <button class="order_basket button_style nav_btn" type="submit">Оформить заказ</button>
                    </form>
                </div>
                <form type="post" action="/basket.php">
                    <input hidden name="clear" value="true">
                    <button class="clear_basket button_style nav_btn" type="submit">Очистить корзину</button>
                </form>
            </div>
                <? else: ?>
                    Корзина пуста
                <? endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require "blocks/html_structure_close.php" ?>