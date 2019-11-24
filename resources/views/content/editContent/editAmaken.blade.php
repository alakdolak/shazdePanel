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
            /*display: none;*/
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
                                            ویرایش <span style="font-weight: bold">{{$place->name}}</span>
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{route('storeAmaken')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$place->id}}">
                                    <input type="hidden" name="inputType" value="edit">

                                    <div class="row">
                                        <div class="col-md-4 f_r">
                                            <div class="form-group">
                                                <label for="name"> نام مکان</label>
                                                <input type="text" class="form-control" name="name" id="name" value="{{$place->name}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group">
                                                <label for="name"> استان</label>
                                                <select id="state" name="state" class="form-control" onchange="findCity(this.value)">
                                                    @foreach($allState as $item)
                                                        <option value="{{$item->id}}" {{$item->id == $place->stateId? 'selected' : ''}}>
                                                            {{$item->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group" style="position: relative">
                                                <label for="name"> شهر</label>
                                                <input type="text" class="form-control" name="city" id="city" value="{{$place->city}}" onkeyup="searchCity(this.value)">
                                                <input type="hidden" name="cityId" id="cityId" value="{{$place->cityId}}">

                                                <div id="citySearch" class="citySearch">
                                                    <ul id="resultCity"></ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 f_r">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mapModal">انتخاب از روی نقشه</button>
                                                <input type="hidden" name="C" id="lat" value="{{$place->C}}">
                                                <input type="hidden" name="D" id="lng" value="{{$place->D}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="phone"> شماره تماس</label>
                                                <input type="text" class="form-control" name="phone" id="phone" value="{{$place->phone}}" minlength="8">
                                                <div class="inputDescription">
                                                    شماره تماس را همراه با کد شهر وارد کنید.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="site"> آدرس سایت</label>
                                                <input type="text" class="form-control" name="site" id="site" value="{{$place->site}}" dir="ltr">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address"> آدرس</label>
                                                <input type="text" class="form-control" name="address" id="address" value="{{$place->address}}">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="display: flex">
                                        <div class="col-sm-2 f_r">
                                            کاربری:
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">تاریخی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="tarikhi" id="tarikhi" value="on" {{$place->tarikhi? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">موزه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="mooze" id="mooze" value="on" {{$place->mooze? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">تفریحی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="tafrihi" id="tafrihi" value="on" {{$place->tafrihi? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">طبیعت گردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="tabiatgardi" id="tabiatgardi" value="on" {{$place->tabiatgardi? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">مرکز خرید</span>
                                            <label class="switch">
                                                <input type="checkbox" name="markazkharid" id="markazkharid" {{$place->markazkharid? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">بافت تاریخی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="baftetarikhi" id="baftetarikhi" value="on" {{$place->baftetarikhi? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">محدوده قرار گیری:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">مرکز شهر</span>
                                            <label class="switch">
                                                <input type="radio" name="boundArea" value="1" {{$place->boundArea == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">حومه شهر</span>
                                            <label class="switch">
                                                <input type="radio" name="boundArea" value="2" {{$place->boundArea == 2? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">خارج شهر</span>
                                            <label class="switch">
                                                <input type="radio" name="boundArea" value="3" {{$place->boundArea == 3? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">موقعیت:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">شلوغ</span>
                                            <label class="switch">
                                                <input type="radio" name="population" value="1" {{$place->shologh? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">خلوت</span>
                                            <label class="switch">
                                                <input type="radio" name="population" value="2" {{$place->khalvat? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        {{--this section for shologh and khalvat--}}
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">محیط:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">طبیعت</span>
                                            <label class="switch">
                                                <input type="checkbox" name="tabiat" id="tabiat" value="on" {{$place->tabiat? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">کوه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="kooh" id="kooh" value="on" {{$place->kooh? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">دریا</span>
                                            <label class="switch">
                                                <input type="checkbox" name="darya" id="darya" value="on" {{$place->darya? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">کویر</span>
                                            <label class="switch">
                                                <input type="checkbox" name="kavir" id="kavir" value="on" {{$place->kavir? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">معماری:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">مدرن: </span>
                                            <label class="switch">
                                                <input type="radio" name="archi" value="1" {{$place->modern? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">تاریخی</span>
                                            <label class="switch">
                                                <input type="radio" name="archi" value="2" {{$place->tarikhibana? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl" class="myLabel">معمولی</span>
                                            <label class="switch">
                                                <input type="radio" name="archi" value="3" {{$place->mamooli? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        {{--this section for mamooli and tarikhibana and modern--}}
                                    </div>

                                    <hr>
                                    <div class="row center">
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="keyword"> کلمه کلیدی</label>
                                                <input type="text" class="form-control" name="keyword" id="keyword" value="{{$place->keyword}}" onchange="setkeyWord(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="h1"> عنوان اصلی</label>
                                                <input type="text" class="form-control" name="h1" id="h1" value="{{$place->h1}}" onchange="changeH1(this.value)">
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
                                                <textarea class="form-control" name="meta" id="meta" rows="10" onkeyup="metaCheck(this.value)" maxlength="153" minlength="130">{!! $place->meta !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWordMeta" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="site">توضیح</label>
                                                <textarea class="form-control" name="description" id="description" rows="10" onkeyup="descriptionCheck(this.value)">{!! $place->description !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWord" style="font-size: 15px;"></div>
                                                    <div class="inputDescription" id="keywordDensity" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        @for($i = 0; $i < count($place->tags); $i++)
                                            <div class="f_r" style="margin-left: 15px;">
                                                <div class="form-group">
                                                    <label for="name"> برچسب {{$i+1}} </label>
                                                    <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" value="{{$place->tags[$i]}}" onchange="checkTags()" {{$i < 5 ? 'required' : ''}}>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px; display: flex; justify-content: center;">
                                        <button type="button" class="btn btn-success" style="margin-left: 10px;" onclick="checkForm()">تایید</button>
                                        <button type="button" class="btn" onclick="window.location.href = '{{url('newChangeContent/'. $place->stateId . '/' . $mode . '/0')}}'">خروج</button>
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
        var keyword = '{{$place->keyword}}';
        var _token = '{{csrf_token()}}';
        var city = {!! $cities !!};
        var findCityUrl = '{{route("find.city.withState")}}';

        function checkForm(){
            var error_text = '';
            var error = false;

            var address = document.getElementById('address').value;
            if(address == '' || address == null || address == ' '){
                error = true;
                error_text += '<li>ادرس را کامل کنید.</li>';
                document.getElementById('address').classList.add('error');
            }
            else
                document.getElementById('address').classList.remove('error');

            showErrorDivOrsubmit(error_text, error);
        }
    </script>

    <script src="{{URL::asset('js/editContentPage.js')}}"></script>

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
        var C = {{$place->C}};
        var D = {{$place->D}};
        var marker;

        function init(){
            var mapOptions = {
                zoom: 15,
                center: new google.maps.LatLng(C, D),
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

            marker = new google.maps.Marker({
                position: {lat: C, lng: D},
                map: map,
            });
        }

        function getLat(location){
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