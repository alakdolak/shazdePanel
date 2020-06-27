@extends('layouts.structure')

@section('header')
    @parent

    <style>

        td {
            padding: 7px;
            border: 2px solid;
        }

    </style>

@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-2"></div>

    <div class="col-md-8" style="direction: rtl">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>بک آپ ها</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    @if(count($backups) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">بک آپی وجود ندارد</h4>
                        </div>
                    @else

                        <table>
                            <tr>
                                <td><center>نوع بک آپ</center></td>
                                <td><center>url داده شده</center></td>
                                <td><center>نام کاربری</center></td>
                                <td><center>رمز عبور</center></td>
                                <td><center>دوره دریافت</center></td>
                                <td><center>عملیات</center></td>
                            </tr>
                            @foreach($backups as $itr)
                                <tr id="row_{{$itr->id}}">
                                    <td><center>{{$itr->kind}}</center></td>
                                    <td><center>{{$itr->url}}</center></td>
                                    <td><center>{{$itr->username}}</center></td>
                                    <td><center>{{$itr->password}}</center></td>
                                    <td><center>{{$itr->interval}}</center></td>
                                    <td><center><span style="cursor: pointer" onclick="deleteBackup('{{$itr->id}}')" class="glyphicon glyphicon-trash"></span></center></td>
                                </tr>
                            @endforeach
                        </table>

                    @endif

                    <div class="col-xs-12">
                        <button id="addBtn" onclick="addBackup()" class="btn btn-default transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>

                    <a target="_blank" href="{{route('manualBackup')}}" style="color: white; margin: 20px" class="btn btn-danger">دریافت بک آپ دستی از دیتابیس</a>
                    <span onclick="getBackUp()" style="color: white; margin: 20px" class="btn btn-danger">دریافت بک آپ دستی از تصاویر</span>

                    <div id="percentDiv" class="hidden">
                        <p>در حال ایجاد بک آپ</p>
                        <p id="percent"></p>
                        <div style="margin-top: 20px; text-align: center" id="links">
                            <p>لینک های دانلود</p>
                        </div>
                    </div>

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        var percent = 0;
        var timer;

        function deleteBackup(id) {

            $.ajax({
                type: 'post',
                url: '{{route('removeBackup')}}',
                data: {
                    'id': id
                }
            });

            $("#row_" + id).remove();
        }

        function getBackUp() {

            $("#percentDiv").removeClass('hidden');
            $("#percent").empty().append(percent + "%");

            $.ajax({
                type: 'post',
                url: '{{route('initialImageBackUp')}}',
                success: function () {

                    timer = setTimeout(getDonePercentage, 5000);

                    $.ajax({
                        type: 'post',
                        url: '{{route('imageBackup')}}'
                    });
                }
            });
        }

        var counter = 1;

        function getDonePercentage() {

            $.ajax({
                type: 'post',
                url: '{{route('getDonePercentage')}}',
                success: function (response) {

                    response = JSON.parse(response);
                    percent = parseFloat(response.percent);
                    if(Number.isNaN(percent))
                        return;
                    $("#percent").empty().append(percent + "%");
                    var url = response.url;

                    if(url != "") {
                        $("#links").append('<a style="display: block" href="' + response.url + '" download>لینک ' + (counter++) + '</a>');
                    }
                    if(percent != 100)
                        timer = setTimeout(getDonePercentage, 5000);
                }
            });
        }
        
        function addBackup() {
            createModal('{{route('addBackup')}}',
                    [
                        {'name': 'url', 'class': [], 'type': 'text', 'label': 'آدرس مقصد', 'value': ''},
                        {'name': 'username', 'class': [], 'type': 'text', 'label': 'نام کاربری', 'value': ''},
                        {'name': 'password', 'class': [], 'type': 'password', 'label': 'رمزعبور', 'value': ''},
			{'name': 'mysql', 'class': [], 'type': 'checkbox', 'label': 'آیا بک اپ از دیتابیس است؟'},
                        {'name': 'interval', 'value': '', 'class': [], 'type': 'select', 'label': 'وضعیت جدید', 'options': '{!! json_encode($intervals) !!}'}
                    ],
                    'افزودن بک آپ جدید', '{{(isset($msg) ? $msg : '')}}'
            );
        }

    </script>

@stop
