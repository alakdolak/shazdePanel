@extends('layouts.structure')

@section('header')
    @parent

    <style>

        .col-xs-12 {
            margin-top: 20px;
        }

    </style>

@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>افزودن محتوا</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <form method="post" action="{{route('doUploadMainContent')}}" enctype="multipart/form-data" style="direction: rtl">

                    {{csrf_field()}}

                    <center class="row">

                        <div class="col-xs-12">
                            <div class="btn-group images-cropper-pro">
                                <label for="kindPlaceId">مکان مورد نظر</label>
                                <select name="kindPlaceId" id="kindPlaceId">
                                    @foreach($places as $place)
                                        @if($kindPlaceId == $place->id)
                                            <option selected value="{{$place->id}}">{{$place->name}}</option>
                                        @else
                                            <option value="{{$place->id}}">{{$place->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="btn-group images-cropper-pro">
                                <label for="inputImage" class="btn btn-primary img-cropper-cp">
                                    <input type="file" accept="application/vnd.ms-excel" name="content" id="inputImage" class="hide"> آپلود فایل اکسل محتوا
                                </label>
                                <p id="fileName_content"></p>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="btn-group images-cropper-pro">
                                <label for="inputImage2" class="btn btn-primary img-cropper-cp">
                                    <input type="file" accept="application/zip" name="photos" id="inputImage2" class="hide"> آپلود فایل زیپ تصاویر
                                </label>
                                <p id="fileName_photos"></p>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <input class="btn btn-success" type="submit" value="تایید و ارسال">
                            <p style="margin-top: 10px">{!! html_entity_decode($msg) !!}</p>
                        </div>
                    </center>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        $(document).ready(function(){
            $('input[type="file"]').change(function(e){
                var fileName = e.target.files[0].name;
                $("#fileName_" + this.name).empty().append(fileName);
            });
        });

    </script>

@stop