@extends('layouts.structure')

@section('header')
    @parent
    <style>
        th, td{
            text-align: right;
        }
        th{
            background-color: #333333;
            color: white;
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
        .buttonIcon{
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            font-size: 20px;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
        }
        .buttonIcon:hover{
            color: white;
        }
    </style>

    <link rel="stylesheet" href="{{URL::asset('css/DataTable/jquery.dataTables.css')}}">
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
                            <div>
                                <h2>
                                    مکان های افزوده شده توسط کاربر
                                </h2>
                            </div>
                            <hr>


                            <table id="table" class="table table-borderless table-striped table-earning" style="text-align: center; font-size: 13px; direction: rtl">
                                <thead class="thead-dark">
                                <tr>
                                    <th>نام مکان</th>
                                    <th>نوع مکان</th>
                                    <th>کاربر</th>
                                    <th>استان</th>
                                    <th>شهر</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($places as $item)
                                        <tr id="row_{{$item->id}}">
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->kindPlace->name}}</td>
                                            <td>{{$item->user->username}}</td>
                                            <td>{{$item->state}}</td>
                                            <td>{{$item->city}}</td>
                                            <td style="display: flex; justify-content: space-between">
                                                <a href="{{route('userAddPlace.edit', ['id' => $item->id])}}" data-toggle="tooltip" data-placement="top" title="ویرایش و دخیره" class="buttonIcon" style="background: blue;">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                                @if($item->addPic)
                                                    <a href="{{route('userAddPlace.pics', ['id' => $item->id])}}" data-toggle="tooltip" data-placement="top" title="عکس ها" class="buttonIcon" style="background: darkgreen;">
                                                        <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                                <div data-toggle="tooltip" data-placement="top" title="حذف" class="buttonIcon" style="background: red;" onclick="deleteModal({{$item->id}})">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
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

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        حذف مکان کاربر
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>
                <div class="modal-body" style="direction: rtl">
                    <div  class="row" style="padding: 20px">
                        ایا می خواهید <span id="deleteName" style="color: red"></span> را پاک کنید؟ توجه داشته باشید که عکس های کاربر هم پاک می شود و قابل برگشت نیست.
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer center" >
                    <input type="hidden" id="deletedId">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn btn-danger" onclick="doDelete()">حذف</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
        <script type="text/javascript" charset="utf8" src="{{URL::asset('js/DataTable/jquery.dataTables.js')}}" defer></script>

        <script>
            let dataTable;
            let places = {!! $places !!};

            $(document).ready(function () {
                dataTable = $('#table').DataTable({
                    "language": {
                        "paginate": {
                            "previous": "صفحه قبل",
                            "next" : "صفحه بعد",
                            "search": "جستجو:"
                        }
                    }
                });
            });

            function deleteModal(_id){
                let place = null;
                places.forEach(item => {
                    if(item.id == _id)
                        place = item;
                });

                if(place != null) {
                    $('#deletedId').val(_id);
                    $('#deleteName').text(place.name);
                    $('#deleteModal').modal('show');
                }
            }

            function doDelete(){
                let id = $('#deletedId').val();
                $('#deleteModal').modal('hide');
                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("userAddPlace.delete")}}',
                    data: {
                        _toke: '{{csrf_token()}}',
                        id: id
                    },
                    success: function(response){
                        closeLoading();
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                $('#row_' + id).remove();
                            }
                            else
                                alert('در حذف مشکلی پیش امده لطفا دوباره تلاش کنید');
                        }
                        catch (e) {
                            console.log(e);
                            alert('در حذف مشکلی پیش امده لطفا دوباره تلاش کنید');
                        }
                    },
                    error: function(err){
                        console.log(err);
                        alert('در حذف مشکلی پیش امده لطفا دوباره تلاش کنید');
                        closeLoading()
                    }
                })
            }
        </script>
@endsection
