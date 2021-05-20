<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?
    $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
    $res = pg_fetch_object($query);
    $dolzhn_active = $res -> staff;
?>

<?php if ($_COOKIE['log'] == 'Да' && $dolzhn_active == 'admin'): ?>

<div class="container">
    <?php 
    ?>
    <div>
        <form type="post" action="/blocks/confirmAddStaff.php">
            <div class="choice_button">
                <input type="text"  name="username" placeholder="Введите логин">
            </div>
            <div class="choice_button">
                <input type="text"  name="pass" placeholder="Введите пароль">
            </div>
            <div class="choice_button">
                <select name=staff>
                    <option value="admin">Администратор</option>
                    <option value="manager">Менеджер</option>
                    <option value="storekeeper">Кладовщик</option>
                </select>
            <br><button class="order_basket button_style nav_btn" type="submit">Добавить сотрудника</button>
        </form>
    </div>
</div>

<?php endif ?>

<?php require "../blocks/html_structure_close.php" ?>