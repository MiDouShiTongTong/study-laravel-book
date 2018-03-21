@extends('admin.layouts.master')
@section('content')
<article class="page-container">
    <form class="form form-horizontal" id="form-category-add">
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="" placeholder="" name="name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3"><span class="c-red">*</span>序号：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" name="category_no" value="0">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-3">父类别：</label>
            <div class="formControls col-xs-8 col-sm-9"> <span class="select-box" style="width : 150px;">
			<select class="select" name="parent_id" size="1">
                <option value="0">顶级分类</option>
				@foreach ($categories as $_key => $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
			</select>
			</span> </div>
        </div>
        <div class="row col">
            <label for="" class="form-label col-xs-4 col-sm-3">预览图</label>
            <div class="formControls col-xs-8 col-sm-9" style="padding-bottom: 23px;">
                <img src="/admin/images/icon-add.png" alt="" id="preview-img" onclick="$('#preview').click()" style="cursor: pointer;">
                <input type="file" name="preview" style="display: none;" id="preview" onchange="return uploadImageToServer('preview', 'images', 'preview-img')">
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
            </div>
        </div>
    </form>
</article>
@endsection

@section('js')
    <script src="{{ url('admin/js/uploadFile.js') }}"></script>
    <script>
        $("#form-category-add").validate({
            rules :  {
                name : {
                    required : true,
                    minlength : 4,
                    maxlength : 16
                },
                category_no: {
                    required : true,
                },
                parent_id : {
                    required : true
                }
            },
            onkeyup : false,
            focusCleanup : true,
            success : "valid",
            submitHandler : function(form){
                $('#form-category-add').ajaxSubmit({
                    type: 'post', // 提交方式 get/post
                    url: '/admin/service/category/add', // 需要提交的 url
                    dataType: 'json',
                    data: {
                        preview : 1,
                        preview: ($('#preview-img').attr('src')!='/admin/images/icon-add.png'?$('#preview-img').attr('src'):''),
                        _token: "{{csrf_token()}}"
                    },
                    success: function(data) {
                        if(data == null) {
                            layer.msg('服务端错误', {icon:2, time:2000});
                            return;
                        }
                        if(data.status != 0) {
                            layer.msg(data.message, {icon:2, time:2000});
                            return;
                        }
                        layer.msg(data.message, {icon:1, time:2000});
                        parent.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        layer.msg('ajax error', {icon:2, time:2000});
                    },
                    beforeSend: function(xhr){
                        layer.load(0, {shade: false});
                    }
                });

                return false;
            }
        });
    </script>
@endsection
