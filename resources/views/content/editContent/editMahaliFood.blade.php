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
                                            ویرایش {{$food->name}}
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{route('storeMahaliFood')}}" method="post"
                                      enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$food->id}}">
                                    <input type="hidden" id="lat" value="1">
                                    <input type="hidden" id="lng" value="1">
                                    <input type="hidden" name="inputType" value="edit">

                                    <div class="row">
                                        <div class="col-md-4 f_r">
                                            <div class="form-group">
                                                <label for="name"> نام غذا</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                       placeholder="نام غذای محلی" value="{{$food->name}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 f_r">
                                            <div class="form-group">
                                                <label for="name"> استان</label>
                                                <select id="state" name="state" class="form-control"
                                                        onchange="findCity(this.value)">
                                                    @foreach($allState as $item)
                                                        <option value="{{$item->id}}" {{$item->id == $food->stateId? 'selected' : ''}}>
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
                                                       value="{{$food->city}}" onkeyup="searchCity(this.value)">
                                                <input type="hidden" name="cityId" id="cityId"
                                                       value="{{$food->cityId}}">

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
                                                <option value="1" {{$food->kind == 1? 'selected': ''}}>چلوخورش</option>
                                                <option value="2" {{$food->kind == 2? 'selected': ''}}>خوراک</option>
                                                <option value="3" {{$food->kind == 3? 'selected': ''}}>سالاد و پیش غذا
                                                </option>
                                                <option value="4" {{$food->kind == 4? 'selected': ''}}>ساندویچ</option>
                                                <option value="5" {{$food->kind == 5? 'selected': ''}}>کباب</option>
                                                <option value="6" {{$food->kind == 6? 'selected': ''}}>دسر</option>
                                                <option value="7" {{$food->kind == 7? 'selected': ''}}>نوشیدنی</option>
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
                                                       value="1" {{$food->hotOrCold == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r">
                                            <span style="direction: rtl" class="myLabel">سرد</span>
                                            <label class="switch">
                                                <input type="radio" name="hotOrCold" id="hotOrCold"
                                                       value="2" {{$food->hotOrCold == 2? 'checked' : ''}}>
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
                                                       onchange="fitFor(1)" {{$food->vegetarian == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">وگان</span>
                                            <label class="switch">
                                                <input type="checkbox" name="vegan" id="vegan" value="on"
                                                       onchange="fitFor(2)" {{$food->vegan == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">افراد مبتلا به دیابت</span>
                                            <label class="switch">
                                                <input type="checkbox" name="diabet" id="diabet" value="on"
                                                       onchange="fitFor(3)" {{$food->diabet == 1? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">هیچ کدام</span>
                                            <label class="switch">
                                                <input type="checkbox" name="commonEat" id="commonEat" value="on"
                                                       onchange="fitFor(4)" {{$food->vegan == 0 && $food->vegetarian == 0 && $food->diabet == 0? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class=" f_r" style="margin-left: 10px;">
                                            <span>
                                                <input type="text" class="form-control" name="energy" id="energy" placeholder="مقدار کالری"
                                                       style="width: 100px; display: inline-block" value="{{$food->energy}}">
                                            </span>
                                            <span>
                                                کیلوکالری در هر
                                            </span>
                                            <span>
                                                <input type="text" class="form-control" name="volume" id="volume" placeholder="مقدار مرجع"
                                                       style="width: 100px; display: inline-block"  value="{{$food->volume}}">
                                            </span>
                                            <span>
                                                <select name="source" class="form-control" style="width: 100px; display: inline-block">
                                                    <option value="1" {{$food->spoon == 1 ? 'selected' : ''}}>قاشق</option>
                                                    <option value="2" {{$food->gram == 1 ? 'selected' : ''}}>گرم</option>
                                                </select>
                                            </span>
                                        </div>

                                        <span class=" f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">برنج</span>
                                            <label class="switch">
                                                <input type="checkbox" name="rice" id="rice" value="on" {{$food->rice == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </span>

                                        <span class=" f_r" style="margin-left: 10px;">
                                            <span style="direction: rtl" class="myLabel">نان</span>
                                            <label class="switch">
                                                <input type="checkbox" name="bread" id="bread" value="on" {{$food->bread == 1 ? 'checked' : ''}}>
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
                                            @for($i = 0; $i < count($food->material); $i++)
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-md-3 f_r">
                                                        <label>
                                                            نام مواد
                                                        </label>
                                                        <input type="text" name="matName[{{$i}}]"
                                                               value="{{$food->material[$i][0]}}">
                                                    </div>
                                                    <div class="col-md-3 f_r">
                                                        <label>
                                                            مقدار مواد
                                                        </label>
                                                        <input type="text" name="matValue[{{$i}}]"
                                                               value="{{$food->material[$i][1]}}">
                                                    </div>
                                                </div>
                                            @endfor
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
                                                          rows="10">{!! $food->recipes !!}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row center">
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="keyword"> کلمه کلیدی</label>
                                                <input type="text" class="form-control" name="keyword" id="keyword"
                                                       onchange="setkeyWord(this.value)" value="{{$food->keyword}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 f_r">
                                            <div class="form-group">
                                                <label for="h1"> عنوان اصلی</label>
                                                <input type="text" class="form-control" name="h1" id="h1"
                                                       onchange="changeH1(this.value)" value="{{$food->h1}}">
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
                                                <textarea class="form-control" name="meta" id="meta" rows="10"
                                                          onkeyup="metaCheck(this.value)" maxlength="153"
                                                          minlength="130">{!! $food->meta !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWordMeta"
                                                         style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="site">توضیح</label>
                                                <textarea class="form-control" name="description" id="description"
                                                          rows="10"
                                                          onkeyup="descriptionCheck(this.value)">{!! $food->description !!}</textarea>
                                                <div>
                                                    <div class="inputDescription" id="remainWord"
                                                         style="font-size: 15px;"></div>
                                                    <div class="inputDescription" id="keywordDensity"
                                                         style="font-size: 15px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        @for($i = 0; $i < count($food->tags); $i++)
                                            <div class="f_r" style="margin-left: 15px;">
                                                <div class="form-group">
                                                    <label for="name"> برچسب {{$i+1}} </label>
                                                    <input type="text" class="form-control" name="tag[]"
                                                           id="tag{{$i+1}}" value="{{$food->tags[$i]}}"
                                                           {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
                                                </div>
                                            </div>
                                        @endfor
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px; display: flex; justify-content: center;">
                                        <button type="button" class="btn btn-success" style="margin-left: 10px;"
                                                onclick="checkForm()">تایید
                                        </button>
                                        <button type="button" class="btn"
                                                onclick="window.location.href = '{{url('newChangeContent/'. $state->id . '/' . $mode . '/0')}}'">
                                            خروج
                                        </button>
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
        var keyword = '{{$food->keyword}}';
        var _token = '{{csrf_token()}}';
        var findCityUrl = '{{route("get.allcity.withState")}}';
        var city = {!! $cities !!};
    </script>

    <script src="{{URL::asset('js/editContentPage.js')}}"></script>

    <script>
        var materialNum = {{count($food->material)}};

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