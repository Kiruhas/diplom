<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<? 
    $query = pg_query($db_connection, 'SELECT * FROM orders WHERE active=false');
    while ($res = pg_fetch_object($query)) {
        $orders[$res->id] = unserialize($res->contain);
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
        if ($res -> isAdmin == false) header("Location: personal_area/lk_user.php");
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_email">
                <a class="button_style nav_btn" href="/personal_area/active_orders.php">Активные заказы</a>
                <a class="button_style nav_btn" style="background-color: rgba(28, 119, 20, 0.871); color:#fff;">Завершенные заказы</a>
                <a class="button_style nav_btn" href="/personal_area/not_agreed_orders.php">Требующие подтверждения заказы</a></div>
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
                    <th>Размер единицы товара (ДхШхВ, см)</th>
                    <th>Вес единицы товара (гр)</th>
                    <th>Количество товара</th>
                    <th>Общий вес товара (кг)</th>
                    <th>Номера использованных поддонов</th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($orders as $id => $order):?>
                <tr class="row_inside">
                    <td><?= $id ?></td>
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
                                echo '<tr><td>' . $product['package_size'] . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table style="width:100%">
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td>' . $product['weight'] . '</td></tr>';
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
                    <td>
                        <table style="width:100%">
                            <? $all_weight = 0;?>
                            <? foreach ($order['products'] as $product) {
                                $all_weight += ($product['weight'] * $product['amount']) / 1000;
                                echo '<tr><td>' . ($product['weight'] * $product['amount']) / 1000  . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table style="width:100%">
                            <? foreach ($order['products'] as $product) {
                                if (is_array($product['id_palet']))
                                    echo '<tr><td>' . implode(', ', $product['id_palet']) . '</td></tr>';
                                else
                                    echo '<tr><td>' . $product['id_palet'] . '</td></tr>';
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