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

    <script>
        var selfUrl = '{{route('lastActivities')}}';
        var deleteLogsDir = '{{route('deleteLogs')}}';
    </script>

@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>آخرین مطالب کاربران</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row">

                    <div class="col-xs-12">

                        <center class="col-xs-4">
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

                        <center class="col-xs-4">

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

                        <center class="col-xs-4">
                            <label for="confirm">وضعیت تایید</label>
                            <select onchange="redirect(this.value)" id="confirm">
                                @if($confirm)
                                    <option value="1">تایید شده ها</option>
                                    <option value="0">تایید نشده ها</option>
                                @else
                                    <option value="0">تایید نشده ها</option>
                                    <option value="1">تایید شده ها</option>
                                @endif
                            </select>
                        </center>

                    </div>

                    <?php $i = 0; $dates = [] ?>

                    @if(count($logs) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">فعالیتی وجود ندارد</h4>
                        </div>
                    @else

                        <div class="col-xs-12" style="padding: 20px">

                            <table>
                                <tr>
                                    <td>نام کاربری عامل</td>
                                    <td>نوع مکان</td>
                                    <td>نام مکان</td>
                                    <td>تاریخ بارگذاری</td>
                                    <td>توضیحات محتوا</td>
                                    <td>
                                        <p>وضعیت تایید</p>
                                        <p>
                                            <span>انتخاب همه</span><input type="checkbox" data-val="select" onclick="checkAll(this)">
                                        </p>
                                    </td>
                                </tr>

                                @foreach($logs as $log)

                                    <?php $dates[$i] = $log->date; ?>

                                    <tr id="{{$i}}">
                                        <td>{{$log->visitorId}}</td>
                                        <td>{{$log->kindPlaceName}}</td>
                                        <td>{{$log->name}}</td>
                                        <td>{{$log->date}}</td>
                                        <td>
                                            @if($activityName != "عکس")
                                                <p>
                                                    <span>محتوا: </span>
                                                    <span style="cursor: pointer" onclick="createAjaxModal('{{route('changeUserContent')}}', [{'name': 'logId', 'class': ['hidden'], 'type': 'text', 'label': 'نام سطح', 'value': '{{$log->id}}'}, {'name': 'content', 'class': [], 'type': 'textarea', 'label': 'مقدار جدید', 'value': '{{ json_encode($log->text) }}'}], 'افزودن سطح جدید', '')" data-toggle="modal" data-target="#InformationproModalalertAjax" class="glyphicon glyphicon-edit"></span>
                                                    <span>{{$log->text}}</span>
                                                </p>
                                            @else
                                                <span><img width="100px" height="100px" src="{{$log->userPic}}"></span>
                                            @endif

                                            @if($activityName == "پاسخ")
                                                <p><span style="color: darkred; font-weight: bolder; font-size: 14px">سوال مربوطه: </span><span>{{$log->relatedTo}}</span></p>
                                            @elseif($activityName == "نظر")
                                                <p><span style="color: darkred; font-weight: bolder; font-size: 14px">نوع سفر: </span><span>{{$log->comment->placeStyleId}}</span></p>
                                                <p><span style="color: darkred; font-weight: bolder; font-size: 14px">فصل سفر: </span><span>{{$log->comment->seasonTrip}}</span></p>
                                                <p><span style="color: darkred; font-weight: bolder; font-size: 14px">مبدا سفر: </span><span>{{$log->comment->src}}</span></p>
                                                @if($log->pic != "")
                                                    <img src="{{URL::asset('userPhoto/comments/' . $log->kindPlaceId . '/' . $log->pic)}}" width="200px" height="200px">
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <input value="{{$log->id}}" type="checkbox" name="checkedLogs[]">
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach

                            </table>

                            {{ $logs->links() }}
                        </div>

                        <button onclick="submitLogs()" class="btn btn-success">{{(!$confirm) ? 'تایید' : 'رد'}}</button>
                        <button onclick="deleteLogs()" class="btn btn-danger">حذف</button>
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

        var confirm = '{{$confirm}}';

        function redirect(val) {
            document.location.href = '{{route('controlActivityContent', ['activityId' => $activityId])}}' + '/' + val;
        }

        function checkAll(val) {

            if($(val).attr('data-val') == 'select') {
                $(":checkbox[name='checkedLogs[]']").prop('checked', true);
                $(val).attr('data-val', 'unSelect');
            }
            else {
                $("input[name='checkedLogs[]']").prop('checked', false);
                $(val).attr('data-val', 'select');
            }

        }
        
        function submitLogs() {

            var checkedValues = $("input:checkbox[name='checkedLogs[]']:checked").map(function() {
                return this.value;
            }).get();

            if(checkedValues.length == 0)
                return;

            $.ajax({
                type: 'post',
                url: (confirm == "1") ? '{{route('unSubmitLogs')}}' : '{{route('submitLogs')}}',
                data: {
                    'logs': checkedValues
                },
                success: function (response) {
                    if(response == "ok") {
                        document.location.href = selfUrl;
                    }
                }
            });
        }

        function deleteLogs() {

            var checkedValues = $("input:checkbox[name='checkedLogs[]']:checked").map(function() {
                return this.value;
            }).get();

            if(checkedValues.length == 0)
                return;

            $.ajax({
                type: 'post',
                url: deleteLogsDir,
                data: {
                    'logs': checkedValues
                },
                success: function (response) {
                    if(response == "ok") {
                        document.location.href = selfUrl;
                    }
                }
            });
        }
    </script>
@stop