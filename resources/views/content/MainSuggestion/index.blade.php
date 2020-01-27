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
        .result{
            cursor: pointer;
            font-size: 20px;
            padding: 5px 20px;
        }
        .result:hover{
            background-color: #bbbbbb;
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
                    <h1>پیشنهادهای صفحه‌ی اول</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row" style="padding: 10px;">
                    <button class="btn btn-primary width-auto margin_bot" onclick="showModal('محبوب‌ترین غذا‌ها')">محبوب‌ترین غذا‌ها</button>
                    <button class="btn btn-primary width-auto margin_bot" onclick="showModal('سفر طبیعت‌گردی')">سفر طبیعت‌گردی</button>
                    <button class="btn btn-primary width-auto margin_bot" onclick="showModal('محبوب‌ترین رستوران‌ها')">محبوب‌ترین رستوران‌ها</button>
                    <button class="btn btn-primary width-auto margin_bot" onclick="showModal('سفر تاریخی-فرهنگی')">سفر تاریخی-فرهنگی</button>
                    <button class="btn btn-primary width-auto margin_bot" onclick="showModal('مراکز خرید')">مراکز خرید</button>

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>


    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modalHeader"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group" style="padding: 10px 40px 0px 40px;">
                                <label for="searchSection">جستجو:</label>
                                <input type="text" class="form-control" id="searchInput" onkeyup="search(this.value)">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div id="result" class="result" style="background-color: lightgrey; width: 100%;"></div>
                        </div>

                    </div>

                    <table class="table table-striped" style="margin-top: 20px; direction: rtl">
                        <tbody id="modalTable"></tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">خروج</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        var nowSection;
        var suggestion = {!! $suggest !!};

        function showModal(section){
            nowSection = section;
            document.getElementById('modalHeader').innerText = section;
            document.getElementById('result').innerHTML = '';
            document.getElementById('searchInput').value = '';
            document.getElementById('modalTable').innerHTML = '';

            for(i = 0; i < suggestion.length; i++){
                if(suggestion[i]['section'] == section){
                    var text =  '<tr id="row' + suggestion[i]['id'] + '">\n' +
                                '<td style="text-align: center">' + suggestion[i]["place"]["name"] + '</td>\n' +
                                '<td class="center">\n' +
                                '<button name="deleteDescription" class="btn btn-danger width-auto center" onclick="deleteRecord(' + suggestion[i]['id'] + ')">\n' +
                                '<span class="glyphicon glyphicon-remove mg-tp-30per"></span>\n' +
                                '</button>\n' +
                                '</td>\n' +
                                '</tr>';

                        $('#modalTable').append(text);
                }
            }

            $('#myModal').modal('show');
        }

        function search(value){
            if(value.trim().length > 0) {
                $.ajax({
                    type: 'post',
                    url: '{{route("mainSuggestion.search")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        section: nowSection,
                        value: value,
                    },
                    success: function (response) {
                        document.getElementById('result').innerHTML = '';
                        response = JSON.parse(response);
                        for (i = 0; i < response.length; i++) {
                            var text = '<div class="result" onclick="chooseResult(\'' + response[i]["id"] + '\', \'' + response[i]["kindPlaceId"] + '\', \'' + response[i]["name"] + '\')">' + response[i]["name"] + '</div>\n';
                            $('#result').append(text);
                        }
                    }
                })
            }
            else
                document.getElementById('result').innerHTML = '';
        }

        function chooseResult(id, kindPlaceId, name){
            $.ajax({
                type: 'post',
                url: '{{route("mainSuggestion.chooseId")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    section: nowSection,
                    id: id,
                    kindPlaceId: kindPlaceId,
                },
                success: function(response){
                    response = JSON.parse(response);
                    if(response[0] == 'ok'){
                        var text =  '<tr id="row' + response[1] + '">\n' +
                            '<td style="text-align: center">' + name + '</td>\n' +
                            '<td class="center">\n' +
                            '<button name="deleteDescription" class="btn btn-danger width-auto center" onclick="deleteRecord(' + response[1] + ')">\n' +
                            '<span class="glyphicon glyphicon-remove mg-tp-30per"></span>\n' +
                            '</button>\n' +
                            '</td>\n' +
                            '</tr>';

                        $('#modalTable').append(text);
                    }
                }
            })
        }

        function deleteRecord(id){
            $.ajax({
                type: 'post',
                url: '{{route("mainSuggestion.deleteRecord")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                },
                success: function(response){
                    if(response == 'ok')
                        $('#row' + id).remove();
                }
            })
        }
    </script>

@stop