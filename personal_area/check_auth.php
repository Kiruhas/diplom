<?php
    require_once "../blocks/databaseConnect.php";

    $login = $_POST['login'];
    $pass = $_POST['password'];

    if (trim($login) == ''){
        pg_close($db_connection);
        setcookie('error_lk', 'yes', time() + 3600, '/');
        header('Location: /personal_area/lk.php?er_auth=wrlog');
    } else if (trim($pass) == ''){
        pg_close($db_connection);
        setcookie('error_lk', 'yes', time() + 3600, '/');
        header('Location: /personal_area/lk.php?er_auth=wrpas&name=' . $login . '');
    }

    $pass = md5($pass);
    $query = pg_query_params($db_connection, 'SELECT id FROM users WHERE username = $1 AND password = $2', array($login, $pass));
    try{
        $res = pg_fetch_object($query);
    } catch(Exception $e) {
        $res = false;
    }
    
    if ($res){
        setcookie('log', 'Да', time() + 3600, '/');
        setcookie('id', $res -> id, time() + 3600, '/');
        unset($_COOKIE['error_lk']);
        setcookie('error_lk', 'yes', -1, '/');
        header('Location: /personal_area/lk.php');
    } else {
        header('Location: /personal_area/lk.php?er_auth=wruser&name=' . $login . '');
    }
    pg_close($db_connection);
?>