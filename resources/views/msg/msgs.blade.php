@extends('layouts.structure')

@section('header')
    @parent

    <style>

        .col-xs-12 {
            margin: 5px;
        }

        button {
            min-width: 100px;
        }

        td {
            padding: 10px;
            max-width: 300px;
            border: 2px solid black;
        }

        .row {
            direction: rtl;
        }

    </style>

    <script src= {{URL::asset("js/calendar.js") }}></script>
    <script src= {{URL::asset("js/calendar-setup.js") }}></script>
    <script src= {{URL::asset("js/calendar-fa.js") }}></script>
    <script src= {{URL::asset("js/jalali.js") }}></script>
    <link rel="stylesheet" href="{{URL::asset('css/standalone.css')}}">
    <link rel="stylesheet" href= {{URL::asset("css/calendar-green.css") }}>

@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>آخرین ارسال ها</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row">

                    <div class="col-xs-12">

                        <center class="col-xs-6">
                            <input type="button"
                                   style="border: none; width: 15px;  height: 15px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;"
                                   id="date_btn_end">
                            <span>تا تاریخ</span>
                            <input type="text" style="max-width: 200px" class="form-detail"
                                   id="date_input_end" onchange="end()" readonly>

                            <script>
                                Calendar.setup({
                                    inputField: "date_input_end",
                                    button: "date_btn_end",
                                    ifFormat: "%Y/%m/%d",
                                    dateType: "jalali"
                                });
                            </script>
                        </center>

                        <center class="col-xs-6">

                            <input type="button"
                                   style="border: none;  width: 15px; height: 15px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;"
                                   id="date_btn_Start">

                            <span style="direction: rtl">از تاریخ</span>

                            <input type="text" style="max-width: 200px" class="form-detail"
                                   id="date_input_start" onchange="start()" readonly>

                            <script>
                                Calendar.setup({
                                    inputField: "date_input_start",
                                    button: "date_btn_Start",
                                    ifFormat: "%Y/%m/%d",
                                    dateType: "jalali"
                                });
                            </script>
                        </center>

                    </div>

                    <?php $i = 0; $dates = [] ?>

                    @if(count($logs) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">پیامی وجود ندارد</h4>
                        </div>
                    @else

                        <div class="col-xs-12" style="padding: 20px">

                            <table>
                                <tr>
                                    <td>ارسال کننده</td>
                                    <td>از طریق</td>
                                    <td>به</td>
                                    <td>پیام داده شده</td>
                                    <td>تاریخ ایجاد</td>
                                </tr>

                                @foreach($logs as $log)

                                    <?php $dates[$i] = $log->date; ?>

                                    <tr id="{{$i}}">
                                        <td>{{$log->username}}</td>
                                        <td>{{$log->mode}}</td>
                                        <td>{{$log->additional1}}</td>
                                        <td>{{$log->comment}}</td>
                                        <td>{{$log->date}}</td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach

                            </table>

                            {{ $logs->links() }}
                        </div>

                    @endif
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-1"></div>

    <script>

        var exams = {!! json_encode($dates) !!};

        var start_time;
        var examsStartValue = [];
        var examsEndValue = [];
        var stateValue = [];
        var end_time;


        for (var j = 0; j < exams.length; j++) {
            examsEndValue[j] = 1;
            examsStartValue[j] = 1;
            stateValue[j] = 1;
        }

        function start() {
            start_time = document.getElementById('date_input_start').value;
            start_time = start_time.split('/');
            changeStartTime()
        }

        function end() {
            end_time = document.getElementById('date_input_end').value;
            end_time = end_time.split('/');
            changeEndTime();
        }

        function changeStartTime() {

            var day, month, year;

            for (var i = 0; i < exams.length; i++) {

                day = parseInt((exams[i].split('/')[2]));
                month = parseInt((exams[i].split('/')[1]));
                year = parseInt((exams[i].split('/')[0]));

                if (year == start_time[0]) {
                    if (month == start_time[1]) {
                        if (day >= start_time[2]) {
                            examsStartValue[i] = 1;
                        }
                        else {
                            examsStartValue[i] = 0;
                        }
                    }
                    else if (month > start_time[1]) {
                        examsStartValue[i] = 1;
                    }
                    else {
                        examsStartValue[i] = 0;
                    }
                }
                else if (year > start_time[0]) {
                    examsStartValue[i] = 1;
                }
                else {
                    examsStartValue[i] = 0;
                }
            }
            doChange();
        }

        function doChange() {

            for (var i = 0; i < exams.length; i++) {
                if (examsStartValue[i] + examsEndValue[i] + stateValue[i] == 3) {
                    document.getElementById(i).style.display = '';
                }
                else {
                    document.getElementById(i).style.display = 'none';
                }
            }
        }

        function changeEndTime() {

            var day, month, year;

            for (var i = 0; i < exams.length; i++) {

                day = parseInt((exams[i].split('/')[2]));
                month = parseInt((exams[i].split('/')[1]));
                year = parseInt((exams[i].split('/')[0]));

                if (year == end_time[0]) {
                    if (month == end_time[1]) {
                        if (day <= end_time[2]) {
                            examsEndValue[i] = 1;
                        }
                        else {
                            examsEndValue[i] = 0;
                        }
                    }
                    else if (month < end_time[1]) {
                        examsEndValue[i] = 1;
                    }
                    else {
                        examsEndValue[i] = 0;
                    }
                }
                else if (year < end_time[0]) {
                    examsEndValue[i] = 1;
                }
                else {
                    examsEndValue[i] = 0;
                }
            }
            doChange();
        }

    </script>
@stop