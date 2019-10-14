@extends('layouts.structure')

@section('header')
    @parent

    <script src = {{URL::asset("js/calendar.js") }}></script>
    <script src = {{URL::asset("js/calendar-setup.js") }}></script>
    <script src = {{URL::asset("js/calendar-fa.js") }}></script>
    <script src = {{URL::asset("js/jalali.js") }}></script>
    <link rel="stylesheet" href="{{URL::asset('css/standalone.css')}}">
    <link rel="stylesheet" href = {{URL::asset("css/calendar-green.css") }}>

    <style>
        select {
            color: black;
        }

        .totalPane {
            direction: rtl;
        }
        .col-xs-2 {
            float: right;
        }
        .col-xs-4 {
            float: right;
        }

        .col-xs-12 {
            margin-top: 20px;
        }

    </style>

@stop

@section('content')


    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت کد تخفیف</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    <div class="totalPane">

                        <div class="col-xs-12">
                            <div class="col-xs-3">
                                <span>نوع تخفیف</span>

                                <label for="static">مقداری</label>
                                <input type="radio" value="1" name="offerKind" id="static">

                                <label for="dynamic">درصدی</label>
                                <input type="radio" value="2" name="offerKind" id="dynamic">
                            </div>

                            <div class="col-xs-3">
                                <label for="amount">مقدار تخفیف</label>
                                <input type="text" id="amount">
                            </div>

                            <div class="col-xs-2">
                                <select id="sexSelect" onchange="changeSex(this.value)">
                                    <option id="selectSex" value="-1">جنسیت</option>
                                    <option value="0">خانم</option>
                                    <option value="1">آقا</option>
                                </select>
                            </div>

                            <div class="col-xs-4">
                                <label for="point">امتیاز</label>
                                <input id="point" type="text">
                                <button class="btn btn-success" onclick="changePoint()">اعمال</button>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="col-xs-4">
                                <select id="tripStyleSelect" onchange="changeTripStyle(this.value)" style="text-align-last: center; direction: rtl !important;" dir="auto">
                                    <option value="-1">نوع سفر</option>
                                    @foreach($tripStyles as $tripStyle)
                                        <option value="{{$tripStyle->id}}">{{$tripStyle->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-4">
                                <select id="ageSelect" onchange="changeAge(this.value)">
                                    <option value="-1">سن</option>
                                    @foreach($ages as $age)
                                        <option value="{{$age->id}}">{{$age->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-xs-4" id="cityMode">
                                <label for="placeName">شهر/استان مورد نظر</label>
                                <input type="text" onkeyup="search(event)" id="stateOrCity">
                                <div id="result"></div>
                            </div>

                        </div>

                        <div class="col-xs-12">

                            <div class="col-xs-2">
                                <span>تاریخ تولد</span>
                            </div>

                            <div class="col-xs-4">
                                <span>از</span>
                                <label>
                                    <input class="glyphicon glyphicon-plus" type="button" style="border: none; width: 30px; height: 30px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;" id="date_btn">
                                </label>
                                <input type="text" style="margin-top: -5px; max-width: 200px" class="form-detail" id="date_input" readonly>
                                <script>
                                    Calendar.setup({
                                        inputField: "date_input",
                                        button: "date_btn",
                                        ifFormat: "%Y/%m/%d",
                                        dateType: "jalali"
                                    });
                                </script>
                            </div>

                            <div class="col-xs-4">
                                <span>تا</span>
                                <label>
                                    <input class="glyphicon glyphicon-plus" type="button" style="border: none; width: 30px; height: 30px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;" id="date_btn_end">
                                </label>
                                <input type="text" style="margin-top: -5px; max-width: 200px" class="form-detail" id="date_input_end" readonly>
                                <script>
                                    Calendar.setup({
                                        inputField: "date_input_end",
                                        button: "date_btn_end",
                                        ifFormat: "%Y/%m/%d",
                                        dateType: "jalali"
                                    });
                                </script>
                            </div>

                            <div class="col-xs-2">
                                <button class="btn btn-success" onclick="changeBirthDay()">اعمال تاریخ</button>
                            </div>

                        </div>

                        <div class="col-xs-12">
                            <div class="col-xs-3">
                                <button onclick="addAnd()">و</button>
                            </div>

                            <div class="col-xs-3">
                                <button onclick="addOr()">یا</button>
                            </div>

                            <div class="col-xs-3">
                                <button onclick="closeP()">(</button>
                            </div>

                            <div class="col-xs-3">
                                <button onclick="openP()">)</button>
                            </div>

                        </div>

                        <div class="col-xs-12">
                            <span>تاریخ انقضا</span>
                            <label>
                                <input class="glyphicon glyphicon-plus" type="button" style="border: none; width: 30px; height: 30px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;" id="date_btn_expire">
                            </label>
                            <input type="text" style="margin-top: -5px; max-width: 200px" class="form-detail" id="date_input_expire" readonly>
                            <script>
                                Calendar.setup({
                                    inputField: "date_input_expire",
                                    button: "date_btn_expire",
                                    ifFormat: "%Y/%m/%d",
                                    dateType: "jalali"
                                });
                            </script>
                        </div>

                        <div class="col-xs-12">
                            <div id="query" style="min-width: 400px; height: 100px; overflow-y: auto; border-radius: 7px; padding: 7px; border: 3px solid black">
                            </div>
                        </div>

                        <center class="col-xs-12">

                            <div class="col-xs-4">
                                <label for="mail">ارسال به کاربر از طریق ایمیل</label>
                                <input type="checkbox" id="mail">
                            </div>

                            <div class="col-xs-4">
                                <label for="phone">ارسال به کاربر از طریق شماره همراه</label>
                                <input type="checkbox" id="phone">
                            </div>

                            <div class="col-xs-4">
                                <label for="messageBox">ارسال به کاربر از طریق صندوق داخلی</label>
                                <input type="checkbox" value="" id="messageBox">
                            </div>

                            <button onclick="sendQuery()" class="btn btn-primary">تایید</button>
                            <p style="color: black" id="msg"></p>
                        </center>

                    </div>
                </center>
            </div>
        </div>
    </div>

    <script>

        var whereClause = "";
        var fromClause = "users";
        var selectClause;
        var query;
        var open = 0;
        var andOr = true;
        var fromAbout = false;
        var fromTripStyle = false;
        var fromState = false;
        var lastSexIdx = -1;
        var lastTripStyleIdx = -1;
        var lastAgeIdx = -1;

        $(document).ready(function(){
            updateQuery();
        });

        function createFromClause() {
            fromClause = "users";

            if(fromAbout)
                fromClause += ", aboutMe";

            if(fromTripStyle)
                fromClause += ", tripStyle";

            if(fromState)
                fromClause += ", cities";
        }

        function changeSex(val) {

            if(val == -1)
                return;

            $("#selectSex").addClass('hidden');

            if(andOr) {

                if(!fromAbout) {
                    fromAbout = true;
                    whereClause = "users.id = aboutMe.uId and " + whereClause;
                    createFromClause();
                }

                lastSexIdx = val;
                whereClause += "sex = " + val + " ";
                andOr = false;
                updateQuery();
            }
            else {
                alert("باید در این مرحله از و/یا استفاده کنید");
                $("#sexSelect").val(lastSexIdx);
            }
        }

        function changeTripStyle(val) {

            if(val == -1)
                return;

            if(andOr) {

                if(!fromTripStyle) {
                    fromTripStyle = true;
                    whereClause = "users.id = tripStyle.uId and " + whereClause;
                    createFromClause();
                }

                lastTripStyleIdx = val;
                whereClause += "tripStyleId = " + val + " ";
                andOr = false;
                updateQuery();
            }
            else {
                alert("باید در این مرحله از و/یا استفاده کنید");
                $("#tripStyleSelect").val(lastTripStyleIdx);
            }
        }

        function changeBirthDay() {

            if(andOr) {
                var start = $("#date_input").val();
                var end = $("#date_input_end").val();

                if (start.length == 0 || end.length == 0) {
                    alert("لطفا هر دو تاریخ از و تا را مشخص نمایید");
                    return;
                }

                var tmp = start.split("/");
                start = tmp[0] + tmp[1] + tmp[2];

                tmp = end.split("/");
                end = tmp[0] + tmp[1] + tmp[2];

                if(start >= end) {
                    alert("تاریخ شروع باید کوچک تر مساوی تاریخ پایان باشد");
                    return;
                }

                whereClause += "(birthDay >= " + start + " and birthDay <= " + end + ") ";
                andOr = false;
                updateQuery();
            }
            else
                alert("باید در این مرحله از و/یا استفاده کنید");
        }

        function changeAge(val) {

            if(val == -1)
                return;

            if(andOr) {

                if(!fromTripStyle) {
                    fromAbout = true;
                    whereClause = "users.id = aboutMe.uId and " + whereClause;
                    createFromClause();
                }

                lastAgeIdx = val;
                whereClause += "ageId = " + val + " ";
                andOr = false;
                updateQuery();
            }
            else {
                $("#ageSelect").val(lastAgeIdx);
                alert("باید در این مرحله از و/یا استفاده کنید");
            }
        }

        function changePoint() {

            var val = $("#point").val();

            if(andOr) {
                whereClause += "(SELECT SUM(activity.rate) as total FROM log, activity WHERE confirm = 1 and log.visitorId = users.id and log.activityId = activity.id) > " + val + " ";
                andOr = false;
                updateQuery();
            }
            else
                alert("باید در این مرحله از و/یا استفاده کنید");
        }

        function addAnd() {
            if(!andOr) {
                whereClause += "and ";
                andOr = true;
                updateQuery();
            }
            else
                alert("باید در این مرحله از clause ها استفاده کنید");
        }

        function addOr() {
            if(!andOr) {
                whereClause += "or ";
                andOr = true;
                updateQuery();
            }
            else
                alert("باید در این مرحله از clause ها استفاده کنید");
        }

        function openP() {
            if(andOr) {
                open++;
                whereClause += "( ";
                updateQuery();
            }
            else
                alert("باید در این مرحله از و/یا استفاده کنید");
        }

        function closeP() {

            if(!andOr) {
                if (open == 0) {
                    alert("ابتدا باید پرانتزی باز کنید");
                    return;
                }
                open--;
                whereClause += ") ";
                updateQuery();
            }
            else
                alert("باید در این مرحله از clause ها استفاده کنید");
        }

        function updateQuery() {
            selectClause = "select users.* from " + fromClause + " where ";
            query = selectClause + whereClause;

            var elem = "<p style='direction: ltr; color: black'>" + query + "</p>";
            $("#query").empty().append(elem);
        }

        function sendQuery() {

            if(andOr && whereClause.length != 0) {
                alert("باید در این مرحله از clause ها استفاده کنید");
                return;
            }

            if(open > 0) {
                alert("لطفا همه ی پرانتز های باز را ببندید");
                return;
            }

            var amount = $("#amount").val();
            if(amount.length == 0 || amount == 0) {
                alert("لطفا مقدار تخفیف را مشخص فرمایید");
                return;
            }

            var offerKind = $("input[name='offerKind']:checked").val();
            if(offerKind == null || offerKind.length == 0 || offerKind == "undefined") {
                alert("لطفا نوع تخفیف خود را مشخص فرمایید");
                return;
            }

            if(whereClause.length == 0) {
                whereClause = " 1 = 1";
                andOr = false;
                updateQuery();
            }

            $("#msg").empty().append("در حال انجام عملیات");


            var expireTime = $("#date_input_expire").val();
            if(expireTime.length == 0)
                expireTime = "none";

            $.ajax({
                type: 'post',
                url: '{{route('doCreateOffer')}}',
                data: {
                    'query': query,
                    'offerKind': offerKind,
                    'amount': amount,
                    'expireTime': expireTime,
                    'sendByMail': $("#mail").prop('checked') ? '1' : '0',
                    'sendByPhone': $("#phone").prop('checked') ? '1' : '0',
                    'sendByMessageBox': $("#messageBox").prop('checked') ? '1' : '0'
                },
                success: function (response) {
                    if(response == "ok")
                        document.location.href = '{{route('offers')}}';
                    else {
                        alert('متن وارد شده نامعتبر است');
                        $("#msg").empty();
                    }
                }
            });

        }

        function search(e) {

            var val = $("#stateOrCity").val();
            $(".suggest").css("background-color", "transparent").css("padding", "0").css("border-radius", "0");

            if (null == val || "" == val || val.length < 2)
                $("#result").empty();
            else {

                if (13 == e.keyCode && -1 != currIdx) {
                    return changeCity(suggestions[currIdx].id, suggestions[currIdx].mode);
                }

                if (13 == e.keyCode && -1 == currIdx && suggestions.length > 0) {
                    return changeCity(suggestions[0].id, suggestions[0].mode);
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
                        url: '{{route('searchForCityAndState')}}',
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
                                    newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='changeCity(" + response[i].id + ", \"city\")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                                else
                                    newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='changeCity(" + response[i].id + ", \"state\")'>" + " استان " + response[i].name + "</p>";
                            }

                            $("#result").empty().append(newElement)
                        }
                    })
                }

                else $.ajax({
                    type: "post",
                    url: '{{route('searchForCityAndState')}}',
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
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='changeCity(" + response[i].id + ", \"city\")'>" + response[i].cityName + " در " + response[i].stateName + "</p>";
                            else
                                newElement += "<p style='cursor: pointer' class='suggest' id='suggest_" + i + "' onclick='changeCity(" + response[i].id + ", \"state\")'>" + " استان " + response[i].name + "</p>";
                        }

                        $("#result").empty().append(newElement)
                    }
                })
            }
        }

        function changeCity(val, mode) {

            $("#result").empty();

            if(val == -1)
                return;

            if(andOr) {
                if(mode == "city") {
                    whereClause += "cityId = " + val + " ";
                }
                else {

                    if(!fromState) {
                        fromState = true;
                        whereClause = "users.cityId = cities.id and " + whereClause;
                        createFromClause();
                    }

                    whereClause += "cities.stateId = " + val + " ";
                }

                andOr = false;
                updateQuery();
            }
            else {
                alert("باید در این مرحله از و/یا استفاده کنید");
            }
        }

    </script>

@stop