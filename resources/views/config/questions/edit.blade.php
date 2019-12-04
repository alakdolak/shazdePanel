@extends('layouts.structure')

@section('header')
    @parent

    <link rel="stylesheet" href="{{URL::asset('css/multiselect/semantic.css')}}">
    <script src="{{URL::asset('js/multiselect/semantic.js')}}"></script>

    <style>
        *{
            font-family: IRANSans;
        }
        .ui.selection.dropdown .menu > .item{
            direction: rtl;
            text-align: right;
        }
        .ui.multiple.search.dropdown, .ui.multiple.search.dropdown > input.search{
            text-align: right;
            border-color: black !important;
        }
        .row {
            direction: rtl;
            text-align: right;

        }
        .chooseCity{
            padding: 2px 10px;
            cursor: pointer;
        }
        .chooseCity:hover{
            background-color: darkgray;
        }
        option{
            font-weight: bold !important;
        }
        th{
            text-align: right;
        }
        td{
            text-align: right;
        }
        .mainContainer{
            background-color: white;
            margin: 0px 10px;
            padding: 20px;
            border-radius: 11px;
            margin-top: 15px;
        }
    </style>
@stop

@section('content')
    <div class="col-md-12">

        @if(\Session::has('error'))
            <div class="alert alert-danger alert-dismissible " style="direction: rtl">
                <button type="button" class="close" data-dismiss="alert" style="float: left;">&times;</button>
                {{session('error')}}
            </div>
        @endif

        <div class="sparkline8-hd">
            <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                <h1>ویرایش سوال</h1>
            </div>
        </div>


        <div class="container-fluid mainContainer">
            <div class="row" style="padding: 20px">
                <div class="form-group">
                    <label for="newQuestionText">متن سوال</label>
                    <input type="text" id="newQuestionText" name="newQuestionText" class="form-control" value="{{$question->description}}" required>
                </div>
            </div>

            <div class="row" style="padding: 20px">
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="newName">نوع پاسخ را مشخص کنید؟</label>
                        <select class="form-control" name="ansType" id="ansType" onchange="changeAnsType(this.value)">
                            <option value="text" {{$question->ansType == 'text' ? 'selected' : ''}}>متنی</option>
                            <option value="multi" {{$question->ansType == 'multi' ? 'selected' : ''}}>4 گزینه ای</option>
                            <option value="rate" {{$question->ansType == 'rate' ? 'selected' : ''}}>درجه ای</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="questionAnsDiv" class="row" style="display: {{$question->ans == null ? 'none' : 'block'}};">
                <div class="col-sm-3 form-group">
                    <label for="ans4">پاسخ 4</label>
                    <input type="text" class="form-control" id="ans4" name="ans4" value="{{isset($question->ans[3]) ? $question->ans[3]->ans : ''}}">
                </div>
                <div class="col-sm-3 form-group">
                    <label for="ans3">پاسخ 3</label>
                    <input type="text" class="form-control" id="ans3" name="ans3" value="{{isset($question->ans[2]) ? $question->ans[2]->ans : ''}}">
                </div>
                <div class="col-sm-3 form-group">
                    <label for="ans2">پاسخ 2</label>
                    <input type="text" class="form-control" id="ans2" name="ans2" value="{{isset($question->ans[1]) ? $question->ans[1]->ans : ''}}">
                </div>
                <div class="col-sm-3 form-group">
                    <label for="ans1">پاسخ 1</label>
                <input type="text" class="form-control" id="ans1" name="ans1" value="{{isset($question->ans[0]) ? $question->ans[0]->ans : ''}}">
                </div>
            </div>

            <div class="row" style="padding: 20px">
                <div class="form-group">
                    <label for="kindPlaceIdInput">فقط در مکان خاصی پرسیده شود؟</label>
                    <select class="ui fluid search dropdown" multiple="" name="kindPlaceIdInput" id="kindPlaceIdInput">
                        <option value="0" {{isset($question->kindPlaceId[0]) ? 'selected' : ''}}>همه مکان ها</option>
                        @foreach($kindPlace as $item)
                            <option value="{{$item->id}}" {{isset($question->kindPlaceId[$item->id]) ? 'selected' : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row" style="padding: 20px">
                <div class="form-group">
                    <label for="stateIdInput">فقط در استان خاصی پرسیده شود؟</label>
                    <select class="ui fluid search dropdown" multiple="" name="stateIdInput" id="stateIdInput" onchange="getCity()  ">
                        <option value="0" {{isset($question->state[0]) ? 'selected' : ''}}>همه استان ها</option>
                        @foreach($state as $item)
                            <option value="{{$item->id}}" {{isset($question->state[$item->id]) ? 'selected' : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="cityDiv">
                @if(!isset($question->state[0]))
                    @foreach($question->state as $item)
                        <div id="city_{{$item[0]->stateId}}">
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="allCity_{{$item[0]->stateId}}">
                                        بله در تمام شهرها
                                    </label>
                                    <input type="checkbox" name="allCity[]" value="1" id="allCity_{{$item[0]->stateId}}" onchange="showChooseCity({{$item[0]->stateId}})" {{$item[0]->cityId == 0 ? 'checked' : ''}}>
                                    <input type="hidden" name="allCityId[]" value="{{$item[0]->stateId}}">
                                </div>
                                <div class="col-sm-9" style="font-size: 20px; font-weight: bold">
                                    ایا در تمام شهرهای استان {{$item[0]->stateName}} پرسیده شود؟
                                </div>
                                <div id="chooseCity_{{$item[0]->stateId}}" class="col-sm-12" style="display: {{$item[0]->cityId == 0 ? 'none' : 'block'}};">
                                    <div class="container-fluid">
                                        <div id="citySection_{{$item[0]->stateId}}" class="row" style="display: flex; flex-wrap: wrap">
                                            @if($item[0]->cityId == 0)
                                                <div class="col-sm-4" style="position: relative">
                                                <label for="city1">شهر1</label>
                                                <input type="text" id="cityInput_{{$item[0]->stateId}}_1" class="form-control" onkeyup="findCity({{$item[0]->stateId}}, 1, this.value)">
                                                <input type="hidden" id="cityInputId_{{$item[0]->stateId}}_1" value="0">
                                                <div id="searchResult_{{$item[0]->stateId}}_1" style="width: 100%; top: 60px; background-color: lightgrey; color: black; display: none; position: absolute; z-index: 9"></div>
                                            </div>
                                            @else
                                                @for($i = 0; $i < count($item); $i++)
                                                    <div class="col-sm-4" style="position: relative">
                                                        <label for="city1">شهر{{$i+1}}</label>
                                                        <input type="text" id="cityInput_{{$item[$i]->stateId}}_{{$i+1}}" class="form-control" onkeyup="findCity({{$item[$i]->stateId}}, {{$i+1}}, this.value)" value="{{$item[$i]->cityName}}">
                                                        <input type="hidden" id="cityInputId_{{$item[$i]->stateId}}_{{$i+1}}" value="{{$item[$i]->cityId}}">
                                                        <div id="searchResult_{{$item[$i]->stateId}}_{{$i+1}}" style="width: 100%; top: 60px; background-color: lightgrey; color: black; display: none; position: absolute; z-index: 9"></div>
                                                    </div>
                                                @endfor
                                            @endif
                                        </div>
                                        <div class="row" style="margin-top: 15px">
                                            <button type="button" class="btn btn-primary" onclick="newCity({{$item[0]->stateId}})">شهر جدید</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
               @else

                @endif
            </div>

            <hr>
            <div class="row" style="margin: 0px; text-align: center; justify-content: center; padding: 20px 0px">
                <a href="{{route('questions.index')}}">
                    <button type="button" class="btn btn-secondary">بازگشت</button>
                </a>
                <button type="button" class="btn btn-success" onclick="checkNewQuestion()">ایجاد</button>
            </div>
        </div>
    </div>

    <form id="newQuestionForm" action="{{route('questions.doEdit')}}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{$question->id}}">
        <input type="hidden" name="question" id="formQuestion">
        <input type="hidden" name="kindPlaceId" id="formKindPlaceId">
        <input type="hidden" name="ansType" id="formAnsType">
        <input type="hidden" name="ans" id="formAns">
        <input type="hidden" name="state" id="formState">
        <input type="hidden" name="city" id="formCity">
    </form>

    <script>
        var findCityUrl = '{{route("find.city.withState")}}';
        var _token = '{{csrf_token()}}';
        var city = [];
        var selectedCity = [];
        var stateId = {!! $stateId !!};
        var cityCount = {!! $cityCount !!};
        var cityCountInState = [];
        var state = {!! $state !!};
        var showCity = [];

        function getCity(){
            // document.getElementById('cityDiv').innerHTML = '';
            var showCity2 = [];

            $('#stateIdInput :selected').each(function(i, sel){
                var stateName = '';
                showCity2[showCity2.length] = $(sel).val();

                if($(sel).val() != 0) {
                    if($('#city_' + $(sel).val()).length == 0) {
                        for (i = 0; i < state.length; i++) {
                            if (state[i]['id'] == $(sel).val()) {
                                stateName = state[i]['name'];
                                break;
                            }
                        }

                        if (!selectedCity.includes($(sel).val())) {
                            $.ajax({
                                type: 'post',
                                url: findCityUrl,
                                data: {
                                    '_token': _token,
                                    'id': $(sel).val()
                                },
                                success: function (response) {
                                    response = JSON.parse(response);
                                    if (response != 'nok') {
                                        city[$(sel).val()] = response;
                                        cityCountInState[$(sel).val()] = 1;
                                        createCityRow($(sel).val(), stateName);
                                    }
                                }
                            });
                        }
                        else {
                            cityCountInState[$(sel).val()] = 1;
                            createCityRow($(sel).val(), stateName);
                        }
                    }
                }
            });

            for(i = 0; i < showCity.length; i++){
                same = false;
                for(j = 0; j < showCity2.length; j++){
                    if(showCity[i] == showCity2[j]){
                        console.log(showCity[i])
                        same = true;
                        break;
                    }
                }

                if(!same){
                    console.log('remove');
                    $('#city_' + showCity[i]).remove();
                }
            }
            showCity = showCity2;
        }

        function createCityRow(_id, _stateName){
            text = '<div id="city_' + _id + '">\n' +
                '                                <hr>\n' +
                '                                <div class="row">\n' +
                '                                    <div class="col-sm-3">\n' +
                '                                        <label for="allCity_' + _id + '">\n' +
                '                                            بله در تمام شهرها\n' +
                '                                        </label>\n' +
                '                                        <input type="checkbox" name="allCity[]" value="1" id="allCity_' + _id + '" onchange="showChooseCity(' + _id + ')" checked>\n' +
                '                                        <input type="hidden" name="allCityId[]" value="' + _id + '">\n' +
                '                                    </div>\n' +
                '                                    <div class="col-sm-9" style="font-size: 20px; font-weight: bold">\n' +
                '                                        ایا در تمام شهرهای استان ' + _stateName + ' پرسیده شود؟\n' +
                '                                    </div>\n' +
                '                                    <div id="chooseCity_' + _id + '" class="col-sm-12" style="display: none;">\n' +
                '                                        <div class="container-fluid">\n' +
                '                                            <div id="citySection_' + _id + '" class="row" style="display: flex; flex-wrap: wrap">\n' +
                '                                                <div class="col-sm-4" style="position: relative">\n' +
                '                                                    <label for="city1">شهر1</label>\n' +
                '                                                    <input type="text" id="cityInput_' + _id + '_1" class="form-control" onkeyup="findCity(' + _id + ', 1, this.value)">\n' +
                '                                                    <input type="hidden" id="cityInputId_' + _id + '_1" value="0">\n' +
                '                                                    <div id="searchResult_' + _id + '_1" style="width: 100%; top: 60px; background-color: lightgrey; color: black; display: none; position: absolute; z-index: 9"></div>' +
                '                                                </div>\n' +
                '                                            </div>\n' +
                '                                            <div class="row" style="margin-top: 15px">\n' +
                '                                                <button type="button" class="btn btn-primary" onclick="newCity(' + _id + ')">شهر جدید</button>\n' +
                '                                            </div>\n' +
                '                                        </div>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                            </div>';

            $('#cityDiv').append(text);
        }

        function newCity(_id){
            cityCountInState[_id]++;
            var text ='<div class="col-sm-4" style="position: relative">\n' +
                '<label for="cityInput_' + _id + '_' + cityCountInState[_id] + '">شهر' + cityCountInState[_id] + '</label>\n' +
                '<input type="text" id="cityInput_' + _id + '_' + cityCountInState[_id] + '" class="form-control" onkeyup="findCity(' + _id + ', ' + cityCountInState[_id] + ', this.value)">\n' +
                '<input type="hidden" id="cityInputId_' + _id + '_' + cityCountInState[_id] + '">\n' +
                '<div id="searchResult_' + _id + '_' + cityCountInState[_id] + '" style="width: 100%; z-index: 9; top: 60px; background-color: lightgrey; color: black; display: none; position: absolute"></div>' +
                '</div>\n';

            $('#citySection_' + _id).append(text);
        }

        function findCity(_id, _section, _value){
            if(_value != '' && _value != ' ') {
                var text = '<ul>';
                for (i = 0; i < city[_id].length; i++) {
                    if (city[_id][i]['name'].includes(_value)) {
                        text += '<li class="chooseCity" onclick="chooseCity(' + city[_id][i]['id'] + ', ' + _id + ',' + _section + ', this)">' + city[_id][i]['name'] + '</li>';
                    }
                }
                text += '</ul>';
                document.getElementById('searchResult_' + _id + '_' + _section).innerHTML = text;
                document.getElementById('searchResult_' + _id + '_' + _section).style.display = 'block';
            }
            else {
                document.getElementById('searchResult_' + _id + '_' + _section).innerHTML = '';
                document.getElementById('searchResult_' + _id + '_' + _section).style.display = 'none';
            }
        }

        function chooseCity(_chooseId, _id, _section, _element){
            var txt = _element.innerText;
            document.getElementById('cityInput_' + _id + '_' + _section).value = txt;
            document.getElementById('cityInputId_' + _id + '_' + _section).value = _chooseId;

            document.getElementById('searchResult_' + _id + '_' + _section).innerHTML = '';
            document.getElementById('searchResult_' + _id + '_' + _section).style.display = 'none';
        }

        function showChooseCity(_id){
            if(document.getElementById('allCity_' + _id).checked)
                document.getElementById('chooseCity_' + _id).style.display = 'none';
            else
                document.getElementById('chooseCity_' + _id).style.display = 'block';

        }

        function changeAnsType(_value){
            if(_value == 'multi')
                document.getElementById('questionAnsDiv').style.display = 'block';
            else
                document.getElementById('questionAnsDiv').style.display = 'none';
        }

        function checkNewQuestion(){
            var question = document.getElementById('newQuestionText').value;
            var ansType = document.getElementById('ansType').value;
            var ans = [];
            var kindPlaceId = [];
            var stateId = [];
            var hasCity = true;
            var cities = [];

            if(question == null || question == ''){
                alert('متن سوال را پر کنید');
                return;
            }

            if(ansType == 'multi'){
                ans[0] = document.getElementById('ans1').value;
                ans[1] = document.getElementById('ans2').value;
                ans[2] = document.getElementById('ans3').value;
                ans[3] = document.getElementById('ans4').value;
            }

            $('#kindPlaceIdInput :selected').each(function(i, sel) {
                kindPlaceId[kindPlaceId.length] = $(sel).val();
            });

            $('#stateIdInput :selected').each(function(i, sel) {
                stateId[stateId.length] = $(sel).val();
            });

            if(kindPlaceId.includes('0') || kindPlaceId.length == 0)
                kindPlaceId = ['0'];

            if(stateId.includes('0') || stateId.length == 0) {
                stateId = ['0'];
                hasCity = false;
            }

            if(hasCity){
                for(i = 0; i < stateId.length; i++){
                    var arrayCity = [];
                    if(document.getElementById('allCity_' + stateId[i]).checked)
                        cities[i] = '0';
                    else {
                        for(j = 1; j <= cityCountInState[stateId[i]]; j++){
                            var cityId = document.getElementById('cityInputId_' + stateId[i] + '_' + j).value;
                            var cityName = document.getElementById('cityInput_' + stateId[i] + '_' + j).value;

                            if(cityId != 0 && cityName != '' && cityName != null){
                                arrayCity[arrayCity.length] = cityId;
                            }

                        }
                        if(arrayCity.length == 0)
                            cities[i] = '0';
                        else
                            cities[i] = arrayCity;
                    }

                }
            }

            document.getElementById('formQuestion').value = question;
            document.getElementById('formKindPlaceId').value = JSON.stringify(kindPlaceId);
            document.getElementById('formAnsType').value = ansType;
            document.getElementById('formAns').value = JSON.stringify(ans);
            document.getElementById('formState').value = JSON.stringify(stateId);
            document.getElementById('formCity').value = JSON.stringify(cities);

            $('#newQuestionForm').submit();
        }

        function init(index){

            var id = stateId[index];
            showCity[showCity.length] = id;
            $.ajax({
                type: 'post',
                url: findCityUrl,
                data: {
                    '_token': _token,
                    'id': id
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if (response != 'nok') {
                        city[id] = response;
                        cityCountInState[id] = cityCount[index];

                        if(cityCountInState[id] == 0)
                            cityCountInState[id] = 1;

                        if(index+1 < stateId.length)
                            init(index + 1);
                    }
                }
            });
        }

        $('#stateIdInput').dropdown();
        $('#kindPlaceIdInput').dropdown();

        if(stateId.length > 0 && stateId[0] != 0)
            init(0);

    </script>

@stop