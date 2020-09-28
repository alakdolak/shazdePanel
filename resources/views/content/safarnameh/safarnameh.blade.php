@extends('layouts.structure')

@section('header')
    @parent

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="{{URL::asset('js/DataTable/jquery.dataTables.js')}}" defer></script>

    <style>
        th{
            text-align: right;
            min-width: 100px;
        }
        .col-xs-6 {
            float: right;
        }
        .checkBoxTd {
            position: relative;
            cursor: pointer;
            font-size: 22px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .checkBoxTd input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        .checkmark {
            position: absolute;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }
        .checkBoxTd:hover input ~ .checkmark {
            background-color: #ccc;
        }
        .checkBoxTd input:checked ~ .checkmark {
            background-color: #2196F3;
        }
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }
        .checkBoxTd input:checked ~ .checkmark:after {
            display: block;
        }
        .checkBoxTd .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        .arrow {
            border: solid black;
            border-width: 0 3px 3px 0;
            display: inline-block;
            padding: 3px;
        }
        .down {
            transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
        }

        .SafarnamehTabs{
            display: flex;
            justify-content: center;
            border-bottom: solid 2px #15b3ac;
            margin-bottom: 15px;
        }
        .SafarnamehTabs .tabs{
            ont-size: 23px;
            cursor: pointer;
            padding: 10px 20px;
        }
        .SafarnamehTabs .tabs.active{
            color: white;
            background: #15b3ac;
            border-radius: 10px 10px 0px 0px;
        }
        .dataTables_scrollHeadInner{
            width: 100% !important;
        }
        .dataTables_scrollFootInner{
            width: 100% !important;
        }

    </style>
@stop

