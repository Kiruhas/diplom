<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
        $res = pg_fetch_object($query);
        if ($res -> isAdmin == false) header("Location: personal_area/lk_user.php");
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_name">Административная панель</div>
            <div class="personal_header_email">Пользователь: <?php echo $res -> username ?></div>
        </div>
    </div>
    <div class="personal_header_email"> 
        <a class="button_style nav_btn" href="/personal_area/active_orders.php">Все заказы</a>
        <a class="button_style nav_btn" href="/personal_area/palets.php">Состояние палетов</a>
    </div>
</div>
<?php else: ?>
<div class="container"> 
    <div class="input_data">
            <?php if ($_GET['er_auth'] == 'wrlog'): ?>
                <div class="lk_error" id="lk_error_auth">Введите логин!</div>
            <?php elseif ($_GET['er_auth'] == 'wrpas'): ?>
                <div class="lk_error" id="lk_error_auth">Введите пароль!</div>
            <?php elseif ($_GET['er_auth'] == 'wruser'): ?>
                <div class="lk_error" id="lk_error_auth">Неверный логин или пароль!</div>
            <?php endif ?>
        <div class="authorizate_form" id="auth_form">
            <div class="auth_form">
                <form action="check_auth.php" method="post" class="add_form">
                <input type="text" name="login" class="add_form_input" placeholder="Введите логин" value="<?= $_GET['name'] ?? ''?>">
                <input type="text" name="password" class="add_form_input" placeholder="Введите пароль">              
                <button type="submit" name="btn" class="add_form_submit">Вход</button>
            </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
<?php unset($_GET) ?>

<?php require "../blocks/html_structure_close.php" ?>