@extends('layouts.structure')

@section('header')
    @parent

    <style>

        #editor1 {
            min-height: 400px;
            max-height: 700px;
            overflow: auto;
            padding: 20px;
        }

        #toolbar-container {
            padding: 20px;
        }

        th, td {
            min-width: 100px;
            text-align: right;
        }

        .col-xs-12 {
            direction: rtl;
            margin-top: 5px;
        }

    </style>

    <script src="{{URL::asset('js/editor.js')}}"></script>
    <script>
        var selectedPlaceId, selectedKindPlaceId;
    </script>
@stop

@section('content')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>افزودن پست جدید</h1>
                </div>
            </div>

            <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                <center class="row">

                    <div class="col-xs-12">

                        <div class="col-xs-12 col-md-4">
                            <label for="category">دسته پست</label>
                            <select id="category">
                                @foreach($categories as $category)
                                    <optgroup label="{{$category["super"]}}">
                                        @foreach($category["childs"] as $itr)
                                            @if(isset($post) && $post->category == $itr["id"])
                                                <option selected value="{{$itr["id"]}}">{{$itr["key"]}}</option>
                                            @else
                                                <option value="{{$itr["id"]}}">{{$itr["key"]}}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <label for="city">پست مربوط به کدام شهر است</label>
                            <input value="{{(isset($post) ? $post->city : '')}}" type="text" onkeyup="citySearch(event)" id="city">
                            <input value="{{(isset($post) ? $post->cityId : '')}}" type="hidden" name="cityId" id="cityId">
                            <div id="cityResult"></div>
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <label for="title">عنوان پست</label>
                            <input value="{{(isset($post) ? $post->title : '')}}" type="text" id="title" maxlength="300">
                        </div>

                    </div>

                    <div class="col-xs-12" style="padding: 30px 0px">

                        <div class="col-xs-12 col-md-4">
                            <label for="backColor">رنگ پشت عنوان پست</label>
                            <input value="{{(isset($post) ? $post->backColor : '')}}" type="color" id="backColor">
                        </div>

                        <div class="col-xs-12 col-md-4">
                            <label for="categoryColor">رنگ عنوان پست</label>
                            <input value="{{(isset($post) ? $post->categoryColor : '')}}" type="color" id="categoryColor">
                        </div>


                        <div class="col-xs-12 col-md-4">
                            <label for="color">رنگ عنوان</label>
                            <input value="{{(isset($post) ? $post->color : '')}}" type="color" id="color">
                        </div>
                    </div>

                    <div class="col-xs-12">

                        <div class="col-xs-4">
                            <div id="cDIV" class="hidden">
                                <label for="C">مختصات C</label>
                                <input value="{{(isset($post) ? $post->C : '')}}" id="C" onkeypress="validateNumber(event)" type="text">
                            </div>
                            <div id="placeDiv">
                                <label>
                                    <span>مکان مورد نظر</span>
                                    <input type="text" value="{{isset($post) ? $placeName : ''}}" onkeyup="search(event)" id="placeName">
                                    @if(isset($post))
                                        <script>
                                            selectedPlaceId = '{{$post->placeId}}';
                                            selectedKindPlaceId = '{{$post->kindPlaceId}}';
                                        </script>
                                    @endif
                                </label>
                                <div id="result"></div>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div id="dDiv" class="hidden">
                                <label for="D">مختصات D</label>
                                <input value="{{(isset($post) ? $post->D : '')}}" id="D" onkeypress="validateNumber(event)" type="text">
                            </div>
                            <div id="kindPlaceDiv">
                                <label>
                                    <span>دسته مورد نظر</span>
                                    <select id="kindPlaceId">
                                        @foreach($places as $place)
                                            @if(isset($post) && $post->kindPlaceId == $place->id)
                                                <option selected value="{{$place->id}}">{{$place->name}}</option>
                                            @else
                                                <option value="{{$place->id}}">{{$place->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <label for="relatedTo">مرتبط با</label>
                            <select onchange="changeRelated(this.value)" id="relatedTo">
                                @if(isset($post) && $post->placeId == null)
                                    <option value="2">مکان جغرافیایی</option>
                                    <option value="1">مکان های تعریف شده</option>
                                @else
                                    <option value="1">مکان های تعریف شده</option>
                                    <option value="2">مکان جغرافیایی</option>
                                @endif
                            </select>
                        </div>
                    </div>


                    <div class="col-xs-12">
                        <div id="toolbar-container"></div>
                        <div id="editor1">
                            @if(isset($post))
                                {!! html_entity_decode($post->description) !!}
                            @endif
                        </div>
                    </div>

                </center>
            </div>

            <center style="padding: 10px">
                <input type="submit" onclick="save()" value="مرحله بعد" class="btn btn-success">
            </center>

        </div>
    </div>

    <div class="col-md-1"></div>

    <script>

        var UploadURL = '{{route('uploadCKEditor')}}';
        var desc = [];

        @if(isset($post))
            desc = '{!! html_entity_decode($post->description) !!}';
        @endif

        var rels = [];

        var currIdx = 0, suggestions = [];
        var searchDir = '{{route('totalSearch')}}';

        function changeRelated(val) {

            if(val == 1) {
                $("#cDIV").addClass('hidden');
                $("#dDiv").addClass('hidden');
                $("#placeDiv").removeClass('hidden');
                $("#kindPlaceDiv").removeClass('hidden');
            }
            else {
                $("#cDIV").removeClass('hidden');
                $("#dDiv").removeClass('hidden');
                $("#placeDiv").addClass('hidden');
                $("#kindPlaceDiv").addClass('hidden');
            }
        }

        extractLinks();

        function redirect(id, name) {
            selectedPlaceId = id;
            selectedKindPlaceId = $("#kindPlaceId").val() ;
            $("#placeName").val(name);
            $("#result").empty();
        }

        function search(e) {

            var val = $("#placeName").val();
            $(".suggest").css("background-color", "transparent").css("padding", "0").css("border-radius", "0");

            if (null == val || "" == val || val.length < 2)
                $("#result").empty();
            else {

                if (13 == e.keyCode && -1 != currIdx) {
                    return redirect(suggestions[currIdx].id, suggestions[currIdx].targetName);
                }

                if (13 == e.keyCode && -1 == currIdx && suggestions.length > 0) {
                    return redirect(suggestions[0].id, suggestions[0].targetName);
                }

                if (40 == e.keyCode) {
                    if (currIdx + 1 < suggestions.length) {
                        currIdx++;
                    }
                    else {
                        currIdx = 0;
                    }

                    if (currIdx >= 0 && currIdx < suggestions.length)
                        $("#suggest_" + currIdx).css("background-color", "#dcdcdc").css("padding", "10px").css("border-radius", "5px");

                    return;
                }
                if (38 == e.keyCode) {
                    if (currIdx - 1 >= 0) {
                        currIdx--;
                    }
                    else
                        currIdx = suggestions.length - 1;

                    if (currIdx >= 0 && currIdx < suggestions.length)
                        $("#suggest_" + currIdx).css("background-color", "#dcdcdc").css("padding", "10px").css("border-radius", "5px");
                    return;
                }

                if ("ا" == val[0]) {

                    for (var val2 = "آ", i = 1; i < val.length; i++) val2 += val[i];

                    $.ajax({
                        type: "post",
                        url: searchDir,
                        data: {
                            kindPlaceId: $('#kindPlaceId').val(),
                            key: val,
                            key2: val2
                        },
                        success: function (response) {

                            var newElement = "";

                            if (response.length == 0) {
                                newElement = "موردی یافت نشد";
                                return;
                            }

                            response = JSON.parse(response);
                            currIdx = -1;
                            suggestions = response;

                            for (i = 0; i < response.length; i++) {
                                if(response[i].cityName !== undefined)
                                    newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"" + response[i].targetName + "\")'>" + response[i].targetName + " در " + response[i].cityName + " در " + response[i].stateName + "</p>";
                                else
                                    newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"" + response[i].targetName + "\")'>" + response[i].targetName + " در " + response[i].stateName + "</p>";
                            }

                            $("#result").empty().append(newElement)
                        }
                    })
                }

                else $.ajax({
                    type: "post",
                    url: searchDir,
                    data: {
                        kindPlaceId: $("#kindPlaceId").val(),
                        key: val
                    },
                    success: function (response) {

                        var newElement = "";

                        if (response.length == 0) {
                            newElement = "موردی یافت نشد";
                            return;
                        }

                        response = JSON.parse(response);
                        currIdx = -1;
                        suggestions = response;

                        for (i = 0; i < response.length; i++) {
                            if(response[i].cityName !== undefined)
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"" + response[i].targetName + "\")'>" + response[i].targetName + " در " + response[i].cityName + " در " + response[i].stateName + "</p>";
                            else
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"" + response[i].targetName + "\")'>" + response[i].targetName + " در " + response[i].stateName + "</p>";
                        }

                        $("#result").empty().append(newElement)
                    }
                })
            }
        }

        function extractLinks() {

            var tmpStr = desc;

            var start = tmpStr.search("<a");
            var link, href, rel;

            while (start != -1) {
                var end = tmpStr.search("</a>");
                link = tmpStr.substr(start, end - start + 4);
                href = link.search("href");

                if(href != -1) {
                    var hrefTmp = "";
                    var i = href + 6;

                    while(link.charAt(i) != '"') {
                        hrefTmp += link.charAt(i);
                        i++;
                    }

                    href = hrefTmp;

                    rel = link.search("rel");

                    if(rel != -1) {

                        hrefTmp = "";
                        i = rel + 5;

                        while (link.charAt(i) != '"') {
                            hrefTmp += link.charAt(i);
                            i++;
                        }

                        rel = hrefTmp;
                    }
                    else {
                        rel = "follow";
                    }

                    rels[href] = rel;
                }

                tmpStr = tmpStr.substr(end + 4);
                start = tmpStr.search("<a");
            }
        }
        
        function save() {

            var mode = $("#relatedTo").val();
            var placeIdOrC, kindPlaceIdOrD;

            if(mode == 1) {
                placeIdOrC = selectedPlaceId;
                kindPlaceIdOrD = selectedKindPlaceId;
            }
            else {
                placeIdOrC = $("#C").val();
                kindPlaceIdOrD = $("#D").val();
            }

            $.ajax({
                type: 'post',
                url: '{{(isset($post)) ? route('doEditPost') : route('doAddPost')}}',
                data: {
                    'description': $("#editor1").html(),
                    'title': $("#title").val(),
                    'color': $("#color").val(),
                    'backColor': $("#backColor").val(),
                    'category': $("#category").val(),
                    'categoryColor': $("#categoryColor").val(),
                    'relatedMode': mode,
                    'placeIdOrC': placeIdOrC,
                    'kindPlaceIdOrD': kindPlaceIdOrD,
                    'cityId': $("#cityId").val(),
                    'id': '{{(isset($post) ? $post->id : '')}}'
                },
                success: function (response) {

                    response = JSON.parse(response);

                    if(response.status == "ok") {
                        document.location.href = response.url;
                    }
                    else {
                        alert(response.msg);
                    }
                }
            });
        }

        function citySearch(e) {

            var cityDir = '{{route('searchForCityAndState')}}';
            var val = $("#city").val();
            $(".suggest").css("background-color", "transparent").css("padding", "0").css("border-radius", "0");

            if (null == val || "" == val || val.length < 2)
                $("#cityResult").empty();
            else {

                if (13 == e.keyCode && -1 != currIdx) {
                    return cityRedirect(suggestions[currIdx].id, suggestions[currIdx].mode);
                }

                if (13 == e.keyCode && -1 == currIdx && suggestions.length > 0) {
                    return redirect(suggestions[0].id, suggestions[0].mode);
                }

                if (40 == e.keyCode) {
                    if (currIdx + 1 < suggestions.length) {
                        currIdx++;
                    }
                    else {
                        currIdx = 0;
                    }

                    if (currIdx >= 0 && currIdx < suggestions.length)
                        $("#suggest_" + currIdx).css("background-color", "#dcdcdc").css("padding", "10px").css("border-radius", "5px");

                    return;
                }
                if (38 == e.keyCode) {
                    if (currIdx - 1 >= 0) {
                        currIdx--;
                    }
                    else
                        currIdx = suggestions.length - 1;

                    if (currIdx >= 0 && currIdx < suggestions.length)
                        $("#suggest_" + currIdx).css("background-color", "#dcdcdc").css("padding", "10px").css("border-radius", "5px");
                    return;
                }

                if ("ا" == val[0]) {

                    for (var val2 = "آ", i = 1; i < val.length; i++) val2 += val[i];

                    $.ajax({
                        type: "post",
                        url: cityDir,
                        data: {
                            key: val
                        },
                        success: function (response) {

                            var newElement = "";

                            if (response.length == 0) {
                                newElement = "موردی یافت نشد";
                                return;
                            }

                            response = JSON.parse(response);

                            currIdx = -1;
                            suggestions = response;

                            for (i = 0; i < response.length; i++) {

                                if (response[i].mode == 'city') {
                                    var name = response[i].cityName + " در " + response[i].stateName ;
                                    newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='cityRedirect(\"" + name + "\", " + response[i].id + ")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                                }

                            }

                            $("#cityResult").empty().append(newElement)
                        }
                    })
                }
                else $.ajax({
                    type: "post",
                    url: cityDir,
                    data: {
                        key: val
                    },
                    success: function (response) {

                        var newElement = "";

                        if (response.length == 0) {
                            newElement = "موردی یافت نشد";
                            return;
                        }

                        response = JSON.parse(response);
                        currIdx = -1;
                        suggestions = response;

                        for (i = 0; i < response.length; i++) {
                            if (response[i].mode == 'city') {
                                var name = response[i].cityName + " در " + response[i].stateName ;
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='cityRedirect(\"" + name + "\", " + response[i].id + ")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                            }
                        }

                        $("#cityResult").empty().append(newElement)
                    }
                })
            }
        }
        function cityRedirect(_name, _id){
            document.getElementById('city').value = _name;
            document.getElementById('cityId').value = _id;

            $("#cityResult").empty();
        }


    </script>

    <script src="{{URL::asset('js/ckeditor.js')}}"></script>

@stop