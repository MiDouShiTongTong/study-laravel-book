@extends('layouts.master')

@section('title', '书籍列表')

@section('content')
    <div class="weui-cells__title">带图标、说明、跳转的列表项</div>
    <div class="weui-cells">

        @foreach ($products as $_key => $_value)
            <a class="weui-cell weui-cell_access" href="{{ url('/product/'.$_value->id) }}">
                <div class="weui-cell__hd"><img class="bk_preview" src="{{ $_value->preview }}" alt="{{ $_value->name }}"></div>
                <div class="weui-cell__bd">
                    <div>
                        <span class="bk_title">{{ $_value->name }}</span>
                        <span class="bk_price pull-right">{{ $_value->price }}</span>
                    </div>
                    <p class="bk_summary">{{ $_value->summary }}</p>
                </div>
                <div class="weui-cell__ft"></div>
            </a>
         @endforeach

    </div>
@endsection

@section('js')

@endsection