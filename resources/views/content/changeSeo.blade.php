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
            min-width: 150px !important;
        }

        .fixed-table-body {
            overflow-y: hidden;
        }
    </style>
    
    <script>
        var selectedElem;
        var selectedId;
        var selectedKindPlaceId;
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
                                            <th data-field="name" data-editable="false">نام مکان</th>
                                            <th data-field="kindPlaceName" data-editable="false">نوع مکان</th>
                                            <th class="bigTd" data-field="meta" data-editable="true">تگ متا</th>
                                            <th data-field="keyword" data-editable="true">تگ کلیداصلی</th>
                                            <th data-field="h2" data-editable="true">تگ h</th>
                                            <th data-field="tag1" data-editable="true">تگ tag1</th>
                                            <th data-field="tag2" data-editable="true">تگ tag2</th>
                                            <th data-field="tag3" data-editable="true">تگ tag3</th>
                                            <th data-field="tag4" data-editable="true">تگ tag4</th>
                                            <th data-field="tag5" data-editable="true">تگ tag5</th>
                                            <th data-field="tag6" data-editable="true">تگ tag6</th>
                                            <th data-field="tag7" data-editable="true">تگ tag7</th>
                                            <th data-field="tag8" data-editable="true">تگ tag8</th>
                                            <th data-field="tag9" data-editable="true">تگ tag9</th>
                                            <th data-field="tag10" data-editable="true">تگ tag10</th>
                                            <th data-field="tag11" data-editable="true">تگ tag11</th>
                                            <th data-field="tag12" data-editable="true">تگ tag12</th>
                                            <th data-field="tag13" data-editable="true">تگ tag13</th>
                                            <th data-field="tag14" data-editable="true">تگ tag14</th>
                                            <th data-field="tag15" data-editable="true">تگ tag15</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($places as $place)
                                            <tr>
                                                <td></td>
                                                <td class="hidden">{{$place->id}}</td>
                                                <td class="hidden">{{$place->kindPlaceId}}</td>
                                                <td>{{$place->name}}</td>
                                                <td>{{$place->kindPlaceName}}</td>
                                                <td class="bigTd">{{$place->meta}}</td>
                                                <td>{{$place->keyword}}</td>
                                                <td>{{$place->h1}}</td>
                                                <td>{{$place->tag1}}</td>
                                                <td>{{$place->tag2}}</td>
                                                <td>{{$place->tag3}}</td>
                                                <td>{{$place->tag4}}</td>
                                                <td>{{$place->tag5}}</td>
                                                <td>{{$place->tag6}}</td>
                                                <td>{{$place->tag7}}</td>
                                                <td>{{$place->tag8}}</td>
                                                <td>{{$place->tag9}}</td>
                                                <td>{{$place->tag10}}</td>
                                                <td>{{$place->tag11}}</td>
                                                <td>{{$place->tag12}}</td>
                                                <td>{{$place->tag13}}</td>
                                                <td>{{$place->tag14}}</td>
                                                <td>{{$place->tag15}}</td>
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
            selectedKindPlaceId = placeId;
            selectedElem = mode;
        }

        function handleSubmitFormDataTable() {
            
            $.ajax({
                type: 'post',
                url: '{{route('doChangeSeo')}}',
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