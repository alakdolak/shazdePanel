@extends('layouts.structure')

@section('header')
    @parent
    <style>
        .row {
            direction: rtl;
        }

        .content{
            margin-bottom: 30px;
            float: right;
        }
        .mainContent{
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            border: solid lightgray 1px;
            border-radius: 10px;
        }

        .tab{
            cursor: pointer;
            color: black;
        }
        .chooseTab{
            font-weight: bold;
            font-size: 20px;
        }
        .citySearch{
            background-color: #eaeaea;
            position: absolute;
            width: 100%;
            z-index: 9;
            top: 40px;
            border-radius: 5px;
        }
        .liSearch{
            padding: 5px;
            cursor: pointer;
        }
        .liSearch:hover{
            background-color: #d4ebff;
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
                                    <div class="col-md-4" style="text-align: left">
                                        <a href="{{url('newContent/' . $cityMode . '/' . $id . '/' . $mode)}}">
                                            <button class="btn btn-primary">
                                                جدید
                                            </button>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="جستجو..." onkeyup="search(this.value)">
                                        </div>
                                        <div class="form-group" style="position: relative">
                                            <select id="state" class="form-control" onchange="findCity(this.value)" style="width: 49%; display: inline-block">
                                                @foreach($state as $item)
                                                    <option value="{{$item->id}}" {{$item->id == $stateId ? 'selected' : ''}}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" id="searchCity" class="form-control" placeholder="شهر..." onkeyup="searchCity(this.value)" style="width: 49%; display: inline-block">
                                            <input type="hidden" id="cityId" value="0">
                                            <input type="hidden" id="stateId" value="{{$stateId}}">
                                            <button class="btn btn-primary" onclick="newSearch()">
                                                جستجو
                                            </button>

                                            <div id="citySearch" class="citySearch" >
                                                <ul id="resultCity"></ul>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-4" style="text-align: right">
                                        <h4>
                                            {{$cityMode == 1 ? 'شهر' : 'استان'}}
                                            {{$name}}
                                        </h4>
                                        @if($mode != 10 && $mode != 11)
                                            <a href="{{url('changeContent/' . $id . '/' . $mode . '/' . $cityMode . '/1' )}}">
                                                <button type="button" class="btn btn-success">
                                                    نمایش به صورت جدولی
                                                </button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <div class="row" style="display: flex; justify-content: space-around">
                                    <a href="{{url('/newChangeContent/' . $id . '/1/' . $cityMode )}}">
                                        <div class="tab {{$kind == 'amaken' ? 'chooseTab': ''}}">اماکن</div>
                                    </a>
                                    <a href="{{url('/newChangeContent/' . $id . '/4/' . $cityMode )}}">
                                        <div class="tab {{$kind == 'hotels' ? 'chooseTab': ''}}">هتل</div>
                                    </a>
                                    <a href="{{url('/newChangeContent/' . $id . '/3/' . $cityMode )}}">
                                        <div class="tab {{$kind == 'restaurant' ? 'chooseTab': ''}}">رستوران</div>
                                    </a>
                                    <a href="{{url('/newChangeContent/' . $id . '/6/' . $cityMode )}}">
                                        <div class="tab {{$kind == 'majara' ? 'chooseTab': ''}}">ماجرا</div>
                                    </a>
                                    <a href="{{url('/newChangeContent/' . $id . '/10/' . $cityMode )}}">
                                        <div class="tab {{$kind == 'sogatSanaies' ? 'chooseTab': ''}}">صنایع دستی/سوغات</div>
                                    </a>
                                    <a href="{{url('/newChangeContent/' . $id . '/11/' . $cityMode )}}">
                                        <div class="tab {{$kind == 'mahalifood' ? 'chooseTab': ''}}">غذای محلی</div>
                                    </a>
                                </div>
                                <hr>
                                <div class="row">
                                    @foreach($places as $item)
                                        <div id="place_{{$item->id}}" class="col-sm-3 content">
                                            <div class="mainContent">
                                                <div class="row" style="width: 100%;">
                                                    {{--<div class="col-12" style="height: 200px;">--}}
                                                        {{--<img src="{{$item->pic}}" style="height: 200px;">--}}
                                                    {{--</div>--}}
                                                </div>
                                                <div class="row" style="margin-top: 10px;">
                                                    <div class="col-12">
                                                        <h4>
                                                            {{ $item->name }}
                                                        </h4>
                                                    </div>
                                                    <div class="col-12">
                                                        <a href="{{URL::asset('editContent/' . $mode . '/' . $item->id)}}">
                                                            <button class="btn btn-warning">ویرایش</button>
                                                        </a>
                                                        <a href="{{URL::asset('uploadImgPage/' . $mode . '/' . $item->id)}}">
                                                            <button class="btn btn-primary">ویرایش عکس</button>
                                                        </a>
                                                        <button class="btn btn-danger" onclick="deleteModal({{$item->id}})">حذف</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-body" style="direction: rtl">
                    <h3>حذف <span id="deleteName1" style="font-weight: bold"></span></h3>
                    <p>
                        ایا می خواهید <span id="deleteName2"  style="font-weight: bold"></span> را پاک کنید؟
                    </p>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="text-align: center; display: flex; justify-content: center">
                    <form action="{{route('deletePlace')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="deleteId">
                        <input type="hidden" name="kindPlaceId" value="{{$mode}}">

                        <button type="submit" class="btn btn-danger">بله حذف شود</button>
                    </form>
                    <button class="btn nextStepBtnTourCreation" data-dismiss="modal">خیر</button>
                </div>

            </div>
        </div>
    </div>


    <script>
        var places = {!! $jsonPlace !!};
        var _token = '{{csrf_token()}}';

        function search(_value){
            for(i = 0; i < places.length; i++){
                if(places[i]['name'].includes(_value)){
                    document.getElementById('place_' + places[i]['id']).style.display = 'block';
                }
                else{
                    document.getElementById('place_' + places[i]['id']).style.display = 'none';
                }
            }
        }

        function deleteModal(_id){
            var place;
            for(i = 0; i < places.length; i++){
                if(places[i]['id'] == _id){
                    place = places[i];
                    break;
                }
            }

            document.getElementById('deleteName1').innerText = place.name;
            document.getElementById('deleteName2').innerText = place.name;
            document.getElementById('deleteId').value = place.id;

            $("#deleteModal").modal();
        }

        var city = {!! $city !!};

        function findCity(_value){
            document.getElementById('citySearch').style.display = 'none';
            document.getElementById('stateId').value = _value;
            city = [];
            $.ajax({
                type: 'post',
                url: '{{route("find.city.withState")}}',
                data: {
                    '_token' : _token,
                    'id' : _value
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response != 'nok')
                        city = response;
                }
            })
        }

        function searchCity(_value){
            var text = '';
            document.getElementById('citySearch').style.display = 'none';

            if(_value != ' ' && _value != '  ' && _value != '') {
                for (i = 0; i < city.length; i++) {
                    if (city[i]['name'].includes(_value)) {
                        text += '<li class="liSearch" onclick="chooseCity(' + i + ')">' + city[i].name + '</li>';
                    }
                }

                document.getElementById('citySearch').style.display = 'block';
                document.getElementById('resultCity').innerHTML = text;
            }
            else{
                document.getElementById('cityId').value = 0;
            }
        }

        function chooseCity(i){
            var id = city[i]['id'];
            document.getElementById('cityId').value = id;
            document.getElementById('searchCity').value = city[i]['name'];

            document.getElementById('citySearch').style.display = 'none';
            document.getElementById('resultCity').innerHTML = '';

        }

        var url = '{{url("newChangeContent/")}}';
        function newSearch(){
            var cityId = document.getElementById('cityId').value;
            var stateId = document.getElementById('stateId').value;

            if(cityId == 0){
                window.location.href = url + '/' + stateId + '/' + {{$mode}} + '/0';
            }
            else
                window.location.href = url + '/' + cityId + '/' + {{$mode}} + '/1' ;
        }


    </script>

@stop