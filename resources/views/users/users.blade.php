@extends('layouts.structure')

@section('header')
    @parent

    <style>

        table {
            direction: rtl;
        }

        th {
            text-align: right !important;
        }

        .fixed-table-body {
            overflow-y: hidden;
        }

        .fixed-table-pagination {
            margin-top: 15px;
        }

        .userPic{
            border-radius: 50%;
            overflow: hidden;
            width: 35px;
            height: 35px;
        }
        .userPic img{
            width: 100%;
        }
        .status{
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 50% auto;
        }
        .status.green{
            background: green;
        }
        .status.red{
            background: red;
        }

    </style>

@stop

@section('content')

    @include('layouts.modal')

    <div class="static-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline9-list sparkel-pro-mg-t-30 shadow-reset">
                        <div class="sparkline9-hd">
                            <div style="direction: rtl" class="main-sparkline9-hd">
                                <h1>کاربران</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th>وضعیت</th>
                                            <th>#</th>
                                            <th>نام و نام خانوادگی</th>
                                            <th>نام کاربری</th>
                                            <th>ایمیل</th>
                                            <th>شماره همراه</th>
                                            <th>سطح دسترسی</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                    <tbody>

                                        @foreach($users as $user)

                                            <tr>
                                                <td>
                                                    <div id="status_{{$user->id}}" data-status="{{$user->status}}" class="status {{$user->status == 1 ? 'green' : 'red'}}"></div>
                                                </td>
                                                <td>
                                                    <div class="userPic">
                                                        <img src="{{$user->pic}}">
                                                    </div>
                                                </td>
                                                <td>{{$user->first_name . ' ' . $user->last_name}}</td>
                                                <td>{{$user->username}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->phone}}</td>
                                                <td>{{$user->level}}</td>
                                                <td>
                                                    <a href="{{route('manageAccess', ['userId' => $user->id])}}" class="btn btn-default">مدیریت سطح دسترسی</a>
                                                    @if($user->level != 'ادمین کل')
                                                        <button class="btn btn-danger" onclick="toggleStatus('{{$user->id}}')">تغییر وضعیت اکانت</button>
                                                    @endif
                                                    <button onclick="createAjaxModal('{{route('changePass')}}', [{'name': 'password', 'class': [], 'type': 'password', 'label': 'رمز جدید', 'value': ''}, {'name': 'confirmPass', 'class': [], 'type': 'password', 'label': 'تکرار رمزعبور جدید', 'value': ''}, {'name': 'userId', 'class': ['hidden'], 'type': 'hidden', 'label': '', 'value': '{{$user->id}}'}], 'تغییر رمزعبور', '')" style="display: block; margin: 7px" class="btn btn-warning" data-toggle="modal" data-target="#InformationproModalalertAjax">تغییر رمزعبور</button>
                                                </td>
                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function toggleStatus(userId) {

            $.ajax({
                type: 'post',
                url: '{{route('user.toggleStatus')}}',
                data: {
                    'userId': userId
                }
            });

            var elem = $("#status_" + userId);

            var currStatus = parseInt(elem.attr('data-status'));

            if(currStatus == 1)
                elem.attr('data-status', 0).addClass('red').removeClass('green');
            else
                elem.attr('data-status', 1).addClass('green').removeClass('red');

        }

    </script>
@stop
