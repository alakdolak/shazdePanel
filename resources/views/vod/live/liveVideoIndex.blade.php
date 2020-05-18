@extends('layouts.structure')

@section('header')
    @parent
    <link rel="stylesheet" href="{{URL::asset('css/calendar/persian-datepicker.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/clockPicker/clockpicker.css')}}"/>

    <style>
        .row{
            width: 100%;
            margin: 0;
        }
        th, td{
            text-align: right;
        }
        .headerSec{
            display: flex;
            direction: rtl;
            align-items: center;
        }
        .addIcon{
            font-size: 20px;
            color: white;
            background: green;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            margin: 0 20px;
            cursor: pointer;
        }
        .isLive{
            background: #adffab;
        }
        .isRecorded{
            background: #a8cbff;
        }

        .errorInput{
            background: #ffd9d9;
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
                                <div class="sparkline13-outline-icon"></div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="container-fluid">
                                    <div class="headerSec">
                                        <h1 style="margin: 0;">
                                            Live Stream
                                        </h1>
                                        <div class="addIcon" onclick="newLive()">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped table-bordered" dir="rtl"
                                                       data-toggle="table"
                                                       data-pagination="true"
                                                       data-search="true"
                                                       data-show-refresh="true"
                                                       data-auto-refresh="true">
                                                    <thead>
                                                    <tr>
                                                        <th>عنوان</th>
                                                        <th>ضبط شده</th>
                                                        <th>کد پخش</th>
                                                        <th>ساعت شروع</th>
                                                        <th>تاریخ پخش</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($videos as $video)
                                                            <tr id="video_{{$video->id}}">
                                                                <td>
                                                                    {{$video->title}}
                                                                </td>
                                                                <td class="{{$video->isLive == 1 ? 'isLive' : 'isRecorded'}}">
                                                                    {{$video->isLive == 1 ? 'live' : 'Record'}}
                                                                </td>
                                                                <td>
                                                                    {{$video->code}}
                                                                </td>
                                                                <td>
                                                                    {{$video->sTime}}
                                                                </td>
                                                                <td>
                                                                    {{$video->sDate}}
                                                                </td>
                                                                <td>
                                                                    @if($video->isLive == 1)
                                                                        <button class="btn btn-warning" onclick="isLiveFunc({{$video->id}})">ضبط شده</button>
                                                                    @else
                                                                        <button class="btn btn-warning" onclick="isLiveFunc({{$video->id}})">پحش زنده</button>
                                                                    @endif
                                                                    <button class="btn btn-primary" onclick="editLive({{$video->id}})">ویرایش</button>
                                                                    <button class="btn btn-danger" onclick="deleteLive({{$video->id}})">حذف</button>
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

        <!-- The Modal -->
        <div class="modal fade" id="newLiveModal" style="direction: rtl; text-align: right">
            <input type="hidden" id="descriptionId">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title" id="newLiveHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body" id="newLiveBody">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="liveTitle">عنوان</label>
                                    <input type="text" id="liveTitle" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="liveDate">تاریخ پخش</label>
                                    <input type="text" id="liveDate" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="liveTime">ساعت شروع</label>
                                    <input type="text" id="liveTime" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="liveDesc">توضیح</label>
                                <textarea  id="liveDesc" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="justify-content: center; display: flex;">
                        <input type="hidden" id="liveId">
                        <button class="btn btn-success" onclick="storeLive()">ثبت</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>


        <script src="{{URL::asset('js/calendar/persian-date.min.js')}}"></script>
        <script src="{{URL::asset('js/calendar/persian-datepicker.js')}}"></script>

        <script src= {{URL::asset("js/clockPicker/clockpicker.js") }}></script>
        <script>

            $('#liveDate').persianDatepicker({
                minDate: new Date().getTime(),
                format: 'YYYY-MM-DD',
                autoClose: true,
            });

            $('#liveTime').clockpicker({
                donetext: 'تایید',
                autoclose: true,
            });

            let videos = {!! $videos !!};

            function newLive() {
                $('#liveId').val(0);
                $('#liveTitle').val('');
                $('#liveTime').val('');
                $('#liveDate').val('');
                $('#liveDesc').val('');
                $('#newLiveHeader').text('افزودن Live جدید');
                $('#newLiveModal').modal({backdrop: 'static', keyboard: false});
            }

            function storeLive(){
                let id = $('#liveId').val();
                let title = $('#liveTitle').val();
                let time = $('#liveTime').val();
                let date = $('#liveDate').val();
                let desc = $('#liveDesc').val();
                let error = false;

                if(title.trim().length < 2){
                    $('#liveTitle').addClass('errorInput');
                    error = true;
                }
                else
                    $('#liveTitle').removeClass('errorInput');

                if(time.trim().length < 2){
                    $('#liveTime').addClass('errorInput');
                    error = true;
                }
                else
                    $('#liveTime').removeClass('errorInput');

                if(date.trim().length < 2){
                    $('#liveDate').addClass('errorInput');
                    error = true;
                }
                else
                    $('#liveDate').removeClass('errorInput');


                if(!error){
                    $.ajax({
                        type: 'post',
                        url: '{{route("vod.live.store")}}',
                        data:{
                            _token: '{{csrf_token()}}',
                            id: id,
                            title: title,
                            time: time,
                            date: date,
                            desc: desc,
                        },
                        success: function(response){
                            try{
                                response = JSON.parse(response);
                                if(response['status'] == 'ok')
                                    location.reload();
                                else
                                    alert('Error in Store');
                            }
                            catch (e) {
                                console.log(e);
                                alert('Error in Store');
                            }
                        },
                        error: function(err){
                            console.log(err);
                            alert('Error in Store');
                        }
                    })
                }
            }

            function editLive(_id){
                let vid;
                videos.forEach((video) => {
                    if(video.id == _id)
                        vid = video;
                });

                $('#liveId').val(vid.id);
                $('#liveTitle').val(vid.title);
                $('#liveTime').val(vid.sTime);
                $('#liveDate').val(vid.sDate);
                $('#liveDesc').val(vid.description);
                $('#newLiveHeader').text('ویرایش ' + vid.title);
                $('#newLiveModal').modal({backdrop: 'static', keyboard: false});
            }

            function isLiveFunc(_id){
                $.ajax({
                    type: 'post',
                    url: '{{route("vod.live.isLive")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok')
                                location.reload();
                            else
                                alert('Error In change');
                        }
                        catch (e) {
                            console.log(e);
                            alert('Error In change');
                        }
                    },
                    error: function (err) {
                        console.log(err);
                        alert('Error In change');
                    }
                })
            }

            function deleteLive(_id){

            }
        </script>

@stop
