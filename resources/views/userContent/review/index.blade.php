@extends('layouts.structure')

@section('header')
    @parent

    <style>
        *{
            direction: rtl;
        }
        th{
            text-align: center;
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
                                        <span id="newCommentCount"  class="label label-success"> {{count($newReviews)}} </span>
                                        پست های جدید
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#menu1"> پست های تایید شده</a>
                                </li>
                            </ul>

                            <div class="tab-content border mb-3">
                                <div id="home" class="container tab-pane active" style="width: 100%">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <h2>پست های جدید</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table"  dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th> کاربر </th>
                                                        <th> نام مکان </th>
                                                        <th> عکس </th>
                                                        <th> فیلم </th>
                                                        <th> متن نقد </th>
                                                        <th> زمان </th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($newReviews as $item)
                                                        <tr id="row_{{$item->id}}">
                                                            <td>{{$item->username}}</td>
                                                            <td>{{$item->placeName}}</td>
                                                            <td>
                                                                <span onclick="showPics({{$item->id}}, 'pic', 'new')"  style="cursor:pointer;"> {{$item->countPic}}</span>
                                                            </td>
                                                            <td>
                                                                <span onclick="showPics({{$item->id}}, 'video', 'new')"  style="cursor:pointer;"> {{$item->countVideo}}</span>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary" onclick="showText({{$item->id}}, 'new')">نمایش متن</button>
                                                            </td>
                                                            <td>{{$item->dateTime}}</td>
                                                            <td>
                                                                <button class="btn btn-warning" onclick="confirmReview({{$item->id}}, 'new')">تایید نقد</button>
                                                                <button class="btn btn-danger" onclick="deleteReview({{$item->id}})">حذف نقد</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="menu1" class="container tab-pane fade"  style="width: 100%">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <h2>پست های تایید شده</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table"  dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                        <tr>
                                                            <th> کاربر </th>
                                                            <th> نام مکان </th>
                                                            <th> عکس </th>
                                                            <th> فیلم </th>
                                                            <th> متن نقد </th>
                                                            <th> زمان </th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($confirmedReviews as $item)
                                                        <tr id="row_{{$item->id}}">
                                                            <td>{{$item->username}}</td>
                                                            <td>{{$item->placeName}}</td>
                                                            <td>
                                                                <span onclick="showPics({{$item->id}}, 'pic', 'confirmed')"  style="cursor:pointer;"> {{$item->countPic}}</span>
                                                            </td>
                                                            <td>
                                                                <span onclick="showPics({{$item->id}}, 'video', 'confirmed')"  style="cursor:pointer;"> {{$item->countVideo}}</span>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-primary" onclick="showText({{$item->id}}, 'confirmed')">نمایش متن</button>
                                                            </td>
                                                            <td>{{$item->dateTime}}</td>
                                                            <td>
                                                                <button class="btn btn-warning" onclick="confirmReview({{$item->id}}, 'confirmed')">عدم نمایش</button>
                                                                <button class="btn btn-danger" onclick="deleteReview({{$item->id}})">حذف نقد</button>
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
    </div>


    <div class="modal" id="textModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">متن نقد</h4>
                    <button type="button" class="close" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="textP"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="picModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="picModalHeader" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="picDiv" class="row"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var confirmedReviews = {!! $confirmedReviews !!};
        var reviews = {!! $newReviews !!};
        var picUrl = '{{URL::asset("userPhoto/")}}';

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

        function showPics(_id, _kind, _type){
            var rev;
            var html = '';
            var pics;
            var searchInReview = _type == 'new' ? reviews : confirmedReviews;

            searchInReview.map(item => { if(item.id == _id) rev = item });
            pics = rev.pics;

            pics.map(item => {
                if(_kind == 'pic' && item.is360 == 0 && item.isVideo == 0) {
                    html += `<div id="pic_${item.id}" class="col-md-6" style="text-align: center; margin-bottom: 20px">
                                <img src="${item.url}" style="width: 100%;">
                                <button onclick="deletePic(${item.id}, ${rev.id})" class="btn btn-danger"> حذف </button>
                            </div>`;
                }
                else if(_kind == 'video' && item.isVideo == 1){
                    html += `<div id="pic_${item.id}" class="col-md-12" style="text-align: center; margin-bottom: 20px">
                                <video src="${item.url}" controls style="width: 100%;"></video>
                                <button onclick="deletePic(${item.id}, ${rev.id})" class="btn btn-danger"> حذف </button>
                            </div>`;
                }
            });

            $('#picModalHeader').text(_kind == 'pic' ? 'عکس ها' : 'ویدیو ها');

            document.getElementById('picDiv').innerHTML = html;

            $('#picModal').modal('show');
        }

        function showText(_id, _type){
            var rev;
            var searchInReview = _type == 'new' ? reviews : confirmedReviews;

            for(i = 0; i < searchInReview.length; i++){
                if(searchInReview[i]['id'] == _id){
                    rev = searchInReview[i];
                    break;
                }
            }
            $('#textP').html(rev.text);
            $("#textModal").modal('show');
        }

        function deletePic(_id, _logId){
            $.ajax({
                type: 'post',
                url: '{{route("reviews.pic.delete")}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id,
                },
                success: function (response){
                    if(response == 'ok'){
                        for(i = 0; i < reviews.length; i++){
                            if(reviews[i]['id'] == _logId){
                                for(j = 0; j < reviews[i]['pics'].length; j++){
                                    if(reviews[i]['pics'][j]['id'] == _id){
                                        reviews[i]['pics'][j] = null;

                                        $('#pic_' + _id).remove();
                                    }
                                }
                            }
                        }


                    }
                }
            })
        }

        function confirmReview(_id, _type){
            $.ajax({
                type : 'POST',
                url: '{{route("reviews.confirm")}}',
                data: {
                    _token : '{{csrf_token()}}',
                    id : _id
                },
                success: response => {
                    if(response == 'ok') {
                        if(_type == 'new') alert('نقد مورد نظر تایید شد');
                        else alert('نقد مورد نظر مسدود شد');

                        $('#row_' + _id).remove();
                    }
                }
            });
        }

        function deleteReview(_id){
            $.ajax({
                type : 'post',
                url: '{{route("reviews.delete")}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id
                },
                success: function(response){
                    if(response == 'ok') {
                        alert('نقد مورد نظر حذف شد');
                        $('#row_' + _id).remove();
                    }
                }
            });
        }

    </script>

@stop
