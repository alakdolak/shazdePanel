@extends('layouts.structure')

@section('header')
    @parent

    <style>
        th, td{
            text-align: right;
        }
        .butMain{
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            color: white;
        }
        .editButton{
            background: steelblue;
            width: auto;
            padding: 10px;
            border-radius: 0;
            margin: 0px 10px
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
                                        <span id="newCommentCount"  class="label label-success">
                                            {{count($nonConfirmVideo)}}
                                        </span>
                                        ویدیو های جدید
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#menu1">
                                        ویدیو های تایید شده
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content border mb-3">

                                <div id="home" class="container-fluid tab-pane active">
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
                                                        <th>نام کاربری</th>
                                                        <th>عنوان</th>
                                                        <th>دسته بندی</th>
                                                        <th>متن</th>
                                                        <th>تاریخ بارگذاری</th>
                                                        <th>ویدیو</th>
                                                        <th>thumbnail</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($nonConfirmVideo as $item)
                                                        <tr>
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <div class="text_{{$item->id}}">
                                                                    {{$item->title}}
                                                                </div>
                                                                <div class="input_{{$item->id}}" style="display: none">
                                                                    <input type="text" class="form-control" id="titleInput_{{$item->id}}" value="{{$item->title}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text_{{$item->id}}">
                                                                    {{$item->categoryName}}
                                                                </div>
                                                                <div class="input_{{$item->id}}" style="display: none">
                                                                    <select id="categoryInput_{{$item->id}}" class="form-control">
                                                                        @foreach($videoCategory as $category)
                                                                            <option value="{{$category->id}}" {{$category->id == $item->categoryId ? 'selected' : ''}}>{{$category->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if($item->description != null)
                                                                    <a onclick="showDescription({{$item->id}})" style="cursor: pointer">
                                                                        مشاهده معرفی
                                                                    </a>
                                                                @else
                                                                    <div onclick="showDescription({{$item->id}})" style="cursor: pointer">
                                                                        ایجاد معرفی
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{$item->date}} - {{$item->time}}
                                                            </td>
                                                            <td>
                                                                <a onclick="showVideo({{$item->id}})" style="cursor: pointer">
                                                                    مشاهده ویدیو
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a onclick="showThumbnail({{$item->id}})" style="cursor: pointer">
                                                                    مشاهده thumbnail
                                                                </a>
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div style="display: flex; justify-content: center;">
                                                                    <span class="butMain" style="background-color: greenyellow" onclick="confirmVideoFunc({{$item->id}}, this)">
                                                                        <i class="fa fa-check"></i>
                                                                    </span>
                                                                    <span class="butMain editButton" onclick="openEdit({{$item->id}}, this)">
                                                                        ویرایش
                                                                    </span>
                                                                    <span class="butMain editButton " style="display: none;" onclick="storeEdit({{$item->id}}, this)">
                                                                        ذخیره
                                                                    </span>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteVideo({{$item->id}}, this)">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
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

                                <div id="menu1" class="container-fluid tab-pane fade">
                                    <br>
                                    <div class="container-fluid">

                                        <div class="row">
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                <table class="table table-striped  table-bordered" dir="rtl" data-toggle="table" data-pagination="true" data-search="true">
                                                    <thead>
                                                    <tr>
                                                        <th>نام کاربری</th>
                                                        <th>عنوان</th>
                                                        <th>دسته بندی</th>
                                                        <th>متن</th>
                                                        <th>تاریخ بارگذاری</th>
                                                        <th>ویدیو</th>
                                                        <th>thumbnail</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($confirmedVideo as $item)
                                                        <tr>
                                                            <td>{{$item->username}}</td>
                                                            <td>
                                                                <div class="text_{{$item->id}}">
                                                                    {{$item->title}}
                                                                </div>
                                                                <div class="input_{{$item->id}}" style="display: none">
                                                                    <input type="text" class="form-control" id="titleInput_{{$item->id}}" value="{{$item->title}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text_{{$item->id}}">
                                                                    {{$item->categoryName}}
                                                                </div>
                                                                <div class="input_{{$item->id}}" style="display: none">
                                                                    <select id="categoryInput_{{$item->id}}" class="form-control">
                                                                        @foreach($videoCategory as $category)
                                                                            <option value="{{$category->id}}" {{$category->id == $item->categoryId ? 'selected' : ''}}>{{$category->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if($item->description != null)
                                                                    <a onclick="showDescription({{$item->id}})" style="cursor: pointer">
                                                                        مشاهده معرفی
                                                                    </a>
                                                                @else
                                                                    <div onclick="showDescription({{$item->id}})" style="cursor: pointer">
                                                                        ایجاد معرفی
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{$item->date}} - {{$item->time}}
                                                            </td>
                                                            <td>
                                                                <a onclick="showVideo({{$item->id}})" style="cursor: pointer">
                                                                    مشاهده ویدیو
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a onclick="showThumbnail({{$item->id}})" style="cursor: pointer">
                                                                    مشاهده thumbnail
                                                                </a>
                                                            </td>
                                                            <td style="display: flex; justify-content: space-around">
                                                                <div style="display: flex; justify-content: center;">
                                                                    <span class="butMain" style="background-color: darkgoldenrod; width: auto; padding: 10px" onclick="confirmVideoFunc({{$item->id}}, this)">
                                                                        توقف
                                                                    </span>
                                                                    <span class="butMain editButton" onclick="openEdit({{$item->id}}, this)">
                                                                        ویرایش
                                                                    </span>
                                                                    <span class="butMain editButton " style="display: none;" onclick="storeEdit({{$item->id}}, this)">
                                                                        ذخیره
                                                                    </span>
                                                                    <span class="butMain" style="background-color: red" onclick="deleteVideo({{$item->id}}, this)">
                                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                                    </span>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal fade" id="descriptionModal" style="direction: rtl; text-align: right">
            <input type="hidden" id="descriptionId">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title" id="descriptionModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body" id="descriptionModalBody"></div>
                    <button class="btn btn-primary" onclick="editDescriptionModal()">ویرایش</button>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- The Modal -->
        <div class="modal fade" id="editDescriptionModal" style="direction: rtl; text-align: right">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title" id="descriptionModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body">
                        <textarea name="editDescriptionText" id="editDescriptionText" rows="5" class="form-control" style="width: 100%;" maxlength="400"></textarea>
                    </div>
                    <div class="modal-footer" style="display: flex; justify-content: center; align-items: center;">
                        <button class="btn btn-success" onclick="storeDescription()">ذخیره</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="videoModal" style="direction: rtl; text-align: right">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title" id="videoModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body">
                        <video id="videoVideo" src="" style="width: 100%" controls></video>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="thumbnailModal" style="direction: rtl; text-align: right">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <input type="hidden" id="thumbnailModalId">
                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title" id="thumbnailModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body">
                        <img src="" id="thumbnailImg" style="width: 100%;">
                        <button class="btn btn-primary" onclick="openThumbnailEdit()">ویرایش عکس</button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="editThumbnailModal" style="direction: rtl; text-align: right">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <input type="hidden" id="thumbnailModalId">
                    <div class="modal-header" style="display: flex;">
                        <h4 class="modal-title" id="thumbnailModalHeader"></h4>
                        <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                    </div>
                    <div class="modal-body" style="display: flex; justify-content: center; align-items: center; flex-direction: column">
                        <video crossOrigin="anonymous" id="editVideoThumbnailVideo" src="" style="height: 300px" controls></video>
                        <button onclick="cropImg()">برش تصویر</button>
                        <canvas id="resultThumbnail" class="resultThumbnail"  style="height: 300px"></canvas>
                    </div>

                    <div class="modal-footer" style="display: flex; justify-content: center; align-items: center">
                        <button class="btn btn-success" onclick="setNewThumbnail()">تایید</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                    </div>

                </div>
            </div>
        </div>

        <script>
            let video = {!! $videos !!};
            let confirmVideo = @json($confirmedVideo);
            let nonConfirmVideo = @json($nonConfirmVideo);


            function showDescription(_id){
                let v = null;
                for(let i = 0; i < video.length; i++){
                    if(_id == video[i].id){
                        v = video[i];
                        break;
                    }
                }

                $('#descriptionId').val(_id);
                $('#descriptionModalHeader').text(v.title);
                $('#descriptionModalBody').text(v.description);
                $('#descriptionModal').modal('show');
            }

            function editDescriptionModal(){
                let id = $('#descriptionId').val();
                let vid = null;
                for(let i = 0; i < video.length; i++){
                    if(id == video[i].id){
                        vid = video[i];
                        break;
                    }
                }

                $('#editDescriptionText').val(vid.description);
                $('#editDescriptionModal').modal('show');
            }

            function storeDescription(){
                let id = $('#descriptionId').val();
                let text = $('#editDescriptionText').val();
                openLoading();
                $.ajax({
                    type : 'post',
                    url : '{{route("vod.doEditVideo")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        id: id,
                        text: text
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                for(let i = 0; i < video.length; i++){
                                    if(id == video[i].id){
                                        video[i]['description'] = text;
                                        $('#descriptionModalBody').text(text);
                                        break;
                                    }
                                }
                            }
                            else
                                console.log(response['msg']);
                        }
                        catch (e) {
                            console.log(e)
                        }
                        closeLoading();
                    },
                    error: function(err){
                        console.log(err);
                        closeLoading();
                    }
                });
                $('#editDescriptionModal').modal('hide');

            }

            function showVideo(_id){
                let vid;
                for(let i = 0; i < video.length; i++){
                    if(_id == video[i].id){
                        vid = video[i];
                        break;
                    }
                }

                $('#videoModalHeader').text(vid.title);
                $('#videoVideo').attr('src', vid.video);
                $('#videoModal').modal('show');
            }

            function showThumbnail(_id){
                let vid;
                for(let i = 0; i < video.length; i++){
                    if(_id == video[i].id){
                        vid = video[i];
                        break;
                    }
                }

                $('#thumbnailModalId').val(vid.id);
                $('#thumbnailModalHeader').text(vid.title);
                $('#thumbnailImg').attr('src', vid.thumbnail);
                $('#thumbnailModal').modal('show');
            }

            let newThumbnailCrop;
            function openThumbnailEdit(){
                newThumbnailCrop = null;
                let id = $('#thumbnailModalId').val();
                let vid;
                for(let i = 0; i < video.length; i++){
                    if(id == video[i].id){
                        vid = video[i];
                        break;
                    }
                }

                $('#editThumbnailModal').modal('show');
                $('#editVideoThumbnailVideo').attr('src', vid.video);
            }

            function cropImg(){
                let videoThumbnailDiv = document.getElementById('editVideoThumbnailVideo');
                var canvasThumbnail = document.getElementById('resultThumbnail');
                canvasThumbnail.width = videoThumbnailDiv.videoWidth;
                canvasThumbnail.height = videoThumbnailDiv.videoHeight;
                canvasThumbnail.getContext('2d').drawImage(videoThumbnailDiv, 0, 0, canvasThumbnail.width, canvasThumbnail.height);
                newThumbnailCrop = canvasThumbnail.toDataURL();
            }

            function setNewThumbnail(){
                let id = $('#thumbnailModalId').val();

                if(newThumbnailCrop != null) {
                    let formData = new FormData;
                    formData.append('_token', '{{csrf_token()}}');
                    formData.append('id', id);
                    formData.append('pic', newThumbnailCrop);
                    openLoading();
                    $.ajax({
                        type: 'post',
                        url: '{{route("vod.editThumbnail")}}',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            try {
                                response = JSON.parse(response);
                                if (response['status'] == 'ok') {
                                    let vid;
                                    $('#thumbnailImg').attr('src', newThumbnailCrop);
                                    for (let i = 0; i < video.length; i++) {
                                        if (id == video[i].id) {
                                            vid = video[i];
                                            video[i].thumbnail = newThumbnailCrop;
                                            break;
                                        }
                                    }
                                } else
                                    console.log(response['msg']);
                            } catch (e) {
                                console.log(e)
                            }
                            closeLoading();
                        },
                        error: function (err) {
                            closeLoading();
                            console.log(err);
                        }
                    });
                }

                $('#editThumbnailModal').modal('hide');
            }

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


            function confirmVideoFunc(_id, _element){
                let vid;
                for(let i = 0; i < video.length; i++){
                    if(_id == video[i].id){
                        vid = video[i];
                        break;
                    }
                }

                $.ajax({
                    type: 'post',
                    url: '{{route("vod.confirm")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                $(_element).parent().parent().parent().remove();
                            }
                            else{
                                console.log('BackEnd Error');
                                console.error(response['msg']);
                            }
                        }
                        catch (e) {
                            console.log('Parse Error');
                            console.error(e);
                        }
                    },
                    error: function(err){
                        console.log('server Error');
                        console.error(err);
                    }
                })
            }

            function openEdit(_id, _element){
                $('.text_' + _id).hide();
                $('.input_' + _id).show();
                $(_element).hide();
                $(_element).prev().hide();
                $(_element).next().next().hide();

                $(_element).next().show();
            }

            function storeEdit(_id, _element){
                $('.text_' + _id).show();
                $('.input_' + _id).hide();
                $(_element).hide();

                $(_element).prev().show();
                $(_element).prev().prev().show();
                $(_element).next().show();

                let title = $('#titleInput_' + _id).val();
                let categoryId = $('#categoryInput_' + _id).val();
                let categoryName = $('#categoryInput_' + _id).text();

                $.ajax({
                    type: 'post',
                    url: '{{route("vod.doEditVideo")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id,
                        title: title,
                        categoryId: categoryId
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                $('#categoryInput_' + _id).parent().prev().text(response['category']);
                                $('#titleInput_' + _id).parent().prev().text(title);
                            }
                            else
                                console.log(response['msg'])
                        }
                        catch (e) {
                            console.log(e)
                        }
                    },
                    error: function(err){
                        console.log(err);
                    }
                })
            }

            function deleteVideo(_id, _element){
                $.ajax({
                    type: 'post',
                    url: '{{route("vod.deleteVideo")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            $(_element).parent().parent().parent().remove();
                        else
                            console.log(response['msg']);
                    },
                    error: function(err){
                        console.log(err)
                    }
                })
            }

        </script>
@stop
