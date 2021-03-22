<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/functions.php";

$products = unserialize($_COOKIE['products']); // ПЕРЕДЕЛАТЬ НА POST

$palets = 0;
$revenue = 100;

if ($products) {
    foreach ($products as $key => $product) {
        $query_text = "SELECT size FROM sizes WHERE product_id=$key";
        $query = pg_query($db_connection, $query_text);
        $res = pg_fetch_object($query);

        $products[$key]['size'] = $res->size;
        $prod = checkFill($products[$key]['size'], $products[$key]["amount"], "100x100x$revenue", $palets);
    
        $palets = $prod[1];
        $revenue = 100 - $prod[0];
        pg_free_result($query);
    }
}

var_dump($prod);
var_dump($palets);
