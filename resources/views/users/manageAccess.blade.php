@extends('layouts.structure')

@section('header')
    <link rel="stylesheet" href="{{URL::asset('css/switch.css')}}">
    @parent

    <style>

        .myLabel {
            padding: 10px;
            min-width: 200px;
            display: inline-block;
        }

    </style>

@stop

@section('content')


    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت دسترسی</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('seo')" type="checkbox" {{($access->seo) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت سئو ها</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('alt')" type="checkbox" {{($access->alt) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span style="direction: rtl" class="myLabel">مدیریت alt ها و تصاویر</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('content')" type="checkbox" {{($access->content) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت محتوای صفحات</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('post')" type="checkbox" {{($access->post) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت پست ها</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('comment')" type="checkbox" {{($access->comment) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت کامنت ها</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('config')" type="checkbox" {{($access->config) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت تنظیمات سیستمی</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('offCode')" type="checkbox" {{($access->offCode) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت کد های تخفیف</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('publicity')" type="checkbox" {{($access->publicity) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت تبلیغات</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeAccess('msg')" type="checkbox" {{($access->msg) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مدیریت پیام رسانی</span>
                    </div>

                </center>
            </div>

        </div>
    </div>

    <div class="col-md-2"></div>
    
    <script>
        
        function changeAccess(val) {

            $.ajax({
                type: 'post',
                url: '{{route('changeAccess')}}',
                data: {
                    'userId': '{{$userId}}',
                    'val': val
                }
            });

        }
        
    </script>

@stop