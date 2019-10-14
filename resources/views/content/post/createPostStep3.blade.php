@extends('layouts.structure')

@section('header')
    @parent

    <style>

        th, td {
            min-width: 100px;
            text-align: right;
        }

    </style>

@stop

@section('content')

    <div class="col-md-1"></div>

    <div class="col-md-10">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>افزودن پست جدید</h1>
                </div>

                <form method="post" action="{{route('setPostPic', ['id' => $id])}}" enctype="multipart/form-data">

                    {{csrf_field()}}

                    <center>
                        <h3>تصویر اصلی پست خود را مشخص کنید</h3>

                        @if($pic != null)
                            <p>تصویر انتخاب شده</p>
                            <img src="{{\Illuminate\Support\Facades\URL::asset('posts/' . $pic)}}">

                            <div style="padding: 5px; margin: 10px; border: 1px dashed">
                                <p>تغییر تصویر</p>
                                <input name="pic" type="file" accept="image/*">
                            </div>

                        @else
                            <input name="pic" type="file" accept="image/*">
                        @endif

                    </center>

                <center style="padding: 10px">
                    <input type="submit" value="مرحله بعد" class="btn btn-success">
                </center>

                </form>

            </div>

        </div>
    </div>

    <div class="col-md-1"></div>

@stop