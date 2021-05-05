<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<? 
    $query = pg_query($db_connection, 'SELECT * FROM orders WHERE active=true AND agreed=true ORDER BY id');
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

    $query = pg_query($db_connection, 'SELECT * FROM palets ORDER BY id');
    while ($res = pg_fetch_object($query)) {
        $palets[$res->id]['size'] = $res->palet_size;
        $palets[$res->id]['border'] = $res->border_id;
    }
    pg_free_result($query);

    $query = pg_query($db_connection, 'SELECT * FROM borders ORDER BY id');
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
    
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_email">
                <a class="button_style nav_btn" style="background-color: rgba(28, 119, 20, 0.871); color:#fff;">Активные заказы</a>
                <a class="button_style nav_btn" href="/personal_area/inactive_orders.php">Завершенные заказы</a>
                <a class="button_style nav_btn" href="/personal_area/not_agreed_orders.php">Требующие подтверждения заказы</a>
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
                    <th>Размер единицы товара (ДхШхВ, см)</th>
                    <th>Вес единицы товара (гр)</th>
                    <th>Количество товара</th>
                    <th>Общий вес товара (кг)</th>
                    <th>Номера используемых поддонов</th>
                    <th>Готовность к отправке</th>
                    <th>Завершить заказ</th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($orders as $id => $order):?>
                <tr class="row_inside <?= $_GET['scroll']==$id ? 'scroll' : '' ?>" >
                    <td><?= $id ?></td>
                    <td>
                        <table style="text-align:center;">
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td><strong>' . $product['name'] . "</strong>, <i>р:" . $product['size'] . ", цв:" . $product['color'] . '</i></td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table>
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
                                if (is_array($product['id_palet'])) {
                                    echo '<tr><td>';
                                    foreach ($product['id_palet'] as $palet) {
                                        echo '<span id="i-have-a-tooltip" data-description="Размер:' . $palets[$palet]['size'] . ' Обрешетка:' . $borders[$palets[$palet]['border']] .'">' . $palet . '</span>' . '<br/>';
                                    }
                                    echo '</td></tr>';
                                }
                                else
                                    echo '<tr><td><span id="i-have-a-tooltip" data-description="Размер:' . $palets[$product['id_palet']]['size'] . ' Обрешетка:' . $borders[$palets[$product['id_palet']]['border']] .'">' . $product['id_palet'] . '</span></td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <?= $orders_ready[$id] == 'f' ? 'Не готов' : 'Готов' ?>
                    </td>
                    <td>
                         <? if ($orders_ready[$id] == 'f'): ?>  <!-- ПОМЕНЯТЬ НА !== -->
                            <button class="end_order button_style nav_btn" data-id="<?= $id ?>">
                                Завершить
                            </button>
                        <? endif; ?>
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