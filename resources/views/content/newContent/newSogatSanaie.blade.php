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
                                            سوغات\صنایع دستی جدید
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{route('storeSogatSanaie')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                    @csrf

                                    <input type="hidden" id="lat" value="1">
                                    <input type="hidden" id="lng" value="1">

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
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class=" f_r" style="margin-left: 10px;">
                                            <label for="kind">
                                                نوع
                                            </label>
                                            <select id="kind" name="eatable" onchange="changeKind(this.value)">
                                                <option value="1">خوراکی</option>
                                                <option value="0">غیر خوراکی</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="eatableDiv">
                                        <hr>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-12 f_r" style="margin-bottom: 10px; font-weight: bold">
                                                <span style="direction: rtl" class="myLabel">مزه خوراکی:</span>
                                            </div>

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">ترش</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="torsh" id="torsh" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">شیرین</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="shirin" id="shirin" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">تلخ</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="talkh" id="talkh" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">ملس</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="malas" id="malas" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">شور</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="shor" id="shor" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">تند</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="tond" id="tond" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                    <div id="notEatableDiv" style="display: none">
                                        <hr>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-2 f_r">
                                                <span style="direction: rtl; font-weight: bold" class="myLabel">نوع:</span>
                                            </div>

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">زیورآلات</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="jewelry" id="jewelry" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">پارچه و پوشیدنی</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="cloth" id="cloth" value="on" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">لوازم تزئینی</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="decorative" id="decorative" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">لوازم کاربردی منزل</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="applied" id="applied" value="on">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-2 f_r">
                                                <span style="direction: rtl; font-weight: bold" class="myLabel">سبک:</span>
                                            </div>

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">سنتی</span>
                                                <label class="switch">
                                                    <input type="radio" name="style" value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">مدرن</span>
                                                <label class="switch">
                                                    <input type="radio" name="style" value="2" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">تلفیقی</span>
                                                <label class="switch">
                                                    <input type="radio" name="style" value="3">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row" style="margin-top: 10px;">

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">شکستنی</span>
                                                <label class="switch">
                                                    <input type="radio" name="fragile" value="1">
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">غیر شکستنی</span>
                                                <label class="switch">
                                                    <input type="radio" name="fragile" value="0" >
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>

                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="site">جنس</label>
                                                    <input type="text" class="form-control" name="material" id="material" placeholder="جنس ان را توضیح دهید" value="{{old('material')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <hr>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl; font-weight: bold" class="myLabel">ابعاد:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">کوچک</span>
                                            <label class="switch">
                                                <input type="radio" name="size" value="1">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">متوسط</span>
                                            <label class="switch">
                                                <input type="radio" name="size" value="2" >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">بزرگ</span>
                                            <label class="switch">
                                                <input type="radio" name="size" value="3">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl; font-weight: bold" class="myLabel">وزن:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">سبک</span>
                                            <label class="switch">
                                                <input type="radio" name="weight" value="1">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">متوسط</span>
                                            <label class="switch">
                                                <input type="radio" name="weight" value="2" >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">سنگین</span>
                                            <label class="switch">
                                                <input type="radio" name="weight" value="3">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-2 f_r">
                                            <span style="direction: rtl; font-weight: bold" class="myLabel">کلاس قیمتی:</span>
                                        </div>

                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">ارزان</span>
                                            <label class="switch">
                                                <input type="radio" name="price" value="1">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">متوسط</span>
                                            <label class="switch">
                                                <input type="radio" name="price" value="2" >
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">گران</span>
                                            <label class="switch">
                                                <input type="radio" name="price" value="3">
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>

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
                                                <textarea class="form-control" name="meta" id="meta" rows="10" onkeyup="metaCheck(this.value)" maxlength="153" minlength="130" value="{{old('meta')}}"></textarea>
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

    <script>
        var kind = 1;

        function changeKind(_value){
            kind = _value;
            if(_value == 1){
                document.getElementById('notEatableDiv').style.display = 'none';
                document.getElementById('eatableDiv').style.display = 'block';
            }
            else{
                document.getElementById('eatableDiv').style.display = 'none';
                document.getElementById('notEatableDiv').style.display = 'block';
            }
        }

        function checkForm() {
            var error_text = '';
            var error = false;

            if (kind != 1){
                var material = document.getElementById('material').value;
                if (material == '' || material == null || material == ' ') {
                    error = true;
                    error_text += '<li>جنس ان را مشخص کنید.</li>';
                    document.getElementById('material').classList.add('error');
                }
                else
                    document.getElementById('material').classList.remove('error');
            }

            showErrorDivOrsubmit(error_text, error);
        }

    </script>

@stop