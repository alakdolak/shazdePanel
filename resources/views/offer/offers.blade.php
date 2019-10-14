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
        var selfUrl = '{{route('offers')}}';
        var deleteOffersDir = '{{route('deleteOffer')}}';
    </script>

@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>کد های تخفیف</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row">

                    <div class="col-xs-12">
                        <a href="{{route('createOffer')}}">ایجاد کد تخفیف جدید</a>
                    </div>
                    
                    <div class="col-xs-12" style="margin-top: 10px">

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
                            <label>نمایش</label>
                            <select onchange="redirect($(this).val())">
                                @if($mode == 'all')
                                    <option value="all">همه</option>
                                    <option value="used">استفاده شده ها</option>
                                    <option value="not_used">استفاده نشده ها</option>
                                @elseif($mode == 'used')
                                    <option value="all">همه</option>
                                    <option selected value="used">استفاده شده ها</option>
                                    <option value="not_used">استفاده نشده ها</option>
                                @else
                                    <option value="all">همه</option>
                                    <option value="used">استفاده شده ها</option>
                                    <option selected value="not_used">استفاده نشده ها</option>
                                @endif
                            </select>
                        </center>

                    </div>

                    <?php $i = 0; $dates = [] ?>

                    @if(count($offers) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">کد تخفیفی وجود ندارد</h4>
                        </div>
                    @else

                        <div class="col-xs-12" style="padding: 20px">
                            <center>
                                <h3>تعداد کل رکورد ها {{count($offers)}}</h3>
                            </center>
                            <table>
                                <tr>
                                    <td>نام کاربری ایجاد کننده</td>
                                    <td>کد</td>
                                    <td>نوع</td>
                                    <td>مقدار</td>
                                    <td>گیرنده</td>
                                    <td>تاریخ ایجاد</td>
                                    <td>تاریخ انقضا</td>
                                    <td>استفاده شده/نشده</td>
                                    <td>
                                        <label>انتخاب همه</label>
                                        <input type="checkbox" onchange="checkAll($(this).prop('checked'))">
                                    </td>
                                </tr>

                                @foreach($offers as $offer)

                                    <?php $dates[$i] = $offer->date; ?>

                                    <tr id="{{$i}}">
                                        <td>{{$offer->creator}}</td>
                                        <td>{{$offer->code}}</td>
                                        <td>{{($offer->kind == getValueInfo('staticOffer')) ? 'مقداری' : 'درصدی'}}</td>
                                        <td>{{$offer->amount}}</td>
                                        <td>{{$offer->username}}</td>
                                        <td>{{$offer->date}}</td>
                                        <td>{{$offer->expire}}</td>
                                        <td>{{($offer->used) ? 'استفاده شده' : 'استفاده نشده'}}</td>
                                        <td>
                                            <input value="{{$offer->id}}" type="checkbox" name="checkedLogs[]">
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach

                            </table>
                        </div>

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

        function redirect(mode) {
            document.location.href = '{{route('offers')}}' + "/" + mode;
        }

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

        function checkAll(val) {

            if(val) {
                $(":checkbox[name='checkedLogs[]']").prop('checked', true);
                $(val).attr('data-val', 'unSelect');
            }
            else {
                $("input[name='checkedLogs[]']").prop('checked', false);
                $(val).attr('data-val', 'select');
            }

        }

        function deleteLogs() {

            var checkedValues = $("input:checkbox[name='checkedLogs[]']:checked").map(function() {
                return this.value;
            }).get();

            if(checkedValues.length == 0)
                return;

            $.ajax({
                type: 'post',
                url: deleteOffersDir,
                data: {
                    'offers': checkedValues
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