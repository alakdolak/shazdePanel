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
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                    <h1>عکس های پیش فرض </h1>
                    <button class="btn btn-success" data-toggle="modal" data-target="#newModal">عکس پیش فرض جدید</button>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row">

                    @if(count($defaultPics) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">عکس پیش فرض وجود ندارد</h4>
                        </div>
                    @else
                        @foreach($defaultPics as $defaultPic)
                            <div class="col-xs-4 activities">
                                <img width="100" height="100"
                                     src="{{URL::asset('defaultPic') . $defaultPic->name}}" style="width: 100px; height: 100px;">
                                <div class="center">
                                    <button name="deleteActivity" class="btn btn-danger width-auto center" title="حذف عکس پیش فرض" onclick="deleteActive({{$defaultPic->id}}, '{{$defaultPic->name}}')">
                                        <span class="glyphicon glyphicon-remove mg-tp-30per"></span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <div class="modal fade" id="newModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        عکس پیش فرض جدید
                        <span id="activitiesName"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('defaultPics.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row" style="margin-bottom: 10px;  padding: 10px">
                                    <img id="newPic" src="#" style="width: 100%; max-height: 300px;">
                                </div>
                                <div class="row">
                                    <input type="file" name="newPic"  onchange="readURL(this, 'newPic');" required>
                                </div>
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

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        پاک کردن عکس پیش فرض
                        <span id="activitiesName"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('defaultPics.delete')}}" method="post">
                    @csrf
                    <input type="hidden" id="deleteId" name="id">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            ایا می خواهید عکس پیش فرض <span id="deleteName"></span> را پاک کنید؟
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
        @if(count($defaultPics) != 0)
            var defaultPics = {!! $defaultPics !!};
        @endif

        function deleteActive(_id, _name){
            document.getElementById('deleteId').value = _id;
            document.getElementById('deleteName').innerText = _name;
            $('#deleteModal').modal();
        }

        function readURL(input, _id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + _id)
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>


@stop