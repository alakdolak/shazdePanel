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
    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>پیشنهاد های ویژه</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    @for($i = 1; $i < 5; $i++)
                        <div class="col-xs-12">
                            @if($i == 1)
                                <input id="advice_{{$i}}" checked type="radio" name="advice" value="{{$i}}">
                            @else
                                <input id="advice_{{$i}}" type="radio" name="advice" value="{{$i}}">
                            @endif
                            <label for="advice_{{$i}}">پیشنهاد {{$i}}</label>
                        </div>
                    @endfor

                    <div class="col-xs-12">
                        @foreach($kindPlaceIds as $kindPlaceId)
                            @if($kindPlaceId->name == "هتل" || $kindPlaceId->name == "رستوران" ||
                                $kindPlaceId->name == "اماکن")
                                <div class="col-xs-12">
                                    @if($kindPlaceId->name == "هتل")
                                        <input onchange="search()" checked id="kindPlaceId_{{$kindPlaceId->id}}" type="radio" name="kindPlaceId" value="{{$kindPlaceId->id}}">
                                    @else
                                        <input onchange="search()" id="kindPlaceId_{{$kindPlaceId->id}}" type="radio" name="kindPlaceId" value="{{$kindPlaceId->id}}">
                                    @endif
                                    <label for="kindPlaceId_{{$kindPlaceId->id}}">{{$kindPlaceId->name}}</label>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-xs-12">
                        <label for="placeName">مکان مورد نظر</label>
                        <input type="text" onkeyup="search()" id="placeName" maxlength="40">
                        <input type="hidden" id="placeId">
                    </div>

                    <div class="col-xs-12">
                        <input type="submit" class="btn btn-primary" value="تایید" onclick="submitAdvice()">
                        <p style="padding: 10px; color: #963019" id="msg"></p>
                    </div>

                    <div class="col-xs-12" style="min-height: 400px" id="result"></div>

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        var searchDir = '{{route('findPlace')}}';
        var submitAdviceDir = '{{route('submitAdvice')}}';

        function submitAdvice() {

            if($("#placeId").val() === "")
                return;

            $.ajax({
                type: 'post',
                url: submitAdviceDir,
                data: {
                    'placeId': $("#placeId").val(),
                    'kindPlaceId': $("input[name='kindPlaceId']:checked").val(),
                    'mode': $("input[name='advice']:checked").val()
                },
                success: function (response) {
                    if(response === "ok")
                        $("#msg").empty().append('عملیات مورد نظر به درستی انجام پذیرفت');
                    else
                        $("#msg").empty().append('خطایی در انجام عملیات مورد نظر رخ داده است');
                }
            });
        }

        function search() {

            val = $("#placeName").val();

            if(val == null || val === "" || val.length < 3) {
                return;
            }

            $.ajax({
                type: 'post',
                url: searchDir,
                data: {
                    'kindPlaceId': $("input[name='kindPlaceId']:checked").val(),
                    'key': val
                },
                success: function (response) {

                    response = JSON.parse(response);
                    $("#result").empty();
                    var newElement = "";

                    if(response.length === 0) {
                        $("#placeName").val("");
                        $("#placeId").val("");
                        newElement = 'موردی یافت نشد';
                    }

                    else {
                        for(i = 0; i < response.length; i++) {
                            newElement += "<p style='cursor: pointer' onclick='setInput(\"" + response[i].name + "\", \"" + response[i].id + "\")'>" + response[i].name + "</p>";
                        }
                    }

                    $("#result").append(newElement);
                }
            });
        }

        function setInput(text, val) {
            $("#placeName").val(text);
            $("#placeId").val(val);
            $("#result").empty();
        }

    </script>
@stop
