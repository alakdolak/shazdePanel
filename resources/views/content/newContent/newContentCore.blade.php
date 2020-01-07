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
                                                    @foreach($allState as $item)
                                                        <option value="{{$item->id}}" {{$item->id == $state->id? 'selected' : ''}}>
                                                            {{$item->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group" style="position: relative">
                                                <label for="name"> شهر</label>
                                                <input type="text" class="form-control" name="city" id="city" value="{{$city ? $city->name : ''}}" onkeyup="searchCity(this.value)">
                                                <input type="hidden" name="cityId" id="cityId" value="{{$city ? $city->id : 0}}">

                                                <div id="citySearch" class="citySearch">
                                                    <ul id="resultCity"></ul>
                                                </div>
                                            </div>
                                        </div>

                                        @if($kind != 'mahalifood' && $kind != 'sogatsanaie')
                                            <div class="col-md-2 f_r">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">انتخاب از روی نقشه</button>
                                                    <input type="hidden" name="C" id="lat" value="0">
                                                    <input type="hidden" name="D" id="lng" value="0">
                                                </div>
                                            </div>
                                        @else
                                            <input type="hidden" name="C" id="lat" value="1">
                                            <input type="hidden" name="D" id="lng" value="1">
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
                                    @endif


                                    <hr>
                                    <div class="row center">
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="keyword"> کلمه کلیدی</label>
                                                <input type="text" class="form-control" name="keyword" id="keyword" onchange="setkeyWord(this.value)" value="{{old('keyword')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="h1"> عنوان اصلی</label>
                                                <input type="text" class="form-control" name="h1" id="h1" onchange="changeH1(this.value)" value="{{old('h1')}}">
                                                <div class="inputDescription">
                                                    همان h1 است
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="site">متا</label>
                                                <textarea class="form-control" name="meta" id="meta" rows="10" onkeyup="metaCheck(this.value)" maxlength="153" minlength="130">{{old('meta')}}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWordMeta" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="site">توضیح</label>
                                                <textarea class="form-control" name="description" id="description" rows="10" onkeyup="descriptionCheck(this.value)">{!! old('description') !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWord" style="font-size: 15px;"></div>
                                                    <div class="inputDescription" id="keywordDensity" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">

                                        @for($i = 0; $i < 15; $i++)
                                            <div class="f_r" style="margin-left: 15px;">
                                                <div class="form-group">
                                                    <label for="name"> برچسب {{$i+1}} </label>
                                                    <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
                                                </div>
                                            </div>
                                        @endfor

                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px; display: flex; justify-content: center;">
                                        <button type="button" class="btn btn-success" style="margin-left: 10px;" onclick="checkForm()">تایید</button>
                                        <button type="button" class="btn" onclick="window.location.href = '{{url('newChangeContent/'. $state->id . '/' . $mode . '/0')}}'">خروج</button>
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
        var findCityUrl = '{{route("find.city.withState")}}';
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

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCdVEd4L2687AfirfAnUY1yXkx-7IsCER0&callback=init"></script>

@stop