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
            /*display: none;*/
        }

        .eleman{
            margin-left: 30px;
            width: 200px;
            margin-bottom: 10px;
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
                                <form id="form" action="{{route('storeRestaurant')}}" method="post" enctype="multipart/form-data" autocomplete="off">
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
                                    <div class="row" style="display: flex; align-items: center; height: 55px;">
                                        <div class="col-md-2">
                                            <span style="direction: rtl" class="myLabel">نوع رستوران </span>
                                            <select name="kind_id" id="kind_id" class="form-control">
                                                <option value="1" {{$place->kind_id == 1? 'selected' : ''}}>رستوران</option>
                                                <option value="2" {{$place->kind_id == 2? 'selected' : ''}}>فست فود</option>
                                            </select>
                                        </div>
                                    </div>

                                    @foreach($features as $feat)
                                        <hr>
                                        <div class="row" style="display: flex; flex-wrap: wrap">
                                            <div class="col-sm-12 f_r" style="font-weight: bold; margin-bottom: 10px">
                                                {{$feat->name}}:
                                            </div>
                                            <?php $last = 0; ?>
                                            @foreach($feat->subFeat as $item)
                                                <?php $last++; ?>
                                                <div class="col-sm-2 f_r" style="{{$last == count($feat->subFeat) ? '' : 'border-left: solid gray; '}} display: flex; justify-content: space-around; margin-bottom: 5px">
                                                    <span style="direction: rtl" class="myLabel">{{$item->name}}</span>

                                                    @if($item->type == 'YN')
                                                        <label class="switch">
                                                            <input type="checkbox" name="features[]" value="{{$item->id}}" {{in_array($item->id, $placeFeatures) ? 'checked' : ''}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 f_r">
                                            <div class="form-group">
                                                <label for="keyword"> کلمه کلیدی</label>
                                                <input type="text" class="form-control" name="keyword" id="keyword" value="{{$place->keyword}}" onchange="setkeyWord(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-12 f_r">
                                            <div class="form-group">
                                                <label for="seoTitle"> عنوان سئو : <span id="seoTitleNumber" style="font-weight: 200;"></span></label>
                                                <input type="text" class="form-control" name="seoTitle" id="seoTitle" value="{{$place->seoTitle}}" onkeyup="changeSeoTitle(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-12 f_r">
                                            <div class="form-group">
                                                <label for="slug"> نامک</label>
                                                <input type="text" class="form-control" name="slug" id="slug" value="{{$place->seoTitle}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="site">متا : <span id="metaNumber" style="font-weight: 200;"></span></label>
                                                <textarea class="form-control" name="meta" id="meta" rows="10" onkeyup="changeMeta(this.value)" maxlength="153" minlength="130">{!! $place->meta !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWordMeta" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="site">توضیح</label>
                                                <textarea class="form-control" name="description" id="description" rows="10">{!! $place->description !!}</textarea>
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
                                                    <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" value="{{$place->tags[$i]}}" {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
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

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-body" style="direction: rtl">
                    <h3>حذف <span id="deleteName1" style="font-weight: bold"></span></h3>
                    <p>
                        ایا می خواهید <span id="deleteName2"  style="font-weight: bold"></span> را پاک کنید؟
                    </p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="text-align: center">
                    <button type="button" class="btn btn-danger">بله</button>
                    <button class="btn nextStepBtnTourCreation" data-dismiss="modal">خیر</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="mapModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-body" style="direction: rtl">
                    <div id="map" style="width: 100%; height: 500px; background-color: red"></div>
                </div>

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
                    <button type="button" class="btn btn-success"  data-dismiss="modal" onclick="checkForm()">بله پست ثبت شود</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        var keyword = '{{$place->keyword}}';
        var _token = '{{csrf_token()}}';
        var findCityUrl = '{{route("get.allcity.withState")}}';
        var city = {!! $cities !!};
    </script>

    <script src="{{URL::asset('js/editContentPage.js')}}"></script>

    <script>
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
                    id: {{$place->id}},
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
                        checkForm();
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
            if(_value.length > 120 && _value.length <= 156)
                $('#metaNumber').css('color', 'green');
            else
                $('#metaNumber').css('color', 'red');

        }

    </script>

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