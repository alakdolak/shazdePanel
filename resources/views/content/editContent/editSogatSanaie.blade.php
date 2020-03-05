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
                                            ویرایش {{$place->name}}
                                        </h3>
                                    </div>
                                </div>
                                <hr>
                                <form id="form" action="{{route('storeSogatSanaie')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <input type="hidden" id="id" name="id" value="{{$place->id}}">
                                    <input type="hidden" name="inputType" value="edit">

                                    <input type="hidden" id="lat" value="1">
                                    <input type="hidden" id="lng" value="1">

                                    <div class="row">
                                        <div class="col-md-4 f_r">
                                            <div class="form-group">
                                                <label for="name"> نام</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="نام" value="{{$place->name}}">
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
                                                <option value="1" {{$place->eatable == 1 ? 'selected' : ''}}>خوراکی</option>
                                                <option value="0" {{$place->eatable == 0 ? 'selected' : ''}}>غیر خوراکی</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="eatableDiv" style="display: {{$place->eatable == 1? 'block': 'none'}};">
                                        <hr>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-12 f_r" style="margin-bottom: 10px; font-weight: bold">
                                                <span style="direction: rtl" class="myLabel">مزه خوراکی:</span>
                                            </div>

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">ترش</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="torsh" id="torsh" value="on" {{$place->torsh == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">شیرین</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="shirin" id="shirin" value="on" {{$place->shirin == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">تلخ</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="talkh" id="talkh" value="on" {{$place->talkh == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">ملس</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="malas" id="malas" value="on" {{$place->malas == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">شور</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="shor" id="shor" value="on" {{$place->shor == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">تند</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="tond" id="tond" value="on" {{$place->tond == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                    <div id="notEatableDiv" style="display: {{$place->eatable == 0? 'block': 'none'}};">
                                        <hr>
                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-sm-2 f_r">
                                                <span style="direction: rtl; font-weight: bold" class="myLabel">نوع:</span>
                                            </div>

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">زیورآلات</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="jewelry" id="jewelry" value="on" {{$place->jewelry == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">پارچه و پوشیدنی</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="cloth" id="cloth" value="on" {{$place->cloth == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">لوازم تزئینی</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="decorative" id="decorative" value="on"{{$place->decorative == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">لوازم کاربردی منزل</span>
                                                <label class="switch">
                                                    <input type="checkbox" name="applied" id="applied" value="on" {{$place->applied == 1 ? 'checked' : ''}}>
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
                                                    <input type="radio" name="style" value="1" {{$place->style == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">مدرن</span>
                                                <label class="switch">
                                                    <input type="radio" name="style" value="2" {{$place->style == 2 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">تلفیقی</span>
                                                <label class="switch">
                                                    <input type="radio" name="style" value="3" {{$place->style == 3 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="row" style="margin-top: 10px;">

                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">شکستنی</span>
                                                <label class="switch">
                                                    <input type="radio" name="fragile" value="1" {{$place->fragile == 1 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                            <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                                <span style="direction: rtl" class="myLabel">غیر شکستنی</span>
                                                <label class="switch">
                                                    <input type="radio" name="fragile" value="0" {{$place->fragile == 0 ? 'checked' : ''}}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>

                                        </div>

                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="site">جنس</label>
                                                    <input type="text" class="form-control" name="material" id="material" placeholder="جنس ان را توضیح دهید" value="{{ $place->material }}">
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
                                                <input type="radio" name="size" value="1" {{$place->size == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">متوسط</span>
                                            <label class="switch">
                                                <input type="radio" name="size" value="2"  {{$place->size == 2 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">بزرگ</span>
                                            <label class="switch">
                                                <input type="radio" name="size" value="3" {{$place->size == 3 ? 'checked' : ''}}>
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
                                                <input type="radio" name="weight" value="1" {{$place->weight == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">متوسط</span>
                                            <label class="switch">
                                                <input type="radio" name="weight" value="2" {{$place->weight == 2 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">سنگین</span>
                                            <label class="switch">
                                                <input type="radio" name="weight" value="3" {{$place->weight == 3 ? 'checked' : ''}}>
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
                                                <input type="radio" name="price" value="1" {{$place->price == 1 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">متوسط</span>
                                            <label class="switch">
                                                <input type="radio" name="price" value="2" {{$place->price == 2 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-2 f_r" style="border-left: solid gray;">
                                            <span style="direction: rtl" class="myLabel">گران</span>
                                            <label class="switch">
                                                <input type="radio" name="price" value="3" {{$place->price == 3 ? 'checked' : ''}}>
                                                <span class="slider round"></span>
                                            </label>
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
                                                    <input type="text" class="form-control" name="tag[]" id="tag{{$i+1}}" value="{{$place->tags[$i]}}" {{$i < 5 ? 'required' : ''}} onchange="checkTags()">
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
        var _token = '{{csrf_token()}}';
        var findCityUrl = '{{route("get.allcity.withState")}}';
        var city = {!! $cities !!};
        var keyword = '{{$place->keyword}}';
        var kind = 1;
    </script>

    <script src="{{URL::asset('js/editContentPage.js')}}"></script>

    <script>

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

    </script>

@stop