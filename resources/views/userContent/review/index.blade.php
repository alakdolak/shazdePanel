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

                                    <div class="income-order-visit-user-area">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <h2>
                                                    نقدهای جدید
                                                </h2>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th> کاربر </th>
                                                            <th> نام مکان </th>
                                                            <th> عکس </th>
                                                            <th> فیلم </th>
                                                            <th> متن نقد </th>
                                                            <th> زمان </th>
{{--                                                            <th>پاسخ به سئوالات</th>--}}
                                                            <th></th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($newReviews as $item)
                                                            <tr id="row_{{$item->id}}">
                                                                <td>{{$item->username}}</td>
                                                                <td>{{$item->placeName}}</td>
                                                                <td onclick="showPics({{$item->id}}, 'pic')" style="cursor:pointer;">{{$item->countPic}}</td>
                                                                <td onclick="showPics({{$item->id}}, 'video')" style="cursor:pointer;">{{$item->countVideo}}</td>
                                                                <td>
                                                                    <button class="btn btn-primary" onclick="showText({{$item->id}})">نمایش متن</button>
                                                                </td>
                                                                <td>{{$item->dateTime}}</td>
                                                                {{--<td>--}}
                                                                    {{--<button class="btn btn-primary" onclick="showAns({{$item->id}})">--}}
                                                                        {{--نمایش پاسخ ها--}}
                                                                    {{--</button>--}}
                                                                {{--</td>--}}
                                                                <td>
                                                                    <button class="btn btn-warning" onclick="confirmReview({{$item->id}})">تایید نقد</button>
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
        var reviews = {!! $newReviews !!};
        var picUrl = '{{URL::asset("userPhoto/")}}';

        function showPics(_id, _kind){
            var rev;
            var html = '';
            var pics;

            reviews.map(item => {
                if(item.id == _id) rev = item;
            });

            pics = rev.pics;

            pics.map(item => {
                console.log(item);
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

        function showText(_id){
            var rev;
            for(i = 0; i < reviews.length; i++){
                if(reviews[i]['id'] == _id){
                    rev = reviews[i];
                    break;
                }
            }

            document.getElementById('textP').innerText = rev['text'];
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

        function confirmReview(_id){
            $.ajax({
                type : 'post',
                url: '{{route("reviews.confirm")}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id
                },
                success: function(response){
                    if(response == 'ok') {
                        alert('نقد مورد نظر تایید شد')
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
