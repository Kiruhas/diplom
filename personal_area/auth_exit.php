<?php
    setcookie('log', '', time() - 3600, '/');
    setcookie('id', '', time() - 3600, '/');
    header('Location: /');
?>