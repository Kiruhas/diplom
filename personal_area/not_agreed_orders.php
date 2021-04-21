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

    $url = $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $url = $url[0];
?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
        $res = pg_fetch_object($query);
        if ($res -> isAdmin == false) header("Location: personal_area/lk_user.php");
        pg_free_result($query);
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_name">Административная панель</div>
            <div class="personal_header_email"> 
                <a class="button_style nav_btn" <?= $url == "/personal_area/palets.php" ? 'href="/personal_area/active_orders.php"' : 'style="background-color: rgb(219, 29, 76)"' ?>>Все заказы</a>
                <a class="button_style nav_btn" <?= $url !== "/personal_area/palets.php" ? 'href="/personal_area/palets.php"' : 'style="background-color: rgb(219, 29, 76)"' ?>>Состояние поддонов</a>
                
            </div>
            <div class="personal_header_email">
                <a class="button_style nav_btn"
                    <?= $url == "/personal_area/active_orders.php" ? 'style="background-color: rgb(219, 29, 76)"' : 'href="/personal_area/active_orders.php"'?>>Активные заказы</a>
                <a class="button_style nav_btn" 
                    <?= $url == "/personal_area/inactive_orders.php" ? 'style="background-color: rgb(219, 29, 76)"' : 'href="/personal_area/inactive_orders.php"' ?>>Завершенные заказы</a>
                <a class="button_style nav_btn" 
                    <?= $url == "/personal_area/not_agreed_orders.php" ? 'style="background-color: rgb(219, 29, 76)"' : 'href="/personal_area/not_agreed_orders.php"' ?>>Требующие подтверждения заказы</a>
            </div>
        </div>
    </div>
    <? if ($orders): ?>
        <table class="orders_table" border=1 cellpadding=10>
            <tr>
                <td>Номер заказа</td>
                <td>Содержимое</td>
                <td>Артикул</td>
                <td>Размер единицы товара (ДхШхВ, см)</td>
                <td>Вес единицы товара (гр)</td>
                <td>Количество товара</td>
                <td>Общий вес товара (кг)</td>
                <td>Обрешетка</td>
                <td>Подтвердить заказ</td>
            </tr>
            <? foreach ($orders as $id => $order):?>
            <?
                $border_id = (int)$orders[$id]['border'];
                $query = pg_query($db_connection, "SELECT * FROM borders WHERE id=$border_id");
                $res = pg_fetch_object($query);
                $border_title = $res->title;
                pg_free_result($query);
                ?>
                <tr <?= $_GET['scroll']==$id ? 'class="scroll" style="background-color:rgb(189, 31, 70);"' : '' ?>>
                    <td><?= $id ?></td>
                    <td>
                        <table>
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td><strong>' . $product['name'] . "</strong>, <i>р:" . $product['size'] . ", цв:" . $product['color'] . '</i></td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table>
                            <? foreach ($order['products'] as $prod_id=>$product) {
                                echo '<tr><td>' . $prod_id . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table>
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td>' . $product['package_size'] . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table>
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td>' . $product['weight'] . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table>
                            <? foreach ($order['products'] as $product) {
                                echo '<tr><td>' . $product['amount'] . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <table>
                            <? $all_weight = 0;?>
                            <? foreach ($order['products'] as $product) {
                                $all_weight += ($product['weight'] * $product['amount']) / 1000;
                                echo '<tr><td>' . ($product['weight'] * $product['amount']) / 1000  . '</td></tr>';
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <?= $border_title ?>
                    </td>
                    <td>
                        <button class="confirm_order button_style nav_btn" data-id="<?= $id ?>">
                            Подтвердить
                        </button>
                    </td>
                </tr>
            <? endforeach; ?>
        </table>
    <? endif; ?>
</div>

<?
    
?>

<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>