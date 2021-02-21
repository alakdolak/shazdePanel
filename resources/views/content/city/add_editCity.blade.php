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

        .mainPicLabel{
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            cursor: pointer;
            border: solid 3px lightgray;
            border-radius: 10px;
            color: black;
            flex-direction: column;
            padding: 10px;
        }
        .mainPicLabel:before{
            content: 'عکس اصلی';
            position: absolute;
            top: 0px;
            z-index: 1;
            background: white;
            border-radius: 10px;
            padding: 0px 7px;
        }
        .mainPicLabel > img{
            max-width: 100%;
            max-height: 100%;
        }

        .resultOfSearch{
            position: absolute;
            background: white;
            width: 100%;
            max-height: 300px;
            overflow: auto;
            top: 100%;
            box-shadow: 0px 0px 3px 1px #80808052;
            border-radius: 10px;
            z-index: 1;
        }
        .resultOfSearch .item{
            margin-bottom: 5px;
            padding: 5px;
            border-bottom: solid 1px lightgray;
            cursor: pointer;
        }
        .resultOfSearch .item:hover{
            background: var(--koochita-light-green);
        }
    </style>
@stop

@section('content')

    <div class="col-md-12">
        <div class="container-fluid" style="margin-top: 30px;">
            <div class="row" style="padding: 15px; display: flex; background-color: #f9f9f9;">
                @if($mode == 'add')
                    <h1>افزودن اطلاعات {{$text}} جدید</h1>
                @else
                    <h1>ویرایش {{$text}} {{$city->name}}</h1>
{{--                    <form method="post" action="{{route('city.delete')}}" style="margin-right: auto;">--}}
{{--                        @csrf--}}
{{--                        <input type="hidden" name="id" value="{{$city->id}}">--}}
{{--                        <button type="submit" class="btn btn-danger">حذف {{$text}}</button>--}}
{{--                    </form>--}}
                @endif
            </div>
            <div class="row">
                <div class="container-fluid" style="background: white; padding: 15px; width: 100%;">
                    <div class="row" style="padding: 0px 40px; text-align: right">
                        <div style="width: 100%;">
                            <input type="hidden" id="typeOfLocation" value="{{$type}}">
                            <input type="hidden" id="cityId" value="{{isset($city->id) ? $city->id : 0}}">

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city_name">نام {{$text}}:</label>
                                        <input type="text" class="form-control" id="city_name" name="city_name" value="{{isset($city->name) ? $city->name : ''}}">
                                    </div>
                                </div>

                                @if($type === "city" || $type === "village")
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="state">کشور:</label>
                                            <select class="form-control" id="country" name="country" onchange="changeCountry(this)">
                                                <option value="-1">ایران</option>
                                                @foreach($country as $item)
                                                    <option value="{{$item->id}}" {{isset($city->stateId) && $city->stateId == $item->id ? 'selected' : ''}} >
                                                        {{$item->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="stateSelect" class="col-md-3" >
                                        <div class="form-group">
                                            <label for="state">استان:</label>
                                            <select class="form-control" id="state" name="state">
                                                <option value="-1"></option>
                                                @foreach($state as $item)
                                                    <option value="{{$item->id}}" {{isset($city->stateId) && $city->stateId == $item->id ? 'selected' : ''}} >
                                                        {{$item->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if($type === "village")
                                        <div class="col-md-3">
                                            <div class="form-group" style="position: relative;">
                                                <label for="state">شهر یا شهر نزدیک:</label>
                                                <input type="text" id="villageCity" class="form-control" onkeyup="findCityForVillage(this)" value="{{isset($city->villageCityName) ? $city->villageCityName : ''}}">
                                                <input type="hidden" id="villageCityId" value="{{isset($city->isVillage) ? $city->isVillage : 0}}">
                                                <div id="citySearchResult" class="resultOfSearch"></div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            @if($type === "city" || $type === "village")
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
                            @endif

                            <div class="form-group">
                                <label for="description">متن:</label>
                                <textarea class="form-control" rows="20" id="description" name="description"> {!! isset($city->description) ? $city->description : '' !!}</textarea>
                            </div>

                            <div id="picSection" class="{{isset($city->id) ? '' : 'hidden'}}">
                                <div class="form-group">
                                    @if($type === "city" || $type === "village")
                                        <label for="showMainPic">عکس اصلی:</label>
                                        <img id="showMainPic" src="{{isset($city->image) ? $city->image : ''}}" style="max-width: 300px;">
                                    @else
                                        <label class="mainPicLabel" for="mainPic">
                                            <img id="showMainPic" src="{{isset($city->image) ? $city->image : ''}}">
                                        </label>
                                        <input type="file" id="mainPic" accept="image/*" onchange="newMainPic(this)" style="display: none;">
                                    @endif
                                </div>

                                @if($type === "city" || $type === "village")
                                    <div class="form-group">
                                        <label for="image">عکس:</label>
                                        <div id="dragAndDropSection"></div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" class="btn btn-success submitButton" onclick="storeDate()">{{$mode == 'add' ? 'ایجاد' : 'ویرایش'}}</button>
                        </div>
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
                <div class="modal-footer" style="text-align: center">
                    <button class="btn nextStepBtnTourCreation" data-dismiss="modal">تایید</button>
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
    @if($type === "city" || $type === "village")
        <script>
            var map;
            var marker = 0;

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
    @endif


    <script src="{{URL::asset('packages/dropzone/dropzone.js')}}"></script>
    <script src="{{URL::asset('packages/dropzone/dropzone-amd-module.js')}}"></script>
    <script src="{{URL::asset('packages/imageUploader/imageUploader.js')}}"></script>

    <script>
        var cityId = {{isset($city->id) ? $city->id : 0}};
        var cityPic = [];
        var mainCropPic;
        var mainPicBlob;
        var lastPic;
        var ajaxForSearchCity;

        function changeCountry(_element){
            var value = $(_element).val();
            if(value == -1){
                $('#state').val(-1);
                $('#stateSelect').removeClass('hidden');
            }
            else{
                $('#state').val(-1);
                $('#stateSelect').addClass('hidden');
            }
        }

        function findCityForVillage(_element){
            var value = $(_element).val();
            if(ajaxForSearchCity != null)
                ajaxForSearchCity.abort();

            $("#citySearchResult").empty();

            if(value.trim().length > 1){
                ajaxForSearchCity = $.ajax({
                    type: 'get',
                    url: `{{route('city.search')}}?kind=city&value=${value}`,
                    success: response => {
                        var newElement = "";
                        response.result.map(item => newElement += `<div class="item" onclick="chooseThisCityForVillage(${item.id}, this)">${item.text}</div>` );
                        $("#citySearchResult").html(newElement);
                    }
                })
            }
        }

        function chooseThisCityForVillage(_id, _element){
            $("#citySearchResult").empty();
            $('#villageCityId').val(_id);
            $('#villageCity').val($(_element).text());
        }

        function storeDate(){
            var state = 0;
            var city_x = '';
            var city_y = '';
            var villageCityId = 0;
            var country = 0;

            var cityIdVal = $('#cityId').val();
            var city_name = $('#city_name').val();
            var description = $('#description').val();
            var type = $('#typeOfLocation').val();

            if(city_name.trim().length === 0){
                alert('نام {{$text}} را مشخص کنید');
                return;
            }

            if(type === "city" || type === "village") {
                state = $('#state').val();
                city_x = $('#city_x').val();
                city_y = $('#city_y').val();
                country = $('#country').val();

                if(country !== '-1')
                    state = country;
                else if(state === '-1'){
                    alert('استان را مشخص کنید.');
                    return;
                }

                if(city_x.trim().length === 0 || city_y.trim().length === 0){
                    alert('محل {{$text}} را روی نقشه مشخص کنید.');
                    return;
                }

                if(type === "village"){
                    villageCityId = $('#villageCityId').val();
                    if(villageCityId === 0){
                        alert('برای روستا باید یک شهر انتخاب کنید');
                        return;
                    }
                }
            }

            openLoading();
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
                    villageCityId: villageCityId,
                    description: description,
                    type: type,
                },
                complete: closeLoading,
                success: function(response){
                    if(response.status === "ok") {
                        if(cityIdVal == 0)
                            location.href = `{{url('city/edit')}}/${response.id}/${type}`;
                        else {
                            cityId = response.id;
                            $('#picSection').removeClass('hidden');
                            $('#cityId').val(cityId);
                        }
                    }
                    else
                        alert('خطا');
                },
                error: function(err){
                    alert('error 2')
                }
            });

        }

        function newMainPic(_input){
            if(_input.files && _input.files[0]){
                openLoading();
                var render = new FileReader();
                render.onload = e => {
                    mainPicBlob = _input.files[0];
                    lastPic = $('#showMainPic').attr('src');
                    $('#showMainPic').attr('src', e.target.result);
                    doUploadMainPic()
                };
                render.readAsDataURL(_input.files[0]);
            }
        }

        function doUploadMainPic(){
            openLoading(true, 'در حال بارگزاری عکس');

            formData = new FormData();
            formData.append('id', $('#cityId').val());
            formData.append('type', $('#typeOfLocation').val());
            formData.append('pic', mainPicBlob);

            $.ajax({
                type: 'POST',
                url: '{{route("city.store.mainPic")}}',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.onprogress = function (e) {
                        if (e.lengthComputable) {
                            percent = Math.round((e.loaded / e.total) * 100);
                            updateLoadingProcess(percent)
                        }
                    };
                    return xhr;
                },
                complete: closeLoading,
                success: response => {
                    if(response.status === "ok"){
                        alert('عکس با موفقیت آپلود شد');
                    }
                    else{
                        alert('خطا در بارگزاری');
                        $('#showMainPic').attr('src', lastPic);
                        $('#mainPic').val('');
                    }
                },
                error: err => {
                    console.log(err);
                }
            })
        }
    </script>

    <script>

        @if(isset($city->pic))
            cityPics = {!! $city->pic !!};
        for(x of cityPics)
            cityPic[cityPic.length] = {
                id: x['id'],
                url: x['pic'],
                haveAlt: true,
                haveEdit: false,
                alt: x['alt']
            };
            @endif

        var createNewPicSectionInfo = {
            id : 'dragAndDropSection',
            url: '{{route("city.store.image")}}',
            csrf: '{{csrf_token()}}',
            data: {
                id: cityId
            },
            haveAlt: true,
            haveEdit: false,
            initPic: cityPic,
            initCallBack: '',
            callBack: '',
            onDeletePic: deletePic,
            onEditPic: editPic,
            onChangeAlt: changeAlt,
            onChooseMainPic: chooseMainPic,
        };

        var createdDropZone = createNewPicSection(createNewPicSectionInfo);

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

        function chooseMainPic(_id, _src){
            $.ajax({
                type: 'post',
                url: '{{route("city.chooseMainPic")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    cityId: cityId,
                    id: _id
                },
                success: response => {
                    if(response.status == 'ok') {
                        alert('عکس اصلی با موفقیت تغییر یافت');
                        $('#showMainPic').attr('src', response.result);
                    }
                    else
                        alert('مشکلی در تغییر به وجود امد لطفا دوباره تلاش کنید')
                }
            })
        }

        function changeAlt(_id, _value){
            $.ajax({
                type: 'post',
                url: '{{route("city.store.alt")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id,
                    value: _value
                },
                success: function(response){
                    try{
                        response = JSON.parse(response);
                        if(response['status'] != 'ok')
                            $('#altPic_' + _id).val('');
                    }
                    catch (e) {
                        console.log(e);
                        $('#altPic_' + _id).val('');
                    }
                },
                error: function(err){
                    console.log(err)
                    $('#altPic_' + _id).val('');
                }
            })
        }

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

        var cropper;
        var image = document.getElementById('cropImage');
        var numberPicOpen = 0;
        function cropPicModal(_numberPicOpen){
            image.src = mainCropPic;
            numberPicOpen = _numberPicOpen;

            $('#cropModal').modal({backdrop: 'static', keyboard: false})
                .ready(function(){
                    var ratio = 3/2;
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

        var cropedPic;
        function cropImg(){
            var canvas1;

            $('#cropModal').modal('hide');
            canvas1 = cropper.getCroppedCanvas({
                width: 960,
            });
            $('#resultMainCropPic_' + numberPicOpen).attr('src', canvas1.toDataURL());
            canvas1.toBlob(function (blob) {
                var formData = new FormData();
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
                var formData = new FormData();
                formData.append('_tokne', '{{csrf_token()}}');

                var pic = document.getElementById('resultMainCropPic_' + _kind);

                var cropperSqu = new Cropper(pic, {
                    ready: function () {
                        var canvasl = cropperSqu.getCroppedCanvas({
                            width: 960
                            // imageSmoothingQuality: 'medium',
                        });

                        squaResultCanvas = canvasl.toDataURL();
                        canvasl.toBlob(function (blob) {
                            formData.append('pic', blob, 'pic.jpg');

                            var compress = $('#compresInput_1').val();
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

