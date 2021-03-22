<?

if ($_COOKIE['products'])
    $cook_val = unserialize($_COOKIE['products']);

if ($cook_val[$_POST['product_id']]) {
    $palets = intval($_POST['all_palets']) - $cook_val[$_POST['product_id']]['palets'];
    unset($cook_val[$_POST['product_id']]);
    setcookie('products', serialize($cook_val), time() + 3600, '/');
    echo $palets;
}
    

    
