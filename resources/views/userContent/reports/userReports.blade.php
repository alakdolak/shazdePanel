@extends('layouts.structure')

@section('header')
    @parent

    <style>
        .row{
            direction: rtl;
            width: 100%;
            margin: 0px;
            padding: 0px;
        }
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
                                                    گزارش های جدید
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
                                                            نوع مطلب
                                                        </th>
                                                        <th>
                                                            مکان مطلب
                                                        </th>
                                                        <th>
                                                            نوع گزارش
                                                        </th>
                                                        <th>
                                                            مطلب
                                                        </th>
                                                        <th>
                                                            تاریخ گزارش
                                                        </th>
                                                        <th>
                                                        </th>
                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    @foreach($reports as $item)
                                                        <tr id="row_{{$item->id}}">
                                                            <td>
                                                                {{$item->username}}
                                                            </td>
                                                            <td>
                                                                {{$item->refName}}
                                                            </td>
                                                            <td>
                                                                {{$item->placeName}}
                                                            </td>
                                                            <td>
                                                                {{$item->text}}
                                                            </td>
                                                            <td onclick="showReported({{$item->id}})" style="cursor:pointer;">
                                                                <button class="btn btn-primary">
                                                                    نمایش مطلب
                                                                </button>
                                                            </td>
                                                            <td>
                                                                {{$item->dateTime}}
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-warning" onclick="confirmReports({{$item->id}})">
                                                                    مشاهده شد
                                                                </button>
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

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div id="textKind" class="col-sm-8" ></div>
                        <div class="col-sm-4">نوع مطلب: </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div id="textUserName" class="col-sm-8" ></div>
                        <div class="col-sm-4">نویسنده مطلب: </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div id="textPlaceName" class="col-sm-8" ></div>
                        <div class="col-sm-4">محل مطلب: </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div id="textText" class="col-sm-8" ></div>
                        <div class="col-sm-4">متن مطلب: </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div id="textPics" class="col-sm-8" ></div>
                        <div class="col-sm-4">عکس فیلم مطلب: </div>
                    </div>
                    <hr>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        var reports = {!! json_encode($reports) !!};

        function showReported(_id){
            var rep = null;
            reports.forEach(item => {
               if(item.id == _id)
                   rep = item;
            });

            if(rep != null){
                $('#textKind').text(rep.refName);
                $('#textUserName').text(rep.reviewUser);
                $('#textPlaceName').text(rep.placeName);
                $('#textText').text(rep.logTxt);

                let pics = null;
                if(rep.pics) {
                    if (rep.pics.length > 0) {
                        rep.pics.forEach(p => {
                            pics += '<img src="' + p + '" style="max-height: 100%; max-width: 100%;">';
                        });
                    }
                    if (rep.Video.length > 0) {
                        rep.Video.forEach(p => {
                            pics += '<video src="' + p + '" controls style="max-height: 100%; max-width: 100%;"></video>';
                        });
                    }
                }
                $('#textPics').html(pics);

                $('#textModal').modal('show');
            }
        }

        function confirmReports(_id){
            $.ajax({
                type: 'post',
                url: '{{route("user.report.confirm")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    if(response == 'ok')
                        $('#row_' + _id).remove();
                    else
                        alert('در آرشیو کردن گزارش مشکلی پیش امده');
                },
                error: function(err){
                    console.log(err);
                    alert('در آرشیو کردن گزارش مشکلی پیش امده');
                }
            })
        }
    </script>

@stop
