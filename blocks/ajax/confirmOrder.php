<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/functions.php";
$order_id = $_POST["order_id"];

$query = pg_query($db_connection, "SELECT * FROM orders WHERE id=$order_id");
while ($res = pg_fetch_object($query)) {
    $products = unserialize($res->contain)['products'];
    $border_id = unserialize($res->contain)['border'];
}
pg_free_result($query);

$query_text = 'SELECT id FROM "palets" WHERE free=true ORDER BY id';
$query = pg_query($db_connection, $query_text);
while ($res = pg_fetch_object($query)) {
    $paletsFree[] = $res->id;
}
pg_free_result($query);


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
    
    if (!$no_order) 
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

    if (!$no_order) {
        $query_text = "UPDATE orders SET agreed=true WHERE id=$order_id";
        $query = pg_query($db_connection, $query_text);
        pg_free_result($query);

        $query_text = "UPDATE orders SET contain='$contain' WHERE id=$order_id";
        $query = pg_query($db_connection, $query_text);
        pg_free_result($query);
        
        $paletsBusy = implode(',', $paletsBusy);

        $query_text = "UPDATE palets SET free=false, order_id=$order_id, border_id=$border_id WHERE id IN ($paletsBusy)";
        $query = pg_query($db_connection, $query_text);
        pg_free_result($query);
    }

    
}