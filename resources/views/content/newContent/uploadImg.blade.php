@extends('layouts.structure')

@section('header')
    @parent
    <style>
        .row {
            direction: rtl;
        }
        .f_r{
            float: right;
        }
        .selectLabel{
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: solid gray 2px;
            border-radius: 10px;
            color: gray;
            cursor: pointer;
            transition: 0.5s;
        }
        .selectLabel:hover{
            background-color: lightcyan;
        }
        .cropedImg{
            width: 100%;
            margin-bottom: 20px;
        }
        .aspect32{
            position: relative;
            width: 100%;
            padding-top: 66.66%;
        }
        .aspect11{
            width: 100%;
            padding-top: 100%; /* 1:1 Aspect Ratio */
            position: relative; /* If you want text inside of it */
        }
        .aspect11Img{
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        .aspect32Img{
            position:  absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            text-align: center;
            font-size: 20px;
            color: white;
        }
        .progress {
            display: none;
            margin-bottom: 1rem;
        }
    </style>

    <link rel="stylesheet" href="{{URL::asset('css/cropper/cropper.css')}}">

    <script src="{{URL::asset('js/cropper/cropper.js')}}"></script>

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
                                    <h2>
                                        بارگذاری عکس‌های {{$place->name}}
                                    </h2>
                                </div>

                                <input type="hidden" id="placeId" value="{{$place->id}}">
                                <input type="hidden" id="kindPlaceId" value="{{$kindPlaceId}}">

                                <div id="picSection">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-2 f_r" >
                                            <div class="form-group">
                                                <label for="picInput0" class="btn btn-primary">
                                                    عکس اصلی
                                                    <input type="file" name="picInput0" id="picInput0" style="display: none;" onchange="showPic(this, 0, '{{$place->picNumber != null ? "edit" : "new"}}')">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-10" style="display: flex; justify-content: space-evenly">
                                            <div style="width: 250px; height: 167px;">
                                                @if($place->picNumber != null)
                                                    <img id="mainPicShowReq0" src="{{URL::asset('_images/' . $kindPlaceName . '/' . $place->file . '/f-'. $place->picNumber)}}" style="width: 100%; height: 100%;">
                                                @else
                                                    <img id="mainPicShowReq0" src="" style="width: 100%; height: 100%; display: none;">
                                                @endif
                                            </div>
                                            <div style="width: 160px; height: 160px;">
                                                @if($place->picNumber != null)
                                                    <img id="mainPicShowSqa0" src="{{URL::asset('_images/' . $kindPlaceName . '/' . $place->file . '/l-'. $place->picNumber)}}" style="width: 100%; height: 100%;">
                                                @else
                                                    <img id="mainPicShowSqa0" src="" style="width: 100%; height: 100%; display: none;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @for($i = 0; $i < count($place->pics); $i++)
                                        <hr>
                                        <div id="divPic{{$place->pics[$i]->id}}" class="row">
                                            <div class="col-md-4 f_r" >
                                                <div class="form-group" style="display: flex; flex-direction: column">
                                                    <div>
                                                        <label for="picInput{{$place->pics[$i]->number}}" class="btn btn-primary">
                                                            تغییر عکس
                                                            <input type="file" name="picInput{{$place->pics[$i]->number}}" id="picInput{{$place->pics[$i]->number}}" style="display: none;" onchange="showPic(this, {{$place->pics[$i]->number}}, 'edit')">
                                                        </label>
                                                        <button class="btn btn-success" onclick="setMainPic({{$place->pics[$i]->id}})">
                                                            انتخاب به عنوان عکس اصلی
                                                        </button>
                                                        <button class="btn btn-danger" onclick="deletePicAns({{$place->pics[$i]->id}})">
                                                            حذف
                                                        </button>
                                                    </div>
                                                    <div style="margin-top: 20px;">
                                                        <label>
                                                            alt عکس
                                                        </label>
                                                        <input type="text" class="form-control" id="alt{{$place->pics[$i]->id}}" value="{{$place->pics[$i]->alt}}">
                                                        <button class="btn btn-warning" onclick="changeAlt({{$place->pics[$i]->id}})">
                                                            تغییر alt
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8" style="display: flex; justify-content: space-evenly">
                                                <div style="width: 250px; height: 167px;">
                                                    @if($place->picNumber != null)
                                                        <img id="mainPicShowReq{{$place->pics[$i]->number}}" src="{{URL::asset('_images/' . $kindPlaceName . '/' . $place->file . '/f-'. $place->pics[$i]->picNumber)}}" style="width: 100%; height: 100%;">
                                                    @else
                                                        <img id="mainPicShowReq{{$place->pics[$i]->number}}" src="" style="width: 100%; height: 100%; display: none;">
                                                    @endif
                                                </div>
                                                <div style="width: 160px; height: 160px;">
                                                    @if($place->picNumber != null)
                                                        <img id="mainPicShowSqa{{$place->pics[$i]->id}}" src="{{URL::asset('_images/' . $kindPlaceName . '/' . $place->file . '/l-'. $place->pics[$i]->picNumber)}}" style="width: 100%; height: 100%;">
                                                    @else
                                                        <img id="mainPicShowSqa{{$place->pics[$i]->id}}" src="" style="width: 100%; height: 100%; display: none;">
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    @endfor


                                    <hr>
                                    <div id="pic1000" class="row">
                                        <div class="col-md-12 f_r" >
                                            <div class="form-group" style="display: flex; flex-direction: column">
                                                <div>
                                                    <label for="picInput1000" class="btn btn-success">
                                                        عکس جدید
                                                        <input type="file" name="picInput1000" id="picInput1000" style="display: none;" onchange="showPic(this, 1000, 'new')">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="pic1000" class="row">
                                        <div class="col-md-12 f_r" >
                                            <div class="form-group" style="display: flex; flex-direction: column">
                                                <div>
                                                    <button type="button" class="btn" onclick="window.location.href = '{{url('newChangeContent/'. $state->id . '/' . $kindPlaceId . '/0')}}'">خروج</button>
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
    </div>

    <div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6" style="display: flex; justify-content: center;flex-direction: column;">
                                <div class="aspect11">
                                    <img id="squarePic" class="cropedImg aspect11Img">
                                </div>

                                <button class="btn btn-primary" onclick="cropSquarePic()">
                                    ویرایش حالت مربع
                                </button>
                            </div>
                            <div class="col-md-6" style="display: flex; justify-content: center;flex-direction: column;">
                                <div class="aspect32">
                                    <img id="rectanglePic" class="cropedImg aspect32Img">
                                </div>

                                <button class="btn btn-primary" onclick="cropRectanglePic()">
                                    ویرایش حالت مستطیلی
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                    <button type="button" class="btn btn-primary" onclick="resizeImg()">بارگزاری عکس</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image" src="#">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop" onclick="cropImg()">Crop</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="text-align: right">
                    ایا می خواهید عکس را پاک کنید؟
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deletePic()">بله حذف شود</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="deletePic">

    <script>
        var kindPlaceId = {{$kindPlaceId}};
        var placeId = {{$place->id}};
        var showPicId;
        var kindCrop;
        var formData;

        var squaResultCanvas;
        var reqResultCanvas;

        var $modal = $('#modal');
        var image = document.getElementById('image');
        var $progress = $('.progress');
        var $progressBar = $('.progress-bar');

        function showPic(input, id, kind) {

            formData = new FormData();
            formData.append('kind', kind);
            formData.append('picNumber', id);
            formData.append('placeId', placeId);
            formData.append('kindPlaceId', kindPlaceId);

            $('#mainModal').modal({backdrop: 'static', keyboard: false});

            showPicId = id;

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#rectanglePic')
                        .attr('src', e.target.result);
                    $('#squarePic')
                        .attr('src', e.target.result);

                    var sqr = document.getElementById('squarePic');
                    var cropper1 = new Cropper(sqr, {
                        dragMode: 'move',
                        aspectRatio: 1/1,
                        viewMode: 2,
                        autoCropArea: 1,
                        restore: false,
                        movable: true,
                        guides: false,
                        center: false,
                        highlight: false,
                        toggleDragModeOnDblclick: false,
                        ready: function(){
                            var pic = cropper1.getCroppedCanvas();
                            $('#squarePic').attr('src', pic.toDataURL());

                            cropper1.destroy();
                            cropper1 = null;
                        }
                    });

                    var req = document.getElementById('rectanglePic');
                    var cropper2 = new Cropper(req, {
                        dragMode: 'move',
                        aspectRatio: 3/2,
                        viewMode: 2,
                        autoCropArea: 1,
                        restore: false,
                        movable: true,
                        guides: false,
                        center: false,
                        highlight: false,
                        toggleDragModeOnDblclick: false,
                        ready: function(){
                            var pic = cropper2.getCroppedCanvas();
                            $('#rectanglePic').attr('src', pic.toDataURL());
                            cropper2.destroy();
                            cropper2 = null;
                        }
                    });
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        window.addEventListener('DOMContentLoaded', function () {

            $('#modal').on('shown.bs.modal', function () {
                if (kindCrop == 1) {
                    cropper = new Cropper(image, {
                        dragMode: 'move',
                        aspectRatio: 1/1,
                        viewMode: 2,
                        autoCropArea: 1,
                        restore: false,
                        movable: true,
                        guides: false,
                        center: false,
                        highlight: false,
                        toggleDragModeOnDblclick: false,
                    });
                }
                else {
                    cropper = new Cropper(image, {
                        dragMode: 'move',
                        aspectRatio: 3/2,
                        viewMode: 2,
                        autoCropArea: 1,
                        restore: false,
                        movable: true,
                        guides: false,
                        center: false,
                        highlight: false,
                        toggleDragModeOnDblclick: false,
                    });
                }
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

        });

        function cropSquarePic(){
            kindCrop = 1;
            var e = document.getElementById('picInput' + showPicId);

            var files = e.files;
            var done = function (url) {
                image.src = url;
                $('#modal').modal({backdrop: 'static', keyboard: false});
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        function cropRectanglePic(){
            kindCrop = 2;
            var e = document.getElementById('picInput' + showPicId);

            var files = e.files;
            var done = function (url) {
                image.src = url;
                // $('#modal').modal('show');
                $('#modal').modal({backdrop: 'static', keyboard: false});
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }

        }

        function cropImg(){
            var canvas1;

            $('#modal').modal('hide');

            if (cropper) {
                if(kindCrop == 1){
                    canvas1 = cropper.getCroppedCanvas();

                    $('#squarePic').attr('src', canvas1.toDataURL());
                    squaResultCanvas = canvas1.toDataURL();
                }
                if(kindCrop == 2){
                    canvas1 = cropper.getCroppedCanvas();

                    $('#rectanglePic').attr('src', canvas1.toDataURL());
                    reqResultCanvas = canvas1.toDataURL();
                }
            }
        }

        function resizeImg(){
            var req = document.getElementById('rectanglePic');
            var squ = document.getElementById('squarePic');

            $progress.show();
            var cropperSqu = new Cropper(squ, {
                viewMode: 3,
                aspectRatio: 1/1,
                autoCropArea: 1,
                ready: function (){
                    var canvasl = cropperSqu.getCroppedCanvas({
                        width: 150,
                        height: 150,
                    });
                    var canvast = cropperSqu.getCroppedCanvas({
                        width: 50,
                        height: 50,
                    });

                    squaResultCanvas = canvasl.toDataURL();

                    canvasl.toBlob(function (blob) {
                        formData.append('l-1', blob, 'l-1.jpg');
                    });
                    canvast.toBlob(function (blob) {
                        formData.append('t-1', blob, 't-1.jpg');
                    });

                    cropperSqu.destroy();
                    cropperSqu = null;

                    var cropperReq = new Cropper(req,{
                        viewMode: 3,
                        aspectRatio: 3/2,
                        autoCropArea: 1,
                        ready: function (){
                            var canvasf = cropperReq.getCroppedCanvas({
                                width: 250,
                                height: 167
                            });
                            var canvass = cropperReq.getCroppedCanvas({
                                width: 550,
                                height: 367,
                            });

                            squaResultCanvas = canvass.toDataURL();

                            canvasf.toBlob(function (blob) {
                                formData.append('f-1', blob, 'f-1.jpg');
                            });
                            canvass.toBlob(function (blob) {
                                formData.append('s-1', blob, 's-1.jpg');

                                var input = document.getElementById('picInput' + showPicId);
                                formData.append('mainPic', input.files[0]);
                                sendData();
                            });

                            cropperReq.destroy();
                            cropperReq = null;
                        }
                    });

                }
            });

        }

        function sendData(){
            $.ajax('{{route("getCrop")}}', {
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                xhr: function () {
                    var xhr = new XMLHttpRequest();

                    xhr.upload.onprogress = function (e) {
                        var percent = '0';
                        var percentage = '0%';

                        if (e.lengthComputable) {
                            percent = Math.round((e.loaded / e.total) * 100);
                            percentage = percent + '%';
                            $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                        }
                    };

                    return xhr;
                },

                success: function (response) {
                    if(response == 'ok')
                        window.location.reload();
                    else if(response == 'nok1' || response == 'nok3')
                        alert('مشکلی در بارگزاری اطلاعات پیش امده');
                    else if(response == 'nok2')
                        alert('فرمت عکس باید یا jpeg باشد و یا png');
                },
                error: function () {
                    alert('بارگذاری عکس با مشکل مواجه شد');
                },
                complete: function () {
                    // $progress.hide();
                },
            });
        };

        function deletePicAns(_id){
            document.getElementById('deletePic').value = _id;
            $("#deleteModal").modal('show');
        }

        function deletePic(){
            var id = document.getElementById('deletePic').value;
            $.ajax({
                type: 'post',
                url : '{{route('deletePlacePic')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : id,
                },
                success: function(response){
                    if(response == 'ok')
                        $('#divPic' + id).remove();
                }
            })
        }

        function changeAlt(_id){
            var value = document.getElementById('alt' + _id).value;

            $.ajax({
                type: 'post',
                url : '{{route('changeAltPic')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id,
                    'alt' : value,
                },
                success: function(response){
                    if(response == 'ok')
                        alert('alt عکس با موفقیت تغییر پیدا کرد')
                }
            });

        }

        function setMainPic(_id){
            $.ajax({
                type: 'post',
                url : '{{route('setMainPic')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id,
                },
                success: function(response){
                    if(response == 'ok')
                        window.location.reload();
                }
            });

        }

    </script>

@stop