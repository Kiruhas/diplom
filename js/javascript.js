var auth_form = document.getElementById('auth_form');

var lk_error_auth = document.getElementById('lk_error_auth');

if (lk_error_auth){
    auth_form.classList.add('active');
} else if (auth_form) {
    auth_form.classList.remove('active');
}

function removediv() {
    if (lk_error_auth) lk_error_auth.remove();
    history.pushState(null, null, document.URL.split('?')[0]);
}

$('.add_product').on('click', function(){
    var id = $(this).attr('id');
    var elem_amount = $(this).parent().siblings('.amount').find('.product_amount');
    var amount = elem_amount.val();
    var name = $(this).parent().siblings('#product_name').data('attr');
    var size = $(this).parent().siblings('#product_size').data('attr');
    var color = $(this).parent().siblings('#product_color').data('attr');
    var weight = $(this).parent().siblings('#product_weight').data('attr');
    var img = $(this).parent().siblings('#product_img').data('attr');
    $.ajax({
        url: "../blocks/ajax/addToCart.php",
        type: "post",
        data: {
            product_id: id,
            product_amount: amount,
            product_name: name,
            product_size: size,
            product_color: color,
            product_weight: weight,
            product_img: img,
        }
    }).done(function(result){
        console.log(result);
        elem_amount.val(elem_amount.attr('min'));
    });
});

$('.minus_prod').on('click', function(){
    var value = parseInt($(this).next('.product_amount').val());
    if (value > $(this).next('.product_amount').attr('min'))
        $(this).next('.product_amount').val(value - 1);
})

$('.plus_prod').on('click', function(){
    var value = parseInt($(this).prev('.product_amount').val());
    if (value < 1000)
        $(this).prev('.product_amount').val(value + 1);
})

$('.delete_prod').on('click', function(){
    var id = $(this).data('id');
    var btn = $(this);
    $.ajax({
        url: "../blocks/ajax/deleteFromBasket.php",
        type: "post",
        data: {
            product_id: id,
            all_palets: $(".count_palets").text(),
        }
    }).done(function(result){
        $(".count_palets").text(result);
        btn.before("<span>Удалено</span>");
        btn.remove();
    });
});

$('.end_order').on('click', function(){
    var id = $(this).data('id');
    var btn = $(this);
    $.ajax({
        url: "../blocks/ajax/endOrder.php",
        type: "post",
        data: {
            order_id: id,
        }
    }).done(function(result){
        btn.before("<span>Завершен</span>");
        btn.remove();
    });
});

$('.confirm_order').on('click', function(){
    var id = $(this).data('id');
    var btn = $(this);
    $.ajax({
        url: "../blocks/ajax/confirmOrder.php",
        type: "post",
        data: {
            order_id: id,
        }
    }).done(function(result){
        console.log(result);
        btn.before("<span>Подтвержден</span>");
        btn.remove();
    });
});

function scrollToElement(ele) {
    $(window).scrollTop(ele.offset().top).scrollLeft(ele.offset().left);
}

$(document).ready(function () {
    var $scroll_elem = $(".scroll");
    if ($scroll_elem) {
        $('html,body').animate({
            scrollTop: $(".scroll").offset().top - $(window).height()/2
         }, 1000);
    }
});

$('.confirm_palet').on('click', function(){
    var id = $(this).data('id');
    var order = $(this).data('order');
    var btn = $(this);
    $.ajax({
        url: "../blocks/ajax/confirmPalet.php",
        type: "post",
        data: {
            palet_id: id,
            order_id: order,
        }
    }).done(function(result){
        console.log(result);
        btn.before("<span>Подтвержден</span>");
        btn.remove();
    });
});





