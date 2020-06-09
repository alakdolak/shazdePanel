@extends('layouts.structure')

@section('header')
    <link rel="stylesheet" href="{{URL::asset('css/switch.css')}}">
    @parent
    <style>
        .row {
            direction: rtl;
            text-align: right;
        }

        .f_r {
            float: right;
        }

        .inputDescription {
            color: #a5a5a5;
            font-size: 12px
        }

        input, select {
            border-radius: 10px !important;
        }

        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .switch {
            width: 30px;
            height: 17px;
            float: left;
        }

        input:checked + .slider:before {
            transform: translateX(13px);
        }

        .slider:before {
            height: 13px;
            width: 13px;
            left: 2px;
            bottom: 2px;
        }

        .error {
            background-color: #ffb6b6;
        }

        .citySearch {
            background-color: #eaeaea;
            position: absolute;
            width: 100%;
            z-index: 9;
            /*border: solid gray 1px;*/
            border-radius: 5px;
        }

        .liSearch {
            padding: 5px;
            cursor: pointer;
        }

        .liSearch:hover {
            background-color: #d4ebff;
        }

        .errorDiv {
            position: fixed;
            z-index: 99999;
            top: 50px;
            width: 100%;
            padding: 0 20%;
            direction: rtl;
        }

        .eleman {
            margin-left: 30px;
            width: 160px;
            margin-bottom: 10px;
            padding-left: 10px;
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
                                            غذای محلی:
                                            ویرایش {{$place->name}}
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{route('storeMahaliFood')}}" method="post"
                                      enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="userId" value="{{isset($place->userId) ? $place->userId : auth()->user()->id}}">
                                    <input type="hidden" name="addPlaceByUser" value="{{isset($place->addPlaceByUser) ? 1 : 0}}">
                                    <input type="hidden" name="id" value="{{$place->id}}">
                                    <input type="hidden" name="inputType" value="edit">

                                    <input type="hidden" id="lat" value="1">
                                    <input type="hidden" id="lng" value="1">

                                    <div class="row">
                                        <div class="col-md-4 f_r">
                                            <div class="form-group">
                                                <label for="name"> نام غذا</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                       placeholder="نام غذای محلی" value="{{$place->name}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group">
                                                <label for="name"> استان</label>
                                                <select id="state" name="state" class="form-control"
                                                        onchange="findCity(this.value)">
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
                                                <input type="text" class="form-control" name="city" id="city"
                                                       value="{{isset($place->city) ? $place->city : ''}}" onkeyup="searchCity(this.value)">
                                                <input type="hidden" name="cityId" id="cityId"
                                                       value="{{isset($place->cityId) ? $place->cityId : 0}}">
                                                @if(isset($place->newCity))
                                                    <div class="newCityWarning" style="color: red">
                                                        کاربر شهر جدید به نام
                                                        - <span style="color: green">{{$place->newCity}}</span> -
                                                        وارد کرده است.
                                                    </div>
                                                @endif

                                                <div id="citySearch" class="citySearch">
                                                    <ul id="resultCity"></ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class=" f_r" style="margin-left: 10px;">
                                            <label for="kind">
                                                نوع غذا
                                            </label>
                                            <select id="kind" name="kind">
                                                <option value="1" {{$place->kind == 1? 'selected': ''}}>چلوخورش</option>
                                                <option value="2" {{$place->kind == 2? 'selected': ''}}>خوراک</option>
                                                <option value="8" {{$place->kind == 8? 'selected': ''}}>سوپ و آش</option>
                                                <option value="3" {{$place->kind == 3? 'selected': ''}}>سالاد و پیش غذا</option>
                                                <option value="4" {{$place->kind == 4? 'selected': ''}}>ساندویچ</option>
                                                <option value="5" {{$place->kind == 5? 'selected': ''}}>کباب</option>
                                                <option value="6" {{$place->kind == 6? 'selected': ''}}>دسر</option>
                                                <option value="7" {{$place->kind == 7? 'selected': ''}}>نوشیدنی</option>
                                            </select>
                                        </div>

                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">

                                        <div class="f_r" style="margin-left: 20px">
                                            <span style="direction: rtl; text-align: right" class="myLabel">غذا سرد است و یا گرم?</span>
                                        </div>

                                        <div class=" f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">گرم</span>
                                            <label class="switch">
                                                <input type="radio" name="hotOrCold" id="hotOrCold"
                                                       value="1" {{$place->hotOrCold == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r">
                                            <span style="direction: rtl" class="myLabel">سرد</span>
                                            <label class="switch">
                                                <input type="radio" name="hotOrCold" id="hotOrCold"
                                                       value="2" {{$place->hotOrCold == 2? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div id="firForWho" class="row" style="margin-top: 10px;">

                                        <div class="f_r" style="margin-left: 20px">
                                            <span style="direction: rtl; text-align: right"
                                                  class="myLabel">مناسب برای:</span>
                                        </div>

                                        <div class=" f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">افراد گیاه‌خوار</span>
                                            <label class="switch">
                                                <input type="checkbox" name="vegetarian" id="vegetarian" value="on"
                                                       onchange="fitFor(1)" {{$place->vegetarian == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">وگان</span>
                                            <label class="switch">
                                                <input type="checkbox" name="vegan" id="vegan" value="on"
                                                       onchange="fitFor(2)" {{$place->vegan == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">افراد مبتلا به دیابت</span>
                                            <label class="switch">
                                                <input type="checkbox" name="diabet" id="diabet" value="on"
                                                       onchange="fitFor(3)" {{$place->diabet == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">هیچ کدام</span>
                                            <label class="switch">
                                                <input type="checkbox" name="commonEat" id="commonEat" value="on"
                                                       onchange="fitFor(4)" {{$place->vegan == 0 && $place->vegetarian == 0 && $place->diabet == 0? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class=" f_r" style="margin-left: 10px;">
                                            <span>
                                                <input type="text" class="form-control" name="energy" id="energy" placeholder="مقدار کالری"
                                                       style="width: 100px; display: inline-block" value="{{$place->energy}}">
                                            </span>
                                            <span>
                                                کیلوکالری در هر
                                            </span>
                                            <span>
                                                <input type="text" class="form-control" name="volume" id="volume" placeholder="مقدار مرجع"
                                                       style="width: 100px; display: inline-block"  value="{{$place->volume}}">
                                            </span>
                                            <span>
                                                <select name="source" class="form-control" style="width: 100px; display: inline-block">
                                                    <option value="1" {{$place->spoon == 1 ? 'selected' : ''}}>قاشق</option>
                                                    <option value="2" {{$place->gram == 1 ? 'selected' : ''}}>گرم</option>
                                                </select>
                                            </span>
                                        </div>

                                        <span class=" f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">برنج</span>
                                            <label class="switch">
                                                <input type="checkbox" name="rice" id="rice" value="on" {{$place->rice == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </span>

                                        <span class=" f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">نان</span>
                                            <label class="switch">
                                                <input type="checkbox" name="bread" id="bread" value="on" {{$place->bread == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </span>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="f_r" style="font-weight: bold">
                                            مواد مورد نیاز
                                        </div>
                                        <div id="material" class="col-md-12">
                                            @if(isset($place->material) && is_array($place->material))
                                                @for($i = 0; $i < count($place->material); $i++)
                                                    <div class="row" style="margin-top: 10px;">
                                                        <div class="col-md-3 f_r">
                                                            <label>
                                                                نام مواد
                                                            </label>
                                                            <input type="text" name="matName[{{$i}}]"
                                                                   value="{{$place->material[$i][0]}}">
                                                        </div>
                                                        <div class="col-md-3 f_r">
                                                            <label>
                                                                مقدار مواد
                                                            </label>
                                                            <input type="text" name="matValue[{{$i}}]"
                                                                   value="{{$place->material[$i][1]}}">
                                                        </div>
                                                    </div>
                                                @endfor
                                            @endif
                                        </div>
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <button type="button" class="btn btn-success" onclick="addMaterial()">
                                                افزودن
                                            </button>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="site">دستور پخت</label>
                                                <textarea class="form-control" name="recipes" id="recipes"
                                                          rows="10">{!! $place->recipes !!}</textarea>
                                            </div>
                                        </div>
                                    </div>

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
                                                <input type="text" class="form-control" name="slug" id="slug" value="{{$place->slug}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="site">متا : <span id="metaNumber" style="font-weight: 200;"></span></label>
                                                <textarea class="form-control" name="meta" id="meta" rows="10" onkeyup="changeMeta(this.value)">{!! $place->meta !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWordMeta" style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="description">توضیح</label>
                                                <textarea class="form-control" name="description" id="description" rows="10" onkeyup="descriptionCounter(this.value)">{!! $place->description !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="descriptionWordCount" style="font-size: 15px;"></div>
                                                    <div class="inputDescription" id="descriptionCharCount" style="font-size: 15px;"></div>
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
                                                    <input type="text" class="form-control" name="tag[]"
                                                           id="tag{{$i+1}}" value="{{$place->tags[$i]}}"
                                                           {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
                                                </div>
                                            </div>
                                        @endfor
                                        @if(count($place->tags) < 20)
                                            @for($i = count($place->tags); $i < 20; $i++)
                                                <div class="f_r" style="margin-left: 15px;">
                                                    <div class="form-group">
                                                        <label for="name"> برچسب {{$i+1}} </label>
                                                        <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" value="" onchange="checkTags()" {{$i < 5 ? 'required' : ''}}>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
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
        @if(isset($place->material) && is_array($place->material))
            var materialNum = {{count($place->material)}};
        @else
            var materialNum = 0;
        @endif

        function addMaterial() {
            var text = '<div class="row" style="margin-top: 10px;">\n' +
                '                                                <div class="col-md-3 f_r">\n' +
                '                                                    <label>\n' +
                '                                                        نام مواد\n' +
                '                                                    </label>\n' +
                '                                                    <input type="text" name="matName[' + materialNum + ']">\n' +
                '                                                </div>\n' +
                '                                                <div class="col-md-3 f_r">\n' +
                '                                                    <label>\n' +
                '                                                        مقدار مواد\n' +
                '                                                    </label>\n' +
                '                                                    <input type="text" name="matValue[' + materialNum + ']">\n' +
                '                                                </div>\n' +
                '                                            </div>';
            $('#material').append(text);
            materialNum++;
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
            if(_value.length > 120 && _value.length <= 160)
                $('#metaNumber').css('color', 'green');
            else
                $('#metaNumber').css('color', 'red');

        }

        function checkForm() {
            var error_text = '';
            var error = false;

            var diabet = document.getElementById('diabet').checked;
            var vegetarian = document.getElementById('vegetarian').checked;
            var vegan = document.getElementById('vegan').checked;
            var commonEat = document.getElementById('commonEat').checked;

            if (!diabet && !vegetarian && !vegan && !commonEat) {
                error = true;
                error_text += '<li>حتما باید یکی از گزینه های "مناسب برای" را انتخاب کنید</li>';
                document.getElementById('firForWho').classList.add('error');
            }

            var volume = document.getElementById('volume').value;
            var energy = document.getElementById('energy').value;
            if (volume == '' || energy == '') {
                error = true;
                error_text += '<li>فیلدهای کالری باید پر شود.</li>';
            }

            showErrorDivOrsubmit(error_text, error);
        }

        function fitFor(num) {
            if (num == 4) {
                document.getElementById('diabet').checked = false;
                document.getElementById('vegetarian').checked = false;
                document.getElementById('vegan').checked = false;
            }
            else
                document.getElementById('commonEat').checked = false;
        }

    </script>

@stop
