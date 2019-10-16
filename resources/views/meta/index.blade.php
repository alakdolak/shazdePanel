@extends('layouts.structure')

@section('header')
    @parent
    <style>
        .col-xs-12 {
            margin-top: 10px;
        }

        button {
            margin-right: 10px;
        }

        .row {
            direction: rtl;
        }

        .margin_bot {
            margin-bottom: 10px;
        !important;
        }

    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                    <h1>meta اضافه کردن {{isset($name) ? '<'.$name.'>' : ''}}</h1>

                    @if(isset($kind))
                        <button class="btn btn-warning" onclick="document.location.href = '{{route('meta.index')}}' ">بازگشت</button>
                    @endif
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    @if(!isset($kind))
                        <a href="{{URL('meta/index/adab')}}">
                            <button class="btn btn-primary width-auto margin_bot">آداب و رسوم</button>
                        </a>
                        <a href="{{URL('meta/index/amaken')}}">
                            <button class="btn btn-primary width-auto margin_bot">اماکن گردشگری</button>
                        </a>
                        <a href="{{URL('meta/index/concert')}}">
                            <button class="btn btn-primary width-auto margin_bot">کنسرت</button>
                        </a>
                        <a href="{{URL('meta/index/hotel')}}">
                            <button class="btn btn-primary width-auto margin_bot">هتل</button>
                        </a>
                        <a href="{{URL('meta/index/jashnvare')}}">
                            <button class="btn btn-primary width-auto margin_bot">جشنواره</button>
                        </a>
                        <a href="{{URL('meta/index/majara')}}">
                            <button class="btn btn-primary width-auto margin_bot">ماجراجویی</button>
                        </a>
                        <a href="{{URL('meta/index/restaurant')}}">
                            <button class="btn btn-primary width-auto margin_bot">رستوران</button>
                        </a>
                        <a href="{{URL('meta/index/rosoom')}}">
                            <button class="btn btn-primary width-auto margin_bot">رسوم</button>
                        </a>
                    @else
                        <div class="col-md-12" style="margin: 20px 0px; padding-bottom: 20px; border-bottom: solid gray 1px;">
                            <label for="search"> جستجو:</label>
                            <input type="text" id="search" onkeyup="search(this.value)">
                        </div>
                        <div>
                        @foreach($target as $item)
                            <div id="item_{{$item->id}}" class="col-sm-2">
                                <a href="{{URL('meta/edit/' . $kind . '/' . $item->id)}}">
                                    <button class="btn btn-primary width-auto margin_bot">
                                        {{$item->name}}
                                    </button>
                                </a>
                            </div>
                        @endforeach
                        </div>
                    @endif
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        var names = {!! $target !!};

        function search(_value){
            if(_value == ''){
                for(i = 0; i < names.length; i++){
                    document.getElementById('item_' + names[i]['id']).style.display = 'block';
                }
            }
            else{
                for(i = 0; i < names.length; i++){
                    if(names[i]['name'].includes(_value))
                        document.getElementById('item_' + names[i]['id']).style.display = 'block';
                    else
                        document.getElementById('item_' + names[i]['id']).style.display = 'none';
                }
            }
        }
    </script>


@stop