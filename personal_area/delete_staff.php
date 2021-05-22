<?php require "../blocks/html_structure_open.php" ?>
<?php require "../blocks/header.php" ?>

<?
    $query = pg_query_params($db_connection, 'SELECT * FROM users WHERE username = $1 AND "password" = $2', array($_COOKIE['username'], $_COOKIE['pass']));
    $res = pg_fetch_object($query);
    $dolzhn_active = $res -> staff;
    pg_free_result($query);
?>

<?php if ($_COOKIE['log'] == 'Да' && $dolzhn_active == 'admin'): ?>
<?
    $query = pg_query($db_connection, 'SELECT * FROM users ORDER BY id');
    while ($res = pg_fetch_object($query)) {
        $users[$res->id]['username'] = $res->username;
        $users[$res->id]['staff'] = $res->staff;
    }
    pg_free_result($query);

    $staff = [
        'admin' => 'Администратор',
        'manager' => 'Менеджер',
        'storekeeper' => 'Кладовщик',
    ]
?>

<div class="container">
    <div class="personal_header_name">Сотрудники</div><br>
    <div class="orders_table_wrapper">
        <table class="orders_table">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Логин</th>
                    <th>Должность</th>
                    <th><span>         </span></th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($users as $id => $user):?>
                <tr class="row_inside">
                    <td><?= $id ?></td>
                    <td>
                        <?= $user['username']?>
                    </td>
                    <td>
                        <?= $staff[$user['staff']]?>
                    </td>
                    <td>
                        <button class="delete_staff button_style nav_btn" data-id="<?= $id ?>">
                            Удалить
                        </button>
                    </td>
                </tr>
            <? endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif ?>

<?php require "../blocks/html_structure_close.php" ?>