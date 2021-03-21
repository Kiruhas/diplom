<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?php if ($_COOKIE['log'] == 'Да'): ?>
<div class="container">
    <?php 
        $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE id = $1', array($_COOKIE['id']));
        $res = pg_fetch_object($query);
    ?>
    <div class="personal">
        <div class="personal_header">
            <div class="personal_header_name"><?php echo $res -> username ?></div>
            <div class="personal_header_email"><?php echo $res -> email ?></div>
        </div>
        <div class="personal_content">
            <div class="personal_finance">
                <div class="finance_sub">
                    <div class="sub_content">
                        <div class="sub_head">Подписка</div>
                        <?php if (($res -> subscription) == 't'): ?>
                            <div class="sub_timeout">истекает <?php echo $res -> subscription_date ?></div>
                        <?php else: ?>
                            <div class="sub_timeout">Не оплачено</div>
                        <?php endif ?>
                        <div class="sub_control">Управлять</div>
                    </div>
                </div>
                <div class="finance_money">
                    <div class="money_content">
                        <div class="money_head">0 ₽</div>
                        <div class="money_timeout">на счете</div>
                        <div class="money_control">Пополнить</div>
                    </div>
                </div>
            </div>
            <div class="personal_back">
                <div class="back_changepass">
                    <img src="../images/Change_pass.png" alt="">
                    <div class="back_change_name">
                        <a href="" class="back_change_word">Сменить пароль</a>
                    </div> 
                </div>
                <div class="back_out">
                    <img src="../images/login.png" alt="">
                    <div class="back_out_name">
                        <a href="auth_exit.php" class="back_out_word">Выйти</a>
                    </div>
                </div>
            </div>
        </div>
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