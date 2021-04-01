<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<? 
    $query = pg_query($db_connection, 'SELECT * FROM orders WHERE active=true');
    while ($res = pg_fetch_object($query)) {
        $orders[$res->id] = unserialize($res->contain);
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
            <div class="personal_header_name">Панель администратора</div>
            <div class="personal_header_email">Активные заказы</div>
        </div>
    </div>
    <table class="orders_table" border=1 cellpadding=10>
        <tr>
            <td>Номер заказа</td>
            <td>Содержимое</td>
            <td>Размер товара</td>
            <td>Количество товара</td>
            <td>Номер используемых палетов</td>
        </tr>
        <? foreach ($orders as $id => $order):?>
            <tr>
                <td><?= $id ?></td>
                <td>
                    <? foreach ($order['products'] as $product) {
                        echo $product['name'] . '<br/>';
                    }
                    ?>
                </td>
                <td>
                    <? foreach ($order['products'] as $product) {
                        echo $product['size'] . '<br/>';
                    }
                    ?>
                </td>
                <td>
                    <? foreach ($order['products'] as $product) {
                        echo $product['amount'] . '<br/>';
                    }
                    ?>
                </td>
                <td>
                    <? foreach ($order['products'] as $product) {
                        if ($product['id_palet'][1])
                            echo implode(', ', $product['id_palet']) . '<br/>';
                        else
                            echo $product['id_palet'][0] . '<br/>';
                    }
                    ?>
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