<? 
function checkFill ($product_size, $product_count, $palet_size, $palets_amount, $palet_with_border) {
    $palet = explode('x', $palet_size); // Получаем размеры палета ДхШхВ
    $palet_border = explode('x', $palet_with_border); // Получаем размеры палета ДхШхВ с обрешеткой
    $product = explode('x', $product_size); // Получаем размеры вещи ДхШхВ

    $height = floor($palet[2] / $product[2]); // Сколько влезет в одну колонну в высоту
    $length = (floor($palet[1] / $product[1])) * $height; // Сколько влезет в одну шеренгу во всю высоту
    $productsOnPalet = (floor($palet[0] / $product[0])) * $length; // Сколько влезет на весь палет
    if ($palet[2] == $palet_border[2]){
        $palets_amount += 1; // Считаем количество палетов
    }
    if ($productsOnPalet < $product_count) {
        // Значит не хватает
        $residue = $product_count - $productsOnPalet; // Остаток, который не влез на палет
        return checkFill($product_size, $residue, $palet_with_border, $palets_amount, $palet_with_border); // Рекурсия - берем еще палет
    } else {
        // Нормально, считаем 
        $percentFill = ceil($product_count / ($productsOnPalet / 100)); // Процент заполненности
        while ($percentFill % $product[2]) { // Пока процент не делится на высоту без остатка
            $percentFill += 1;
        }
        return [$percentFill, $palets_amount, $product_count]; // Возвращаем [процент заполнения последнего палета, количество палетов]
    }
}