@section('content')

    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>سفرنامه ها</h1>
                    <div>
                        <button onclick="$('#filtersDiv').slideToggle()" class="btn btn-success">فیلترها</button>
                        <button onclick="document.location.href = '{{route('safarnameh.new')}}'" class="btn btn-primary">افزودن سفرنامه جدید</button>
                        {{--<button class="btn btn-primary">افزودن دسته ای سفرنامه</button>--}}
                    </div>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages" style="height: auto!important;">

                <div class="col-md-12" style="direction: rtl; margin-bottom: 40px;  border: solid lightgrey 1px;">
                    <div id="filtersDiv" class="row" style="display: none;">
                        <div class="container">
                            <div class="row" style="padding: 20px; text-align: right">
                                <div class="col-md-4" id="titleSearch" style="float: right; margin-bottom: 10px"></div>
                                <div class="col-md-4" id="creatorSearch" style="float: right; margin-bottom: 10px"></div>
                                <div class="col-md-4" id="categorySearch" style="float: right; margin-bottom: 10px"></div>
                                <div class="col-md-4" id="TagSearch" style="float: right; margin-bottom: 10px;"></div>
                                <div class="col-md-4" id="statusSearch" style="float: right;"></div>

                        </div>
                    </div>

                </div>

                <center class="col-xs-12" style="direction: rtl;">

                    <div class="row SafarnamehTabs">
                        <div class="tabs active" onclick="showThisTabs(this, 'new')">تازه ها</div>
                        <div class="tabs" onclick="showThisTabs(this, 'old')">تایید شده ها</div>
                    </div>
                    @if(count($safarnameh) == 0)
                        <p>سفرنامهی موجود نیست</p>
                    @else
                        <div id="confirmedTable" style="display: none">
                            <table id="mainTable" class="table">
                                <thead class="thead-dark" style="background: black; color: white;">
                                <tr>
                                    <th style="text-align: right">عنوان </th>
                                    <th style="text-align: right">نویسنده </th>
                                    <th style="text-align: right; min-width: 150px">وضعیت </th>
                                    <th style="text-align: right">اخرین ویرایش </th>
                                    <th style="text-align: right"> سفرنامه داغ </th>
                                    <th style="text-align: right"> سفرنامه بنر</th>
                                    <th style="min-width: 100px"></th>
                                </tr>
                                </thead>
                                <tbody id="tBody">
                                @foreach($safarnameh as $item)
                                    <tr id="safarnameh_{{$item->id}}">
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->user->username}}</td>
                                        <td style="color: {{$item->confirm == 1 ? 'green' : 'red'}}">
                                            {{$item->status}}
                                            {{isset($item->futureDate) ? $item->futureDate : ''}}
                                        </td>
                                        <td>{{$item->lastUpdate}}</td>
                                        <td style="width: 100px">
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeFav({{$item->id}}, this)" {{($item->favorited) ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeBanner({{$item->id}}, this)" {{($item->bannered) ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td style="display: flex">
                                            <a href='{{route('safarnameh.edit', ['id' => $item->id])}}'>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="deleteSafarnameh('{{$item->id}}')" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="text-align: right">
                                        عنوان
                                    </th>
                                    <th style="text-align: right">
                                        نویسنده
                                    </th>
                                    <th style="text-align: right; min-width: 150px">
                                        وضعیت
                                    </th>
                                    <th style="text-align: right">
                                        اخرین ویرایش
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div id="noneConfirmedTable">
                            <table id="noneConfirmMainTable" class="table">
                                <thead class="thead-dark" style="background: black; color: white;">
                                <tr>
                                    <th style="text-align: right">عنوان </th>
                                    <th style="text-align: right">نویسنده </th>
                                    <th style="text-align: right; min-width: 150px">وضعیت </th>
                                    <th style="text-align: right">اخرین ویرایش </th>
                                    <th style="text-align: right"> سفرنامه داغ </th>
                                    <th style="text-align: right"> سفرنامه بنر</th>
                                    <th style="min-width: 100px"></th>
                                </tr>
                                </thead>
                                <tbody id="tBody">
                                @foreach($noneConfirmSafar as $item)
                                    <tr id="safarnameh_{{$item->id}}">
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->user->username}}</td>
                                        <td style="color: {{$item->confirm == 1 ? 'green' : 'red'}}">
                                            {{$item->status}}
                                            {{isset($item->futureDate) ? $item->futureDate : ''}}
                                        </td>
                                        <td>{{$item->lastUpdate}}</td>
                                        <td style="width: 100px">
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeFav({{$item->id}}, this)" {{($item->favorited) ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeBanner({{$item->id}}, this)" {{($item->bannered) ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td style="display: flex">
                                            <a href='{{route('safarnameh.edit', ['id' => $item->id])}}'>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="deleteSafarnameh('{{$item->id}}')" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="text-align: right">
                                        عنوان
                                    </th>
                                    <th style="text-align: right">
                                        نویسنده
                                    </th>
                                    <th style="text-align: right; min-width: 150px">
                                        وضعیت
                                    </th>
                                    <th style="text-align: right">
                                        اخرین ویرایش
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                </center>

            </div>
        </div>

    </div>

    <script>
        var safarnameh = {!! $safarnameh !!}

        function showThisTabs(_element, _kind){
            $(_element).parent().find('.active').removeClass('active');
            $(_element).addClass('active');

            if(_kind == 'new'){
                $('#confirmedTable').hide();
                $('#noneConfirmedTable').show();
            }
            else{
                $('#confirmedTable').show();
                $('#noneConfirmedTable').hide();
            }
        }

        function deleteSafarnameh(safarnamehId) {
            $.ajax({
                type: 'post',
                url: '{{route('safarnameh.delete')}}',
                data: { 'safarnamehId': safarnamehId },
                success: function (res) {
                    if(res == "ok")
                        $("#safarnameh_" + safarnamehId).remove();
                }
            });
        };

        function deleteFromFavoriteSafarnameh(safarnamehId) {

            $.ajax({
                type: 'post',
                url: '{{route('deleteFromFavoriteSafarnameh')}}',
                data: {
                    'safarnamehId': safarnamehId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('از سفرنامه ‌های منتخب حذف شد')
                }
            });
        }

        function deleteFromBannerSafarnameh(safarnamehId) {

            $.ajax({
                type: 'post',
                url: '{{route('deleteFromBannerSafarnameh')}}',
                data: {
                    'safarnamehId': safarnamehId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('از سفرنامه ‌های بنر حذف شد')
                }
            });
        }

        function addToBannerSafarnameh(safarnamehId) {

            $.ajax({
                type: 'post',
                url: '{{route('addToBannerSafarnameh')}}',
                data: {
                    'safarnamehId': safarnamehId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('به سفرنامه ‌های بنر افزوده شد')
                }
            });

        }

        function addToFavoriteSafarnameh(safarnamehId) {

            $.ajax({
                type: 'post',
                url: '{{route('addToFavoriteSafarnameh')}}',
                data: {
                    'safarnamehId': safarnamehId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('به سفرنامه ‌های منتخب افزوده شد')
                }
            });

        }

        function changeFav(id, element){
            if($(element).prop('checked'))
                addToFavoriteSafarnameh(id);
            else
                deleteFromFavoriteSafarnameh(id);
        }

        function changeBanner(id, element){
            if($(element).prop('checked'))
                addToBannerSafarnameh(id);
            else
                deleteFromBannerSafarnameh(id);
        }

        function deleteGardesh(id, element){
            $.ajax({
                type: 'post',
                url: '{{route('deleteGardesh')}}',
                data:{
                    _token: '{{csrf_token()}}',
                    id: id
                },
                success: function (response){
                    if(response == 'ok')
                        $(element).parent().parent().remove();
                }
            })
        }

        $(document).ready(function() {
            tables = ['noneConfirmMainTable', 'mainTable'];
            for(let x of tables){
                $(`#${x} thead tr:eq(0) th`).each( function (i) {
                    var title = $(this).text();
                    var trimTitle = title.trim();
                    switch (trimTitle){
                        case 'عنوان':
                            $('#titleSearch').html( '<label for="titleSearchInput">عنوان</label><input id="titleSearchInput" type="text" style="color: black; width: 150px"/>' );
                            $('#titleSearchInput').on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table
                                        .column(i)
                                        .search( this.value )
                                        .draw();
                                }
                            } );
                            break;
                        case 'نویسنده':
                            $('#creatorSearch').html( '<label for="creatorSearchInput">نویسنده</label><input id="creatorSearchInput" type="text" style="color: black; width: 150px"/>' );
                            $('#creatorSearchInput').on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table
                                        .column(i)
                                        .search( this.value )
                                        .draw();
                                }
                            } );
                            break;
                        case 'دسته بندی ها':
                            $('#categorySearch').html( '<label for="categorySearchInput">دسته بندی ها</label><input id="categorySearchInput" type="text" style="color: black; width: 150px"/>' );
                            $('#categorySearchInput').on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table
                                        .column(i)
                                        .search( this.value )
                                        .draw();
                                }
                            } );
                            break;
                        case 'برچسپ ها':
                            $('#TagSearch').html( '<label for="TagSearchInput">برچسپ ها</label><input id="TagSearchInput" type="text" style="color: black; width: 150px"/>' );
                            $('#TagSearchInput').on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table
                                        .column(i)
                                        .search( this.value )
                                        .draw();
                                }
                            } );
                            break;
                        case 'وضعیت':
                            var options = '<option></option>';
                            options += '<option>پیش نویس</option>';
                            options += '<option>در آینده منتشر می شود</option>';
                            options += '<option>منتشر شده</option>';

                            $('#statusSearch').html( '<label for="statusSearchInput">وضعیت</label><select id="statusSearchInput" type="text" style="color: black; width: 150px">' + options + '</select>' );
                            $('#statusSearchInput').on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table
                                        .column(i)
                                        .search( this.value )
                                        .draw();
                                }
                            } );
                            break;
                    }
                } );

                var table = $('#'+x).DataTable( {
                    "order": [[ 5, "desc" ]],
                    "scrollY": 400,
                    "scrollX": true,
                    orderCellsTop: true,
                    fixedHeader: true,
                } );
            }
        });
    </script>
@stop
