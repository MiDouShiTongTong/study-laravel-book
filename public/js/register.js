$(function () {

    var ele = {
        register : $('.register'),
        code : $('.validate_code'),
        register_form : $('.weui-cells_form'),
        phone_code_send : $('.phone-code-send'),
        bk_tooltip : $('.bk_tooltip')
    };

    var form = {
        register_radio : $('input[name="register_type"]'),
        radio_email : $('#email'),
        radio_phone : $('#phone'),
        phone : $('input[name="phone"]'),
        password_phone : $('input[name="password_phone"]'),
        password_phone_confirm : $('input[name="password_phone_confirm"]'),
        code_phone : $('input[name="code_phone"]'),
        email : $('input[name="email"]'),
        password_email : $('input[name="password_email"]'),
        password_email_confirm : $('input[name="password_email_confirm"]'),
        validate_code : $('input[name="validate_code"]')
    };

    var val = {
        phone : function () {
            return $.trim(form.phone.val());
        },
        password_phone : function () {
            return $.trim(form.password_phone.val());
        },
        password_phone_confirm : function () {
            return $.trim(form.password_phone_confirm.val());
        },
        code_phone : function () {
            return $.trim(form.code_phone.val())
        },
        email : function () {
            return $.trim(form.email.val());
        },
        password_email : function () {
            return $.trim(form.password_email.val());
        },
        password_email_confirm : function () {
            return $.trim(form.password_email_confirm.val());
        },
        validate_code : function () {
            return $.trim(form.validate_code.val())
        }
    };

    var flag = {
        phone : undefined,
        password_phone : undefined,
        password_phone_confirm : undefined,
        code_phone : undefined,
        email : undefined,
        password_email : undefined,
        password_email_confirm : undefined,
        tooltip : undefined,
        phone_code : undefined
    };

    var tool = {
        showTooltip : function (text) {
            if (flag.tooltip != undefined) return;
            flag.tooltip = 1;
            ele.bk_tooltip.show().find('span').html(text);
            tool.hideTooltip();
        },
        hideTooltip : function () {
            setTimeout(function () {
                ele.bk_tooltip.hide().find('span').html('');
                flag.tooltip = undefined;
            }, 2000);
        }
    };


    var check = {

        phone : function () {
            if (val.phone() == '') {
                tool.showTooltip('手机号不能为空');
                flag.phone = 1;
                return false;
            } else if (val.phone().length != 11 || !/^1[34578][0-9]{9}$/.test(val.phone())) {
                tool.showTooltip('手机号格式不正确');
                flag.phone = 1;
                return false;
            } else {
                flag.phone = undefined;
                return true;
            }
        },
        password_phone : function () {
            if (val.password_phone() == '' || val.password_phone().length < 6 || val.password_phone().length > 32) {
                tool.showTooltip('密码不得 小于6位 不得大于20');
                flag.password_phone = 1;
                return false;
            } else {
                flag.password_phone = undefined;
                return true;
            }
        },
        password_phone_confirm : function () {
            if (val.password_phone_confirm() != val.password_phone()) {
                tool.showTooltip('两次输入的密码不一致');
                flag.password_phone_confirm = 1;
                return false;
            } else {
                flag.password_phone_confirm = undefined;
                return true;
            }
        },
        code_phone : function () {
            if (val.code_phone().length != 6) {
                tool.showTooltip('短信验证码不正确');
                flag.code_phone = 1;
                return false;
            } else {
                flag.code_phone = undefined;
                return true;
            }
        },
        email : function () {
            if (val.email().indexOf('@') == -1 || val.email().indexOf('.') == -1) {
                tool.showTooltip('邮箱格式不正确');
                flag.email = 1;
                return false;
            } else {
                flag.email = undefined;
                return true;
            }
        },
        password_email : function () {
            if (val.password_email() == '' || val.password_email().length < 6 || val.password_email().length > 32) {
                tool.showTooltip('密码不得 小于6位 不得大于20位');
                flag.password_email = 1;
                return false;
            } else {
                flag.password_email = undefined;
                return true;
            }
        },
        password_email_confirm : function () {
            if (val.password_email_confirm() != val.password_email()) {
                tool.showTooltip('两次输入的密码不一致');
                flag.password_email_confirm = 1;
                return false;
            } else {
                flag.password_email_confirm = undefined;
                return true;
            }
        },
        validate_code : function () {
            if (val.validate_code() == '') {
                tool.showTooltip('验证码不得为空');
                flag.validate_code = 1;
                return false;
            } else if (val.validate_code().length != 4) {
                tool.showTooltip('验证码格式不正确');
                flag.validate_code = 1;
                return false;
            } else {
                flag.validate_code = undefined;
                return true;
            }
        }
    };

    form.radio_email.next().hide();

    ele.code.click(function () {
        $(this).attr('src', '/service/validate_code/create_code?random' + Math.random());
    });

    form.register_radio.click(function () {
        form.register_radio.attr('checked', false);
        $(this).attr('checked', true);
        if ($(this).attr('id') == 'phone') {
            form.radio_email.next().hide();
            form.radio_phone.next().show();
            ele.register_form.eq(1).hide();
            ele.register_form.eq(0).show();
        } else {
            form.radio_phone.next().hide();
            form.radio_email.next().show();
            ele.register_form.eq(0).hide();
            ele.register_form.eq(1).show();
        }
    });

    ele.phone_code_send.click(function () {
        if (!check.phone() || flag.phone_code != undefined) return;
        flag.phone_code = 1;
        ele.phone_code_send.html('60s 重新发送').css('color', '#696969');
        var num = 60;
        var interval = window.setInterval(function () {
            ele.phone_code_send.html(--num + 's 重新发送');
            if (num == 0) {
                flag.phone_code = undefined;
                window.clearInterval(interval);
                ele.phone_code_send.html('重新发送').css('color', '#3cc51f');
            }
        }, 1000);
        // 请求短信接口
        $.ajax({
            url : '/service/validate_phone/send_sms',
            type : 'POST',
            dataType : 'json',
            data : {
                _token : $('input[name="_token"]').val(),
                phone : val.phone()
            },
            success : function (data) {
                if (data.status == 0) {
                    tool.showTooltip(data.message);
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

    ele.register.click(function () {
        $.each(form.register_radio, function () {
            if ($(this).attr('checked') == 'checked') {
                var check_type = $(this).attr('id');
                if (check_type == 'phone') {
                    if (!check.phone()) return false;
                    if (!check.password_phone()) return false;
                    if (!check.password_phone_confirm()) return false;
                    if (!check.code_phone()) return false;
                    $.ajax({
                        url : '/service/register',
                        type : 'POST',
                        dataType : 'json',
                        data : {
                            register_type : 'phone',
                            _token : $('input[name="_token"]').val(),
                            phone : val.phone(),
                            password : val.password_phone(),
                            password_confirmation : val.password_phone_confirm(),
                            phone_code : val.code_phone()
                        },
                        success : function (data) {
                            if (data.status == 0) {
                                tool.showTooltip('注册成功');
                                location.href = '/login';
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
                } else if (check_type == 'email') {
                    if (!check.email()) return false;
                    if (!check.password_email()) return false;
                    if (!check.password_email_confirm()) return false;
                    if (!check.validate_code()) return false;
                    $.ajax({
                        url : '/service/register',
                        type : 'POST',
                        dataType : 'json',
                        data : {
                            register_type : 'email',
                            _token : $('input[name="_token"]').val(),
                            email : val.email(),
                            password : val.password_email(),
                            password_confirmation : val.password_email_confirm(),
                            validate_code : val.validate_code()
                        },
                        success : function (data) {
                            if (data.status == 0) {
                                tool.showTooltip('注册成功');
                                location.href = '/login';
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
                }
            }
        });
    });


});