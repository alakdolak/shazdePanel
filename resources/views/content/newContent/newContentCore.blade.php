@extends('layouts.structure')

@section('header')

    <link rel="stylesheet" href="{{URL::asset('css/switch.css')}}">

    @parent
    <style>
        .row {
            direction: rtl;
            text-align: right;
        }
        .f_r{
            float: right;
        }
        .inputDescription{
            color: #a5a5a5;
            font-size: 12px
        }
        input, select{
            border-radius: 10px !important;
        }
        .center{
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .switch{
            width: 30px;
            height: 17px;
            float: left;
        }
        input:checked + .slider:before{
            transform: translateX(13px);
        }
        .slider:before{
            height: 13px;
            width: 13px;
            left: 2px;
            bottom: 2px;
        }
        .error{
            background-color: #ffb6b6;
        }
        .citySearch{
            background-color: #eaeaea;
            position: absolute;
            width: 100%;
            z-index: 9;
            /*border: solid gray 1px;*/
            border-radius: 5px;
        }
        .liSearch{
            padding: 5px;
            cursor: pointer;
        }
        .liSearch:hover{
            background-color: #d4ebff;
        }
        .errorDiv{
            position: fixed;
            z-index: 99999;
            top: 50px;
            width: 100%;
            padding: 0 20%;
            direction: rtl;
        }

        .eleman{
            margin-left: 30px;
            width: 160px;
            margin-bottom: 10px;
            padding-left: 10px;
        }

        .marTop{
            margin-top: 10px;
        }
    </style>

@stop

@section('content')

    <div class="errorDiv" id="errorDiv"></div>



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
                                    <div class="col-12">
                                        <h3 style="text-align: center;">
                                            {{$titleName}}
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{$url}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="userId" value="{{auth()->user()->id}}">
                                    <input type="hidden" name="inputType" value="new">

                                    <div class="row">
                                        <div class="col-md-4 f_r">
                                            <div class="form-group">
                                                <label for="name"> نام</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="نام" value="{{old('name')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group">
                                                <label for="name"> استان</label>
                                                <select id="state" name="state" class="form-control" onchange="findCity(this.value)">
                                                    @if($state != null)
                                                        @foreach($allState as $item)
                                                            <option value="{{$item->id}}" {{$item->id == $state->id? 'selected' : ''}}>
                                                                {{$item->name}}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @foreach($allState as $item)
                                                            <option value="{{$item->id}}">
                                                                {{$item->name}}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group" style="position: relative">
                                                <label for="name"> شهر</label>
                                                <input type="text" class="form-control" name="city" id="city" value="{{isset($city) && isset($city->name) ? $city->name : ''}}" onkeyup="searchCity(this.value)">
                                                <input type="hidden" name="cityId" id="cityId" value="{{isset($city) && isset($city->id) ? $city->id : 0}}">
                                                <div id="citySearch" class="citySearch">
                                                    <ul id="resultCity"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        @if($kind != 'mahalifood' && $kind != 'sogatsanaie')
                                            <div class="col-md-2 f_r">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">انتخاب از روی نقشه</button>
                                                    <label for="lat">lat(C)</label>
                                                    <input type="text" name="C" id="lat" value="1" onchange="setNewMarker()">

                                                    <label for="lng">lng(D)</label>
                                                    <input type="text" name="D" id="lng" value="1" onchange="setNewMarker()">
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="C" id="lat" value="0">
                                            <input type="hidden" name="D" id="lng" value="0">
                                        @endif
                                    </div>

                                    @if($kind == 'amaken')
                                        @include('content.newContent.amaken')
                                    @elseif($kind == 'hotels')
                                        @include('content.newContent.hotel')
                                    @elseif($kind == 'restaurant')
                                        @include('content.newContent.restaurant')
                                    @elseif($kind == 'majara')
                                        @include('content.newContent.majara')
                                    @elseif($kind == 'mahalifood')
                                        @include('content.newContent.mahalifood')
                                    @elseif($kind == 'sogatsanaie')
                                        @include('content.newContent.sogatsanaie')
                                    @elseif($kind == 'boomgardies')
                                        @include('content.newContent.boomgardies')
                                    @endif

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 f_r">
                                            <div class="form-group">
                                                <label for="keyword"> کلمه کلیدی</label>
                                                <input type="text" class="form-control" name="keyword" id="keyword" value="{{old('keyword')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 f_r">
                                            <div class="form-group">
                                                <label for="seoTitle"> عنوان سئو : <span id="seoTitleNumber" style="font-weight: 200;"></span></label>
                                                <input type="text" class="form-control" name="seoTitle" id="seoTitle" onkeyup="changeSeoTitle(this.value)" value="{{old('seoTitle')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 f_r">
                                            <div class="form-group">
                                                <label for="slug"> نامک</label>
                                                <input type="text" class="form-control" name="slug" id="slug" value="{{old('slug')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="site">متا : <span id="metaNumber" style="font-weight: 200;"></span></label>
                                                <textarea class="form-control" name="meta" id="meta" rows="10" onkeyup="changeMeta(this.value)">{{old('meta')}}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWordMeta" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="description">توضیح</label>
                                                <textarea class="form-control" name="description" id="description" rows="10" onkeyup="descriptionCounter(this.value)">{!! old('description') !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="descriptionWordCount" style="font-size: 15px;"></div>
                                                    <div class="inputDescription" id="descriptionCharCount" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">

                                        @for($i = 0; $i < 20; $i++)
                                            <div class="f_r" style="margin-left: 15px;">
                                                <div class="form-group">
                                                    <label for="name"> برچسب {{$i+1}} </label>
                                                    <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
                                                </div>
                                            </div>
                                        @endfor

                                    </div>

                                    <hr>
                                    <div class="row" style="text-align: center">
                                        <button type="button" class="btn btn-primary" onclick="checkSeo(0)">تست سئو</button>
                                    </div>
                                    <div class="row" style="text-align: right">
                                        <div id="errorResult"></div>
                                        <div id="warningResult"></div>
                                        <div id="goodResult"></div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px; display: flex; justify-content: center;">
                                        <button type="button" class="btn btn-success" style="margin-left: 10px;" onclick="checkSeo(1)">تایید</button>
                                        <button type="button" class="btn" onclick="window.location.href = '{{url('newChangeContent/0/' . $mode . '/country')}}'">خروج</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var _token = '{{csrf_token()}}';
        var findCityUrl = '{{route("get.allcity.withState")}}';
        var city = {!! $cities !!};
    </script>

    <script src="{{URL::asset('js/editContentPage.js')}}"></script>


    {{--map--}}
    <div class="modal fade" id="mapModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="direction: rtl">
                    <div id="map" style="width: 100%; height: 500px; background-color: red">

                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="text-align: center">
                    <button class="btn nextStepBtnTourCreation" data-dismiss="modal">تایید</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal" id="warningModal" style="direction: rtl">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">اخطارها</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div style="font-size: 18px; margin-bottom: 20px;">
                        در پست شما اخطارهای زیر موجود است . ایا از ثبت پست خود اطمینان دارید؟
                    </div>

                    <div id="warningContentModal" style="padding-right: 5px;"></div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="text-align: center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر اصلاح می کنم.</button>
                    <button type="button" class="btn btn-success"  data-dismiss="modal" onclick="showErrorDivOrsubmit()">بله پست ثبت شود</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        var map;
        var marker = 0;

        function init(){
            var mapOptions = {
                zoom: 5,
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
        }

        function getLat(location){
            if(marker != 0)
                marker.setMap(null);
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            document.getElementById('lat').value = marker.getPosition().lat();
            document.getElementById('lng').value = marker.getPosition().lng();
        }

        function setNewMarker(){
            marker.setMap(null);
            var lat = document.getElementById('lat').value;
            var lng = document.getElementById('lng').value;

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map,
            });
        }

        function checkSeo(kind){

            var name = document.getElementById('name').value;
            var value = document.getElementById('keyword').value;
            var seoTitle = document.getElementById('seoTitle').value;
            var slug = document.getElementById('slug').value;
            var meta = document.getElementById('meta').value;
            var description = document.getElementById('description').value;

            $.ajax({
                type: 'post',
                url : '{{route("placeSeoTest")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    keyword: value,
                    meta: meta,
                    seoTitle: seoTitle,
                    slug: slug,
                    text: description,
                    name: name,
                    kindPlaceId: {{$mode}}
                },
                success: function(response){
                    response = JSON.parse(response);
                    document.getElementById('errorResult').innerHTML = '';
                    document.getElementById('warningResult').innerHTML = '';
                    document.getElementById('goodResult').innerHTML = '';


                    $('#warningResult').append(response[0]);
                    $('#goodResult').append(response[1]);
                    $('#errorResult').append(response[2]);
                    uniqueKeyword = response[5];
                    uniqueSlug = response[6];
                    uniqueTitle = response[7];
                    uniqueSeoTitle = response[8];

                    errorCount = response[3];
                    warningCount = response[4];

                    inlineSeoCheck(kind);
                }
            })
        }

        function inlineSeoCheck(kind){
            // var tags = $("input[id='tags']").map(function(){return [$(this).val()];}).get();
            // if(tags.length == 0){
            //     errorCount++;
            //     text = '<div style="color: red;">شما باید برای متن خود برچسب انتخاب نمایید</div>';
            //     $('#errorResult').append(text);
            // }
            // else if(tags.length < 10){
            //     warningCount++;
            //     text = '<div style="color: #dec300;">پیشنهاد می گردد حداقل ده برچسب انتخاب نمایید.</div>';
            //     $('#warningResult').append(text);
            // }
            // else{
            //     text = '<div style="color: green;">تعداد برچسب های متن مناسب می باشد.</div>';
            //     $('#goodResult').append(text);
            // }

            if(kind == 1) {
                var name = document.getElementById('name').value;
                var city = document.getElementById('cityId').value;
                if(errorCount > 0){
                    alert('برای ثبت مکان باید تمام ارورها را برطرف کنید .');
                    return;
                }
                if(city == 0){
                    alert('لطفا یک شهر انتخاب کنید.');
                    return;
                }
                if(!uniqueTitle){
                    alert('عنوان مقاله یکتا نیست');
                    return;
                }
                else if(!uniqueSlug){
                    alert('نامک مقاله یکتا نیست');
                    return;
                }
                else if(!uniqueKeyword){
                    alert('کلمه کلیدی مقاله یکتا نیست');
                    return;
                }
                else if(!uniqueSeoTitle){
                    alert('عنوان سئو مقاله یکتا نیست');
                    return;
                }
                else {
                    if (warningCount > 0) {
                        $('#warningContentModal').html('');
                        $('#warningResult').children().each(function (){
                            text = '<li style="margin-bottom: 5px">' + $(this).text() + '</li>';
                            $('#warningContentModal').append(text);
                        });
                        $('#warningModal').modal('show');
                        return;
                    }
                    else
                        showErrorDivOrsubmit();
                }
            }
        }

        function changeSeoTitle(_value){
            var text = _value.length + ' حرف';
            $('#seoTitleNumber').text(text)
            if(_value.length > 60 && _value.length <= 85)
                $('#seoTitleNumber').css('color', 'green');
            else
                $('#seoTitleNumber').css('color', 'red');

        }

        function changeMeta(_value){
            var text = _value.length + ' حرف';
            $('#metaNumber').text(text);
            if(_value.length > 120 && _value.length <= 160)
                $('#metaNumber').css('color', 'green');
            else
                $('#metaNumber').css('color', 'red');

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCdVEd4L2687AfirfAnUY1yXkx-7IsCER0&callback=init"></script>

@stop
