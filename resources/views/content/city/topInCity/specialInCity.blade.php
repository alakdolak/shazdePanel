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

        .activities{
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: solid;
        }
        .center{
            display: flex;
            align-items: center;
            justify-content: center;
        }

    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">

        @if(\Session::has('error'))
            <div class="alert alert-danger alert-dismissible " style="direction: rtl">
                <button type="button" class="close" data-dismiss="alert" style="float: left;">&times;</button>
                {{session('error')}}
            </div>
        @endif

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                    <h1>پیشنهادهای ویژه ی {{$city->name}}</h1>
                    <button class="btn btn-warning" onclick="document.location.href = '{{url('topInCity')}}'">بازگشت</button>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row" style="padding: 10px;">
                    <table class="table table-striped">
                        <tbody>
                        <?php
                                $l = 0;
                        ?>
                        @for($i = 0; $i < 4 && $i < count($specialAdvise); $i++, $l++)
                            <tr>
                                <td style="text-align: center">
                                    {{$specialAdvise[$i]->name}}
                                </td>
                                <td style="text-align: center">
                                    {{$specialAdvise[$i]->kindPlaceName}}
                                </td>
                                <td class="center">
                                    <button name="editDescription" class="btn btn-info width-auto center" onclick="newPlace({{$specialAdvise[$i]->id}})" title="ویرایش  مکان " >
                                        <span class="glyphicon glyphicon-edit mg-tp-30per"></span>
                                    </button>
                                </td>
                            </tr>
                        @endfor
                        @if($l != 4)
                            @for($i = $l; $i < 4; $i++)
                                <tr>
                                    <td class="center">
                                        <button class="btn btn-success" onclick="newPlace(0)">افزودن مکان جدید</button>
                                    </td>
                                </tr>
                            @endfor
                        @endif
                        </tbody>
                    </table>
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <div class="modal fade" id="newModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="float: right">
                         مکان جدید
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" style="float: left">&times;</button>
                </div>

                <form id="form" action="{{route('topInCity.store')}}" method="post">
                    <input type="hidden" name="cityId" value="{{$city->id}}">
                    <input type="hidden" id="placeId" name="placeId">
                    <input type="hidden" id="replace" name="replace">

                @csrf
                <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row" style="padding: 20px">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="newName">نام</label>
                                    <input type="text" id="newName" name="name" class="form-control" onkeyup="findPlace(this.value)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="newName">نوع مکان</label>
                                    <select id="kindPlaceId" name="kindPlaceId" class="form-control" onchange="clearInputs()">
                                        <option value="0">...</option>
                                    @foreach($placeId as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <tbody id="resultBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer center" >
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="submit" class="btn btn-success">ایجاد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        var _token = '{!! csrf_token() !!}';
        var cityId = {{$city->id}};


        function newPlace(_kind){
            $('#newModal').modal('toggle');
            document.getElementById('replace').value = _kind;
        }

        function findPlace(_value){
            var kind = document.getElementById('kindPlaceId').value;

            if(kind == 0){
                alert('ابتدا نوع مکان را مشخص کنید.')
            }
            else{
                $.ajax({
                    type: 'post',
                    url: '{{route("findInCity")}}',
                    data: {
                        '_token' : _token,
                        'name'   : _value,
                        'kind'   : kind,
                        'cityId' : cityId
                    },
                    success: function (response){
                        console.log(JSON.parse(response));
                        response = JSON.parse(response);
                        var text = '';
                        for(i = 0; i < response.length; i++){
                            text += '\n' +
                                '<tr>\n' +
                                '<td>' + response[i]["name"] + '</td>\n' +
                                '<td>\n' +
                                '<button type="button" class="btn btn-success" onclick="choosePlace(' + response[i]["id"] + ')">انتخاب</button>\n' +
                                '</td>\n' +
                                '</tr>';
                        }
                        document.getElementById('resultBody').innerHTML = text;
                    }
                })
            }
        }

        function choosePlace(_id){
            document.getElementById('placeId').value = _id;
            $('#form').submit();
        }

        function clearInputs(){
            document.getElementById('newName').value = '';
            document.getElementById('resultBody').innerHTML = '';
        }
    </script>
@stop