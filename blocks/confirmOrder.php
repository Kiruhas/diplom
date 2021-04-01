<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/functions.php";

$products = unserialize($_COOKIE['products']);

$query_text = 'SELECT id FROM "palets" WHERE free=true ORDER BY id';
$query = pg_query($db_connection, $query_text);
while ($res = pg_fetch_object($query)) {
    $paletsFree[] = $res->id;
}
pg_free_result($query);

$palets = 0;
$counter = 0;
$revenue = 100;

if ($products) {
    foreach ($products as $key => $product) {
        $query_text = "SELECT size FROM sizes WHERE product_id=$key";
        $query = pg_query($db_connection, $query_text);
        $res = pg_fetch_object($query);

        $products[$key]['size'] = $res->size;
        $prod = checkFill($products[$key]['size'], $products[$key]["amount"], "100x100x$revenue", $palets);
        $products[$key]['last_palet_value'] = $prod[2];
        $products[$key]['last_palet_percent'] = $prod[0];
        $palets = $prod[1];
        $revenue = 100 - $prod[0];
        pg_free_result($query);
    }

    for ($i = 0; $i < $palets; $i++) {
        $paletsBusy[] = $paletsFree[$i];
    }

    $contain['palets_id'] = $paletsBusy;
    $counter = 0;
    
    foreach ($products as $key => $product) {
        if (!$paletsBusy[1]) {
            $products[$key]['id_palet'] = $paletsBusy[0];
        } else {
            $first = $counter;
            $new = $product['palets'] + $counter;
            for ($counter; $counter < $new; $counter++) {
                if ($product['last_palet_percent'] < 98 && $counter == $first && $counter !== 0) {
                    $products[$key]['id_palet'][] = $paletsBusy[$counter - 1];
                }
                $products[$key]['id_palet'][] = $paletsBusy[$counter];
            }
        }
    }
    
    $contain['products'] =  $products;
    $contain = serialize($contain);
    
    $query_text = "INSERT INTO orders (id, contain, active) VALUES (DEFAULT, '$contain', true)";
    $query = pg_query($db_connection, $query_text);
    pg_free_result($query);

    $query_text = "SELECT LAST_VALUE(id) OVER() FROM orders ORDER BY id";
    $query = pg_query($db_connection, $query_text);
    $order_id = pg_fetch_object($query)->last_value;
    pg_free_result($query);
    
    $paletsBusy = implode(',', $paletsBusy);

    $query_text = "UPDATE palets SET free=false, order_id=$order_id WHERE id IN ($paletsBusy)";
    $query = pg_query($db_connection, $query_text);
    pg_free_result($query);
    

    require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/clearBasket.php";
}
?>

<div class="container">
    <? if ($contain): ?>
        <span>Заказ оформлен</span>
        <a href="/">На главную</a>
    <? else: ?>
        <span>Корзина была пуста</span>
    <? endif ?>
</div>

<?php require $_SERVER['DOCUMENT_ROOT'] . "/blocks/html_structure_close.php" ?>
<?


