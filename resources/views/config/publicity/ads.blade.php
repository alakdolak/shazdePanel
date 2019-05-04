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
{{--    @include('layouts.pop-up-create-trip')--}}

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
                                            <input onchange="changeSection('{{$section->id}}')" id="section_{{$section->id}}" type="checkbox" value="{{$section->id}}" name="sections[]">
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
                                <div class="ui_column" style="max-width: 200px">
                                    <div id="date_btn_start_edit">تاریخ شروع</div>
                                    <label style="position: relative; margin:6px; width: 100%; height: 30px; border: 1px solid #e5e5e5; border-radius: 2px; box-shadow: 0 7px 12px -7px #e5e5e5 inset;">
                                        <span onclick="editDateTrip()" class="ui_icon calendar" style="color: #30b4a6 !important; font-size: 20px; line-height: 32px; position: absolute; right: 7px;"></span>
                                        <input name="startDate" id="date_input_start_edit_2" placeholder="روز/ماه/سال" required readonly style="padding: 7px; position: absolute; top: 1px; right: 35px; border: none; background: transparent;" type="text">
                                    </label>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="ui_column" style="max-width: 200px">
                                    <div id="date_btn_end_edit">تاریخ اتمام</div>
                                    <label style="position: relative; margin:6px; width: 100%; height: 30px; border: 1px solid #e5e5e5; border-radius: 2px; box-shadow: 0 7px 12px -7px #e5e5e5 inset;">
                                        <span onclick="editDateTripEnd()" class="ui_icon calendar" style="color: #30b4a6 !important; font-size: 20px; line-height: 32px; position: absolute; right: 7px;"></span>
                                        <input name="endDate" id="date_input_end_edit_2" placeholder="روز/ماه/سال" required readonly style="padding: 7px; position: absolute; top: 1px; right: 35px; border: none; background: transparent;" type="text">
                                    </label>
                                </div>
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
                                                <input onchange="changeSection('{{$section->id}}')" checked id="section_{{$section->id}}" type="checkbox" value="{{$section->id}}" name="sections[]">
                                                <span id="part_{{$section->id}}">
                                                    <input type="number" min="1" max="10" value="{{$section->part}}" name="parts[]">
                                                </span>
                                            @else
                                                <input onchange="changeSection('{{$section->id}}')" id="section_{{$section->id}}" type="checkbox" value="{{$section->id}}" name="sections[]">
                                                <span id="part_{{$section->id}}"></span>
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
                                    <input onchange="writeFileName(this.value)" id="photo" type="file" name="pic" style="display: none">
                                    <label for="photo">
                                        <div class="ui_button primary addPhotoBtn">تصویر </div>
                                    </label>
                                    <p id="fileName"></p>
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <div class="ui_column" style="max-width: 200px">
                                    <div id="date_btn_start_edit">تاریخ شروع</div>
                                    <label style="position: relative; margin:6px; width: 100%; height: 30px; border: 1px solid #e5e5e5; border-radius: 2px; box-shadow: 0 7px 12px -7px #e5e5e5 inset;">
                                        <span onclick="editDateTrip()" class="ui_icon calendar" style="color: #30b4a6 !important; font-size: 20px; line-height: 32px; position: absolute; right: 7px;"></span>
                                        <input value="{{convertStringToDate($ad->from_)}}" name="startDate" id="date_input_start_edit_2" placeholder="روز/ماه/سال" required readonly style="padding: 7px; position: absolute; top: 1px; right: 35px; border: none; background: transparent;" type="text">
                                    </label>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="ui_column" style="max-width: 200px">
                                    <div id="date_btn_end_edit">تاریخ اتمام</div>
                                    <label style="position: relative; margin:6px; width: 100%; height: 30px; border: 1px solid #e5e5e5; border-radius: 2px; box-shadow: 0 7px 12px -7px #e5e5e5 inset;">
                                        <span onclick="editDateTripEnd()" class="ui_icon calendar" style="color: #30b4a6 !important; font-size: 20px; line-height: 32px; position: absolute; right: 7px;"></span>
                                        <input value="{{convertStringToDate($ad->to_)}}" name="endDate" id="date_input_end_edit_2" placeholder="روز/ماه/سال" required readonly style="padding: 7px; position: absolute; top: 1px; right: 35px; border: none; background: transparent;" type="text">
                                    </label>
                                </div>
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

        function changeSection(val) {
            if($("#section_" + val).prop('checked'))
                $("#part_" + val).empty().append('<input type="number" min="1" max="10" value="1" name="parts[]">');
            else
                $("#part_" + val).empty();
        }

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