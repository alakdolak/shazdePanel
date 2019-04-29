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
    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>ویرایش محتوا</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    <form method="post" action="{{route('goToPage', ['mode' => $mode])}}">
                        {{csrf_field()}}
                        <div class="col-xs-12">
                            <label>
                                <span>آی دی صفحه مورد نظر خود را وارد نمایید</span>
                                <input type="tel" onkeypress="validateNumber(event)" name="id">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <input type="submit" value="تایید" class="btn btn-primary" name="saveChange">
                        </div>
                    </form>
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

@stop