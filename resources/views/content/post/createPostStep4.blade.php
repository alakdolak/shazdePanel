@extends('layouts.structure')

@section('header')
    @parent

    <style>

        .clockpicker-button {
            font-family: IRANSans !important;
        }

    </style>

    <script src = {{URL::asset("js/calendar.js") }}></script>
    <script src = {{URL::asset("js/calendar-setup.js") }}></script>
    <script src = {{URL::asset("js/calendar-fa.js") }}></script>
    <script src = {{URL::asset("js/jalali.js") }}></script>
    <script src="{{URL::asset('js/jquery.timepicker.min.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('css/clockpicker.css')}}">
    <script src="{{URL::asset('js/clockpicker.js')}}"></script>
    <link rel="stylesheet" href="{{URL::asset('css/standalone.css')}}">
    <link rel="stylesheet" href = {{URL::asset("css/calendar-green.css") }}>

    <link rel="stylesheet" type="text/css" href="{{URL::asset('dist/bootstrap-clockpicker.min.css')}}">
    <script type="text/javascript" src="{{URL::asset('dist/bootstrap-clockpicker.min.js')}}"></script>

    <style>
        .clockpicker-popover {
            z-index: 100000;
            direction: ltr;
        }

    </style>

@stop

@section('content')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>افزودن پست جدید</h1>
                </div>

                <form method="post" action="{{route('setPostInterval', ['id' => $id])}}" enctype="multipart/form-data">

                    {{csrf_field()}}


                    <center class="row">

                        <h3>زمان نمایش پست خود را مشخص نمایید</h3>

                        <div class="col-xs-12">

                            <center class="col-xs-12" style="margin-top: 10px">

                                <div>
                                    <input type="button"
                                           style="border: none;  width: 15px; height: 15px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;"
                                           id="date_btn_Start">

                                    <span style="direction: rtl">از تاریخ</span>
                                </div>

                                <input type="text" style="width: 200px" class="form-detail"
                                       name="date" id="date_input_start" readonly>

                                <script>
                                    Calendar.setup({
                                        inputField: "date_input_start",
                                        button: "date_btn_Start",
                                        ifFormat: "%Y/%m/%d",
                                        dateType: "jalali"
                                    });
                                </script>
                            </center>

                            <center class="col-xs-12" style="margin-top: 10px">

                                <div id="timepicker1" style="width: 200px" class="clockpicker">

                                    <label style="display: inline-block">
                                        <span>ساعت شروع</span>
                                    </label>
                                    <div class="clockpicker">
                                        <input type="text" name="time" autocomplete="off" id="sTime" style="direction: ltr" class="form-detail form-control"
                                               value="09:30">
                                    </div>
                                </div>
                            </center>

                        </div>

                    </center>

                <center style="padding: 10px">
                    <input type="submit" value="نهایی سازی پست" class="btn btn-success">
                </center>

                </form>

            </div>

        </div>
    </div>

    <div class="col-md-1"></div>

    <script type="text/javascript">


        $('input.timepicker').timepicker({
            timeFormat: 'hh:mm:ss',
            interval: 5,
            minTime: '00:00',
            maxTime: '11:55',
            defaultTime: '07:00',
            startTime: '00:00',
            dynamic: true,
            dropdown: true,
            scrollbar: true
        });

        $('.clockpicker').clockpicker();

    </script>


@stop