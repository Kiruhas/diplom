<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<? 
    $query = pg_query($db_connection, 'SELECT * FROM orders WHERE active=true AND agreed=false ORDER BY id');
    while ($res = pg_fetch_object($query)) {
        $orders[$res->id] = unserialize($res->contain);
        $orders_ready[$res->id] = $res->ready;
    }
    pg_free_result($query);

    $colors = [
        'Красный' => 'RE',
        'Черный' => 'BL',
        'Зеленый' => 'GR',
    ];
?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
        $res = pg_fetch_object($query);
        $dolzhn_active = $res -> staff;
        if (!$dolzhn_active == 'manager' || !$dolzhn_active == 'admin') header("Location: personal_area/lk_user.php");
        pg_free_result($query);
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_email">
                <a class="button_style nav_btn" href="/personal_area/active_orders.php">Активные заказы</a>
                <? if ($dolzhn_active == 'admin' || $dolzhn_active == 'manager'): ?>
                    <a class="button_style nav_btn" href="/personal_area/inactive_orders.php">Завершенные заказы</a>
                    <a class="button_style nav_btn" style="background-color: rgba(28, 119, 20, 0.871); color:#fff;">Требующие подтверждения заказы</a>
                <? endif; ?>
            </div>
        </div>
    </div>
    <? if ($orders): ?>
        <div class="orders_table_wrapper">
        <table class="orders_table">
            <thead>
                <tr>
                    <th>Номер заказа</th>
                    <th>Содержимое</th>
                    <th>Артикул</th>
                    <th>Количество товара</th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($orders as $id => $order):?>
            <?
                $border_id = (int)$orders[$id]['border'];
                $query = pg_query($db_connection, "SELECT * FROM borders WHERE id=$border_id");
                $res = pg_fetch_object($query);
                $border_title = $res->title;
                pg_free_result($query);
                ?>
                <tr class="row_inside" <?= $_GET['scroll']==$id ? 'class="scroll" style="background-color:rgb(189, 31, 70);"' : '' ?>>
                    <td><a class="button_style nav_btn" href="/personal_area/order_detail.php?id=<?=$id?>"><?=$id?></a></td>
                    <td>
                        <table style="width:100%">
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td><strong>' . $product['name'] . "</strong>, <i>р:" . $product['size'] . ", цв:" . $product['color'] . '</i></td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table style="width:100%">
                            <? foreach ($order['products'] as $prod_id=>$product) {
                                echo '<tr><td>' . $prod_id . 'C-' . $colors[$product['color']] . '/S-' . $product['size'] . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table style="width:100%">
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td>' . $product['amount'] . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
        </div>
    <? endif; ?>
</div>

<?
    
?>

<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>