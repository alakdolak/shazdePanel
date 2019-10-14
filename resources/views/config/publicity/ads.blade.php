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
        .col-xs-12 {
            margin-top: 10px;
        }

        button {
            margin-right: 10px;
        }

        .row {
            direction: rtl;
        }

        .calendar2 {
            top: 113% !important;
            left: 27% !important;
        }
    </style>

    <style>
        .calendar2 {
            top: 113% !important;
            left: 27% !important;
        }
    </style>
@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-2"></div>

    <div class="col-md-8">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت تبلیغات</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    <div class="col-xs-12">
                        <h3>تبلیغات</h3>
                    </div>

                    @if($mode == "see")

                        @if(count($ads) == 0)
                            <div class="col-xs-12">
                                <h4 class="warning_color">تبلیغی وجود ندارد</h4>
                            </div>
                        @else
                            <form method="post" action="{{route('deleteAd')}}">
                                {{csrf_field()}}
                                @foreach($ads as $ad)
                                    <div class="col-xs-12">
                                        <img width="100" height="100" src="{{URL::asset('ads') . '/' . $ad->pic}}">
                                        <span>نام شرکت </span><span>{{$ad->companyId}}</span>
                                        <span>محل قرارگیری </span> <span>{{$ad->sections}}</span>
                                        <span> از {{$ad->from_}} تا {{$ad->to_}}</span>
                                        <span> استان ها </span> <span>{{$ad->states}}</span>

                                        <a href="{{route('editAd', ['adId' => $ad->id])}}" name="editLevel" class="sparkline9-collapse-link transparentBtn" data-toggle="tooltip" title="ویرایش تبلیغ" style="width: auto">
                                            <span class="fa fa-wrench"></span>
                                        </a>
                                        <button name="adId" value="{{$ad->id}}" class="sparkline9-collapse-close transparentBtn" data-toggle="tooltip" title="حذف تبلیغ" style="width: auto">
                                            <span ><i class="fa fa-times"></i></span>
                                        </button>
                                    </div>
                                @endforeach
                            </form>
                        @endif

                        <div class="col-xs-12">
                            <a href="{{route('addAds')}}">
                                <button class="btn btn btn-default" style="width: auto; border-radius: 50% 50% 50% 50%" data-toggle="tooltip" title="اضافه کردن تبلیغ جدید">
                                    <span class="glyphicon glyphicon-plus" style="margin-left: 30%"></span>
                                </button>
                            </a>
                        </div>
                    @elseif($mode == "add")
                        <form method="post" action="{{route('addAds')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="col-xs-12">
                                <label>
                                    <span>نام شرکت</span>
                                    <select name="companyId">
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>استان های مورد نظر</span>
                                    @foreach($states as $state)
                                        <div>
                                            <label for="state_{{$state->id}}">{{$state->name}}</label>
                                            <input id="state_{{$state->id}}" type="checkbox" value="{{$state->id}}" name="states[]">
                                        </div>
                                    @endforeach
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>صفحات مورد نظر</span>
                                    @foreach($sections as $section)
                                        <div>
                                            <label for="section_{{$section->id}}">{{$section->name}}</label>
                                            <input id="section_{{$section->id}}" type="checkbox" value="{{$section->id}}" name="sections[]">
                                            <span id="part_{{$section->id}}"></span>
                                        </div>
                                    @endforeach
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>url</span>
                                    <input type="text" name="url" required maxlength="300">
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>تصویر</span>
                                    <input type="file" name="pic" accept="image/png" required>
                                </label>
                            </div>

                            <div class="col-xs-12">

                                <center class="col-xs-6">
                                    <input type="button"
                                           style="border: none; width: 15px;  height: 15px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;"
                                           id="date_btn_end">
                                    <span>تا تاریخ</span>

                                    <input type="text" style="max-width: 200px" class="form-detail"
                                           name="endDate" id="date_input_end" onchange="end()" readonly>

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
                                           name="startDate" id="date_input_start" onchange="start()" readonly>

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

                            <div class="col-xs-12">
                                <p class="warning_color">{{$msg}}</p>
                                <input type="submit" name="addPublicity" value="اضافه کن" class="btn btn-primary" style="width: auto; margin-top: 10px">
                            </div>
                        </form>
                    @else
                        <form method="post" action="{{route('editAd', ['adId' => $ad->id])}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="col-xs-12">
                                <label>
                                    <span>نام شرکت</span>
                                    <select name="companyId">
                                        @foreach($companies as $company)
                                            @if($ad->companyId == $company->id)
                                                <option selected value="{{$company->id}}">{{$company->name}}</option>
                                            @else
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>استان های مورد نظر</span>
                                    @foreach($states as $state)
                                        <div>
                                            <label for="state_{{$state->id}}">{{$state->name}}</label>
                                            @if($state->select == 1)
                                                <input checked id="state_{{$state->id}}" type="checkbox" value="{{$state->id}}" name="states[]">
                                            @else
                                                <input id="state_{{$state->id}}" type="checkbox" value="{{$state->id}}" name="states[]">
                                            @endif
                                        </div>
                                    @endforeach
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>صفحات مورد نظر</span>
                                    @foreach($sections as $section)
                                        <div>
                                            <label for="section_{{$section->id}}">{{$section->name}}</label>
                                            @if($section->select == 1)
                                                <input checked id="section_{{$section->id}}" type="checkbox" value="{{$section->id}}" name="sections[]">
                                            @else
                                                <input id="section_{{$section->id}}" type="checkbox" value="{{$section->id}}" name="sections[]">
                                            @endif
                                        </div>
                                    @endforeach
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <label>
                                    <span>url</span>
                                    <input type="text" value="{{$ad->url}}" name="url" required maxlength="300">
                                </label>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    writeFileName('{{$ad->pic}}');
                                });

                                function writeFileName(val) {
                                    $("#fileName").empty().append(val);
                                }
                            </script>

                            <div class="col-xs-12">
                                <label>
                                    <input onchange="writeFileName(this.value)" id="photo" type="file" name="pic">
                                    <label for="photo">
                                        <div class="ui_button primary addPhotoBtn">تصویر </div>
                                    </label>
                                    <p id="fileName"></p>
                                </label>
                            </div>

                            <div class="col-xs-12">

                                <center class="col-xs-6">
                                    <input type="button"
                                           style="border: none; width: 15px;  height: 15px; background: url({{ URL::asset('img/calender.png') }}) repeat 0 0; background-size: 100% 100%;"
                                           id="date_btn_end">
                                    <span>تا تاریخ</span>

                                    <input type="text" style="max-width: 200px" class="form-detail"
                                           name="endDate" value="{{convertStringToDate($ad->to_)}}" id="date_input_end" onchange="end()" readonly>

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
                                           name="startDate" value="{{convertStringToDate($ad->from_)}}" id="date_input_start" onchange="start()" readonly>

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

                            <div class="col-xs-12">
                                <p class="warning_color">{{$msg}}</p>
                                <input type="submit" name="addPublicity" value="ویرایش" class="btn btn-primary" style="width: auto; margin-top: 10px">
                            </div>
                        </form>
                    @endif
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        function editDateTrip() {

            $("#date_input_start_edit_2").datepicker({
                numberOfMonths: 2,
                showButtonPanel: true,
                minDate: 0,
                dateFormat: "yy/mm/dd"
            });
        }
        function editDateTripEnd() {

            $("#date_input_end_edit_2").datepicker({
                numberOfMonths: 2,
                showButtonPanel: true,
                minDate: 0,
                dateFormat: "yy/mm/dd"
            });
        }
    </script>
@stop