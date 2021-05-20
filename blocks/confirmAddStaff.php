<?
require_once $_SERVER['DOCUMENT_ROOT'] . "/blocks/databaseConnect.php";

$username = $_GET["username"];
$pass = $_GET["pass"];
$staff = $_GET["staff"];

$add_mas = [
    'username' => trim($username),
    'password' => md5($pass),
    'staff' => $staff,
];

$res = pg_insert($db_connection, 'users', $add_mas);

if ($res): ?>
    <?php require "../blocks/html_structure_open.php" ?>
    <?php require "../blocks/header.php" ?>

    <div class="container">
        <span>Сотрудник добавлен</span>
        <a href="/">На главную</a>
    </div>
<? endif; ?>

<?php require $_SERVER['DOCUMENT_ROOT'] . "/blocks/html_structure_close.php" ?>