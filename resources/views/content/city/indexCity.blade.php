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

        .suggest{
            padding: 10px;
            cursor: pointer;
        }
        .suggest:hover{
            background: #4dc7bc9e;
            color: white;
            border-radius: 20px;
        }


        .topTab{
            display: flex;
            justify-content: space-around;
            font-size: 20px;
            padding-bottom: 10px;
            margin-bottom: 21px;
        }
        .topTab .tab{
            width: 33%;
            text-align: center;
            border-bottom: solid 1px lightgray;
            padding-bottom: 10px;
            cursor: pointer;
        }
        .topTab .tab.selected{
            font-weight: bold;
            border-bottom: solid 3px var(--koochita-light-green);
            background: #4dc7bc7a;
            border-radius: 10px 10px 0px 0px;
        }
    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between; align-items: center;">
                    <h1> استان، شهر و روستا</h1>
                    <a id="newButtonCit" href="{{url("city/add/city")}}" class="btn btn-success">
                        <span class="changeName">شهر</span> جدید
                    </a>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <div class="topTab">
                    <div class="tab" onclick="changeKind(this, 'country')">کشور</div>
                    <div class="tab" onclick="changeKind(this, 'state')">استان</div>
                    <div class="tab selected" onclick="changeKind(this, 'city')">شهر</div>
                    <div class="tab" onclick="changeKind(this, 'village')">روستا</div>
                </div>

                <center class="row">
                    <div class="col-xs-12" id="cityMode">
                        <label for="placeName">
                            <span class="changeName">شهر</span>
                            مورد نظر
                        </label>
                        <input type="text" onkeyup="search(this)" id="placeName">
                        <div id="result"></div>
                    </div>
                    <div class="col-xs-12">
                        <input type="submit" value="تایید" class="btn btn-primary" name="saveChange">
                    </div>
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        var searchInAjax = null;
        var kindOfPage = 'city';

        function redirect(id) {
            document.location.href = `{{url('/city/edit')}}/${id}/${kindOfPage}`;
        }

        function search(_element) {
            var value = $(_element).val();
            if(searchInAjax != null)
                searchInAjax.abort();

            if(value.trim().length > 1) {
                searchInAjax = $.ajax({
                    type: "GET",
                    url: `{{route('city.search')}}?kind=${kindOfPage}&value=${value}`,
                    success: response => {
                        var newElement = "";
                        response.result.map(item => newElement += `<div class="suggest" onclick='redirect(${item.id})'>${item.text}</div>` );
                        $("#result").html(newElement);
                    }
                });
            }
            else
                $("#result").empty()
        }

        function changeKind(_element, _kind){
            var name = '';
            _element = $(_element);

            kindOfPage = _kind;
            if(kindOfPage === "country")
                name = 'کشور';
            else if(kindOfPage === "state")
                name = 'استان';
            else if(kindOfPage === "city")
                name = 'شهر';
            else
                name = 'روستا';

            _element.parent().find('.selected').removeClass('selected');
            _element.addClass('selected');

            $('.changeName').text(name);
            $('#newButtonCit').attr('href', `{{url("city/add/")}}/${kindOfPage}`);
            $("#result").empty();
            $("#placeName").val('');
        }

    </script>

@stop
