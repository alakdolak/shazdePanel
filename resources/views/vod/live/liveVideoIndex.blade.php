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
        .isLive{
            background: #adffab;
        }
        .isRecorded{
            background: #a8cbff;
        }

        .errorInput{
            background: #ffd9d9;
        }
        .guestPic{
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
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
                                                        <th>لیست مهمانان</th>
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
                                                                    <button class="btn btn-success" onclick="openGuestModal({{$video->id}})">مشاهده لیست مهمانان</button>
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

        <div class="modal fade" id="guestModal" style="direction: rtl; text-align: right">
            <input type="hidden" id="descriptionId">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title">
                            لیست مهمانان <span id="guestHeader"></span>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <input type="hidden" id="guestVideoId">
                    <div class="modal-body" id="guestBody">

                        <div class="container-fluid">
                            <input type="hidden" id="guestId_##index##" value="##id##">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guestAction_##index##">نقش مهمان ##index## در برنامه:</label>
                                        <input type="text" id="guestAction_##index##" class="form-control" value="##action##">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guestName_##index##">نام مهمان ##index##:</label>
                                        <input type="text" id="guestName_##index##" class="form-control" value="##name##">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="display: flex; justify-content: center">
                                    <div class="form-group guestPic">
                                        <img id="guestPicImg_##index##" src="##pic##"  style="height: 100%">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guestPic_##index##">عکس مهمان ##index##</label>
                                        <input type="file" id="guestPic_##index##" class="form-control" onchange="readPic(this, ##index##)">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="guestText_##index##">توضیح ##index##</label>
                                    <textarea  id="guestText_##index##" class="form-control">##text##</textarea>
                                </div>
                            </div>

                            <div class="row" style="justify-content: center; display: flex;">
                                <input type="hidden" id="liveId">
                                <button class="btn btn-primary" onclick="storeGuest(##index##)">ثبت</button>
                            </div>
                        </div>
                        <hr>

                    </div>
                    <div style="width: 100%; display: flex; justify-content: center">
                        <div class="addIcon" onclick="newGuest()">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>

                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script src="{{URL::asset('js/calendar/persian-date.min.js')}}"></script>
    <script src="{{URL::asset('js/calendar/persian-datepicker.js')}}"></script>

    <script src= {{URL::asset("js/clockPicker/clockpicker.js") }}></script>
    <script>

        let guestIndex = 1;
        let guestInputSample = $('#guestBody').html();
        $('#guestBody').html('');

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

        function openGuestModal(_id){
            let vid = null;
            videos.forEach((video) => {
                if(video.id == _id)
                    vid = video;
            });
            if(vid != null){
                $('#guestBody').html('');
                guestIndex = 1;
                $('#guestVideoId').val(vid.id);
                $('#guestHeader').text(vid.title);
                $('#guestModal').modal({backdrop: 'static', keyboard: false});
            }
        }

        function readPic(input, _index){
            if (input.files && input.files[0]) {
                // data.append('pic', input.files[0]);
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#guestPicImg_' + _index).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function newGuest(){
            let guest = {
                'index': guestIndex,
                'name' : '',
                'id' : 0,
                'action': '',
                'pic': '',
                'text': '',
            };
            createGuestRow(guest);
        }

        function createGuestRow(_guest){
            let text = guestInputSample;
            let fk = Object.keys(_guest);
            for (let x of fk) {
                let t = '##' + x + '##';
                let re = new RegExp(t, "g");
                text = text.replace(re, _guest[x]);
            }
            $("#guestBody").append(text);
            guestIndex++;
        }

        function storeGuest(_index){
            let id = $('#guestId_' + _index).val();
            let name = $('#guestName_' + _index).val();
            let action = $('#guestAction_' + _index).val();
            let text = $('#guestText_' + _index).val();
        }

        function deleteLive(_id){

        }
    </script>
@endsection
