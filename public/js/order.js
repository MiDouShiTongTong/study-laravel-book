$(function () {

    var elem = {
        commit_order : $('.commit-order'),
        order_commit : $('.order-commit')
    };

    var form = {
        payway : $('.payway'),
        payway_type : $('input[name="payway_type"]'),
        product_id : $('input[name="product_id"]'),
        product_ids : $('input[name="product_ids"]')
    };

    var flag = {
        tooltip : undefined
    };

    // event
    var event = {
        commit_order : function () {
            var payway = form.payway.val();
            var product_ids_arr = [];
            $.each(form.product_id, function (index, value) {
                var _this = $(this);
                product_ids_arr.push(_this.val());
            });

            form.product_ids.val(product_ids_arr.join(','));
            form.payway_type.val(payway);
            elem.order_commit.submit();
        }
    };


    elem.commit_order.click(event.commit_order);

});