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
                    <div class="modal-body" id="guestBody"></div>
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

        <div class="modal fade" id="newGuestModal" style="direction: rtl; text-align: right">
            <input type="hidden" id="descriptionId">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title">
                            افزودن مهمان جدید
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input type="hidden" id="newGuestId" value="0">
                            <div class="row">
                                <div class="col-md-6" style="float: right">
                                    <div class="form-group">
                                        <label for="newGuestName">نام مهمان :</label>
                                        <input type="text" id="newGuestName" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="newGuestAction">نقش مهمان در برنامه:</label>
                                        <input type="text" id="newGuestAction" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" style="display: flex; justify-content: center">
                                    <div class="form-group guestPic">
                                        <img id="newGuestPicImg"  style="height: 100%; max-width: auto;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="newGuestPic">عکس مهمان:</label>
                                        <input type="file" id="newGuestPic" class="form-control" onchange="readPic(this)">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label for="newGuestText">توضیح </label>
                                    <textarea  id="newGuestText" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row" style="justify-content: center; display: flex;">
                                <input type="hidden" id="liveId">
                                <button class="btn btn-primary" onclick="storeGuest()">ثبت</button>
                            </div>
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

    <script>
        let newPic = null;

        function readPic(input, _index){
            if (input.files && input.files[0]) {
                newPic = input.files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#newGuestPicImg').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openGuestModal(_id){
            let vid = null;
            videos.forEach((video) => {
                if(video.id == _id)
                    vid = video;
            });

            if(vid != null){
                $('#guestBody').html('');
                vid.guest.forEach(item =>{
                    createGust(item);
                });
                guestIndex = 1;
                $('#guestVideoId').val(vid.id);
                $('#guestHeader').text(vid.title);
                $('#guestModal').modal({backdrop: 'static', keyboard: false});
            }
        }

        function newGuest(){
            newPic = null;
            $('#newGuestId').val(0);
            $('#newGuestName').val('');
            $('#newGuestPic').val('');
            $('#newGuestText').val('');
            $('#newGuestPicImg').attr('src', '');
            $('#newGuestModal').modal({backdrop: 'static', keyboard: false});
        }


        function storeGuest(_index){
            let id = $('#newGuestId').val();
            let videoId = $('#guestVideoId').val();
            let name = $('#newGuestName').val();
            let action = $('#newGuestAction').val();
            let text = $('#newGuestText').val();

            if(newPic == null){
                alert('عکس مهمان را قرار دهید');
                return;
            }

            if(name.trim().length == 0){
                alert('نام مهمان را وارد کنید');
                return;
            }

            if(action.trim().length == 0){
                alert('نقش مهمان را مشخص کنید');
                return;
            }

            openLoading();
            formData = new FormData();
            formData.append('_token', '{{csrf_token()}}');
            formData.append('id', id);
            formData.append('videoId', videoId);
            formData.append('name', name);
            formData.append('action', action);
            formData.append('text', text);
            formData.append('pic', newPic);

            $.ajax({
                type: 'post',
                url: '{{route('vod.live.guest.store')}}',
                data: formData,
                processData: false,
                contentType: false,
                success: function(resposne){
                    closeLoading();
                    try{
                        resposne = JSON.parse(resposne);
                        if(resposne['status'] == 'ok'){
                            $('#newGuestModal').modal('hide');
                            createGust(resposne['result']);
                            let vid = null;
                            videos.forEach((video) => {
                                if(video.id == videoId)
                                    vid = video;
                            });
                            vid.guest.push(resposne['result']);
                        }
                    }
                    catch (e) {
                        console.log(e);
                    }
                },
                error: function(err){
                    console.log(err);
                    closeLoading();
                }
            })
        }

        function createGust(_guest){
            text = ' <div id="guestRow_' + _guest.id + '" class="row" style="font-size: 20px">\n' +
                '                            <div class="col-md-6" >\n' +
                '                                <div class="guestPic">\n' +
                '                                    <img src="' + _guest.pic +'" style="height: 100%; max-width: auto">\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-md-6">\n' +
                '                                <div class="row">\n' +
                '                                    <span style="color: darkgray">نام مهمان:</span>\n' +
                '                                    <span>' + _guest.name + '</span>\n' +
                '                                </div>\n' +
                '                                <div class="row">\n' +
                '                                    <span style="color: darkgray">نقش مهمان:</span>\n' +
                '                                    <span>' + _guest.action + '</span>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-md-12">\n' +
                '                                <span style="color: darkgray">توضیحات:</span>\n' +
                '                                <span>' + _guest.text + '</span>\n' +
                '                            </div>\n' +
                '                        </div>' +
                '                        <hr>';
            $('#guestBody').append(text);
        }
    </script>
@endsection

