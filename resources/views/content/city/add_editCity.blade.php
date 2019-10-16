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

        label {
            min-width: 150px;
        }

        input, select {
            width: 200px;
        }

        #result {
            max-height: 300px;
            overflow: auto;
            margin-top: 20px;
        }
    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                    @if($mode == 'add')
                        <h1>افزودن اطلاعات شهر جدید</h1>
                    @else
                        <h1>ویرایش شهر {{$city->name}}</h1>
                        <form method="post" action="{{route('city.delete')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{$city->id}}">
                            <button type="submit" class="btn btn-danger">حذف شهر</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <div class="row" style="padding: 0px 40px; text-align: right">
                    @if($mode == 'add')

                        <form action="{{route('city.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="city_name">نام شهر:</label>
                                <input type="text" class="form-control" id="city_name" name="city_name">
                            </div>

                            <div class="form-group">
                                <label for="city_x">X:</label>
                                <input type="text" class="form-control" id="city_x" name="city_x">
                            </div>
                            <div class="form-group">
                                <label for="city_y">Y:</label>
                                <input type="text" class="form-control" id="city_y" name="city_y">
                            </div>
                            <div class="form-group">
                                <label for="state">استان:</label>
                                <select class="form-control" id="state" name="state">
                                    @foreach($state as $item)
                                        <option value="{{$item->id}}" >
                                            {{$item->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment">متن:</label>
                                <textarea class="form-control" rows="5" id="comment" name="comment"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">عکس:</label>
                                <input type="file" class="form-control" id="image" name="image"  onchange="readURL(this);">
                            </div>
                            <div class="form-group" style="text-align: center">
                                <img src="#" id="pic" style="max-height: 170px">
                            </div>
                            <button type="submit" class="btn btn-primary">ایجاد</button>
                        </form>

                    @else

                        <form action="{{route('city.doEdit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$city->id}}">
                            <div class="form-group">
                                <label for="city_name">نام شهر:</label>
                                <input type="text" class="form-control" id="city_name" name="city_name" value="{{$city->name}}">
                            </div>
                            <div class="form-group">
                                <label for="city_x">X:</label>
                                <input type="text" class="form-control" id="city_x" name="city_x" value="{{$city->x}}">
                            </div>
                            <div class="form-group">
                                <label for="city_y">Y:</label>
                                <input type="text" class="form-control" id="city_y" name="city_y" value="{{$city->y}}">
                            </div>
                            <div class="form-group">
                                <label for="state">استان:</label>
                                <select class="form-control" id="state" name="state">
                                    @foreach($state as $item)
                                        <option value="{{$item->id}}"
                                                {{$item->id == $city->stateId ? 'selected' : ''}}>
                                            {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment">متن:</label>
                                <textarea class="form-control" rows="5" id="comment" name="comment"> {{$city->description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">عکس:</label>
                                <input type="file" class="form-control" id="image" name="image" onchange="readURL(this);">
                            </div>
                            <div class="form-group" style="text-align: center">
                                <img src="{{URL::asset('_images/city/'.$city->image)}}" id="pic" style="max-height: 170px">
                            </div>
                            <button type="submit" class="btn btn-primary">ویرایش</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#pic')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

@stop