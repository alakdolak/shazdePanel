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
        var selectedKindPlaceId = '{{getValueInfo('amaken')}}';

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
                                        <option value="all">مود خروجی گرفتن از همه موارد</option>
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
                                            <th data-field="address" data-editable="true">آدرس</th>
                                            <th data-field="phone" data-editable="true">تلفن</th>
                                            <th data-field="site" data-editable="true">سایت</th>
                                            <th data-field="C" data-editable="true">مختصات x</th>
                                            <th data-field="D" data-editable="true">محتصات y</th>
                                            <th data-field="emkanat" data-editable="true">امکانات</th>
                                            <th data-field="tarikhi" data-editable="true">تاریخی</th>
                                            <th data-field="mooze" data-editable="true">موزه</th>
                                            <th data-field="tafrihi" data-editable="true">تفریحی</th>
                                            <th data-field="tabiatgardi" data-editable="true">طبیعت گردی</th>
                                            <th data-field="markazkharid" data-editable="true">مرکز خرید</th>
                                            <th data-field="baftetarikhi" data-editable="true">بافت تاریخی</th>
                                            <th data-field="markaz" data-editable="true">مرکز</th>
                                            <th data-field="hoome" data-editable="true">حومه</th>
                                            <th data-field="shologh" data-editable="true">شلوغ</th>
                                            <th data-field="khalvat" data-editable="true">خلوت</th>
                                            <th data-field="tabiat" data-editable="true">طبیعت</th>
                                            <th data-field="kooh" data-editable="true">کوه</th>
                                            <th data-field="darya" data-editable="true">دریا</th>
                                            <th data-field="modern" data-editable="true">مدرن</th>
                                            <th data-field="tarikhibana" data-editable="true">بنا تاریخی</th>
                                            <th data-field="mamooli" data-editable="true">معمولی</th>
                                            <th data-field="farhangi" data-editable="true">فرهنگی</th>
                                            <th data-field="ghadimi" data-editable="true">قدیمی</th>
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
                                                <td>{{$place->address}}</td>
                                                <td>{{$place->phone}}</td>
                                                <td>{{$place->site}}</td>
                                                <td>{{$place->C}}</td>
                                                <td>{{$place->D}}</td>

                                                <td>{{$place->emkanat}}</td>
                                                <td>{{$place->tarikhi}}</td>
                                                <td>{{$place->mooze}}</td>
                                                <td>{{$place->tafrihi}}</td>
                                                <td>{{$place->tabiatgardi}}</td>
                                                <td>{{$place->markazkharid}}</td>
                                                <td>{{$place->baftetarikhi}}</td>

                                                <td>{{$place->markaz}}</td>
                                                <td>{{$place->hoome}}</td>
                                                <td>{{$place->shologh}}</td>
                                                <td>{{$place->khalvat}}</td>
                                                <td>{{$place->tabiat}}</td>
                                                <td>{{$place->kooh}}</td>
                                                <td>{{$place->darya}}</td>

                                                <td>{{$place->modern}}</td>
                                                <td>{{$place->tarikhibana}}</td>
                                                <td>{{$place->mamooli}}</td>

                                                <td>{{$place->farhangi}}</td>
                                                <td>{{$place->ghadimi}}</td>
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