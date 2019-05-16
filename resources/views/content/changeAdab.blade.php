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
        var selectedKindPlaceId = '{{getValueInfo('adab')}}';

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

                                <div style="display: inline-block">
                                    <label for="filter">دسته مورد نظر</label>
                                    <select id="filter" class="form-control" onchange="changeMode(this.value)">
                                        <option value="-1">همه</option>
                                        @foreach($modes as $mode)
                                            @if(isset($selectedMode) && $mode['id'] == $selectedMode)
                                                <option selected value="{{$mode['id']}}">{{$mode['name']}}</option>
                                            @else
                                                <option value="{{$mode['id']}}">{{$mode['name']}}</option>
                                            @endif
                                        @endforeach
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
                                        <th data-field="dastoor" data-editable="true">دستور</th>
                                        <th data-field="mazze" data-editable="true">مزه</th>
                                        <th data-field="brand_name_1" data-editable="true">برند اول</th>
                                        <th data-field="des_name_1" data-editable="true">توضیحات برند اول</th>
                                        <th data-field="brand_name_2" data-editable="true">برند دوم</th>
                                        <th data-field="des_name_2" data-editable="true">توضیحات برند دوم</th>
                                        <th data-field="brand_name_3" data-editable="true">برند سوم</th>
                                        <th data-field="des_name_3" data-editable="true">توضیحات برند سوم</th>
                                        <th data-field="brand_name_4" data-editable="true">برند چهارم</th>
                                        <th data-field="des_name_4" data-editable="true">توضیحات برند چهارم</th>
                                        <th data-field="brand_name_5" data-editable="true">برند پنجم</th>
                                        <th data-field="des_name_5" data-editable="true">توضیحات برند پنجم</th>
                                        <th data-field="brand_name_6" data-editable="true">برند ششم</th>
                                        <th data-field="des_name_6" data-editable="true">توضیحات برند ششم</th>
                                        <th data-field="brand_name_7" data-editable="true">برند هفتم</th>
                                        <th data-field="des_name_7" data-editable="true">توضیحات برند هفتم</th>
                                        <th data-options="{{$categories}}" data-type="select" data-field="category" data-editable="true">دسته</th>

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
                                            <td class="bigTd">{{$place->dastoor}}</td>
                                            <td>{{$place->mazze}}</td>
                                            <td>{{$place->brand_name_1}}</td>
                                            <td>{{$place->des_name_1}}</td>
                                            <td>{{$place->brand_name_2}}</td>
                                            <td>{{$place->des_name_2}}</td>
                                            <td>{{$place->brand_name_3}}</td>
                                            <td>{{$place->des_name_3}}</td>
                                            <td>{{$place->brand_name_4}}</td>
                                            <td>{{$place->des_name_4}}</td>
                                            <td>{{$place->brand_name_5}}</td>
                                            <td>{{$place->des_name_5}}</td>
                                            <td>{{$place->brand_name_6}}</td>
                                            <td>{{$place->des_name_6}}</td>
                                            <td>{{$place->brand_name_7}}</td>
                                            <td>{{$place->des_name_7}}</td>
                                            <td>{{$place->category}}</td>
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

        function changeMode(val) {
            document.location.href = '{{$pageURL}}' + "/" + val;
        }

        $(document).ready(function () {
            @if($wantedKey != -1)
                setTimeout(function () {
                $("#searchInTable").val("{{$wantedKey}}").change().focusout();
            }, 500);
            @endif
        });

        function handleChangeSelect(id, placeId, mode, counter) {

            selectedId = id;
            selectedElem = mode;

            $.ajax({
                type: 'post',
                url: '{{route('doChangePlace')}}',
                data: {
                    'id': selectedId,
                    'kindPlaceId': selectedKindPlaceId,
                    'mode': selectedElem,
                    'val': $("#" + counter + "_" + selectedElem).val()
                }
            });
        }

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