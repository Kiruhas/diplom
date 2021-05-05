<?
    $url = $_SERVER['REQUEST_URI'];
    $url = explode('?', $url);
    $url = $url[0];

    $order_pages = ["/personal_area/active_orders.php", "/personal_area/inactive_orders.php", "/personal_area/not_agreed_orders.php"];
?>
<header class="header">
    <div class="container">
        <div class="header_inner">
            <div class="header_logo">
                <ul class="logo_categories">
                    <li class="header_menu_item <?= in_array($url, $order_pages) ? 'header_item_active' : '' ?>">
                        <a <?= in_array($url, $order_pages) ? '' : 'href="/personal_area/active_orders.php"' ?>>Управление заказами</a>
                    </li>
                    <li class="header_menu_item <?= $url=="/personal_area/palets.php" ? 'header_item_active' : '' ?>">
                        <a <?= $url=="/personal_area/palets.php" ? '' : 'href="/personal_area/palets.php"' ?>>Состояние поддонов</a>
                    </li>
                    <li class="header_menu_item <?= $url=="/catalog.php" ? 'header_item_active' : '' ?>">
                        <a <?= $url=="/catalog.php" ? '' : 'href="/catalog.php"' ?>>Каталог</a>
                    </li>
                    <li class="header_menu_item <?= $url=="/basket.php" ? 'header_item_active' : '' ?>">
                        <a <?= $url=="/basket.php" ? '' : 'href="/basket.php"' ?>>Корзина</a>
                    </li>
                </ul>
            </div>
            <div class="nav">       
                <a class="nav_btn" href="../personal_area/lk.php">Кабинет</a>
            </div>
        </div>
    </header>
</div>