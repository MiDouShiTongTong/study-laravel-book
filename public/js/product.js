$(function () {
    // 商品轮播js
    var bullets = document.getElementById('position').getElementsByTagName('li');
    Swipe(document.getElementById('mySwipe'), {
        startSlide: 0,
        auto: 3000,
        continuous: true,
        disableScroll: true,
        stopPropagation: true,
        callback: function(pos) {
            var i = bullets.length;
            while (i--) {
                bullets[i].className = '';
            }
            bullets[pos].className = 'cur';
        },
        transitionEnd: function(index, element) {}
    });

    var elem = {
        'addCart' : $('.add-cart'),
        'toCart' : $('.to-cart'),
        'cart_num' : $('#cart_num'),
        'bk_tooltip' : $('.bk_tooltip')
    };
    
    var form = {
        'product_id' : $('input[name="product_id"]')
    };
    
    var val = {
        'product_id' : function () {
            return $.trim(form.product_id.val())
        }
    };

    var flag = {
       'tooltip' : undefined
    };

    var tool = {
        showTooltip : function (text) {
            if (flag.tooltip != undefined) return;
            flag.tooltip = 1;
            elem.bk_tooltip.show().find('span').html(text);
            check.hideTooltip();
        },
        hideTooltip : function () {
            setTimeout(function () {
                elem.bk_tooltip.hide().find('span').html('');
                flag.tooltip = undefined;
            }, 2000);
        }
    };

    var event = {
        addCart : function () {
            $.ajax({
                url : '/service/cart/add/' + val.product_id(),
                type : 'GET',
                dataType : 'json',
                cache : false,
                success : function (data) {
                    if (data.status == 0) {
                        // 购物车数量加一
                        var cart_elem = elem.cart_num;
                        var cart_num = elem.cart_num.html();
                        if (cart_num == '') cart_elem.html(0);
                        cart_elem.html(Number(cart_num) + 1);
                    } else {
                        tool.showTooltip(data.message);
                    }
                },
                error : function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        },
        toCart : function () {
            location.href = '/cart';
        }
    };

    // 购物车
    elem.addCart.click(event.addCart);
    elem.toCart.click(event.toCart);

});