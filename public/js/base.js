$(function() {

    var elem = {
        bk_tooltip : $('.bk_tooltip')
    };

    var flag = {
        tooltip : undefined
    };

    var tool = {
        showTooltip: function (text) {
            if (flag.tooltip != undefined) return;
            flag.tooltip = 1;
            elem.bk_tooltip.show().find('span').html(text);
            tool.hideTooltip();
        },
        hideTooltip: function () {
            setTimeout(function () {
                elem.bk_tooltip.hide().find('span').html('');
                flag.tooltip = undefined;
            }, 2000);
        }
    };

    // 设置页面标题
    $('#bk_title').html(document.title);

    // 返回上个url
    var history_btn = $('#history');
    history_btn.on('click', function () {
        history.go(-1);
    });

    // 右上角菜单事件
    var $menuActionsheet = $('#menuActionsheet');
    var $menuMask = $('#menuMask');
    function hideMenuActionsheet() {
        $menuActionsheet.removeClass('weui-actionsheet_toggle');
        $menuMask.fadeOut(200);
    }
    $menuMask.on('click', hideMenuActionsheet);
    $('#menuActionsheetCancel').on('click', hideMenuActionsheet);
    $("#show-menu").on("click", function(){
        $menuActionsheet.addClass('weui-actionsheet_toggle');
        $menuMask.fadeIn(200);
    });

    // 菜单点击路由
    var weui_actionsheet__cell = $('.weui-actionsheet__cell');
    $.each(weui_actionsheet__cell, function (index, value) {
        var _this = $(this);
        _this.click(function () {
            switch (index) {
                case 0 :
                    tool.showTooltip('敬请期待');
                    break;
                case 1 :
                    location.href = '/category';
                    break;
                case 2 :
                    location.href = '/cart';
                    break;
                case 3 :
                    location.href = '/order_list';
                    break;
            }
        });
    });

    // 水波纹特效
    var addRippleEffect = function (e) {
        var target = e.target;
        var rect = target.getBoundingClientRect();
        var ripple = target.querySelector('.ripple');
        if (!ripple) {
            ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.height = ripple.style.width = Math.max(rect.width, rect.height) + 'px';
            target.appendChild(ripple);
        }
        ripple.classList.remove('show');
        var top = e.pageY - rect.top - ripple.offsetHeight / 2 - document.body.scrollTop;
        var left = e.pageX - rect.left - ripple.offsetWidth / 2 - document.body.scrollLeft;
        ripple.style.top = top + 'px';
        ripple.style.left = left + 'px';
        ripple.classList.add('show');
    }
    $('.glyphicon, .phone-code-send, .pdt-btn ').on('click', addRippleEffect);

});