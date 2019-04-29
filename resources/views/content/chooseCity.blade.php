@extends('layouts.structure')

@section('header')
    @parent
    <style>
        .col-xs-12 {
            margin-top: 10px;
        }

        button {
            margin-right: 10px;
        }

        .row {
            direction: rtl;
        }

        label {
            min-width: 150px;
        }

        input, select {
            width: 200px;
        }

        #result {
            max-height: 300px;
            overflow: auto;
            margin-top: 20px;
        }
    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>ویرایش محتوا</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    @if($mode == 2)
                        <div class="col-xs-12">
                            <label for="kindPlaceId">نوع مکان</label>
                            <select id="kindPlaceId">
                                @foreach($places as $place)
                                    <option value="{{$place->id}}">{{$place->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-xs-12">
                        <label for="placeName">شهر مورد نظر</label>
                        <input type="text" onkeyup="search(event)" id="placeName">
                        <div id="result"></div>
                    </div>

                    <div class="col-xs-12">
                        <input type="submit" value="تایید" class="btn btn-primary" name="saveChange">
                    </div>
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        var currIdx = 0, suggestions = [];
        var searchDir = '{{route('searchForCity')}}';
        var mode = parseInt('{{$mode}}');

        function redirect(id) {
            if(mode == 1)
                document.location.href = '{{$url}}' + id;
            else
                document.location.href = '{{$url}}' + id + "/" + $("#kindPlaceId").val();
        }

        function search(e) {

            var val = $("#placeName").val();
            $(".suggest").css("background-color", "transparent").css("padding", "0").css("border-radius", "0");

            if (null == val || "" == val || val.length < 2)
                $("#result").empty();
            else {

                if (13 == e.keyCode && -1 != currIdx) {
                    return redirect(suggestions[currIdx].id);
                }

                if (13 == e.keyCode && -1 == currIdx && suggestions.length > 0) {
                    return redirect(suggestions[0].id);
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

                            for (i = 0; i < response.length; i++)
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";

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
                            newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                        }

                        $("#result").empty().append(newElement)
                    }
                })
            }
        }

    </script>

@stop