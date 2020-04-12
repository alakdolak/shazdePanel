@extends('layouts.structure')

@section('header')
    <link rel="stylesheet" href="{{asset('packages/dropzone/basic.css')}}">
    <link rel="stylesheet" href="{{asset('packages/dropzone/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('packages/imageUploader/imageUploader.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/cropper/cropper.css')}}">

    <script src="{{URL::asset('js/cropper/cropper.js')}}"></script>

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
            display: flex;
            align-items: center;
        }

        label {
            min-width: 150px;
        }

        input, select {
            width: 200px;
        }

        #result {
            max-height: 300px;
            overflow: auto;
            margin-top: 20px;
        }
        .submitButton{
            width: 100%;
            font-size: 20px;
            border-radius: 15px;
            margin: 10px 0px;
            padding: 15px;
            margin-top: 30px;
        }
        .submitButton:hover{
            background: #2c7100 !important;
        }
        .resultOfCropPic{
            padding: 10px;
            border: solid 1px;
            border-radius: 11px;
        }
    </style>
@stop

@section('content')

    <div class="col-md-12">
        <div class="container-fluid" style="margin-top: 30px;">
            <div class="row" style="padding: 15px; display: flex; background-color: #f9f9f9;">
                @if($mode == 'add')
                    <h1>افزودن اطلاعات شهر جدید</h1>
                @else
                    <h1>ویرایش شهر {{$city->name}}</h1>
                    <form method="post" action="{{route('city.delete')}}" style="margin-right: auto;">
                        @csrf
                        <input type="hidden" name="id" value="{{$city->id}}">
                        <button type="submit" class="btn btn-danger">حذف شهر</button>
                    </form>
                @endif
            </div>
            <div class="row">
                <div class="container-fluid" style="background: white; padding: 15px; width: 100%;">
                    <div class="row" style="padding: 0px 40px; text-align: right">
                        <form action="{{route('city.store')}}" method="post" enctype="multipart/form-data" style="width: 100%;">
                            @csrf
                            <input type="hidden" name="id" id="cityId" value="{{isset($city->id) ? $city->id : 0}}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city_name">نام شهر:</label>
                                        <input type="text" class="form-control" id="city_name" name="city_name" value="{{isset($city->name) ? $city->name : ''}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">استان:</label>
                                        <select class="form-control" id="state" name="state">
                                            @foreach($state as $item)
                                                <option value="{{$item->id}}" {{isset($city->stateId) && $city->stateId == $item->id ? 'selected' : ''}} >
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">انتخاب از روی نقشه</button>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city_x">X:</label>
                                        <input type="text" class="form-control" id="city_x" name="city_x" value="{{isset($city->x) ? $city->x : ''}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city_y">Y:</label>
                                        <input type="text" class="form-control" id="city_y" name="city_y" value="{{isset($city->y) ? $city->y : ''}}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="comment">متن:</label>
                                <textarea class="form-control" rows="20" id="comment" name="comment"> {!! isset($city->description) ? $city->description : '' !!}</textarea>
                            </div>

                            <div id="picSection" class="form-group" style="display: {{isset($city->id) ? 'block' : 'none'}}">
                                <label for="image">عکس:</label>
                                <div id="dragAndDropSection"></div>
                            </div>

                            <button type="button" class="btn btn-success submitButton" onclick="storeDate()">
                                {{$mode == 'add' ? 'ایجاد' : 'ویرایش'}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="mapModal">
        <div class="modal-dialog modal-lg" style="width: 95%;">
            <div class="modal-content">
                <div class="modal-body" style="direction: rtl">
                    <div id="map" style="width: 100%; height: 85vh; background-color: red"></div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="text-align: center">
                    <button class="btn nextStepBtnTourCreation" data-dismiss="modal">
                        تایید
                    </button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="mainCropPic" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document" style="width: 97%">
            <div class="modal-content" style="height: 95vh">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6" style="display: flex; justify-content: center; flex-direction: column">
                                <div style="font-size: 20px; font-weight: bold; text-align: center;">نتیجه</div>
                                <hr>
                                <div class="row resultOfCropPic">
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary" onclick="cropPicModal(1)">crop</button>
                                        <div class="form-group">
                                            <label for="compresInput_1">کیفیت عکس</label>
                                            <input type="number" min="0" max="90" id="compresInput_1" class="form-control" value="90">
                                        </div>
                                        <div id="sizeOFResultPic_1"></div>
                                        <button type="button" class="btn btn-warning" onclick="showTestPic(1)">اعمال تغییرات</button>
                                    </div>
                                    <div class="col-md-8">
                                        <img id="resultMainCropPic_1" src="#" style="max-height: 100%; max-width: 100%;">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6" style="display: flex; justify-content: center; flex-direction: column">
                                <div style="font-size: 20px; font-weight: bold; text-align: center;">عکس اصلی</div>
                                <div id="mainPicSize" style="font-size: 15px; font-weight: bold; text-align: center;"></div>
                                <img id="mainPicMainCropPic" src="" style="max-width: 100%; max-height: 100%;">
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
                    <button type="button" class="btn btn-success" onclick="resizeImg()">ثبت عکس</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width: 97%">
            <div class="modal-content">
                <div class="modal-body" style="height: 90vh">
                    <div class="img-container" style="height: 100%;">
                        <img id="cropImage" src="#" style="max-width: 100%; max-height: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop" onclick="cropImg()">Crop</button>
                </div>
            </div>
        </div>
    </div>


@stop

@section('script')
    <script>
        let map;
        let marker = 0;

        function init(){
            var mapOptions = {
                zoom: 6,
                center: new google.maps.LatLng(32.42056639964595, 54.00537109375),
                // How you would like to style the map.
                // This is where you would paste any style found on Snazzy Maps.
                styles: [{
                    "featureType": "landscape",
                    "stylers": [{"hue": "#FFA800"}, {"saturation": 0}, {"lightness": 0}, {"gamma": 1}]
                }, {
                    "featureType": "road.highway",
                    "stylers": [{"hue": "#53FF00"}, {"saturation": -73}, {"lightness": 40}, {"gamma": 1}]
                }, {
                    "featureType": "road.arterial",
                    "stylers": [{"hue": "#FBFF00"}, {"saturation": 0}, {"lightness": 0}, {"gamma": 1}]
                }, {
                    "featureType": "road.local",
                    "stylers": [{"hue": "#00FFFD"}, {"saturation": 0}, {"lightness": 30}, {"gamma": 1}]
                }, {
                    "featureType": "water",
                    "stylers": [{"hue": "#00BFFF"}, {"saturation": 6}, {"lightness": 8}, {"gamma": 1}]
                }, {
                    "featureType": "poi",
                    "stylers": [{"hue": "#679714"}, {"saturation": 33.4}, {"lightness": -25.4}, {"gamma": 1}]
                }]
            };
            var mapElementSmall = document.getElementById('map');
            map = new google.maps.Map(mapElementSmall, mapOptions);

            google.maps.event.addListener(map, 'click', function(event) {
                getLat(event.latLng);
            });

            setNewMarker();
        }

        function getLat(location){
            if(marker != 0)
                marker.setMap(null);
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            document.getElementById('city_x').value = marker.getPosition().lat();
            document.getElementById('city_y').value = marker.getPosition().lng();
        }

        function setNewMarker(){
            if(marker != 0)
                marker.setMap(null);
            var lat = document.getElementById('city_x').value;
            var lng = document.getElementById('city_y').value;

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map,
            });
        }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCdVEd4L2687AfirfAnUY1yXkx-7IsCER0&callback=init"></script>

    <script src="{{URL::asset('packages/dropzone/dropzone.js')}}"></script>
    <script src="{{URL::asset('packages/dropzone/dropzone-amd-module.js')}}"></script>
    <script src="{{URL::asset('packages/imageUploader/imageUploader.js')}}"></script>

    <script>
        let cityId = {{isset($city->id) ? $city->id : 0}};
        let cityPic = [];

        @if(isset($city->pic))
            cityPics = {!! $city->pic !!};
            for(x of cityPics)
                cityPic[cityPic.length] = {
                    id: x['id'],
                    url: x['pic'],
                    haveAlt: false,
                    haveEdit: false,
                    alt: ''
                };
        @endif

        function storeDate(){
            openLoading();

            let cityIdVal = $('#cityId').val();
            let city_name = $('#city_name').val();
            let state = $('#state').val();
            let city_x = $('#city_x').val();
            let city_y = $('#city_y').val();
            let comment = $('#comment').val();

            $.ajax({
                type: 'post',
                url: '{{route("city.store")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: cityIdVal,
                    city_name: city_name,
                    state: state,
                    city_x: city_x,
                    city_y: city_y,
                    comment: comment,
                },
                success: function(response){
                    closeLoading();
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            cityId = response['result']['id'];
                            $('#picSection').css('display', 'block');
                            $('#cityId').val(cityId);
                        }
                        else
                            alert('error 0')
                    }
                    catch (e) {
                        alert('error 1')
                    }
                },
                error: function(err){
                    closeLoading();
                    alert('error 2')
                }
            });

        }

        let createNewPicSectionInfo = {
            id : 'dragAndDropSection',
            url: '{{route("city.store.image")}}',
            csrf: '{{csrf_token()}}',
            data: {
                id: cityId
            },
            haveAlt: false,
            haveEdit: false,
            initPic: cityPic,
            initCallBack: '',
            callBack: '',
            onDeletePic: deletePic,
            onEditPic: editPic,
            onChangeAlt: ''
        };

        let createdDropZone = createNewPicSection(createNewPicSectionInfo);

        function deletePic(_id){
            $.ajax({
                type: 'post',
                url: '{{route("city.delete.image")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            $('#uploadedPic_' + createdDropZone['index'] + '_' + _id).remove();
                        else
                            alert('error 1 : ' + response['status']);
                    }
                    catch (e) {
                        alert('error 2');
                    }
                },
                error: function(error){
                    alert('error 3');
                }
            })
        }



        let mainCropPic;
        function editPic(_id, _src){
            $.ajax({
                type: 'post',
                url: '{{route('city.size.image')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            mainCropPic = _src;

                            $('#mainPicSize').text('حجم عکس اصلی : ' + response['result']);
                            $('#mainPicMainCropPic').attr('src', _src);
                            $('#mainCropPic').modal('show')
                        }
                        else{
                            alert('error 3')
                        }
                    }
                    catch (e) {
                        alert('error 2')
                    }
                },
                error: function(err){
                    alert('error 1')
                }
            })
        }

        let cropper;
        let image = document.getElementById('cropImage');
        let numberPicOpen = 0;
        function cropPicModal(_numberPicOpen){
            image.src = mainCropPic;
            numberPicOpen = _numberPicOpen;

            $('#cropModal').modal({backdrop: 'static', keyboard: false})
                .ready(function(){
                    let ratio = 3/2;
                    setTimeout(function(){
                        cropper = new Cropper(image, {
                            dragMode: 'move',
                            aspectRatio: ratio,
                            viewMode: 2,
                        });
                    }, 500);
                })
                .on('hidden.bs.modal', function () {
                    cropper.destroy();
                    cropper = null;
                });
        }

        let cropedPic;
        function cropImg(){
            var canvas1;

            $('#cropModal').modal('hide');
            canvas1 = cropper.getCroppedCanvas({
                width: 960,
            });
            $('#resultMainCropPic_' + numberPicOpen).attr('src', canvas1.toDataURL());
            canvas1.toBlob(function (blob) {
                let formData = new FormData();
                _kind = 1;
                formData.append('pic', blob, 'pic.jpg');
                $.ajax({
                    type: 'post',
                    url: '{{route("image.upload.test")}}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        closeLoading();
                        response = JSON.parse(response);
                        if(response['status'] == 'ok'){
                            $('#resultMainCropPic_' + _kind).attr('src', response['result']['url']);
                            $('#sizeOFResultPic_' + _kind).text('حجم عکس :' + response['result']['size']);
                        }
                        else
                            alert('error 3');
                    },
                    error: function(err){
                        alert('error 2');
                        closeLoading();
                    }
                });
            });
        }

        function showTestPic(_kind){

            openLoading();
            try {
                let formData = new FormData();
                formData.append('_tokne', '{{csrf_token()}}');

                let pic = document.getElementById('resultMainCropPic_' + _kind);

                let cropperSqu = new Cropper(pic, {
                    ready: function () {
                        var canvasl = cropperSqu.getCroppedCanvas({
                            width: 960
                            // imageSmoothingQuality: 'medium',
                        });

                        squaResultCanvas = canvasl.toDataURL();
                        canvasl.toBlob(function (blob) {
                            formData.append('pic', blob, 'pic.jpg');

                            let compress = $('#compresInput_1').val();
                            formData.append('compress', compress);
                            $.ajax({
                                type: 'post',
                                url: '{{route("image.upload.test")}}',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response){
                                    closeLoading();
                                    response = JSON.parse(response);
                                    if(response['status'] == 'ok'){
                                        $('#resultMainCropPic_' + _kind).attr('src', response['result']['url']);
                                        $('#sizeOFResultPic_' + _kind).text('حجم عکس :' + response['result']['size']);
                                    }
                                    else
                                        alert('error 3');
                                },
                                error: function(err){
                                    alert('error 2');
                                    closeLoading();
                                }
                            });
                        });

                        cropperSqu.destroy();
                        cropperSqu = null;
                    }
                });
            }
            catch (e) {
                alert('error 1')
                closeLoading();
            }
        }


        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#pic')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>
@endsection

