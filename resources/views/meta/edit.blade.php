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

        .margin_bot {
            margin-bottom: 10px;
        !important;
        }

    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">

                    <h1>meta ویرایش کردن {{isset($result) ? '<'.$result->name.'>' : ''}}</h1>

                    <button class="btn btn-warning" onclick="document.location.href = '{{url('meta/index/' . $kind)}}' ">بازگشت</button>

                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    <form method="post" action="{{route('meta.doEdit')}}">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$result->id}}">
                        <input type="hidden" name="kind" value="{{$kind}}">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="meta">متن مورد نظر خود را وارد نمایید:</label>
                                <textarea class="form-control" rows="5" id="meta" name="meta" style="font-size: 18px; margin-top: 10px;">{{$result->meta}}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <input type="submit" class="btn btn-success width-auto" value="تایید">
                        </div>
                    </form>
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>


@stop