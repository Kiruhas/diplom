<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";

function checkFill ($product_size, $product_count, $palet_size, $palets_amount) {
    $palet = explode('x', $palet_size); // Получаем размеры палета ДхШхВ
    $product = explode('x', $product_size); // Получаем размеры вещи ДхШхВ

    $height = floor($palet[2] / $product[2]); // Сколько влезет в одну колонну в высоту
    $length = (floor($palet[1] / $product[1])) * $height; // Сколько влезет в одну шеренгу во всю высоту
    $productsOnPalet = (floor($palet[0] / $product[0])) * $length; // Сколько влезет на весь палет
    if ($palet[2] == 100)
        $palets_amount += 1; // Считаем количество палетов

    if ($productsOnPalet < $product_count) {
        // Значит не хватает
        $residue = $product_count - $productsOnPalet; // Остаток, который не влез на палет
        return checkFill($product_size, $residue, "100x100x100", $palets_amount); // Рекурсия - берем еще палет
    } else {
        // Нормально, считаем 
        $percentFill = ceil($product_count / ($productsOnPalet / 100)); // Процент заполненности
        while ($percentFill % $product[2]) { // Пока процент не делится на высоту без остатка
            $percentFill += 1;
        }
        return [$percentFill, $palets_amount]; // Возвращаем [процент заполнения последнего палета, количество палетов]
    }
}

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
