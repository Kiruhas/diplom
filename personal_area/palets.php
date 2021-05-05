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
?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
        $res = pg_fetch_object($query);
        if ($res -> isAdmin == false) header("Location: personal_area/lk_user.php");
    ?>
    <div class="orders_table_wrapper">
        <table class="orders_table">
            <thead>
                <tr>
                    <th>Номер поддона</th>
                    <th>Свободен</th>
                    <th>Номер заказа</th>
                    <th>Окантовка</th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($palets as $id => $palet):?>
                <tr class="row_inside">
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
            </tbody>
        </table>
    </div>
</div>

<?
    
?>

<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>