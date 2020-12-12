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
                                                            <th>
                                                                کاربر
                                                            </th>
                                                            <th>
                                                                نام مکان
                                                            </th>
                                                            <th>
                                                                عکس
                                                            </th>
                                                            <th>
                                                                فیلم
                                                            </th>
                                                            <th>
                                                                متن نقد
                                                            </th>
                                                            <th>
                                                                زمان
                                                            </th>
                                                            {{--<th>--}}
                                                                {{--پاسخ به سئوالات--}}
                                                            {{--</th>--}}
                                                            <th>
                                                            </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($newReviews as $item)
                                                            @if($item->place != null)
                                                                <tr id="row_{{$item->id}}">
                                                                <td>
                                                                    {{$item->username}}
                                                                </td>
                                                                <td>
                                                                    {{$item->place->name}}
                                                                </td>
                                                                <td onclick="showPics({{$item->id}}, 'pic')" style="cursor:pointer;">
                                                                    {{$item->countPic}}
                                                                </td>
                                                                <td onclick="showPics({{$item->id}}, 'video')" style="cursor:pointer;">
                                                                    {{$item->countVideo}}
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-primary" onclick="showText({{$item->id}})">
                                                                        نمایش متن
                                                                    </button>
                                                                </td>
                                                                <td>
                                                                    {{$item->dateTime}}
                                                                </td>
                                                                {{--<td>--}}
                                                                    {{--<button class="btn btn-primary" onclick="showAns({{$item->id}})">--}}
                                                                        {{--نمایش پاسخ ها--}}
                                                                    {{--</button>--}}
                                                                {{--</td>--}}
                                                                <td>
                                                                    <button class="btn btn-warning" onclick="confirmReview({{$item->id}})">
                                                                        تایید نقد
                                                                    </button>
                                                                    <button class="btn btn-danger" onclick="deleteReview({{$item->id}})">
                                                                        حذف نقد
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endif
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

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">متن نقد</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p id="textP">

                    </p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="picModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 id="picModalHeader" class="modal-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div id="picDiv" class="row"></div>
                </div>

                <!-- Modal footer -->
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
            for(i = 0; i < reviews.length; i++){
                if(reviews[i]['id'] == _id){
                    rev = reviews[i];
                    break;
                }
            }

            var text = '';
            var pics = rev['pics'];

            var location = picUrl + rev['file'] + '/' + rev['place']['file'] + '/';

            for(i = 0; i < pics.length; i++){
                if(pics[i] != null) {
                    if(_kind == 'pic') {
                        if (pics[i]['is360'] == 0 && pics[i]['isVideo'] == 0) {
                            var file = location + pics[i]['pic'];
                            text += '<div id="pic_' + pics[i]['id'] + '" class="col-md-6" style="text-align: center; margin-bottom: 20px">\n' +
                                '<img src="' + file + '" style="width: 100%;">\n' +
                                '<button class="btn btn-danger" onclick="deletePic(' + pics[i]['id'] + ', ' + rev['id'] + ')">\n' +
                                'حذف عکس\n' +
                                '</button>\n' +
                                '</div>';
                        }
                    }
                    else if(_kind == 'video'){
                        if (pics[i]['isVideo'] == 1) {
                            var file = location + pics[i]['pic'];
                            text += '<div id="pic_' + pics[i]['id'] + '" class="col-md-12" style="text-align: center; margin-bottom: 20px">\n' +
                                '<video src="' + file + '" controls style="width: 100%;"></video>\n' +
                                '<button class="btn btn-danger" onclick="deletePic(' + pics[i]['id'] + ', ' + rev['id'] + ')">\n' +
                                'حذف ویدیو\n' +
                                '</button>\n' +
                                '</div>';
                        }
                    }
                }
            }

            if(_kind == 'pic')
                $('#picModalHeader').text('عکس ها');
            else if(_kind == 'video')
                $('#picModalHeader').text('ویدیو ها');

            document.getElementById('picDiv').innerHTML = text;

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
