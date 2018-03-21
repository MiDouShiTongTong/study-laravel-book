$(function () {

    var elem = {
        login : $('.login'),
        bk_tooltip : $('.bk_tooltip'),
        code : $('.validate_code')
    };

    var form = {
        name : $('input[name="name"]'),
        password : $('input[name="password"]'),
        validateCode : $('input[name="validate_code"]'),
        returnUrl : $('input[name="return-url"]')
    };

    var val = {
        name : function () {
            return $.trim(form.name.val())
        },
        password : function () {
            return $.trim(form.password.val())
        },
        validateCode : function () {
            return $.trim(form.validateCode.val())
        },
        returnUrl : function () {
            return $.trim(form.returnUrl.val())
        }
    };

    var flag = {
        tooltip : undefined,
        name : undefined,
        password : undefined,
        validateCode : undefined
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

    var check = {
        name : function () {
            var name = val.name();
            if (name == '') {
                tool.showTooltip('用户名不得为空');
                flag.name = 1;
                return false;
            } else {
                flag.name = undefined;
                return true;
            }
        },
        password : function () {
            var password = val.password();
            if (password == '') {
                tool.showTooltip('密码不得为空');
                flag.password = 1;
            } else if (password.length < 6 || password.length > 20) {
                tool.showTooltip('密码在6~20位之间');
                flag.password = 1;
                return false;
            } else {
                flag.password = undefined;
                return true;
            }
        },
        validateCode : function () {
            var validateCode = val.validateCode();
            if (validateCode == '') {
                tool.showTooltip('验证码不得为空');
                flag.validateCode = 1;
                return false;
            } else if (validateCode.length != 4) {
                tool.showTooltip('验证码必须是4位');
                flag.validateCode = 1;
                return false;
            } else {
                flag.validateCode = undefined;
                return true;
            }
        }
    };

    elem.code.click(function () {
        $(this).attr('src', '/service/validate_code/create_code?random' + Math.random());
    });

    elem.login.click(function () {
        if (!check.name()) return;
        if (!check.password()) return;
        if (!check.validateCode()) return;
        $.ajax({
            url : 'service/login',
            type : 'POST',
            dataType : 'json',
            data : {
                _token : $('input[name="_token"]').val(),
                username : val.name(),
                password : val.password(),
                validate_code : val.validateCode()
            },
            success : function (data) {
                if (data.status == 0) {
                    tool.showTooltip('账户登陆成功');
                    if (val.returnUrl() != '') {
                        location.href = val.returnUrl();
                    } else {
                        location.href = '/category';
                    }
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
    });

});