$(function () {

    var elem = {
        chargeCart : $('.charge-cart'),
        deleteCart : $('.delete-cart'),
        bk_tooltip : $('.bk_tooltip'),
        order_commit : $('.order_commit')
    };

    var form = {
        productIds : $('input[name="product-ids"]'),
        cartItem : $('input[name="cart-item"]')
    };

    var flag = {
        tooltip : undefined,
        delete : undefined
    };

    var tool = {
        showTooltip : function (text) {
            if (flag.tooltip != undefined) return;
            flag.tooltip = 1;
            elem.bk_tooltip.show().find('span').html(text);
            tool.hideTooltip();
        },
        hideTooltip : function () {
            setTimeout(function () {
                elem.bk_tooltip.hide().find('span').html('');
                flag.tooltip = undefined;
            }, 2000);
        }
    };

    var event = {
        chargeCart : function () {
            var productIdsArr = [];
            $.each(form.cartItem, function (index, value) {
                var _this = $(this);
                if (_this.is(':checked')) {
                    productIdsArr.push(_this.val());
                }
            });
            if (productIdsArr == '') {
                tool.showTooltip('请选择结算商品');
                return false;
            }

            form.productIds.val(productIdsArr.join(','));
            elem.order_commit.submit();
        },
        deleteCart : function () {
            if (flag.delete != undefined) return;
            var productIdsArr = [];
            $.each(form.cartItem, function (index, value) {
                var _this = $(this);
                if (_this.is(':checked')) {
                    productIdsArr.push(_this.val());
                    var label = _this.parent().parent();
                    label.css({
                        'transition' : 'all 0.6s',
                        'transform' : 'translate3d(-100%, 0, 0)'
                    });
                    setTimeout(function () {
                        label.remove();
                    }, 600);
                }
            });
            if (productIdsArr == '') {
                tool.showTooltip('请选择删除的商品');
                return false;
            }
            // 删除cookie
            $.ajax({
                url : '/service/cart/delete',
                type : 'GET',
                dataType : 'json',
                data : {
                    product_ids : productIdsArr.join(',')
                },
                cache : false,
                beforeSend : function () {
                    flag.delete = 1;
                },
                success : function (data) {
                    if (data.status == 0) {
                        flag.delete = undefined;
                    } else {
                        tool.showTooltip('删除失败，请重试');
                    }
                },
                error : function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        }
    };

    // 购物车
    elem.chargeCart.click(event.chargeCart);
    elem.deleteCart.click(event.deleteCart);

});