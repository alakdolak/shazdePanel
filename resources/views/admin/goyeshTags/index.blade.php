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
                    <h1>تگ های گویش</h1>
                    <button class="btn btn-success" data-toggle="modal" data-target="#newModal">تگ جدید</button>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row" style="padding: 10px;">
                    <table class="table table-striped">
                        <tbody>
                        @foreach($tags as $item)
                            <tr>
                                <td style="text-align: center">
                                    {{$item->name}}
                                </td>
                                <td class="center">
                                    <button name="editDescription" class="btn btn-info width-auto center" onclick="editModal({{$item->id}})" title="ویرایش توضیح" >
                                        <span class="glyphicon glyphicon-edit mg-tp-30per"></span>
                                    </button>
                                    <button name="deleteDescription" class="btn btn-danger width-auto center" onclick="deleteModal({{$item->id}})" title="حذف توضیح">
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
                        تگ گویش جدید
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>

                <form action="{{route('goyeshTags.store')}}" method="post">
                @csrf
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row" style="padding: 20px">
                            <div class="form-group">
                                <label for="newName">نام</label>
                                <input type="text" id="newName" name="name" class="form-control" required>
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
                        ویرایش تگ
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>

                <form action="{{route('goyeshTags.doEdit')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="editId">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row" style="padding: 20px">
                            <div class="form-group">
                                <label for="editName">نام</label>
                                <input type="text" id="editName" name="name" class="form-control" required>
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
                        پاک کردن تگ
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('goyeshTags.delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="deleteId" name="id">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            ایا می خواهید  "<span id="deleteName"></span> "را پاک کنید؟
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
        var tags = {!! $tags !!};

        function editModal(_id){

            for(i = 0; i < tags.length; i++){
                if(tags[i]['id'] == _id){
                    document.getElementById('editId').value = _id;
                    document.getElementById('editName').value = tags[i]['name'];
                }
            }

            $('#editModal').modal();
        }

        function deleteModal(_id){
            for(i = 0; i < tags.length; i++){
                if(tags[i]['id'] == _id){
                    document.getElementById('deleteId').value = _id;
                    document.getElementById('deleteName').innerText = tags[i]['name'];
                }
            }

            $('#deleteModal').modal();
        }

    </script>

@stop