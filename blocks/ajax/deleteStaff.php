<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
$user_id = $_POST["user_id"];

$query = pg_query($db_connection, "DELETE FROM users WHERE id=$user_id");
pg_free_result($query);
