@extends('layouts.structure')

@section('header')
    @parent
    <script>
        var UploadURL = '{{url('/uploadCKEditor')}}';
    </script>

    <script src="{{URL::asset('js/ckeditor/ckeditor.js')}}"></script>
    <script src="{{URL::asset('js/ckeditor/sample.js')}}"></script>
    <script src="{{URL::asset('js/jalali.js')}}"></script>


    <link rel="stylesheet" href="{{URL::asset('css/calendar/persian-datepicker.css')}}"/>
    <script src="{{URL::asset('js/calendar/persian-date.min.js')}}"></script>
    <script src="{{URL::asset('js/calendar/persian-datepicker.js')}}"></script>

    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/clockPicker/clockpicker.css')}}"/>
    <script src= {{URL::asset("js/clockPicker/clockpicker.js") }}></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *{
            text-align: right;
            direction: rtl;
        }
        .newTag{
            border: none;
            border-radius: 0px !important;
            width: 85%;
        }
        .checkIconTag{
            color: green;
            border-right: none;
            padding: 3px 0px;
            font-size: 22px;
            border-radius: 10px 0px 0px 10px;
            cursor: pointer;
        }
        .closeIconTag{
            color: white;
            background-color: red;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .searchTagResultDiv{
            width: 100%;
            border-top: 0px;
        }
        .searchTagResult{
            position: relative;
            padding: 0px 10px;
            background-color: white;
            cursor: pointer;
            margin: 6px 0px !important;
        }
        .searchTagResult:hover{
            background-color: #ecebeb;
        }
        .inputBorder{
            border: solid gray;
            border-radius: 10px;
        }
        .inputGroup{
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: solid gray;
            border-radius: 10px
        }
        .inTag{
            display: flex;
            align-items: center;
            justify-content: space-evenly
        }
        .categoryDiv{
            height: 300px;
            border: solid gray;
            overflow-y: auto;
        }
        .mainCategory{
            margin-top: 10px !important;
            margin-right: 5px !important;
        }
        .subCategory{
            margin-right: 15px !important;
            /*border-right: solid 1px;*/
            width: auto !important;
            /*padding-top: 10px;*/
        }
        .checkbox{
            width: auto;
            display: inline-block;
            outline: none;
            height: auto;
            margin: 0 !important;
        }
        .labelCategory{
            display: inline-flex;
            align-items: center;
            cursor: pointer;
        }
        .mainLabelCategory{
            margin-bottom: 0px;
        }
        .subLabelCategory{
            padding-right: 5px;
            margin-bottom: 0px;
            margin-top: 3px;
        }
        .labelName{
            margin-right: 5px;
            font-weight: 400;
            font-size: 11px;
        }
        .mainCatButton{
            color: #b0ccff;
            cursor: pointer;
            /*display: inline-block;*/
            float: left;
            display: none;
        }
        .mainCatButtonChoose{
            color: blue;
            border-bottom: blue 1px solid;
        }
        .row{
            margin: 0;
            width: 100%;
        }
        .floR{
            float: right;
        }
        .labelCategory{
            position: relative;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .labelCategory input{
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            height: 15px;
            width: 15px;
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .labelCategory input:checked ~ .checkmark {
            background-color: #2196F3;
        }
        .labelCategory:hover input ~ .checkmark {
            background-color: #ccc;
        }
        .labelCategory input:checked ~ .checkmark {
            background-color: #2196F3;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .labelCategory input:checked ~ .checkmark:after {
            display: block;
        }
        .labelCategory .checkmark:after {
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        .cke_widget_wrapper_easyimage-side, :not(.cke_widget_wrapper_easyimage):not(.cke_widget_wrapper_easyimage-side) > .easyimage-side, .cke_widget_wrapper_easyimage-align-right, :not(.cke_widget_wrapper_easyimage):not(.cke_widget_wrapper_easyimage-align-right) > .easyimage-align-right{
            float: left !important;
            max-width: 50% !important;
            min-width: 10em !important;
            margin-right: 1.5em !important;
            margin-left: 0em !important;
        }
    </style>
@stop

@section('content')

    <input type="hidden" id="postId" value="{{isset($post) ? $post->id : '0'}}">
        <input type="hidden" id="gardeshName" value="{{isset($post) && isset($post->gardeshName) ? $post->gardeshName : '0'}}">

    <div class="col-md-3 leftSection">
        <div class="sparkline8-list shadow-reset mg-tb-30">

            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h4>زمان انتشار</h4>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages">
                <div class="form-group">
                    <label for="releaseType">انتشار</label>
                    <select class="form-control" id="releaseType" name="release" onchange="changeRelease(this.value)">
                        <option value="released" {{isset($post) ? ($post->release == 'released' ? 'selected' : '')  : ''}}>منتشرشده</option>
                        <option value="draft" {{isset($post) ? ($post->release == 'draft' ? 'selected' : '')  : 'selected'}}>پیش نویس</option>
                        <option value="future" {{isset($post) ? ($post->release == 'future' ? 'selected' : '')  : ''}}>آینده</option>
                    </select>
                </div>

                <div id="futureDiv" style="display: {{isset($post) && $post->release == 'future' ? '' : 'none'}}">
                    <div class="form-group" style="display: flex">
                        <label for="date" style="font-size: 10px;">تاریخ انتشار</label>
                        <input name="date" id="date" class="observer-example inputBoxInput" value="{{isset($post) ? $post->date : ''}}" readonly/>
                    </div>
                    <div class="form-group" style="display: flex;">
                        <label for="time" style="font-size: 10px;">ساعت انتشار</label>
                        <input name="time" id="time" class="inputBoxInput" style="width: 73%;" value="{{isset($post) ? $post->time : ''}}" readonly/>
                    </div>
                </div>
            </div>

            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h4>دسته بندی ها</h4>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto">
                <div class="row">
                    <div class="categoryDiv">
                        <div id="mainCategoryDiv" style="padding: 5px;">
                            @foreach($category as $item)
                                <div class="row mainCategory">
                                    <label for="category{{$item->id}}" class="labelCategory mainLabelCategory" onclick="chooseCategory(this, {{$item->id}})">
                                        <input type="checkbox" id="category{{$item->id}}" name="category[]" value="{{$item->id}}" class="form-control checkbox">
                                        <span class="checkmark"></span>
                                        <span class="labelName">{{$item->name}}</span>
                                        @if(auth()->user()->level == 1)
                                            <i class="fa fa-close closeIconTag" style="width: 15px; height: 15px; font-size: 10px; margin-right: 15px;" onclick="deleteCategory({{$item->id}}, this)"></i>
                                        @endif
                                    </label>
                                    <div id="mainCategory{{$item->id}}" class="mainCatButton" onclick="changeMainCategory({{$item->id}})" >
                                        اصلی
                                    </div>
                                </div>
                                <div id="subCategoryDiv{{$item->id}}">
                                    @foreach($item->sub as $item2)
                                        <div class="row subCategory">
                                            <label for="category{{$item2->id}}" class="labelCategory subLabelCategory" onclick="chooseCategory(this, {{$item2->id}})">
                                                <input type="checkbox" id="category{{$item2->id}}" name="category[]" value="{{$item2->id}}" class="form-control checkbox">
                                                <span class="checkmark"></span>
                                                <span class="labelName">
                                                    {{$item2->name}}
                                                </span>
                                                @if(auth()->user()->level == 1)
                                                    <i class="fa fa-close closeIconTag" style="width: 15px; height: 15px; font-size: 10px; margin-right: 15px;" onclick="deleteCategory({{$item2->id}}, this)"></i>
                                                @endif
                                            </label>
                                            <div id="mainCategory{{$item2->id}}" class="mainCatButton" onclick="changeMainCategory({{$item2->id}})">
                                                اصلی
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if(auth()->user()->level == 1)
                    <div class="row" style="margin-top: 20px; margin-right: 10px;">
                        <a onclick="showNewCategory(this)" style="cursor: pointer;">
                            +افزودن دسته بندی جدید
                        </a>
                    </div>
                @endif
                <div class="row" style="margin-top: 20px; display: none">
                    <div class="form-group">
                        <label for="selectCategory" style="font-size: 9px; margin-left: 3px">دسته بندی جدید</label>
                        <select id="selectCategory" class="form-control">
                            <option value="0">اصلی...</option>
                            @foreach($category as $item)
                                <option value="{{$item->id}}">
                                    {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="inputBorder">
                            <div class="inputGroup">
                                <input type="text" class="newTag" id="newCategory">
                                <i class="fa fa-check checkIconTag" onclick="submitNewCategory()"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h4>برچسب ها</h4>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto">
                <div class="row">
                    <div>
                        <a id="newTagButton" onclick="showNewTag(this)" style="cursor: pointer;">
                            افزودن برچسب جدید+
                        </a>
                    </div>
                    <div class="form-group" style="display: none">
                        <label for="newTag">برچسب جدید</label>
                        <div class="inputBorder">
                            <div class="inputGroup">
                                <input type="text" class="newTag" id="newTag" onkeyup="searchTag(this.value)">
                                <i class="fa fa-check checkIconTag" onclick="selectTag()"></i>
                            </div>
                            <div id="searchTagResultDiv" class="searchTagResultDiv"></div>
                        </div>
                    </div>
                    <div>
                        <div id="selectedTags" class="col-md-12"></div>
                    </div>

                </div>
            </div>

            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h4>عکس اصلی</h4>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto">
                <div class="row">
                    <div class="showImg">
                        <img id="mainPicShow" src="{{isset($post)? $post->pic : ''}}" style="width: 100%;">

                        <label for="imgInput" class="btn btn-success" style="width: 100%; text-align: center; margin-top: 10px">
                            انتخاب عکس
                            <input type="file" id="imgInput" name="mainPic" accept="image/*" style="display: none;" onchange="changePic(this)">
                        </label>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="col-md-9">
        <div class="col-md-12">
            <div class="sparkline8-list shadow-reset mg-tb-30">
                <div class="sparkline8-hd">
                    <div class="main-sparkline8-hd">
                        @if(isset($post))
                            <h1>ویرایش پست</h1>
                        @else
                            <h1>افزودن پست جدید</h1>
                        @endif
                    </div>
                </div>

                <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                    <div class="row" style="text-align: right">

                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="title">عنوان پست</label>
                                <input class="form-control" type="text" name="title" id="title" value="{{(isset($post) ? $post->title : '')}}">
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="adjoined-bottom">
                                <div class="grid-container">
                                    <div class="grid-width-100">
                                    <textarea id="editor" name="text">
                                        @if(isset($post))
                                            {!! html_entity_decode($post->description) !!}
                                        @endif
                                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="sparkline8-list shadow-reset">

                <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                    <div class="row" style="text-align: right">

                        <div class="col-xs-6">
                            <div class="row">
                                <div class="col-md-6" id="searchCityDiv" style="padding: 0px">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="placeNameSearch">شهر:</label>
                                            <div class="inputBorder">
                                                <div class="inputGroup">
                                                    <input type="text" class="newTag" id="cityNameSearch" onkeyup="findCity(this.value)">
                                                    <input type="hidden" id="cityIdSearch">
                                                    <i class="fa fa-check checkIconTag" onclick="chooseCity()"></i>
                                                </div>
                                                <div id="searchCityResultDiv" class="searchTagResultDiv"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 floR">
                                    <div class="form-group">
                                        <label for="osatn">استان</label>
                                        <select id="ostan" class="form-control" onchange="changeOstan(this)">
                                            <option value="0">استان را انتخاب کنید</option>
                                            @foreach($ostan as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="selectedCity" class="row">
                                        @if(isset($post))
                                            @foreach($post->city as $item)
                                                <div class="inTag">
                                                    <input type="text" value="{{$item->name}}" style="border: none; width: 100%; font-size: 12px;" readonly>
                                                    <input type="hidden" name="cities[]" id="cities" value="{{$item->validId}}" style="border: none; width: 100%; font-size: 12px;" readonly>
                                                    <i class="fa fa-close closeIconTag" onclick="deleteCity(this)"></i>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6" style="border-left: solid gray">
                            <div class="row">
                                <div class="form-group">
                                    <label for="placeNameSearch">با چه مکانی ارتباط دارد:</label>
                                    <div class="inputBorder">
                                        <div class="inputGroup">
                                            <input type="text" class="newTag" id="placeNameSearch" onkeyup="findPlace(this.value)" style="width: 95%;">
                                        </div>
                                        <div id="searchPlaceResultDiv" class="searchTagResultDiv"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="selectedPlace" class="row">
                                @if(isset($post))
                                    @foreach($post->place as $item)
                                        <div class="col-md-6 floR">
                                            <div class="inTag">
                                                <input type="text" value="{{$item->name}}" style="border: none; width: 100%; font-size: 12px;" readonly>
                                                <input type="hidden" name="places[]" id="places" value="{{$item->validId}}" style="border: none; width: 100%; font-size: 12px;" readonly>
                                                <i class="fa fa-close closeIconTag" onclick="deletePlace(this)"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-12" style="margin-top: 25px;">
            <div class="sparkline8-list shadow-reset">

                <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                    <div class="row" style="text-align: right">
                        <div class="col-md-12 floR">
                            <div class="form-group">
                                <label for="keyword">کلمه کلیدی</label>
                                <input class="form-control" type="text" id="keyword" name="keyword" value="{{isset($post)? $post->keyword: ''}}">
                            </div>
                        </div>
                        <div class="col-md-12 floR">
                            <div class="form-group">
                                <label for="seoTitle">عنوان سئو: <span id="seoTitleNumber" style="font-weight: 200;"></span> </label>
                                <input class="form-control" type="text" id="seoTitle" name="seoTitle" onkeyup="changeSeoTitle(this.value)" value="{{isset($post)? $post->seoTitle: ''}}">

                            </div>
                        </div>
                        <div class="col-md-12 floR">
                            <div class="form-group">
                                <label for="slug">نامک</label>
                                <input class="form-control" type="text" id="slug" name="slug" value="{{isset($post)? $post->slug: ''}}">
                            </div>
                        </div>
                        <div class="col-md-12 floR">
                            <div class="form-group">
                                <label for="meta">متا: <span id="metaNumber" style="font-weight: 200;"></span></label>
                                <textarea class="form-control" type="text" id="meta" name="meta" onkeyup="changeMeta(this.value)" rows="3">{{isset($post)? $post->meta: ''}}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="row" style="text-align: center">
                        <button class="btn btn-primary" onclick="checkSeo(0)">تست سئو</button>
                    </div>
                    <div class="row" style="text-align: right">
                        <div id="errorResult"></div>
                        <div id="warningResult"></div>
                        <div id="goodResult"></div>
                    </div>
                </div>

                <center style="padding: 10px; text-align: center; width: 100%;">
                    <input type="button" onclick="checkSeo(1)"  value="ثبت" class="btn btn-success">
                    <input type="button" onclick="window.location.href='{{route("posts")}}'"  value="بازگشت" class="btn btn-secondry">
                </center>

            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="warningModal">
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
                    <button type="button" class="btn btn-success"  data-dismiss="modal" onclick="storePost()">بله پست ثبت شود</button>
                </div>

            </div>
        </div>
    </div>


    <img id="beforeSaveImg" src="" style="display: none;">

    <script>
        var mainCategory = {{isset($post) ? $post->mainCategory : 0}};
        var tagsName = [];
        var placeId = [];
        var city = [];
        var cityId = [];
        var selectedOstanId = 0;
        var selectedOstanName = 0;
        var postId;
        var mainDataForm = new FormData();
        var warningCount = 0;
        var errorCount = 0;
        var uniqueKeyword;
        var uniqueTitle;
        var uniqueSeoTitle;
        var uniqueSlug;
        var post = null;

        //ckeditor function
        initSample();

        @if(isset($post))
            post = {!! $postJson !!};

            function init(){
                var cat = post['category'];
                for(var i = 0; i < cat.length; i++) {
                        $('#category' + cat[i]['categoryId']).prop('checked', true);
                    element = $('#category' + cat[i]['categoryId']).parent();
                    chooseCategory(element, cat[i]['categoryId']);
                }
                if(mainCategory != 0)
                    changeMainCategory(mainCategory);

                var tags = post['tags'];
                if(tags.length > 0){
                    element = $('#newTagButton');
                    showNewTag(element);
                    for(var i = 0; i < tags.length; i++) {
                        if(tags[i]['tag'])
                            chooseTag(tags[i]['tag']);
                    }
                }

                for(var i = 0; i < post['city'].length; i++)
                    cityId[cityId.length] = post['city'][i]['validId'];

                for(var i = 0; i < post['place'].length; i++)
                    placeId[placeId.length] = post['place'][i]['validId'];
            }
            init();

        @endif

        $('.observer-example').persianDatepicker({
            minDate: new Date().getTime(),
            format: 'YYYY/MM/DD',
            autoClose: true,
        });

        $('#time').clockpicker({
            donetext: 'تایید',
            autoclose: true,
        });

        function changeRelease(value){
            if(value == 'future')
                document.getElementById('futureDiv').style.display = 'block';
            else
                document.getElementById('futureDiv').style.display = 'none';
        }

        function searchTag(value){
            document.getElementById('searchTagResultDiv').innerHTML = '';

            $.ajax({
                type: 'post',
                url: '{{route("postTagSearch")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    text: value
                },
                success: function (response){
                    response = JSON.parse(response);
                    if(response[0] == 'ok'){
                        var tags = response[1];
                        var text = '';

                        for(var i = 0; i < tags.length; i++)
                            text += '<div class="row searchTagResult" onclick="chooseTag(\'' + tags[i]["tag"] + '\')">' + tags[i]["tag"] + '</div>\n';

                        document.getElementById('searchTagResultDiv').innerHTML = text;
                    }
                }
            })
        }

        function chooseTag(value){

                document.getElementById('searchTagResultDiv').innerHTML = '';
                document.getElementById('newTag').value = value;

                selectTag();
        }

        function selectTag() {
            var value = document.getElementById('newTag').value ;
            if(tagsName.indexOf(value) == -1) {
                tagsName[tagsName.length] = value;
                if (value.trim().length != 0) {
                    document.getElementById('newTag').value = '';
                    var text = '<div class="inTag">\n' +
                        '<input type="text" name="tags[]" id="tags" value="' + value + '" style="border: none; width: 100%; font-size: 12px;" readonly>\n' +
                        '<i class="fa fa-close closeIconTag" onclick="deleteTag(this)"></i>\n' +
                        '</div>';

                    $('#selectedTags').append(text);
                }
            }
        }

        function deleteTag(element){
            value = $(element).prev().val();
            index = tagsName.indexOf(value);
            if(index != -1)
                tagsName[index] = '';

            $(element).parent().remove();
        }

        function changePic(input){
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#mainPicShow').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function chooseCategory(element, id){
            if($(element).children().first().prop('checked')) {
                $(element).next().css('display', 'inline-block');
                if(mainCategory == 0){
                    mainCategory = id;
                    document.getElementById('mainCategory' + id).classList.add('mainCatButtonChoose');
                }
            }
            else {
                $(element).next().css('display', 'none');
                if(mainCategory == id)
                    mainCategory = 0;
            }
        }

        function changeMainCategory(id){
            if(mainCategory != 0)
                document.getElementById('mainCategory' + mainCategory).classList.remove('mainCatButtonChoose');

            mainCategory = id;
            document.getElementById('mainCategory' + id).classList.add('mainCatButtonChoose');

        }

        function showNewCategory(element){
            $(element).toggle();
            $(element).parent().next().toggle();
        }

        function showNewTag(element){
            $(element).toggle();
            $(element).parent().next().toggle();
        }

        function submitNewCategory(){
            var value = document.getElementById('newCategory').value;
            var parent = document.getElementById('selectCategory').value;

            if(value.trim().length != 0){
                $.ajax({
                    type: 'post',
                    url: '{{route("newPostCategory")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        value: value,
                        parent: parent
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            var id = response[1];

                            if(parent == 0){
                                    var text =  '<div class="row mainCategory">\n' +
                                            '<label for="category' + id + '" class="labelCategory mainLabelCategory" onclick="chooseCategory(this, ' + id + ')">\n' +
                                            '<input type="checkbox" id="category' + id + '" name="category[]" value="' + id + '" class="form-control checkbox">\n' +
                                            '<span class="checkmark"></span>\n' +
                                            '<span class="labelName">' + value + '</span>\n' +
                                            '<i class="fa fa-close closeIconTag" style="width: 15px; height: 15px; font-size: 10px; margin-right: 15px;" onclick="deleteCategory(' + id + ', this)"></i>\n' +
                                            '</label>\n' +
                                            '<div id="mainCategory' + id + '" class="mainCatButton" onclick="changeMainCategory(' + id + ')" >\n' +
                                            'اصلی\n' +
                                            '</div>\n' +
                                            '</div>\n';

                                $('#mainCategoryDiv').append(text);
                            }
                            else{
                                var text =  '<div class="row subCategory">\n' +
                                            '<label for="category' + id + '" class="labelCategory subLabelCategory" onclick="chooseCategory(this, ' + id + ')">\n' +
                                            '<input type="checkbox" id="category' + id + '" name="category[]" value="' + id + '" class="form-control checkbox">\n' +
                                            '<span class="checkmark"></span>\n' +
                                            '<span class="labelName">\n' +
                                            value +
                                            '</span>\n' +
                                            '<i class="fa fa-close closeIconTag" style="width: 15px; height: 15px; font-size: 10px; margin-right: 15px;" onclick="deleteCategory(' + id + ', this)"></i>\n' +
                                            '</label>\n' +
                                            '<div id="mainCategory' + id + '" class="mainCatButton" onclick="changeMainCategory(' + id + ')">\n' +
                                            'اصلی\n' +
                                            '</div>\n' +
                                            '</div>';
                                $('#subCategoryDiv' + parent).append(text);
                            }

                            document.getElementById('newCategory').value = '';
                        }
                        else if(response[0] == 'nok2')
                            alert('دسته بندی با این نام موجود است')
                    }
                })
            }
        }

        function findPlace(value){
            document.getElementById('searchPlaceResultDiv').innerHTML = '';

            if(value.trim().length > 1){
                $.ajax({
                    type: 'post',
                    url: '{{route("find.place")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        value: value,
                        kindPlaceId: 0
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        var text = '';
                        for(var i = 0; i < response.length; i++) {
                            var idKind = response[i]['id'] + '_' + response[i]['kindPlaceId'];
                            if(placeId.indexOf(idKind) == -1)
                                text += '<div class="row searchTagResult" onclick="selectPlace(\'' + idKind + '\', \'' + response[i]["targetName"] + '\', this)">' + response[i]["targetName"] + '</div>\n';
                        }
                        document.getElementById('searchPlaceResultDiv').innerHTML = text;
                    }
                })
            }
        }

        function selectPlace(_id, _name, element){
            if(placeId.indexOf(_id) == -1) {
                placeId[placeId.length] = _id;
                $(element).remove();
                var text = '<div class="col-md-6 floR">\n' +
                    '<div class="inTag">\n' +
                    '<input type="text" value="' + _name + '" style="border: none; width: 100%; font-size: 12px;" readonly>\n' +
                    '<input type="hidden" name="places[]" id="places" value="' + _id + '" style="border: none; width: 100%; font-size: 12px;" readonly>\n' +
                    '<i class="fa fa-close closeIconTag" onclick="deletePlace(this)"></i>\n' +
                    '</div>\n' +
                    '</div>';

                $("#selectedPlace").append(text);

                document.getElementById('searchPlaceResultDiv').innerHTML = '';
                document.getElementById('placeNameSearch').value = '';
            }
        }

        function deletePlace(element){
            value = $(element).prev().val();
            index = placeId.indexOf(value);
            if(index != -1)
                placeId[index] = '';

            $(element).parent().parent().remove();
        }

        function changeOstan(_element){
            var _id = _element.value;
            var _name = _element.options[_element.selectedIndex].text;

            if(_id != 0){
                document.getElementById('searchCityResultDiv').innerHTML = '';
                document.getElementById('cityNameSearch').value = '';
                document.getElementById('cityIdSearch').value = 0;
                selectedOstanId = _id;
                selectedOstanName = _name;

                $.ajax({
                    type: 'post',
                    url: '{{route("get.allcity.withState")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id,
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        city = response;
                    }
                })
            }
        }

        function findCity(value){
            document.getElementById('searchCityResultDiv').innerHTML = '';

            if(value.trim().length > 1){
                var text = '';
                for(i = 0; i < city.length; i++){
                    if(city[i]['name'].includes(value)){
                        var val = selectedOstanId + '_' + city[i]['id'];
                        if(cityId.indexOf(val) == -1)
                            text += '<div class="row searchTagResult" onclick="selectCity(\'' + val + '\', \'' + city[i]['name'] + '\')">' + city[i]['name'] + '</div>\n';
                    }
                }

                document.getElementById('searchCityResultDiv').innerHTML = text;
            }
        }

        function selectCity(_id, _name){
            if(cityId.indexOf(_id) == -1) {
                document.getElementById('searchCityResultDiv').innerHTML= '';
                document.getElementById('cityNameSearch').value = _name;
                document.getElementById('cityIdSearch').value = _id;
                // chooseCity();
            }
        }

        function chooseCity(){
            var id = document.getElementById('cityIdSearch').value;
            var name = document.getElementById('cityNameSearch').value;

            if(selectedOstanId == 0){
                alert('استان و شهر را مشخص کنید...')
                return;
            }

            if(id == 0) {
                var val = 'استان ' + selectedOstanName;
                id = selectedOstanId + '_0';
            }
            else
                var val = 'شهر ' + name;

            if(cityId.indexOf(id) == -1) {
                var text = '<div class="inTag">\n' +
                    '<input type="text" value="' + val + '" style="border: none; width: 100%; font-size: 12px;" readonly="">\n' +
                    '<input type="hidden" name="cities[]" id="cities" value="' + id + '" style="border: none; width: 100%; font-size: 12px;" readonly="">\n' +
                    '<i class="fa fa-close closeIconTag" onclick="deleteCity(this)"></i>\n' +
                    '</div>';


                $('#selectedCity').append(text);

                document.getElementById('cityNameSearch').value = '';
                document.getElementById('cityIdSearch').value = 0;
                cityId[cityId.length] = id;
            }
        }

        function deleteCity(element){
            value = $(element).prev().val();
            index = cityId.indexOf(value);
            if(index != -1)
                cityId[index] = '';

            $(element).parent().remove();
        }

        var errorInUploadImage = false;
        function findSrc(startPos){
            var desc = CKEDITOR.instances['editor'].getData();
            var index1 = desc.indexOf('blob', startPos);
            if(index1 != -1){
                var index2 = desc.indexOf('"',(index1-2));
                var index3 = desc.indexOf('"',(index1+2));

                var imageUrl = desc.substring(
                    index2+1,
                    index3
                );
                document.getElementById('beforeSaveImg').src = imageUrl;
                var img = document.getElementById('beforeSaveImg');
                var canvas = document.createElement("canvas");
                var data = new FormData();

                canvas.width = img.width;
                canvas.height = img.height;

                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0);
                var dataURL = canvas.toDataURL("image/png");
                var eee = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");

                data.append('pic', eee);
                data.append('id', postId);

                $.ajax({
                    type: 'post',
                    url: '{{route("imageUploadCKeditor4")}}',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        if(response != 'nok1' && response != 'nok2') {
                            result = desc.replace(imageUrl, response);
                            CKEDITOR.instances['editor'].setData(result);
                            findSrc();
                        }
                        else{
                            errorInUploadImage = true;
                            findSrc(index3);
                        }

                    },
                    error: function(response){
                        console.log(response)
                        errorInUploadImage = true;
                        findSrc(index3);
                    }
                })
            }
            else {
                if(errorInUploadImage)
                    alert('در هنگام اپلود عکس ها مشکلی پیش امد');
                submitDescription();
            }
        }

        function storePost(){

            var id = document.getElementById('postId').value;
            var gardeshName = document.getElementById('gardeshName').value;
            var title = document.getElementById('title').value;
            var description = ' ';
            var release = document.getElementById('releaseType').value;
            var date = document.getElementById('date').value;
            var time = document.getElementById('time').value;
            var tags = $("input[id='tags']").map(function(){return [$(this).val()];}).get();
            var category = $("input[name='category[]']").map(function(){return [$(this).prop('checked'), $(this).val()]}).get();
            var inputMainPic = document.getElementById('imgInput');
            var keyword = document.getElementById('keyword').value;
            var seoTitle = document.getElementById('seoTitle').value;
            var slug = document.getElementById('slug').value;
            var meta = document.getElementById('meta').value;

            if(title.trim().length < 2){
                alert('عنوان مقاله را مشخص کنید');
                return;
            }

            if(release == 'future' && (date == null || date == null || time == null || time == '')){
                alert('تاریخ و ساعت انتشار را مشخص کنید.');
                return;
            }
            else if(release != 'release'){
                var d = new Date();
                date = d.getJalaliFullYear() + '/' + (d.getJalaliMonth() + 1) + '/' + d.getJalaliDate();
            }

            if(release != 'draft'){

                if(mainCategory == 0){
                    alert('لطفا دسته بندی اصلی را مشخص کنید.');
                    return;
                }

                if(keyword.trim().length < 2){
                    alert('کلمه کلیدی مقاله را مشخص کنید');
                    return;
                }

                if(meta.trim().length < 2){
                    alert('متا مقاله را مشخص کنید');
                    return;
                }

                if(seoTitle.trim().length < 2){
                    alert('عنوان سئو مقاله را مشخص کنید');
                    return;
                }

                if(slug.trim().length < 2){
                    alert('نامک مقاله را مشخص کنید');
                    return;
                }
            }


            mainDataForm.append('title', title);
            mainDataForm.append('keyword', keyword);
            mainDataForm.append('seoTitle', seoTitle);
            mainDataForm.append('slug', slug);
            mainDataForm.append('meta', meta);
            mainDataForm.append('gardeshName', gardeshName);
            mainDataForm.append('description', description);
            mainDataForm.append('releaseType', release);
            mainDataForm.append('date', date);
            mainDataForm.append('time', time);
            mainDataForm.append('tags', JSON.stringify(tags));
            mainDataForm.append('mainCategory', mainCategory);
            mainDataForm.append('category', JSON.stringify(category));
            mainDataForm.append('id', id);
            mainDataForm.append('cityId', JSON.stringify(cityId));
            mainDataForm.append('placeId', JSON.stringify(placeId));
            mainDataForm.append('warningCount', warningCount);

            if (id == 0) {

                if(inputMainPic.files && inputMainPic.files[0]){
                    mainDataForm.append('pic', inputMainPic.files[0]);
                    ajaxPost();
                }
                else if(release != 'draft'){
                    alert('لطفا عکس اصلی را مشخص کنید.');
                    return;
                }
                else if(release == 'draft')
                    ajaxPost();
            }
            else {
                if (inputMainPic.files && inputMainPic.files[0])
                    mainDataForm.append('pic', inputMainPic.files[0]);

                ajaxPost();
            }
        }

        function ajaxPost(){
            document.getElementById('loader').style.display = 'flex';
            $.ajax({
                type: 'post',
                url: '{{route("storePost")}}',
                data: mainDataForm,
                processData: false,
                contentType: false,
                success: function(response){
                    response = JSON.parse(response);
                    if(response[0] == 'ok'){
                        postId = response[1];
                        document.getElementById('postId').value = response[1];
                        findSrc(0);
                    }
                }
            });
        }

        function submitDescription(){
            var desc = CKEDITOR.instances['editor'].getData();
            if(desc == '')
                desc = ' ';

            $.ajax({
                type: 'post',
                url: '{{route("storeDescriptionPost")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: postId,
                    description : desc
                },
                success: function(response){
                    if(response == 'ok')
                        alert('تغییرات با موفقیت ثبت شد');
                    var location = window.location.href;

                    if(location.includes('createPost') || location.includes('gardeshEdit'))
                        window.location.href = '{{url("editPost")}}/' + postId;
                    else
                        document.getElementById('loader').style.display = 'none';
                }
            });
        }

        function checkSeo(kind){

            var value = document.getElementById('keyword').value;
            var seoTitle = document.getElementById('seoTitle').value;
            var slug = document.getElementById('slug').value;
            var meta = document.getElementById('meta').value;
            var title = document.getElementById('title').value;
            var postId = document.getElementById('postId').value;
            var desc = CKEDITOR.instances['editor'].getData();

            $.ajax({
                type: 'post',
                url : '{{route("seoTesterPostContent")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    keyword: value,
                    meta: meta,
                    seoTitle: seoTitle,
                    slug: slug,
                    title: title,
                    id: postId,
                    desc: desc
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
            var tags = $("input[id='tags']").map(function(){return [$(this).val()];}).get();
            if(tags.length == 0){
                errorCount++;
                text = '<div style="color: red;">شما باید برای متن خود برچسب انتخاب نمایید</div>';
                $('#errorResult').append(text);
            }
            else if(tags.length < 10){
                warningCount++;
                text = '<div style="color: #dec300;">پیشنهاد می گردد حداقل ده برچسب انتخاب نمایید.</div>';
                $('#warningResult').append(text);
            }
            else{
                text = '<div style="color: green;">تعداد برچسب های متن مناسب می باشد.</div>';
                $('#goodResult').append(text);
            }

            if(mainCategory == 0){
                errorCount++;
                text = '<div style="color: red;">حتما یک دسته را به متن ارجاع دهید.</div>';
                $('#errorResult').append(text);
            }
            else{
                text = '<div style="color: green;">متن دارای دسته می باشد.</div>';
                $('#goodResult').append(text);
            }

            var cityError = false;
            if(cityId.length != 0){
                var ccid = 0;
                for(i = 0; i < cityId.length; i++){
                    if(cityId[i] == '')
                        ccid++;
                }

                if(ccid == cityId.length)
                    cityError = true;
            }
            else
                cityError = true;

            if(cityError){
                warningCount++;
                text = '<div style="color: #dec300;">آیا مطمئن هستید متن شما به شهر یا استان خاصی اختصاص ندارد.</div>';
                $('#warningResult').append(text);
            }
            else{
                text = '<div style="color: green;">متن شما دارای دسته بندی جغرافیایی می باشد.</div>';
                $('#goodResult').append(text);
            }

            var inputMainPic = document.getElementById('imgInput');

            if(!(inputMainPic.files && inputMainPic.files[0]) && (post == null || post['pic'] == null)){
                errorCount++;
                text = '<div style="color: red;">مقاله باید حتما دارای عکس اصلی باشد.</div>';
                $('#errorResult').append(text);
            }
            else{
                text = '<div style="color: green;">متن دارای عکس اصلی است.</div>';
                $('#goodResult').append(text);
            }

            if(kind == 1) {
                var release = document.getElementById('releaseType').value;
                if (release != 'draft' && errorCount > 0) {
                    alert('برای ثبت پست باید ارورها رابرطرف کرده و یا انتشار را به حالت پیش نویس دراوردی.')
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
                        storePost();
                }
            }
        }

        function changeSeoTitle(_value){
            var text = _value.length + ' حرف';
            $('#seoTitleNumber').text(text)
            if(_value.length > 60 && _value.length <= 85)
                $('#seoTitleNumber').css('color', 'green')
            else
                $('#seoTitleNumber').css('color', 'red')

        }

        function changeMeta(_value){
            var text = _value.length + ' حرف';
            $('#metaNumber').text(text);
            if(_value.length > 120 && _value.length <= 156)
                $('#metaNumber').css('color', 'green');
            else
                $('#metaNumber').css('color', 'red');

        }

        function deleteCategory(_id, _element){
            $.ajax({
                type: 'post',
                url: '{{route("post.category.delete")}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                success: function(response){
                    if(response == 'hasArticle')
                        alert('دسته بندی را به علت وجود مطلب نمی توانید حذف کنید. ابتدا مطلب مرتب را حذف کنید.')
                    else if(response == 'hasSub')
                        alert('دسته بندی به علت داشتن زیر دسته نمی تواندی حذف کنید.')
                    else if(response == 'ok') {
                        $(_element).parent().parent().remove();
                        alert('دسته بندی حذف شد')
                    }
                },
                err: function(err){

                }
            })
        }
    </script>

@stop