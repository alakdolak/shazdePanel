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
                    <h1>تعیین سقف سن خردسال</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    <form method="post" action="{{route('ageSentences')}}">
                        {{csrf_field()}}
                        <div class="col-xs-12">
                            <label>
                                <span>بزرگسال در پرواز داخلی</span>
                                <input type="text" name="adultInner" value="{{$adultInner}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>حدکثر سن کودک در پرواز داخلی</span>
                                <input type="text" name="childInnerMax" value="{{$childInnerMax}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>حداقل سن کودک در پرواز داخلی</span>
                                <input type="text" name="childInnerMin" value="{{$childInnerMin}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>خردسال در پرواز داخلی</span>
                                <input type="text" name="infantInner" value="{{$infantInner}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>بزرگسال در پرواز خارجی</span>
                                <input type="text" name="adultExternal" value="{{$adultExternal}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>حداکثر کودک در پرواز خارجی</span>
                                <input type="text" name="childExternalMax" value="{{$childExternalMax}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>حداقل کودک در پرواز خارجی</span>
                                <input type="text" name="childExternalMin" value="{{$childExternalMin}}">
                            </label>
                        </div>
                        <div class="col-xs-12">
                            <label>
                                <span>خردسال در پرواز حارجی</span>
                                <input type="text" name="infantExternal" value="{{$infantExternal}}">
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