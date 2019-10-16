@extends('layouts.structure')

@section('header')
    @parent
    <style>
        .col-xs-12 {
            margin-top: 10px;
        }

        button {
            margin-right: 10px;
        }

        .row {
            direction: rtl;
        }

        .activities{
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: solid;
        }
        .center{
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">

        @if(\Session::has('error'))
            <div class="alert alert-danger alert-dismissible " style="direction: rtl">
                <button type="button" class="close" data-dismiss="alert" style="float: left;">&times;</button>
               {{session('error')}}
            </div>
        @endif

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                    <h1>توضیحات</h1>
                    <button class="btn btn-success" data-toggle="modal" data-target="#newModal">توضیح جدید</button>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row" style="padding: 10px;">
                    <table class="table table-striped">
                        <tbody>
                            @foreach($descriptions as $item)
                                <tr>
                                    <td>
                                        {{$item->description}}
                                    </td>
                                    <td>
                                        {{$item->place}}
                                    </td>
                                    <td class="center">
                                        <button name="editDescription" class="btn btn-info width-auto center" onclick="editModal({{$item->id}})" title="ویرایش توضیح" >
                                            <span class="glyphicon glyphicon-edit mg-tp-30per"></span>
                                        </button>
                                        <button name="deleteDescription" class="btn btn-danger width-auto center" onclick="deleteReport({{$item->id}})" title="حذف توضیح">
                                            <span class="glyphicon glyphicon-remove mg-tp-30per"></span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <div class="modal fade" id="newModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        توضیح جدید
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>

                <form action="{{route('descriptions.store')}}" method="post">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row" style="padding: 20px">
                            <div class="form-group">
                                <label for="newPlaceId">برای چه نوع است؟</label>
                                <select id="newPlaceId" name="placeId" class="form-control" required>
                                    @foreach($places as $item)
                                        <option value="{{$item->id}}">
                                            {{$item->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="newDescription">توضیح</label>
                                <textarea id="newDescription" name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer center" >
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ایجاد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        ویرایش توضیح
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>

                <form action="{{route('descriptions.doEdit')}}" method="post">
                @csrf
                    <input type="hidden" name="id" id="editId">
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row" style="padding: 20px">
                            <div class="form-group">
                                <label for="editPlaceId">برای چه نوع است؟</label>
                                <select id="editPlaceId" name="placeId" class="form-control" required>
                                    @foreach($places as $item)
                                        <option value="{{$item->id}}">
                                            {{$item->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editDescription">توضیح</label>
                                <textarea id="editDescription" name="description" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer center" >
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-primary">ویرایش</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        پاک کردن توضیح
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('descriptions.delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="deleteId" name="id">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            ایا می خواهید توضیح "<span id="deleteDescription"></span> "را پاک کنید؟
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer center" >
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-danger">بله پاک شود</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var report = {!! $descriptions !!};

        function editModal(_id){

            for(i = 0; i < report.length; i++){
                if(report[i]['id'] == _id){
                    document.getElementById('editId').value = _id;
                    document.getElementById('editDescription').value = report[i]['description'];
                    $('#editPlaceId').val(report[i]['kindPlaceId']);
                }
            }

            $('#editModal').modal();
        }

        function deleteReport(_id){
            for(i = 0; i < report.length; i++){
                if(report[i]['id'] == _id){
                    document.getElementById('deleteId').value = _id;
                    document.getElementById('deleteDescription').innerText = report[i]['description'];
                }
            }

            $('#deleteModal').modal();
        }

    </script>

@stop