<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<? 
    $query = pg_query($db_connection, 'SELECT * FROM palets ORDER BY id');
    while ($res = pg_fetch_object($query)) {
        $palets[$res->id]['free'] = $res->free;
        $palets[$res->id]['palet_size'] = $res->palet_size;
        if ($res->free == 'f') {
            $palets[$res->id]['order_id'] = $res->order_id;
            $palets[$res->id]['border_id'] = $res->border_id;
            $palets[$res->id]['ready'] = $res->ready;
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
        $dolzhn_active = $res -> staff;
    ?>
    <div class="orders_table_wrapper">
        <table class="orders_table">
            <thead>
                <tr>
                    <th>Номер поддона</th>
                    <th>Номер заказа</th>
                    <th>Размер поддона (ДхШхВ, см)</th>
                    <th>Окантовка</th>
                    <th>Готовность</th>
                    <th>Свободен</th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($palets as $id => $palet):?>
                <tr class="row_inside">
                    <td><?= $id ?></td>
                    <td>
                        <?= $palet['order_id'] ? '<a class="button_style nav_btn" href="/personal_area/active_orders.php?scroll=' . $palet['order_id'] . '">' . $palet['order_id'] . '</a>' : ''?>
                    </td>
                    <td><?= $palet['palet_size'] ?></td>
                    <td>
                        <?= $palet['border_id'] ? $borders[$palet['border_id']] : ''?>
                    </td>
                    <td>
                        <? if ($palet['order_id']): ?>
                            <? if ($palet['ready'] == 't'): ?>
                                Готов
                            <? else: ?>
                                <? if($dolzhn_active == 'admin' || $dolzhn_active == 'storekeeper'): ?>
                                    <button class="confirm_palet button_style nav_btn" data-id="<?= $id ?>" data-order="<?= $palet['order_id'] ?>">
                                        Подтвердить
                                    </button>
                                <? else:?>
                                    Не готов
                                <? endif;?>
                            <? endif ?>
                        <? endif ?>
                    </td>
                    <td>
                        <?= $palet['free'] == 't' ? 'Да' : 'Нет'?>
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