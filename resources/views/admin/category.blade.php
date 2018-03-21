@extends('admin.layouts.master')

@section('content')
    <div class="page-container">
        <div class="cl pd-5 bg-1 bk-gray mt-20">
            <span class="l">
                <a href="javascript:;" onclick="categoryAdd('添加类别', '/admin/category/add')" class="btn btn-primary radius">
                    <i class="Hui-iconfont">&#xe600;</i> 添加类别
                </a>
            </span>
            <span class="r">共有数据：<strong>{{ count($categories) }}</strong> 条</span>
        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-hover table-bg table-sort">
                <thead>
                <tr class="text-c">
                    <th width="25"><input type="checkbox" name="" value=""></th>
                    <th width="80">ID</th>
                    <th width="100">名称</th>
                    <th width="40">编号</th>
                    <th width="90">父级分类</th>
                    <th width="90">预览图</th>
                    <th width="100">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $_key => $category)
                    <tr class="text-c">
                        <td><input type="checkbox" value="{{ $category->id }}" name=""></td>
                        <td>{{ $category->id }}</td>
                        <td>
                            <u style="cursor:pointer" class="text-primary" onclick="member_show('张三','member-show.html','10001','360','400')">
                                {{ $category->name }}
                            </u>
                        </td>
                        <td>{{ $category->parent_id }}</td>
                        <td>
                            @if (!empty($category->parent_id))
                                {{ $category->parent->name }}
                            @else
                                顶级分类
                            @endif
                        </td>
                        <td style="width: 50px;height: 50px;">
                            @if (!empty($category->preview))
                                <img src="{{ $category->preview }}" alt="" style="max-width: 100%;max-height: 100%;">
                            @endif
                        </td>
                        <td class="td-manage">
                            <a title="编辑" href="javascript:;" onclick="categoryEdit('修改类别','/admin/category/edit/{{ $category->id }}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
                            <a title="删除" href="javascript:;" onclick="categoryDel('{{ $category->name }}', '{{ $category->id }}')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
       function categoryAdd(title, url) {
           var index = layer.open({
               type: 2,
               title: title,
               content: url
           });
           layer.full(index);
       }
       function categoryEdit(title, url) {
           var index = layer.open({
               type: 2,
               title: title,
               content: url
           });
           layer.full(index);
       }
       function categoryDel(title, id) {
          layer.confirm('确认删除' + title + '吗？',function(index){
              $.ajax({
                  url : '/admin/service/category/del',
                  type : 'POST',
                  dataType : 'json',
                  data : {
                      id : id,
                      _token : "{{ csrf_token() }}"
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
                      location.reload();
                  },
                  error: function(xhr, status, error) {
                      console.log(xhr);
                      console.log(status);
                      console.log(error);
                      layer.msg('ajax error', {icon:2, time:2000});
                  },
                  beforeSend: function(xhr){
                      layer.load(0, {
                          shade : false
                      });
                  }
              });
          });
       }
    </script>
@endsection