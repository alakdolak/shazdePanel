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
                                            {{count($newComments)}}
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
                                <div id="home" class="container-fluid tab-pane active">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <h2>کامنت های تایید نشده</h2>

                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>نام ویدیو</th>
                                                        <th>متن کامنت</th>
                                                        <th>در جواب به</th>
                                                        <th>تاریخ</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($newComments as $item)
                                                        <tr id="row_{{$item->id}}">
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <a href="{{$item->videoUrl}}" target="_blank">
                                                                    {{$item->videoTitle}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$item->text}}
                                                            </td>
                                                            <td>
                                                                {{$item->ansTo}}
                                                            </td>
                                                            <td>
                                                                {{$item->time}}
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div style="display: flex; justify-content: space-evenly;">
                                                                    <span class="butMain" style="background-color: greenyellow" onclick="submitComment({{$item->id}}, this)">
                                                                        <i class="fa fa-check"></i>
                                                                    </span>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteComment({{$item->id}}, this)">
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

                                <div id="menu1" class="container-fluid tab-pane fade">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <h2>کامنت های تایید شده</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>نام ویدیو</th>
                                                        <th>متن کامنت</th>
                                                        <th>در جواب به</th>
                                                        <th>Like</th>
                                                        <th>dis like</th>
                                                        <th>پاسخ ها</th>
                                                        <th>تاریخ</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($confirmComments as $item)
                                                            <tr id="row_{{$item->id}}">
                                                                <td>{{$item->username}}</td>
                                                                <td>
                                                                    <a href="{{$item->videoUrl}}" target="_blank">
                                                                        {{$item->videoTitle}}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-primary" onclick="openCommentText({{$item->id}}, 'text')">متن</button>
                                                                </td>
                                                                <td>
                                                                    @if(isset($item->ansTo) && $item->ansTo != '')
                                                                        <button class="btn btn-primary" onclick="openCommentText({{$item->id}}, 'ansTo')">متن</button>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{$item->like}}
                                                                </td>
                                                                <td>
                                                                    {{$item->disLike}}
                                                                </td>
                                                                <td>
                                                                    {{$item->ansCount}}
                                                                </td>
                                                                <td>
                                                                    {{$item->time}}
                                                                </td>
                                                                <td style="display: flex; justify-content: space-around">
                                                                    <div style="display: flex; justify-content: space-evenly;">
                                                                        <span class="butMain" style="background-color: red" onclick="deleteComment({{$item->id}}, this)">
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

        <div class="modal fade" id="textModal" style="direction: rtl; text-align: right">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <input type="hidden" id="thumbnailModalId">
                    <div class="modal-header" style="display: flex;">
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>

                    <div id="modalBodyText" class="modal-body"></div>

                    <div class="modal-footer" style="display: flex; justify-content: center; align-items: center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
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

            let newComments = {!! json_encode($newComments) !!};
            let confirmComments = {!! json_encode($confirmComments) !!};

            function openCommentText(_id, _kind){
                let comment = null;
                confirmComments.forEach(item => {
                    if(item.id == _id)
                        comment = item;
                });

                if(comment != null){
                    if(_kind == 'text')
                        $('#modalBodyText').text(comment['text']);
                    else
                        $('#modalBodyText').text(comment['ansTo']);

                    $('#textModal').modal('show');
                }
            }

            function deleteComment(id, _element){
                $.ajax({
                    type: 'post',
                    url: '{{route("vod.video.comment.delete")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok')
                                location.reload();
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

            function submitComment(id, _element){
                $.ajax({
                    type: 'post',
                    url: '{{route("vod.video.comment.submit")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: id,
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok') {
                                $('#row_' + id).remove();
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
