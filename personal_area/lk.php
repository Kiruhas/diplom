<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>
<?
$dolzhn = [
    'admin' => 'Администратор',
    'manager' => 'Менеджер',
    'storekeeper' => 'Кладовщик',
];
?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
        $res = pg_fetch_object($query);
        $dolzhn_active = $res -> staff;
        if (!$dolzhn_active == 'manager' || !$dolzhn_active == 'admin' || !$dolzhn_active == 'storekeeper') header("Location: personal_area/lk_user.php");
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_name">Личный кабинет</div>
            <div class="personal_header_email">Имя пользователя: <?php echo $res -> username ?></div>
            <div class="personal_header_email">Должность: <?php echo $dolzhn[$res -> staff] ?></div>
        </div>
    </div>
    <div class="personal_header_email"> 
        <a class="button_style nav_btn" href="/personal_area/active_orders.php">Просмотр заказов</a>
        <a class="button_style nav_btn" href="/personal_area/palets.php">Состояние поддонов</a>
    </div>
    <? if ($dolzhn_active == 'admin'): ?>
        <div class="personal_header_email"> 
            <a class="button_style nav_btn" href="/personal_area/add_palet.php">Добавить поддон</a>
            <a class="button_style nav_btn" href="/personal_area/add_staff.php">Добавить сотрудника</a>
            <a class="button_style nav_btn" href="/personal_area/delete_staff.php">Удалить сотрудника</a>
        </div>
    <?php endif ?>
    <div class="personal_header_email"> 
        <a class="button_style nav_btn" href="/personal_area/auth_exit.php">Выйти</a>
    </div>
</div>
<?php else: ?>
<div class="container"> 
    <div class="module_name">PICK-BY-LINE</div>
    <div class="input_data">
            <?php if ($_GET['er_auth'] == 'wrlog'): ?>
                <div class="lk_error" id="lk_error_auth">Введите логин!</div>
            <?php elseif ($_GET['er_auth'] == 'wrpas'): ?>
                <div class="lk_error" id="lk_error_auth">Введите пароль!</div>
            <?php elseif ($_GET['er_auth'] == 'wruser'): ?>
                <div class="lk_error" id="lk_error_auth">Неверный логин или пароль!</div>
            <?php endif ?>
        <div class="login-page" id="auth_form">
            <div class="form">
                <form class="login-form" action="check_auth.php" method="post">
                    <input type="text" name="login" placeholder="Введите логин" value="<?= $_GET['name'] ?? ''?>"/>
                    <input type="password" name="password" placeholder="Введите пароль"/>
                    <button>Войти</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>