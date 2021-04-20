<?


if ($_COOKIE['products'])
    $cook_val = unserialize($_COOKIE['products']);

if ($cook_val[$_POST['product_id']]) 
    $cook_val[$_POST['product_id']]['amount'] += intval($_POST['product_amount']);
else {
    $cook_val[$_POST['product_id']]['amount'] = intval($_POST['product_amount']);
    $cook_val[$_POST['product_id']]['name'] = $_POST['product_name'];
    $cook_val[$_POST['product_id']]['size'] = $_POST['product_size'];
    $cook_val[$_POST['product_id']]['color'] = $_POST['product_color'];
    $cook_val[$_POST['product_id']]['weight'] = intval($_POST['product_weight']);
    if ($_POST['product_img'] !== '')
        $cook_val[$_POST['product_id']]['img'] = $_POST['product_img'];
    else
        $cook_val[$_POST['product_id']]['img'] = "images/no_image.png";
}

setcookie('products', serialize($cook_val), time() + 3600, '/');




