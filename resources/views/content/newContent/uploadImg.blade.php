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
        .topPics{
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
    </style>

    <link rel="stylesheet" href="{{URL::asset('css/cropper/cropper.css')}}">
    <script src="{{URL::asset('js/cropper/cropper.js')}}"></script>

    <link rel="stylesheet" href="{{asset('packages/dropzone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('packages/dropzone/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('packages/imageUploader/imageUploader.css')}}">
@stop

@section('content')

    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div style="direction: rtl" class="main-sparkline13-hd">
                                <div class="sparkline13-outline-icon"></div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="container-fluid">
                                <div class="row">
                                    <h2 style="display: inline-block">
                                        بارگذاری عکس‌های {{$place->name}}
                                    </h2>
                                    <a href="{{$backUrl}}" class="btn btn-primary" style="float: left">بازگشت</a>
                                </div>

                                <input type="hidden" id="placeId" value="{{$place->id}}">
                                <input type="hidden" id="kindPlaceId" value="{{$kindPlaceId}}">

                                <div>
                                    <div id="picSection">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-2 f_r" >
                                                <div class="form-group">
                                                    <label for="picInput0" class="btn btn-primary">عکس اصلی</label>
                                                </div>
                                            </div>
                                            <div class="col-md-10" style="display: flex; justify-content: space-evenly">
                                                <div class="topPics" style="width: 250px; height: 167px;">
                                                    <img id="mainPicShowReq0" src="{{$place->mainPicF}}" style="height: 100%; width: auto; max-width: 100000px">
                                                </div>
                                                <div class="topPics" style="width: 160px; height: 160px;">
                                                    <img id="mainPicShowSqa0" src="{{$place->mainPicL}}" style="height: 100%; width: auto; max-width: 100000px">
                                                </div>
                                            </div>
                                        </div>

                                        @foreach($place->pics as $pic)
                                            <hr>
                                            <div id="divPic{{$pic->id}}" class="row">
                                                <div class="col-md-4 f_r" >
                                                    <div class="form-group" style="display: flex; flex-direction: column">
                                                        <div>
                                                            <label for="picInput{{$pic->id}}" class="btn btn-primary">
                                                                تغییر عکس
                                                                <input type="file" name="picInput{{$pic->id}}" id="picInput{{$pic->id}}" style="display: none;" onchange="uploadNewPic(this, {{$pic->id}})">
                                                            </label>
                                                            <button class="btn btn-success" onclick="setMainPic({{$pic->id}})"> انتخاب به عنوان عکس اصلی </button>
                                                            <button class="btn btn-danger" onclick="deletePicAns({{$pic->id}})">حذف</button>
                                                        </div>
                                                        <div style="margin-top: 20px;">
                                                            <label>alt عکس</label>
                                                            <input type="text" class="form-control" id="alt{{$pic->id}}" value="{{$pic->alt}}">
                                                            <button class="btn btn-warning" onclick="changeAlt({{$pic->id}})"> تغییر alt</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8" style="display: flex; justify-content: space-evenly">
                                                    <div class="topPics" style="width: 250px; height: 167px;">
                                                        <img id="mainPicShowReq{{$pic->id}}" src="{{$pic->picF}}" style="height: 100%; width: auto; max-width: 100000px">
                                                    </div>
                                                    <div class="topPics" style="width: 160px; height: 160px;">
                                                        <img id="mainPicShowSqa{{$pic->id}}" src="{{$pic->picL}}" style="height: 100%; width: auto; max-width: 100000px">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>


                                    <hr>
                                    <div id="pic1000" class="row">
                                        <div class="col-md-12 f_r" >
                                            <div class="form-group" style="display: flex; flex-direction: column">
                                                <div>
                                                    <label for="picInput1000" class="btn btn-success" onclick="$('#dropzoneModal').modal('show')"> عکس جدید</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div id="pic1000" class="row">
                                        <div class="col-md-12 f_r" >
                                            <div class="form-group" style="display: flex; flex-direction: column">
                                                <div>
                                                    <a href="{{$backUrl}}" class="btn">خروج</a>
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
                                <button class="btn btn-primary" onclick="cropSquarePic()"> ویرایش حالت مربع </button>
                            </div>
                            <div class="col-md-6" style="display: flex; justify-content: center;flex-direction: column;">
                                <div class="aspect32">
                                    <img id="rectanglePic" class="cropedImg aspect32Img">
                                </div>

                                <button class="btn btn-primary" onclick="cropRectanglePic()"> ویرایش حالت مستطیلی</button>
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


    <div class="modal fade" id="dropzoneModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="text-align: right">
                    <div id="dropzone" class="dropzone"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')

    <script src="{{URL::asset('packages/dropzone/dropzone.js')}}"></script>
    <script src="{{URL::asset('packages/dropzone/dropzone-amd-module.js')}}"></script>
    <script src="{{URL::asset('packages/imageUploader/imageUploader.js')}}"></script>

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

        let myDropzone = new Dropzone("div#dropzone", {
            url: '{{route('place.storeImg')}}',
            paramName: "pic",
            timeout: 60000,
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            },
            parallelUploads: 1,
            acceptedFiles: 'image/*',
            sending: function(file, xhr, _formData){
                _formData.append('kindPlaceId', kindPlaceId);
                _formData.append('placeId', placeId);
                _formData.append('picNumber', 0);
                _formData.append('kind', 'new');
            }

        }).on('success', (file, response) => createNewRow(response.result));

        function createNewRow(_result){
            let text = '<hr><div id="divPi' + _result.id + '" class="row">\n' +
                '                                                <div class="col-md-4 f_r" >\n' +
                '                                                    <div class="form-group" style="display: flex; flex-direction: column">\n' +
                '                                                        <div>\n' +
                '                                                            <label for="picInput' + _result.id + '" class="btn btn-primary">تغییر عکس\n' +
                '                                                                <input type="file" name="picInput' + _result.id + '" id="picInput' + _result.id + '" style="display: none;" onchange="uploadNewPic(this, ' + _result.id + ')">\n' +
                '                                                            </label>\n' +
                '                                                            <button class="btn btn-success" onclick="setMainPic(' + _result.id + ')"> انتخاب به عنوان عکس اصلی </button>\n' +
                '                                                            <button class="btn btn-danger" onclick="deletePicAns(' + _result.id + ')">حذف</button>\n' +
                '                                                        </div>\n' +
                '                                                        <div style="margin-top: 20px;">\n' +
                '                                                            <label>\n' +
                '                                                                alt عکس\n' +
                '                                                            </label>\n' +
                '                                                            <input type="text" class="form-control" id="alt' + _result.id + '" value="' + _result.alt + '">\n' +
                '                                                            <button class="btn btn-warning" onclick="changeAlt(' + _result.id + ')">\n' +
                '                                                                تغییر alt\n' +
                '                                                            </button>\n' +
                '                                                        </div>\n' +
                '                                                    </div>\n' +
                '                                                </div>\n' +
                '                                                <div class="col-md-8" style="display: flex; justify-content: space-evenly">\n' +
                '                                                    <div class="topPics" style="width: 250px; height: 167px;">\n' +
                '                                                            <img id="mainPicShowReq' + _result.id + '" src="' + _result.pic + '" style="height: 100%; width: auto; max-width: 100000px">\n' +
                '                                                    </div>\n' +
                '                                                    <div class="topPics" style="width: 160px; height: 160px;">\n' +
                '                                                            <img id="mainPicShowSqa' + _result.id + '" src="' + _result.pic + '" style="height: 100%; width: auto; max-width: 100000px">\n' +
                '                                                    </div>\n' +
                '                                                </div>\n' +
                '                                            </div>';

            $('#picSection').append(text);
        }

        function uploadNewPic(_input, _id){
            if (_input.files && _input.files[0]) {
                openLoading();

                formData = new FormData();
                formData.append('_token', '{{csrf_token()}}');
                formData.append('kind', 'edit');
                formData.append('id', _id);
                formData.append('placeId', placeId);
                formData.append('kindPlaceId', kindPlaceId);
                formData.append('pic', _input.files[0]);

                $.ajax({
                    type: 'post',
                    url: '{{route('place.storeImg')}}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        if(response.status == 'ok')
                            location.reload();
                        else{
                            alert('parse error');
                            closeLoading();
                        }
                    },
                    error: function(err){
                        alert('مشکلی در بارگزاری پیش امده لطفا دوباره تلاش کنید');
                        closeLoading();
                    }
                })
            }

        }

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
                let ratio = 1/1;

                if (kindCrop == 2)
                    ratio = 3/2;

                cropper = new Cropper(image, {
                    dragMode: 'move',
                    aspectRatio: ratio,
                    viewMode: 2,
                    autoCropArea: 1,
                    restore: false,
                    movable: true,
                    guides: false,
                    center: false,
                    highlight: false,
                    toggleDragModeOnDblclick: false,
                });

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
                        width: 250,
                        height: 250,
                    });
                    var canvast = cropperSqu.getCroppedCanvas({
                        width: 150,
                        height: 150,
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
                                width: 300,
                                height: 200
                            });
                            var canvass = cropperReq.getCroppedCanvas({
                                width: 610,
                                height: 406,
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
                    _token : '{{csrf_token()}}',
                    id : id,
                    kindPlaceId: kindPlaceId,
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
                    _token : '{{csrf_token()}}',
                    id : _id,
                    kindPlaceId: kindPlaceId,
                    alt : value,
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
                    _token : '{{csrf_token()}}',
                    id : _id,
                    kindPlaceId: kindPlaceId
                },
                success: function(response){
                    if(response == 'ok')
                        window.location.reload();
                }
            });

        }

    </script>
@endsection
