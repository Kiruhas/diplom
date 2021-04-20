<?
// unset($_COOKIE['products']);
// setcookie('products', '', -1, '/');
?>
<?php require_once "blocks/html_structure_open.php" ?>
<?php require_once "blocks/header.php" ?>
<?php require_once "blocks/databaseConnect.php" ?>
<?php 
    $query_text = 'SELECT * FROM "products" ORDER BY id';
    $query = pg_query($db_connection, $query_text);
    
?>

<div class="container">
    <div class="catalog">
        <div class="catalog_header">Каталог товаров</div>
        <div>
            <? 
                // var_dump(unserialize($_COOKIE['products']))  
            ?>
        </div>
        <div class="catalog_products">
            <? while ($res = pg_fetch_object($query)): ?>
            <div class="product">
                <div class="product_poster" id="product_img" data-attr="<?php echo $res -> image ?>">
                    <img src="<?php echo $res -> image == '' ? 'images/no_image.png' : $res -> image ?>" alt="">
                </div>
                <div class="product_name" id="product_name" data-attr="<?php echo $res -> name ?>"><?php echo $res -> name ?></div>
                <div class="product_har" id="product_size" data-attr="<?php echo $res -> size ?>">Размер: <?php echo $res -> size ?></div>
                <div class="product_har" id="product_color" data-attr="<?php echo $res -> color ?>">Цвет: <?php echo $res -> color ?></div>
                <div class="product_har" id="product_weight" data-attr="<?php echo $res -> weight ?>">Вес одной единицы: <?php echo $res -> weight ?> гр.</div>
                <div class="product_har">Минимальная партия: <?php echo $res -> amount ?></div>
                <div class="amount">
                    <input type="button" value="-" class="minus_prod nav_btn">
                    <input class="product_amount" type="number" min="<?php echo $res -> amount ?>" max="3000" onKeyUp="if(this.value>3000){this.value='3000';}else if(this.value<<?php echo $res -> amount ?>){this.value='<?php echo $res -> amount ?>';}" value="<?php echo $res -> amount ?>"></input>
                    <input type="button" value="+" class="plus_prod nav_btn">
                </div>
                <div class="product_button">
                    <button class="add_product button_style nav_btn" id="<? echo $res -> id ?>">Добавить в корзину</button>
                </div>
            </div>
            <? endwhile; ?>
        </div>
    </div>
    <?php 
        pg_free_result($query);
    ?>
</div>

<?php require "blocks/html_structure_close.php" ?>