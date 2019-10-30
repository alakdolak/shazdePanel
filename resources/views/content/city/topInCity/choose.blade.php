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
                    <h1> ویرایش برترین ها در شهر</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    <div class="col-xs-12" id="cityMode">
                        <label for="placeName">شهر مورد نظر</label>
                        <input type="text" onkeyup="search(event)" id="placeName">
                        <div id="result"></div>
                    </div>

                    {{--<div class="col-xs-12 hidden" id="stateMode">--}}
                    {{--<label for="placeNameInStateMode">استان مورد نظر</label>--}}
                    {{--<input type="text" onkeyup="searchInStateMode(event)" id="placeNameInStateMode">--}}
                    {{--<div id="resultInStateMode"></div>--}}
                    {{--</div>--}}

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
        var searchDir = '{{route('searchForCityAndState')}}';

        function redirect(id, cityMode) {
            document.location.href = "{{url('topInCity')}}/" + id;
        }

        function search(e) {

            var val = $("#placeName").val();
            $(".suggest").css("background-color", "transparent").css("padding", "0").css("border-radius", "0");

            if (null == val || "" == val || val.length < 2)
                $("#result").empty();
            else {

                if (13 == e.keyCode && -1 != currIdx) {
                    return redirect(suggestions[currIdx].id, suggestions[currIdx].mode);
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

                            for (i = 0; i < response.length; i++) {

                                if(response[i].mode == 'city')
                                    newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"city\")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                                // else
                                //     newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"state\")'>" + " استان " + response[i].name + "</p>";
                            }

                            $("#result").empty().append(newElement)
                        }
                    })
                }
                else $.ajax({
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

                        for (i = 0; i < response.length; i++) {
                            if(response[i].mode == 'city')
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"city\")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                            // else
                            //     newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"state\")'>" + " استان " + response[i].name + "</p>";
                        }

                        $("#result").empty().append(newElement)
                    }
                })
            }
        }

        function searchInStateMode(e) {

            var val = $("#placeNameInStateMode").val();
            $(".suggest").css("background-color", "transparent").css("padding", "0").css("border-radius", "0");

            if (null == val || "" == val || val.length < 2)
                $("#resultInStateMode").empty();
            else {

                if (13 == e.keyCode && -1 != currIdx) {
                    return redirect(suggestions[currIdx].id, "state");
                }

                if (13 == e.keyCode && -1 == currIdx && suggestions.length > 0) {
                    return redirect(suggestions[0].id, "state");
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
                        url: '{{route('searchForState')}}',
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
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"state\")'>" + " استان " + response[i].name + "</p>";

                            $("#resultInStateMode").empty().append(newElement)
                        }
                    })
                }

                else $.ajax({
                    type: "post",
                    url: '{{route('searchForState')}}',
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
                            newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='redirect(" + response[i].id + ", \"state\")'>" + " استان " + response[i].name + "</p>";
                        }

                        $("#resultInStateMode").empty().append(newElement)
                    }
                })
            }
        }

    </script>

@stop