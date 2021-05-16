<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да' && $_GET['id']): ?>
<? 
    $order_id = $_GET['id'];
    $query = pg_query($db_connection, "SELECT * FROM orders WHERE id=$order_id");
    while ($res = pg_fetch_object($query)) {
        $orders[$res->id] = unserialize($res->contain);
        $order_ready = $res->ready;
        $order_agreed = $res->agreed;
        $order_active = $res->active;
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

    function num2word($num, $words) {
        $num = $num % 100;
        if ($num > 19) {
            $num = $num % 10;
        }
        switch ($num) {
            case 1: {
                return($words[0]);
            }
            case 2: case 3: case 4: {
                return($words[1]);
            }
            default: {
                return($words[2]);
            }
        }
    }

    $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
    $res = pg_fetch_object($query);
    $dolzhn_active = $res -> staff;
?>
<div class="container">
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_email">
                <a class="button_style nav_btn" href="/personal_area/active_orders.php">Активные заказы</a>
                <? if ($dolzhn_active == 'admin' || $dolzhn_active == 'manager'): ?>
                    <a class="button_style nav_btn" href="/personal_area/inactive_orders.php">Завершенные заказы</a>
                    <a class="button_style nav_btn" href="/personal_area/not_agreed_orders.php">Требующие подтверждения заказы</a>
                <? endif; ?>
            </div>
        </div>
    </div>
    <? if ($orders) :?>
    <div class="order">
        <div class="order_head">
            <b>Номер заказа:</b> <i><?= $order_id ?></i><br>
            <b>Активен:</b> <i><?= $order_active == 'f' ? 'нет' : 'да' ?></i><br>
            <? if ($order_active == 't'): ?> 
                <b>Подтвержден:</b> <i><?= $order_agreed == 'f' ? 'нет' : 'да' ?></i><br>
                <b>Готовность к отправке:</b> <i><?= $order_ready == 'f' ? 'не готов' : 'готов' ?></i><br>
            <? endif; ?>   
            <b>Содержимое заказа:</b>
        </div>
        <div class="order_contain">
             <? foreach ($orders as $id => $order):?>
                <? foreach ($order['products'] as $prod_id => $product):?>
                    <div class="contain_detail">
                    <? $all_weight += ($product['weight'] * $product['amount']) / 1000?>
                        <b>Товар:</b> <?= $product['name']?> <br>
                        <b>Размер:</b> <?= $product['size']?><br>
                        <b>Цвет:</b> <?= $product['color']?><br>
                        <b>Артикул:</b> <?=$prod_id . 'C-' . $colors[$product['color']] . '/S-' . $product['size']?> <br>
                        <b>Размер единицы (упаковка):</b> <?= $product['package_size']?> мм<br>
                        <b>Вес единицы:</b> <?= $product['weight']?> гр<br>
                        <b>Количество товара:</b> <?= $product['amount']?> шт<br>
                        <? if ($order_agreed =='t'): ?>
                            <b>Количество коробок по поддонам с товаром:</b> <br>
                            <? foreach ($product['palet_contain'] as $key => $pal):?>
                                <? if ($order_active =='t'): ?>
                                    <a class="button_style detail_palet nav_btn" href="/personal_area/palets.php?scroll=<?=$key?> ">Поддон № <?=$key . '</a> - ' . $pal . num2word($pal, array(' коробка', ' коробки', ' коробок')); ?><br>
                                <? else: ?>
                                    Поддон № <?=$key . ' - ' . $pal . num2word($pal, array(' коробка', ' коробки', ' коробок')); ?><br>
                                <? endif; ?> 
                            <? endforeach; ?> 
                        <? endif; ?>   
                        <b>Общий вес товара:</b> <?=($product['weight'] * $product['amount']) / 1000?> кг <br>
                    </div>
                <? endforeach; ?>
            <? endforeach; ?>       
        </div>
        <b>Максимум в одной коробке:</b> 50шт<br>
        <b>Суммарный вес всех позиций:</b> <?= $all_weight?> кг <br> 
        <? if ($order_ready == 't' && $order_active == 't'): ?> 
            <button class="end_order end_order_detail button_style nav_btn " data-id="<?= $order_id ?>">
                Завершить заказ
            </button>
        <? endif; ?>
        <? if ($order_agreed == 'f' && $order_active == 't'): ?> 
            <button class="confirm_order end_order_detail button_style nav_btn" data-id="<?= $id ?>">
                Подтвердить заказ
            </button>  
        <? endif; ?>  
    </div>
    <? endif; ?>
</div>

<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>