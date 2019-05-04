@extends('layouts.structure')

@section('header')
    @parent
    <style>

        button {
            margin-right: 10px;
        }

        .row {
            direction: rtl;
        }

        td, thead, th {
            direction: rtl;
            text-align: right !important;
            padding: 10px !important;
            min-width: 100px;
        }

        .bigTd {
            min-width: 250px !important;
        }

        .fixed-table-body {
            overflow-y: hidden;
        }
    </style>

    <script>
        var selectedElem;
        var selectedId;
        var selectedKindPlaceId = '{{getValueInfo('majara')}}';

    </script>

@stop

@section('content')

    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div style="direction: rtl" class="main-sparkline13-hd">
                                <div class="sparkline13-outline-icon">
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="selected">مود خروجی گرفتن از موارد انتخاب شده</option>
                                        <option selected value="all">مود خروجی گرفتن از همه موارد</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="state" data-checkbox="true"></th>
                                            <th class="hidden" data-checkbox="true" data-field="id"></th>
                                            <th class="hidden" data-checkbox="true" data-field="kindPlaceId"></th>
                                            <th data-field="name" data-editable="true">نام مکان</th>
                                            <th class="bigTd" data-field="description" data-editable="true">توضیحات</th>
                                            <th data-field="C" data-editable="true">مختصات x</th>
                                            <th data-field="D" data-editable="true">محتصات y</th>
                                            <th data-field="manategh" data-editable="true">مناطق</th>
                                            <th data-field="kooh" data-editable="true">کوه</th>
                                            <th data-field="darya" data-editable="true">دریا</th>
                                            <th data-field="daryache" data-editable="true">دریاچه</th>
                                            <th data-field="shahri" data-editable="true">شهری</th>
                                            <th data-field="hefazat" data-editable="true">حفاظت</th>
                                            <th data-field="kavir" data-editable="true">کویر</th>
                                            <th data-field="raml" data-editable="true">رمل</th>
                                            <th data-field="jangal" data-editable="true">جنگل</th>
                                            <th data-field="kamp" data-editable="true">کمپ</th>
                                            <th data-field="koohnavardi" data-editable="true">کوه نوردی</th>
                                            <th data-field="sahranavardi" data-editable="true">صحرانوردی</th>
                                            <th data-field="abshar" data-editable="true">آبشار</th>
                                            <th data-field="darre" data-editable="true">دره</th>
                                            <th data-field="picnic" data-editable="true">پیک نیک</th>
                                            <th data-field="bekr" data-editable="true">بکر</th>
                                            <th data-field="dasht" data-editable="true">دشت</th>
                                            <th data-field="dastresi" data-editable="true">دسترسی</th>
                                            <th data-field="nazdik" data-editable="true">نزدیک</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($places as $place)
                                            <tr>
                                                <td></td>
                                                <td class="hidden">{{$place->id}}</td>
                                                <td class="hidden">{{$place->kindPlaceId}}</td>
                                                <td>{{$place->name}}</td>
                                                <td class="bigTd">{{$place->description}}</td>
                                                <td>{{$place->C}}</td>
                                                <td>{{$place->D}}</td>
                                                <td>{{$place->manategh}}</td>
                                                <td>{{$place->kooh}}</td>
                                                <td>{{$place->darya}}</td>
                                                <td>{{$place->daryache}}</td>

                                                <td>{{$place->shahri}}</td>
                                                <td>{{$place->hefazat}}</td>
                                                <td>{{$place->kavir}}</td>
                                                <td>{{$place->raml}}</td>
                                                <td>{{$place->jangal}}</td>

                                                <td>{{$place->kamp}}</td>
                                                <td>{{$place->koohnavardi}}</td>
                                                <td>{{$place->sahranavardi}}</td>
                                                <td>{{$place->abshar}}</td>

                                                <td>{{$place->darre}}</td>
                                                <td>{{$place->picnic}}</td>
                                                <td>{{$place->bekr}}</td>
                                                <td>{{$place->dasht}}</td>
                                                <td>{{$place->dastresi}}</td>
                                                <td>{{$place->nazdik}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function handleClick(id, placeId, mode) {
            selectedId = id;
            selectedElem = mode;
        }

        function handleSubmitFormDataTable() {

            $.ajax({
                type: 'post',
                url: '{{route('doChangePlace')}}',
                data: {
                    'id': selectedId,
                    'kindPlaceId': selectedKindPlaceId,
                    'mode': selectedElem,
                    'val': $(".myDynamicInput").val()
                }
            });

        }


    </script>

@stop