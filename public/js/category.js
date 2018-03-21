




$(function () {

    var elem = {
        bk_tooltip : $('.bk_tooltip'),
        categoryChild : $('.child-category')
    };

    var form = {
        categoryParent : $('.category_parent')
    };

    var flag = {
        tooltip : undefined
    };

    var tool = {
        showTooltip : function (text) {
            if (flag.tooltip != undefined) return;
            flag.tooltip = 1;
            elem.bk_tooltip.show().find('span').html(text);
            check.hide_tooltip();
        },
        hide_tooltip : function () {
            setTimeout(function () {
                elem.bk_tooltip.hide().find('span').html('');
                flag.tooltip = undefined;
            }, 2000);
        }
    };

    var event = {
        toCategoryChild : function (parentId) {
            $.ajax({
                url : '/service/category/parent_id/' + parentId,
                type : 'GET',
                data : {},
                dataType : 'json',
                cache : false,
                success : function (data) {
                    if (data.status == 0) {
                        var node = '';
                        $.each(data.categorys, function (index, value) {
                            var next = '/product/category_id/' + value.id;
                            node += '<a class="weui-cell weui-cell_access" href="' + next + '">' +
                                        '<div class="weui-cell__bd">' +
                                            '<p>' + value.name + '</p>' +
                                        '</div>' +
                                        '<div class="weui-cell__ft"></div>' +
                                    '</a>';
                        });
                        elem.categoryChild.html(node);
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
    };

    var initCategoryId = form.categoryParent.val();
    event.toCategoryChild(initCategoryId);
    form.categoryParent.on('change', function () {
        event.toCategoryChild($(this).val());
    });

});