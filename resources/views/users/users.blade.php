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

    </style>

@stop

@section('content')
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
                                            <th>آی دی</th>
                                            <th>نام و نام خانوادگی</th>
                                            <th>ایمیل</th>
                                            <th>شماره همراه</th>
                                            <th>شهر</th>
                                            <th>استان</th>
                                            <th>تصویر</th>
                                            <th>سطح دسترسی</th>
                                            <th>وضعیت اکانت</th>
                                            <th>عملیات</th>
                                        </tr>
                                        </thead>
                                    <tbody>

                                        @foreach($users as $user)

                                            <tr>
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->first_name . ' ' . $user->last_name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->phone}}</td>
                                                <td>{{$user->cityName}}</td>
                                                <td>{{$user->stateName}}</td>
                                                <td><img width="50px" src="{{$user->pic}}"></td>
                                                <td>{{$user->level}}</td>
                                                <td id="status_{{$user->id}}" data-status="{{($user->status == 'فعال') ? 1 : 0}}">{{$user->status}}</td>
                                                <td>
                                                    @if($user->level == 'کاربر عادی')
                                                        <a href="{{route('manageAccess', ['userId' => $user->id])}}" class="btn btn-default">فعالیت ها</a>
                                                    @elseif($user->level == 'ادمین')
                                                        <a href="{{route('manageAccess', ['userId' => $user->id])}}" class="btn btn-default">مدیریت سطح دسترسی</a>
                                                    @endif

                                                    @if($user->level != 'ادمین کل')
                                                        <button class="btn btn-danger" onclick="toggleStatus('{{$user->id}}')">تغییر وضعیت اکانت</button>
                                                    @endif
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
                url: '{{route('toggleStatusUser')}}',
                data: {
                    'userId': userId
                }
            });

            var elem = $("#status_" + userId);

            var currStatus = parseInt(elem.attr('data-status'));

            if(currStatus == 1) {
                elem.attr('data-status', 0).html('غیر فعال');
            }
            else {
                elem.attr('data-status', 1).html('فعال');
            }

        }

    </script>
@stop