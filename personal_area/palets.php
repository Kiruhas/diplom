<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<? 
    $query = pg_query($db_connection, 'SELECT * FROM palets ORDER BY id');
    while ($res = pg_fetch_object($query)) {
        $palets[$res->id]['free'] = $res->free;
        if ($res->free == 'f') {
            $palets[$res->id]['order_id'] = $res->order_id;
            $palets[$res->id]['border_id'] = $res->border_id;
        }
    }
    pg_free_result($query);

    $query = pg_query($db_connection, 'SELECT * FROM borders');
    while ($res = pg_fetch_object($query)) {
        $borders[$res->id] = $res->title;
    }
    pg_free_result($query);

    $url = $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $url = $url[0];
?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
        $res = pg_fetch_object($query);
        if ($res -> isAdmin == false) header("Location: personal_area/lk_user.php");
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_name">Административная панель</div>
            <div class="personal_header_email"> 
                <a class="button_style nav_btn" <?= $url == "/personal_area/palets.php" ? 'href="/personal_area/active_orders.php"' : 'style="background-color: rgb(219, 29, 76)"' ?>>Все заказы</a>
                <a class="button_style nav_btn" <?= $url !== "/personal_area/palets.php" ? 'href="/personal_area/palets.php"' : 'style="background-color: rgb(219, 29, 76)"' ?>>Состояние палетов</a>
            </div>
        </div>
    </div>
    <table class="orders_table" border=1 cellpadding=10>
        <tr>
            <td>Номер палета</td>
            <td>Свободен</td>
            <td>Номер заказа</td>
            <td>Окантовка</td>
        </tr>
        <? foreach ($palets as $id => $palet):?>
            <tr>
                <td><?= $id ?></td>
                <td>
                    <?= $palet['free'] == 't' ? 'Да' : 'Нет'?>
                </td>
                <td>
                    <?= $palet['order_id'] ? '<a class="button_style nav_btn" href="/personal_area/active_orders.php?scroll=' . $palet['order_id'] . '">' . $palet['order_id'] . '</a>' : ''?>
                </td>
                <td>
                    <?= $palet['border_id'] ? $borders[$palet['border_id']] : ''?>
                </td>
            </tr>
        <? endforeach; ?>
    </table>
</div>

<?
    
?>

<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>