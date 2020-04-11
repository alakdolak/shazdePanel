@extends('layouts.structure')

@section('header')
    @parent
    <style>
        th, td{
            text-align: right;
        }
        .butMain{
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            border-radius: 8px;
            align-items: center;
            cursor: pointer;
            color: white;
        }
        .nav-tabs>li{
            float: right;
        }
    </style>
@stop

@section('content')

    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div style="direction: rtl" class="main-sparkline13-hd">
                                <div class="sparkline13-outline-icon">
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <ul class="nav nav-tabs">
                                <li class="nav-item active">
                                    <a class="nav-link active" href="#home">
                                        <span id="newCommentCount"  class="label label-success">
                                            {{count($nLogsComment) + count($nPostComment)}}
                                        </span>
                                        کامنت های جدید
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#menu1">
                                        کامنت های تایید شده
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content border mb-3">
                                <div id="home" class="container tab-pane active">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">

                                            <h2>کامنت های مقالات</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>مقاله</th>
                                                        <th>متن کامنت</th>
                                                        <th>تاریخ </th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($nPostComment as $item)
                                                        <tr>
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <a href="http://koochita.com/article/{{$item->post}}" target="_blank">
                                                                    {{$item->post}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$item->msg}}
                                                            </td>
                                                            <td>
                                                                {{$item->date}}
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div style="display: flex; justify-content: space-evenly;   ">
                                                                    <span class="butMain" style="background-color: greenyellow" onclick="submitComment('article', {{$item->id}}, this)">
                                                                        <i class="fa fa-check"></i>
                                                                    </span>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteComment('article', {{$item->id}}, this)">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row">
                                            <h2>کامنت های پست ها</h2>

                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>محل</th>
                                                        <th>متن کامنت</th>
                                                        <th>تاریخ </th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($nLogsComment as $item)
                                                        <tr>
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <a href="{{$item->url}}" target="_blank">
                                                                    {{$item->kindName}} - {{$item->place}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$item->text}}
                                                            </td>
                                                            <td>
                                                                {{$item->date}}
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div style="display: flex; justify-content: space-evenly;">
                                                                    <span class="butMain" style="background-color: greenyellow" onclick="submitComment('log', {{$item->id}}, this)">
                                                                        <i class="fa fa-check"></i>
                                                                    </span>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteComment('log', {{$item->id}}, this)">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="container tab-pane fade">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <h2>کامنت های تایید شده مقالات</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>مقاله</th>
                                                        <th>متن کامنت</th>
                                                        <th>تاریخ </th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($postComment as $item)
                                                        <tr>
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <a href="http://koochita.com/article/{{$item->post}}" target="_blank">
                                                                    {{$item->post}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$item->msg}}
                                                            </td>
                                                            <td>
                                                                {{$item->date}}
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteComment('article', {{$item->id}}, this)">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <h2>کامنت های تایید شده ی پست ها</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>محل</th>
                                                        <th>متن کامنت</th>
                                                        <th>تاریخ </th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($logsComment as $item)
                                                        <tr>
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <a href="{{$item->url}}" target="_blank">
                                                                    {{$item->kindName}} - {{$item->place}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$item->text}}
                                                            </td>
                                                            <td>
                                                                {{$item->date}}
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteComment('log', {{$item->id}}, this)">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
                                                                </div>
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
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){
            $(".nav-tabs a").click(function(){
                $(this).tab('show');
            });
            $('.nav-tabs a').on('shown.bs.tab', function(event){
                var x = $(event.target).text();         // active tab
                var y = $(event.relatedTarget).text();  // previous tab
                $(".act span").text(x);
                $(".prev span").text(y);
            });
        });

        function deleteComment(kind, id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("comments.delete")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                    kind: kind
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            $(_element).parent().parent().parent().remove();
                            $('#newCommentCount').text(response['result']);
                        }
                    }
                    catch (e) {
                        alert('error 1')
                    }
                },
                error: function(error){
                    alert('error 2')
                }
            })
        }

        function submitComment(kind, id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("comments.submit")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                    kind: kind
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok') {
                            $(_element).parent().parent().parent().remove();
                            $('#newCommentCount').text(response['result']);
                        }
                    }
                    catch (e) {
                        alert('error 1')
                    }
                },
                error: function(error){
                    alert('error 2')
                }
            })
        }
    </script>
@stop
