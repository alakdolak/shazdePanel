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
        td {
            min-width: 80px;
            padding: 8px;
        }
    </style>
@stop

@section('content')

    @include('layouts.modal')

    <div class="col-md-1"></div>

    <div class="col-md-10">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت بخش های تبلیغاتی</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    <form method="post" action="{{route('addPageToSection', ['sectionId' => $section->id])}}">
                        {{csrf_field()}}
                        <center class="col-xs-12">

                            <div class="col-xs-12">
                                <label for="main_page">main_page</label>
                                <input id="main_page" {{(in_array(getValueInfo('main_page'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('main_page')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="hotel-detail">hotel-detail</label>
                                <input id="hotel-detail" {{(in_array(getValueInfo('hotel-detail'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('hotel-detail')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="adab-detail">adab-detail</label>
                                <input id="adab-detail" {{(in_array(getValueInfo('adab-detail'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('adab-detail')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="amaken-detail">amaken-detail</label>
                                <input id="amaken-detail" {{(in_array(getValueInfo('amaken-detail'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('amaken-detail')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="majara-detail">majara-detail</label>
                                <input id="majara-detail" {{(in_array(getValueInfo('majara-detail'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('majara-detail')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="restaurant-detail">restaurant-detail</label>
                                <input id="restaurant-detail" {{(in_array(getValueInfo('restaurant-detail'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('restaurant-detail')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="hotel-list">hotel-list</label>
                                <input id="hotel-list" {{(in_array(getValueInfo('hotel-list'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('hotel-list')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="majara-list">majara-list</label>
                                <input id="majara-list" {{(in_array(getValueInfo('majara-list'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('majara-list')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="restaurant-list">restaurant-list</label>
                                <input id="restaurant-list" {{(in_array(getValueInfo('restaurant-list'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('restaurant-list')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="amaken-list">amaken-list</label>
                                <input id="amaken-list" {{(in_array(getValueInfo('amaken-list'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('amaken-list')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <label for="adab-list">adab-list</label>
                                <input id="adab-list" {{(in_array(getValueInfo('adab-list'), $pages)) ? 'checked' : ''}} name="page[]" value='{{getValueInfo('adab-list')}}' type="checkbox">
                            </div>

                            <div class="col-xs-12">
                                <input type="submit" class="btn btn-primary" value="تایید">
                            </div>

                        </center>
                    </form>

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-1"></div>

@stop