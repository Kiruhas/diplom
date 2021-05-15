<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
$palet_id = $_POST["palet_id"];
$order_id = $_POST["order_id"];

$query_text = "UPDATE palets SET ready=true WHERE id=$palet_id";
$query = pg_query($db_connection, $query_text);
pg_free_result($query);

$query = pg_query($db_connection, "SELECT ready FROM palets WHERE order_id=$order_id");
$order_ready = true;
while ($res = pg_fetch_object($query)) {
    if ($res->ready !== 't') {
        $order_ready = false;
        break;
    }    
}
pg_free_result($query);

if ($order_ready) {
    $query_text = "UPDATE orders SET ready=true WHERE id=$order_id";
    $query = pg_query($db_connection, $query_text);
    pg_free_result($query);
}