<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";
$palet_size = $_GET["palet_size"];

$add_mas = [
    'free' => true,
    'order_id' => null,
    'border_id' => null,
    'palet_size' => $palet_size,
    'ready' => false,
];

$res = pg_insert($db_connection, 'palets', $add_mas);

if ($res): ?>
    <?php require "../blocks/html_structure_open.php" ?>
    <?php require "../blocks/header.php" ?>

    <div class="container">
        <span>Поддон добавлен</span>
        <a href="/">На главную</a>
    </div>
<? endif; ?>

<?php require $_SERVER['DOCUMENT_ROOT'] . "/blocks/html_structure_close.php" ?>