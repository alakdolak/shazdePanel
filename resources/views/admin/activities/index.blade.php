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
                    <h1>فعالیت ها</h1>
                    <button class="btn btn-success" data-toggle="modal" data-target="#newModal">فعالیت جدید</button>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row">

                    @if(count($activities) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">فعالیتی وجود ندارد</h4>
                        </div>
                    @else
                        @foreach($activities as $activity)
                                <div class="col-xs-4 activities">
                                    <img width="100" height="100"
                                         src="{{URL::asset('activities') . '/' . $activity->pic}}" style="width: 100px; height: 100px;">
                                    <div>
                                        {{$activity->name}}
                                    </div>
                                    <div> امتیاز: {{$activity->rate}} </div>
                                    <div class="center">
                                        <button name="editActivity" class="btn btn-info width-auto center" title="ویرایش فعالیت" onclick="editPage({{$activity->id}})">
                                            <span class="glyphicon glyphicon-edit mg-tp-30per"></span>
                                        </button>
                                        <button name="deleteActivity" class="btn btn-danger width-auto center" title="حذف فعالیت" onclick="deleteActive({{$activity->id}}, '{{$activity->name}}')">
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

    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        ویرایش فعالیت
                        <span id="activitiesName"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('activities.doEdit')}}" method="post" id="editForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="editId" id="editId">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row" style="margin-bottom: 10px; padding: 10px">
                                    <img id="activePic" src="#" style="width: 100%; max-height: 300px">
                                </div>
                                <div class="row">
                                    <input type="file" name="editPic"  onchange="readURL(this, 'activePic');">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">نام فعالیت: </label>
                                    <input type="text" name="editName" id="editName" required>
                                </div>
                                <div class="form-group">
                                    <label for="editActualName">Actual Name: </label>
                                    <input type="text" name="editActualName" id="editActualName" required>
                                </div>
                                <div class="form-group">
                                    <label for="editRate">امتیاز فعالیت: </label>
                                    <input type="number" name="editRate" id="editRate" required>
                                </div>
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

    <div class="modal fade" id="newModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                        فعالیت جدید
                        <span id="activitiesName"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('activities.store')}}" method="post" enctype="multipart/form-data">
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="newName">نام فعالیت: </label>
                                    <input type="text" name="newName" id="newName" required>
                                </div>
                                <div class="form-group">
                                    <label for="actualName">Actual Name: </label>
                                    <input type="text" name="actualName" id="actualName" required>
                                </div>
                                <div class="form-group">
                                    <label for="newRate">امتیاز فعالیت: </label>
                                    <input type="number" name="newRate" id="newRate" required>
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
                        پاک کردن فعالیت
                        <span id="activitiesName"></span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>


                <form action="{{route('activities.delete')}}" method="post">
                @csrf
                    <input type="hidden" id="deleteId" name="id">
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            ایا می خواهید فعالیت <span id="deleteName"></span> را پاک کنید؟
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
        @if(count($activities) != 0)
            var activities = {!! $activities !!};
        @endif

        function editPage(_id){

            for(i = 0; i < activities.length; i++){
                if(activities[i]['id'] == _id){
                    document.getElementById('editName').value = activities[i]['name'];
                    document.getElementById('editId').value = activities[i]['id'];
                    document.getElementById('editActualName').value = activities[i]['actualName'];
                    document.getElementById('editRate').value = activities[i]['rate'];
                    document.getElementById('activePic').src = '{{URL::asset('activities')}}/' + activities[i]['pic'];
                }
            }

            $('#myModal').modal();
        }

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