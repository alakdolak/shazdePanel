@extends('layouts.structure')

@section('header')
    @parent

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="{{URL::asset('js/DataTable/jquery.dataTables.js')}}" defer></script>

@stop

@section('content')

    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>اخبار</h1>
                    <div>
                        <button onclick="deleteAllTops()" class="btn btn-danger">پاک کردن تمامی برگزیده ها</button>
                        <button onclick="$('#filtersDiv').slideToggle()" class="btn btn-success">فیلترها</button>
                        <button onclick="document.location.href = '{{route('news.new')}}'" class="btn btn-primary">افزودن خبر جدید</button>
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

                <div class="col-xs-12" style="direction: rtl;">

                    <div class="row SafarnamehTabs">
                        <div class="tabs active" onclick="showThisTabs(this, 'new')">تازه ها</div>
                        <div class="tabs" onclick="showThisTabs(this, 'old')">تایید شده ها</div>
                    </div>
                    @if(count($news) == 0)
                        <p>خبری موجود نیست</p>
                    @else
                        <div id="confirmedTable" style="display: none">
                            <table id="mainTable" class="table">
                                <thead class="thead-dark" style="background: black; color: white;">
                                <tr>
                                    <th style="text-align: right">عنوان </th>
                                    <th style="text-align: right">نویسنده </th>
                                    <th style="text-align: right; min-width: 150px">وضعیت </th>
                                    <th style="text-align: right">اخرین ویرایش </th>
                                    <th style="text-align: right">برگزیده</th>
                                    <th style="min-width: 100px"></th>
                                </tr>
                                </thead>
                                <tbody id="tBody">
                                @foreach($news as $item)
                                    <tr id="news_{{$item->id}}" style="text-align: right">
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->user->username}}</td>
                                        <td style="color: {{$item->confirm == 1 ? 'green' : 'red'}}">
                                            {{$item->status}}
                                            {{isset($item->futureDate) ? $item->futureDate : ''}}
                                        </td>
                                        <td>{{$item->lastUpdate}}</td>
                                        <td>
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeInTop({{$item->id}}, this)" {{$item->topNews == 1 ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td style="display: flex">
                                            <a href='{{route('news.edit', ['id' => $item->id])}}'>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="deleteNews('{{$item->id}}')" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="text-align: right">عنوان</th>
                                    <th style="text-align: right">نویسنده</th>
                                    <th style="text-align: right; min-width: 150px">وضعیت</th>
                                    <th style="text-align: right">اخرین ویرایش</th>
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
                                    <th style="min-width: 100px"></th>
                                </tr>
                                </thead>
                                <tbody id="tBody">
                                @foreach($noneConfirmNews as $item)
                                    <tr id="news_{{$item->id}}">
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->user->username}}</td>
                                        <td style="color: {{$item->confirm == 1 ? 'green' : 'red'}}">
                                            {{$item->status}}
                                            {{isset($item->futureDate) ? $item->futureDate : ''}}
                                        </td>
                                        <td>{{$item->lastUpdate}}</td>
                                        <td style="display: flex">
                                            <a href='{{route('news.edit', ['id' => $item->id])}}'>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="deleteNews('{{$item->id}}')" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="text-align: right">عنوان</th>
                                        <th style="text-align: right">نویسنده</th>
                                        <th style="text-align: right; min-width: 150px">وضعیت</th>
                                        <th style="text-align: right">اخرین ویرایش</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                </div>

            </div>
        </div>

    </div>

        <!-- The Modal -->
        <div class="modal" id="deleteAllTops">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">حذف تمامی برگزیده ها</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        آیا از پاک کردن تمامی برگزیده ها اطمینان دارید؟
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondery" data-dismiss="modal">بستن</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="doDeleteAllTops()">بله پاک شوند</button>
                    </div>

                </div>
            </div>
        </div>

    <script>
        var news = {!! $news !!}

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
        };

        function changeInTop(_id, _element){
            openLoading();
            $.ajax({
                type: 'POST',
                url: '{{route("news.addToTopNews")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                complete: closeLoading,
                success: response =>{
                    if(response.status == 'ok'){
                        alert('تغییر با موفقیت اعمال شد');
                    }
                    else
                        alert('خطا');
                },
                error: err =>{
                    alert('خطا');
                }
            })
        }

        function deleteAllTops(){
            $('#deleteAllTops').modal('show');
        }

        function doDeleteAllTops(){
            openLoading();
            $.ajax({
                type: 'POST',
                url: '{{route("news.removeAllTopNews")}}',
                data: {
                    _token: '{{csrf_token()}}'
                },
                complete: closeLoading,
                success: response =>{
                    if(response.status == 'ok'){
                        location.reload();
                        alert('تغییر با موفقیت اعمال شد');
                    }
                    else
                        alert('خطا');
                },
                error: err =>{
                    alert('خطا');
                }
            })
        }

        function deleteNews(_newsId) {
            $.ajax({
                type: 'DELETE',
                url: '{{route('news.delete')}}',
                data: { 'newsId': _newsId },
                success: res => {
                    if(res.status == "ok")
                        $("#news_" + _newsId).remove();
                }
            });
        };

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
                                    table.column(i)
                                         .search( this.value )
                                         .draw();
                                }
                            } );
                            break;
                        case 'نویسنده':
                            $('#creatorSearch').html( '<label for="creatorSearchInput">نویسنده</label><input id="creatorSearchInput" type="text" style="color: black; width: 150px"/>' );
                            $('#creatorSearchInput').on( 'keyup change', function () {
                                if ( table.column(i).search() !== this.value ) {
                                    table.column(i)
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
