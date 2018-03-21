@extends('layouts.master')

@section('title', '书籍分类')

@section('content')
    <div class="weui-cells__title">选择书籍类别</div>
    <div class="weui-cells weui-cell_select">
        <div class="weui-cell weui-cell_select">
            <div class="weui-cell__bd">
                <select class="weui-select category_parent" name="category" style="">
                    @foreach ($categorys as $_key => $_value)
                        <option value="{{ $_value->id }}">{{ $_value->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="weui-cells child-category">

    </div>
@endsection

@section('js')
    <script src="{{ url('js/category.js') }}"></script>
@endsection