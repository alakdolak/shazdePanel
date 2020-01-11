@extends('layouts.structure')

@section('header')
    @parent
    <link rel="stylesheet" href="{{URL::asset('css/switch.css')}}">

    <style>
        .row {
            direction: rtl;
        }
        .f_r{
            float: right;
        }
        .switch{
            width: 30px;
            height: 17px;
        }
        input:checked + .slider:before{
            transform: translateX(13px);
        }
        .slider:before{
            height: 13px;
            width: 13px;
            left: 2px;
            bottom: 2px;
        }
    </style>

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
                            <div class="container-fluid">
                                <div class="row">
                                    <div style="display: inline-block">
                                        <h2>
                                            عکس های اسلاید اصلی
                                        </h2>
                                    </div>
                                </div>

                                <div id="picSection">
                                    @for($i = 0; $i < count($pics); $i++)
                                        <div id="picRow{{$i}}" class="row">
                                            <div class="col-sm-8">
                                                <img id="pic{{$i}}" src="{{URL::asset('_images/sliderPic/' . $pics[$i]->pic)}}" style="width: 300px;">
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="row">
                                                    <label for="newPic{{$i}}" class="btn btn-primary">
                                                        تغییر عکس
                                                        <input type="file" id="newPic{{$i}}" accept="image/*"  style="display: none;" onchange="showPic(this, {{$i}}, 'edit')">
                                                    </label>
                                                    <button class="btn btn-danger" onclick="deletePic({{$pics[$i]->id}}, {{$i}})">حذف عکس</button>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <label for="alt{{$i}}">alt عکس</label>
                                                    <input id="alt{{$i}}" type="text" class="form-control" placeholder="alt عکس" value="{{$pics[$i]->alt}}">
                                                    <button class="btn btn-warning" onclick="changeAlt({{$pics[$i]->id}}, {{$i}})">تغییر alt</button>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <label for="text{{$i}}">متن روی عکس</label>
                                                    <input id="text{{$i}}" type="text" class="form-control" placeholder="متن روی عکس"  value="{{$pics[$i]->text}}">
                                                    <button class="btn btn-warning" onclick="changeText({{$pics[$i]->id}}, {{$i}})">تغییر متن</button>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12" style="text-align: right">
                                                        <label for="color{{$i}}">رنگ متن</label>
                                                        <input id="color{{$i}}" type="color" value="{{$pics[$i]->textColor == null ? '#ffffff' : $pics[$i]->textColor}}">
                                                    </div>
                                                    <div class="col-sm-12" style="text-align: right">
                                                        <label for="backGround{{$i}}">رنگ پس زمینه ی متن</label>
                                                        <label class="switch">
                                                            <input id="allowBackground{{$i}}" type="checkbox" onchange="changeChooseBackground(this)" {{$pics[$i]->textBackground == null ? '' : 'checked'}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <input id="backGround{{$i}}" type="color" value="{{$pics[$i]->textBackground == null ? '#ffffff' : $pics[$i]->textBackground}}" style="display: {{$pics[$i]->textBackground == null ? 'none' : ''}}">
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <button class="btn btn-warning" onclick="changeColors({{$pics[$i]->id}}, {{$i}})">تغییر رنگ</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    @endfor
                                </div>

                                <div class="row">
                                    <div class="col-md-12 f_r" >
                                        <div class="form-group" style="display: flex; flex-direction: column">
                                            <div>
                                                <label for="picInput1000" class="btn btn-success">
                                                    عکس جدید
                                                    <input type="file" name="picInput1000" accept="image/*" id="picInput1000" style="display: none;" onchange="showPic(this, 1000, 'new')">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var picNumberMain = {{count($pics)}};
        var idIndex = [];

        @for($i = 0; $i < count($pics); $i++)
            idIndex[idIndex.length] = {{$pics[$i]->id}}
        @endfor

        function showPic(input, i, kind) {
            var picNumber;
            var url;
            var data = new FormData();

            if(kind == 'new')
                url = '{{route("slider.storePic")}}';
            else {
                url = '{{route("slider.changePic")}}';
                picNumber = i;
                data.append('id', idIndex[i]);
            }

            if (input.files && input.files[0]) {
                data.append('pic', input.files[0]);
                $.ajax({
                    type: 'post',
                    url: url,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        response = JSON.parse(response);
                        if(response[0] == 'ok'){
                            if(kind == 'new') {
                                picNumber = picNumberMain;
                                idIndex[picNumber] = response[1];

                                var text = '\n' +
                                    '<div id="picRow' + picNumber + '" class="row">\n' +
                                    '<div class="col-sm-8">\n' +
                                    '<img id="pic' + picNumber + '" src="#" style="width: 300px;">\n' +
                                    '</div>\n' +
                                    '<div class="col-sm-4">\n' +
                                    '<div class="row">\n' +
                                    '<label for="newPic' + picNumber + '" class="btn btn-primary">\n' +
                                    'تغییر عکس\n' +
                                    '<input type="file" id="newPic' + picNumber + '" accept="image/*"  style="display: none;" onchange="showPic(this, ' + picNumber + ', \'edit\')">\n' +
                                    '</label>\n' +
                                    '<button class="btn btn-danger" onclick="deletePic(' + idIndex[picNumber] + ', ' + picNumber + ')">حذف عکس</button>\n' +
                                    '</div>\n' +
                                    '<hr>\n' +
                                    '<div class="row">\n' +
                                    '<label for="alt' + picNumber + '">alt عکس</label>\n' +
                                    '<input id="alt' + picNumber + '" type="text" class="form-control" PLACEHOLDER="alt عکس" >\n' +
                                    '<button class="btn btn-warning" onclick="changeAlt(' + idIndex[picNumber] + ', ' + picNumber + ')">تغییر alt</button>\n' +
                                    '</div>\n' +
                                    '<hr>\n' +
                                    '<div class="row">\n' +
                                    '<label for="text' + picNumber + '">متن روی عکس</label>\n' +
                                    '<input id="text' + picNumber + '" type="text" class="form-control" PLACEHOLDER="متن روی عکس" >\n' +
                                    '<button class="btn btn-warning" onclick="changeText(' + idIndex[picNumber] + ', ' + picNumber + ')">تغییر متن</button>\n' +
                                    '</div>\n' +
                                    '<hr>\n' +
                                    '<div class="row">\n' +
                                    '<div class="col-sm-12" style="text-align: right">\n' +
                                    '<label for="color' + picNumber + '">رنگ متن</label>\n' +
                                    '<input id="color' + picNumber + '" type="color" value="#ffffff">\n' +
                                    '</div>\n' +
                                    '<div class="col-sm-12" style="text-align: right">\n' +
                                    '<label for="backGround' + picNumber + '">رنگ پس زمینه ی متن</label>\n' +
                                    '<label class="switch">\n' +
                                    '<input id="allowBackground' + picNumber + '" type="checkbox" onchange="changeChooseBackground(this)">\n' +
                                    '<span class="slider round"></span>\n' +
                                    '</label>\n' +
                                    '<input id="backGround' + picNumber + '" type="color" value="#ffffff" style="display: none">\n' +
                                    '</div>\n' +
                                    '<div class="col-sm-12">\n' +
                                    '<button class="btn btn-warning" onclick="changeColors(' + idIndex[picNumber] + ', ' + picNumber + ')">تغییر رنگ</button>\n' +
                                    '</div>\n' +
                                    '</div>' +
                                    '</div>\n' +
                                    '</div>\n' +
                                    '<hr>';

                                $('#picSection').append(text);

                                picNumberMain++;
                            }

                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $('#pic' + picNumber).attr('src', e.target.result);
                            };
                            reader.readAsDataURL(input.files[0]);
                        }
                        else{
                            if(kind == 'new')
                                $('#picRow' + picNumber).remove();
                            alert('Error in Upload Image')
                        }
                    },
                    error: function(){
                        if(kind == 'new')
                            $('#picRow' + picNumber).remove();
                        alert('Error in Upload Image')
                    }
                });
            }
        }

        function deletePic(id, i){

            $.ajax({
                type: 'post',
                url : '{{route('slider.deletePic')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : id,
                },
                success: function(response){
                    if(response == 'ok')
                        $('#picRow' + i).remove();
                }
            })
        }

        function changeAlt(_id, i){
            var value = document.getElementById('alt' + i).value;

            $.ajax({
                type: 'post',
                url : '{{route('slider.changeAltPic')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id,
                    'alt' : value,
                },
                success: function(response){
                    if(response == 'ok')
                        alert('alt عکس با موفقیت تغییر پیدا کرد')
                }
            });

        }

        function changeText(_id ,i){
            var value = document.getElementById('text' + i).value;

            $.ajax({
                type: 'post',
                url : '{{route('slider.changeTextPic')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : _id,
                    'text' : value,
                },
                success: function(response){
                    if(response == 'ok')
                        alert('متن عکس با موفقیت تغییر پیدا کرد')
                }
            });

        }

        function changeColors(id, i){
            var allowBackground = document.getElementById('allowBackground' + i).checked;
            var color = document.getElementById('color' + i).value;
            var background = null;
            if(allowBackground)
                background = document.getElementById('backGround' + i).value;

            $.ajax({
                type: 'post',
                url : '{{route('slider.changeColor')}}',
                data: {
                    '_token' : '{{csrf_token()}}',
                    'id' : id,
                    'color' : color,
                    'background' : background
                },
                success: function(response){
                    if(response == 'ok')
                        alert('رنگ ها با موفقیت تغییر کردند')
                }
            })

        }

        function changeChooseBackground(element){
            $(element).parent().next().toggle();
        }

    </script>

@stop