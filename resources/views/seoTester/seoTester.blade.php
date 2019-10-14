@extends('layouts.structure')

@section('header')
    @parent

@stop

@section('content')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>بررسی سئو صفحه</h1>
                </div>
            </div>

            <form method="post" action="{{route('doSeoTest')}}">

                {{csrf_field()}}

                <div style="direction: rtl; height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                    <center class="row">
                        <label for="url">آدرس مد نظر</label>
                        <input name="url" id="url" type="text">
                        <center style="margin-top: 10px">
                            <input class="btn btn-success" type="submit" value="تایید">
                        </center>
                    </center>

                </div>
            </form>
        </div>
    </div>

    <div class="col-md-1"></div>

@stop