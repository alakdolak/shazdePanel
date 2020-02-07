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
                            <div class="container-fluid">
                                <div class="row">

                                    <h2>کامنت های مقالات</h2>
                                    <div style="max-height: 80vh; overflow-y: auto">
                                        <table class="table table-striped  table-bordered" dir="rtl">
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
                                                        <span class="butMain" style="background-color: greenyellow" onclick="submitComment('article', {{$item->id}})">
                                                            <i class="fa fa-check"></i>
                                                        </span>
                                                        <span class="butMain" style="background-color: red" onclick="deleteComment('article', {{$item->id}})">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <h2>کامنت های Reveiw ها</h2>

                                    <div style="max-height: 80vh; overflow-y: auto">
                                        <table class="table table-striped  table-bordered" dir="rtl">
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
                                                        <span class="butMain" style="background-color: greenyellow" onclick="submitComment('log', {{$item->id}})">
                                                            <i class="fa fa-check"></i>
                                                        </span>
                                                        <span class="butMain" style="background-color: red" onclick="deleteComment('log', {{$item->id}})">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </span>
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

    <div class="modal fade" id="deleteComment">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="text-align: right; padding-right: 40px; font-size: 20px;">
                    <div class="row">
                        ایا می خواهید کامنت را پاک کنید؟
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center">
                    <form id="deleteForm" method="post" action="{{route('comment.delete')}}">
                        @csrf
                        <input type="hidden" name="id" id="deleteId">
                        <input type="hidden" name="type" id="deleteType">
                        <button type="submit" class="btn btn-danger">بله</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="submitComment">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="text-align: right; padding-right: 40px; font-size: 20px;">
                    <div class="row">
                        ایا می خواهید کامنت را تایید کنید؟
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center">
                    <form id="submitForm" method="post" action="{{route('comment.submit')}}">
                        @csrf
                        <input type="hidden" name="id" id="commentId">
                        <input type="hidden" name="type" id="commentType">
                        <button type="submit" class="btn btn-success">بله</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                </div>

            </div>
        </div>
    </div>


    <script>
        function deleteComment(kind, id){
            $('#deleteType').val(kind);
            $('#deleteId').val(id);
            $('#deleteComment').modal('show');
        }

        function submitComment(kind, id){
            $('#commentType').val(kind);
            $('#commentId').val(id);
            $('#submitComment').modal('show');
        }
    </script>
@stop