@extends('layouts.structure')

@section('header')
    @parent

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="{{URL::asset('js/DataTable/jquery.dataTables.js')}}" defer></script>

@stop

@section('content')

    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>تبلیغات</h1>
                    <div>
                        <button onclick="document.location.href = '{{route('advertisement.new', ['kind' => $kind])}}'" class="btn btn-primary">افزودن تبلیغ جدید</button>
                    </div>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages" style="height: auto!important;">
                <div class="col-md-12" style="direction: rtl; margin-bottom: 40px;  border: solid lightgrey 1px;">
                    <div id="confirmedTable">
                            <table id="mainTable" class="table">
                                <thead class="thead-dark" style="background: black; color: white;">
                                <tr>
                                    <th style="text-align: right">عنوان </th>
                                    <th style="text-align: right"> لینک </th>
                                    <th style="text-align: right; min-width: 150px">بخش </th>
                                    <th style="text-align: right">مدل </th>
                                    <th style="min-width: 100px"></th>
                                </tr>
                                </thead>
                                <tbody id="tBody">
                                @foreach($advertisements as $item)
                                    <tr id="adv_{{$item->id}}" style="text-align: right">
                                        <td>{{$item->title}}</td>
                                        <td>
                                            <a href="{{$item->url}}">لینک تبلیغ</a>
                                        </td>
                                        <td style="color: {{$item->confirm == 1 ? 'green' : 'red'}}">{{$item->section}}</td>
                                        <td>{{$item->kindName}}</td>
                                        <td style="display: flex">
                                            <a href='{{route('advertisement.edit', ['id' => $item->id])}}'>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="deleteAdvertisement('{{$item->id}}')" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: right">عنوان </th>
                                        <th style="text-align: right"> لینک </th>
                                        <th style="text-align: right; min-width: 150px">بخش </th>
                                        <th style="text-align: right">مدل </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var advertisement = {!! $advertisements !!}

        function deleteAdvertisement(_advId) {
            $.ajax({
                type: 'DELETE',
                url: '{{route('advertisement.delete')}}',
                data: { 'advId': _advId },
                success: res => {
                    if(res.status == "ok")
                        $("#adv_" + _advId).remove();
                }
            });
        };
    </script>
@stop
