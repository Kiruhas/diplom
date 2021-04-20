<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/functions.php";

$query = pg_query_params($db_connection, 'SELECT id FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
$id_user = pg_fetch_object($query) -> id;
pg_free_result($query);

$products = unserialize($_COOKIE['products']);

$query_text = 'SELECT id FROM "palets" WHERE free=true ORDER BY id';
$query = pg_query($db_connection, $query_text);
while ($res = pg_fetch_object($query)) {
    $paletsFree[] = $res->id;
}
pg_free_result($query);

$border_id = $_GET['border'];
$query_text = "SELECT size FROM borders WHERE id=$border_id";
$query = pg_query($db_connection, $query_text);
$border_size = (int)pg_fetch_object($query)->size;
pg_free_result($query);

$palets = 0;
$counter = 0;
$revenue = 100-$border_size;
$revenue_border = $revenue;

if ($products) {
    foreach ($products as $key => $product) {
        $query_text = "SELECT size FROM sizes WHERE product_id=$key";
        $query = pg_query($db_connection, $query_text);
        $res = pg_fetch_object($query);

        $products[$key]['package_size'] = $res->size;
        $palet_border = "$revenue_border" . "x$revenue_border" . "x$revenue_border";
        $prod = checkFill($products[$key]['package_size'], $products[$key]["amount"], "$revenue_border" . "x$revenue_border" . "x$revenue", $palets, $palet_border);
        $products[$key]['palets'] = $prod[1];

        $products[$key]['last_palet_value'] = $prod[2];
        $products[$key]['last_palet_percent'] = $prod[0];
        $palets = $prod[1];
        $revenue = $revenue_border - $prod[0];
        pg_free_result($query);
    }

    
    
    for ($i = 0; $i < $palets; $i++) {
        $paletsBusy[] = $paletsFree[$i];
    }

    if ($palets > count($paletsFree)) $no_order = true;
    
    if (!$no_order) {
        $contain['palets_id'] = $paletsBusy;
    $counter = 0;
    $counterProductsOnOne = 0;
    $counterPalets = 0;

    foreach ($products as $key => $product) {
        if (!$paletsBusy[1]) {
            $products[$key]['id_palet'] = $paletsBusy[0];
        } else {           
            $new = $product['palets'] + $counter;
            for ($counter; $counter < $new; $counter++) {
                if ($nextPaletFree && $nextName != $product['name']) {
                    $products[$key]['id_palet'][] = $paletsBusy[$counter - $counterProductsOnOne];
                    $nextPaletFree = false;
                } else {
                    if (!is_null($paletsBusy[$counterPalets])){
                        $products[$key]['id_palet'][] = $paletsBusy[$counterPalets];
                        $counterPalets++;
                    }
                }
                
                if ($product['last_palet_percent'] < 96 && $counter == $new - 1) { 
                    $counterProductsOnOne++;
                    $nextName = $product['name'];
                    $nextPaletFree = true;
                }
            }
        }   
    }

    $contain['products'] =  $products;
    $contain = serialize($contain);
    
    $query_text = "INSERT INTO orders (id, contain, active, ready, id_user) VALUES (DEFAULT, '$contain', true, false, '$id_user')";
    $query = pg_query($db_connection, $query_text);
    pg_free_result($query);

    $query_text = "SELECT MAX(id) FROM orders";
    $query = pg_query($db_connection, $query_text);
    $order_id = (int)pg_fetch_object($query)->max;
    pg_free_result($query);
    
    $paletsBusy = implode(',', $paletsBusy);

    $query_text = "UPDATE palets SET free=false, order_id=$order_id, border_id=$border_id WHERE id IN ($paletsBusy)";
    $query = pg_query($db_connection, $query_text);
    pg_free_result($query);
    

    require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/clearBasket.php";
    }
}
?>

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


