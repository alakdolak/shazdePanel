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
                                            @if($kind == 'edit')
                                                ویرایش <span style="font-weight: bold">{{$place->name}}</span>
                                            @else
                                                مکان جدید
                                            @endif
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{route('storeMajara')}}" method="post" enctype="multipart/form-data" autocomplete="off">
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
                                                <label for="nazdik"> نزدیک به کجاست؟</label>
                                                <input type="text" class="form-control" name="nazdik" id="nazdik" value="{{$place->nazdik}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="dastresi"> دسترسی</label>
                                                <input type="text" class="form-control" name="dastresi" id="dastresi" value="{{$place->dastresi}}">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="col-sm-2 f_r" style="width: 100%;">
                                            <span style="direction: rtl; font-weight: bold" class="myLabel" >مناسب برای:</span>
                                        </div>

                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">کوه نوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="koohnavardi" id="koohnavardi" value="on" {{$place->koohnavardi? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">پیاده‌روی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="walking" id="walking" value="on" {{$place->walking? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">شنا</span>
                                            <label class="switch">
                                                <input type="checkbox" name="swimming" id="swimming" value="on" {{$place->swimming? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">صخره‌نوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="rockClimbing" id="rockClimbing" value="on" {{$place->rockClimbing? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">سنگ‌نوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="stoneClimbing" id="stoneClimbing" value="on" {{$place->stoneClimbing? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">دره‌نوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="valleyClimbing" id="valleyClimbing" value="on" {{$place->valleyClimbing? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">غار‌نوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="caveClimbing" id="caveClimbing" value="on" {{$place->caveClimbing? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">یخ‌نوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="iceClimbing" id="iceClimbing" value="on" {{$place->iceClimbing? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">آفرود</span>
                                            <label class="switch">
                                                <input type="checkbox" name="offRoad" id="offRoad" value="on" {{$place->offRoad? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">قایق‌سواری</span>
                                            <label class="switch">
                                                <input type="checkbox" name="boat" id="boat" value="on" {{$place->boat? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">موج سواری</span>
                                            <label class="switch">
                                                <input type="checkbox" name="surfing" id="surfing" value="on" {{$place->surfing? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">کمپ (چادر زدن)</span>
                                            <label class="switch">
                                                <input type="checkbox" name="kamp" id="kamp" value="on" {{$place->kamp? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">صحرانوردی</span>
                                            <label class="switch">
                                                <input type="checkbox" name="sahranavardi" id="sahranavardi" value="on" {{$place->sahranavardi? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">پیک نیک</span>
                                            <label class="switch">
                                                <input type="checkbox" name="piknik" id="piknik" value="on" {{$place->piknik? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 f_r marTop" style="border-left: solid gray;">
                                        <span style="direction: rtl" class="myLabel">قایق‌سواری در موج‌های خروشان</span>
                                        <label class="switch">
                                            <input type="checkbox" name="rafting" id="rafting" value="on" {{$place->rafting? 'checked' : ''}}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="eleman f_r" style="width: 100%">
                                            <span style="direction: rtl; font-weight: bold" class="myLabel">ویژگی های محیطی:</span>
                                        </div>

                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">کوه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="kooh" id="kooh" value="on" {{$place->kooh? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">دریا</span>
                                            <label class="switch">
                                                <input type="checkbox" name="darya" id="darya" value="on" {{$place->darya? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">دریاچه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="daryache" id="daryache" value="on" {{$place->daryache? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">شهری</span>
                                            <label class="switch">
                                                <input type="checkbox" name="shahri" id="shahri" value="on" {{$place->shahri? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">منطقه‌حفاظت شده</span>
                                            <label class="switch">
                                                <input type="checkbox" name="hefazat" id="hefazat" value="on" {{$place->hefazat? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">حیات وحش</span>
                                            <label class="switch">
                                                <input type="checkbox" name="hayatevahsh" id="hayatevahsh" value="on" {{$place->hayatevahsh? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">کویر</span>
                                            <label class="switch">
                                                <input type="checkbox" name="kavir" id="kavir" value="on" {{$place->kavir? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">رمل</span>
                                            <label class="switch">
                                                <input type="checkbox" name="raml" id="raml" value="on" {{$place->raml? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">جنگل</span>
                                            <label class="switch">
                                                <input type="checkbox" name="jangal" id="jangal" value="on" {{$place->jangal? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">آبشار</span>
                                            <label class="switch">
                                                <input type="checkbox" name="abshar" id="abshar" value="on" {{$place->abshar? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">دره</span>
                                            <label class="switch">
                                                <input type="checkbox" name="darre" id="darre" value="on" {{$place->darre? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">بکر</span>
                                            <label class="switch">
                                                <input type="checkbox" name="bekr" id="bekr" value="on" {{$place->bekr? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">دشت</span>
                                            <label class="switch">
                                                <input type="checkbox" name="dasht" id="dasht" value="on" {{$place->dasht? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>

                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">برکه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="berke" id="berke" value="on" {{$place->berke? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">ساحل</span>
                                            <label class="switch">
                                                <input type="checkbox" name="beach" id="beach" value="on" {{$place->beach? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">ژئوپارک</span>
                                            <label class="switch">
                                                <input type="checkbox" name="geoPark" id="geoPark" value="on" {{$place->geoPark? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">رودخانه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="river" id="river" value="on" {{$place->river? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">چشمه</span>
                                            <label class="switch">
                                                <input type="checkbox" name="cheshme" id="cheshme" value="on" {{$place->cheshme? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="eleman f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">تالاب</span>
                                            <label class="switch">
                                                <input type="checkbox" name="talab" id="talab" value="on" {{$place->talab? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
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
                                                    <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" value="{{$place->tags[$i]}}" {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
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

    <script>
        var keyword = '{{$place->keyword}}';
        var _token = '{{csrf_token()}}';
        var findCityUrl = '{{route("find.city.withState")}}';
        var city = {!! $cities !!};
    </script>

    <script src="{{URL::asset('js/editContentPage.js')}}"></script>

    <script>
        function checkForm(){
            var error_text = '';
            var error = false;

            var dastresi = document.getElementById('dastresi').value;
            if(dastresi == '' || dastresi == null || dastresi == ' '){
                error = true;
                error_text += '<li>دسترسی را کامل کنید.</li>';
                document.getElementById('dastresi').classList.add('error');
            }
            else
                document.getElementById('dastresi').classList.remove('error');

            var nazdik = document.getElementById('nazdik').value;
            if(nazdik == '' || nazdik == null || nazdik == ' '){
                error = true;
                error_text += '<li>نزدیک را کامل کنید.</li>';
                document.getElementById('nazdik').classList.add('error');
            }
            else
                document.getElementById('nazdik').classList.remove('error');

            showErrorDivOrsubmit(error_text, error);
        }
    </script>

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