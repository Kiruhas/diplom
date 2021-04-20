<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
$order_id = $_POST["order_id"];

$query_text = "UPDATE orders SET active=false WHERE id=$order_id";
$query = pg_query($db_connection, $query_text);
pg_free_result($query);

$query_text = "UPDATE palets SET free=true, order_id=null, border_id=null WHERE order_id=$order_id";
$query = pg_query($db_connection, $query_text);
pg_free_result($query);