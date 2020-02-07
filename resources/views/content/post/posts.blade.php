@extends('layouts.structure')

@section('header')
    @parent

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    {{--<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>--}}

    {{--<link rel="stylesheet" href="{{URL::asset('css/DataTable/datatables.bootstrap.css')}}">--}}
    {{--<link rel="stylesheet" href="{{URL::asset('css/DataTable/datatables.bootstrap4.css')}}">--}}
    {{--<link rel="stylesheet" href="{{URL::asset('css/DataTable/datatables.foundation.css')}}">--}}
    {{--<link rel="stylesheet" href="{{URL::asset('css/DataTable/datatables.jqueryui.css')}}">--}}
    {{--<link rel="stylesheet" href="{{URL::asset('css/DataTable/datatables.semanticui.css')}}">--}}
    {{--<link rel="stylesheet" href="{{URL::asset('css/DataTable/jquery.datatables.css')}}">--}}

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

    </style>

@stop

@section('content')

    <div class="col-md-12">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>پست ها</h1>
                    <div>
                        <button onclick="$('#filtersDiv').slideToggle()" class="btn btn-success">فیلترها</button>
                        <button onclick="document.location.href = '{{route('createPost')}}'" class="btn btn-primary">افزودن پست جدید</button>
                        {{--<button class="btn btn-primary">افزودن دسته ای پست</button>--}}
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

                    @if(count($posts) == 0)
                        <p>پستی موجود نیست</p>
                    @else
                        <table id="mainTable" class="table">
                            <thead class="thead-dark" style="background: black; color: white;">
                                <tr>
                                    <th style="text-align: right">
                                        عنوان
                                    </th>
                                    <th style="text-align: right">
                                        نویسنده
                                    </th>
                                    <th style="text-align: right">
                                        دسته بندی ها
                                    </th>
                                    <th style="text-align: right">
                                        برچسپ ها
                                    </th>
                                    <th style="text-align: right; min-width: 150px">
                                        وضعیت
                                    </th>
                                    <th style="text-align: right">
                                        اخرین ویرایش
                                    </th>
                                    <th style="text-align: right">
                                        پست داغ
                                    </th>
                                    <th style="text-align: right">
                                        پست بنر
                                    </th>
                                    <th style="min-width: 100px">
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tBody">
                                @foreach($posts as $post)
                                    <tr id="post_{{$post->id}}">
                                        <td>{{$post->title}}</td>
                                        <td>{{$post->user->username}}</td>
                                        <td>
                                            @foreach($post->categories as $item)
                                                <a>
                                                    {{$item->name}}
                                                </a>
                                                -
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($post->tags as $item)
                                                <a>
                                                    {{$item->tag}}
                                                </a>
                                                -
                                            @endforeach
                                        </td>
                                        <td>
                                            {{$post->status}}
                                            {{isset($post->futureDate) ? $post->futureDate : ''}}
                                        </td>
                                        <td>{{$post->lastUpdate}}</td>
                                        <td style="width: 100px">
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeFav({{$post->id}}, this)" {{($post->favorited) ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="checkBoxTd">
                                                <input type="checkbox" onchange="changeBanner({{$post->id}}, this)" {{($post->bannered) ? 'checked' : ''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td style="display: flex">
                                            <a href='{{route('editPost', ['id' => $post->id])}}'>
                                                <button class="btn btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </a>
                                            <button onclick="deletePost('{{$post->id}}')" class="btn btn-danger">
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
                                <th style="text-align: right">
                                    دسته بندی ها
                                </th>
                                <th style="text-align: right">
                                    برچسپ ها
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
                    @endif

                </center>

            </div>
        </div>

    </div>

    <script>
        var posts = {!! $posts !!}

        function deletePost(postId) {
            $.ajax({
                type: 'post',
                url: '{{route('deletePost')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok")
                        $("#post_" + postId).remove();

                }
            });
        }

        function deleteFromFavoritePosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('deleteFromFavoritePosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('از پست ‌های منتخب حذف شد')
                }
            });
        }

        function deleteFromBannerPosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('deleteFromBannerPosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('از پست ‌های بنر حذف شد')
                }
            });
        }

        function addToBannerPosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('addToBannerPosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('به پست ‌های بنر افزوده شد')
                }
            });

        }

        function addToFavoritePosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('addToFavoritePosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok")
                        alert('به پست ‌های منتخب افزوده شد')
                }
            });

        }

        function changeFav(id, element){
            if($(element).prop('checked'))
                addToFavoritePosts(id);
            else
                deleteFromFavoritePosts(id);
        }

        function changeBanner(id, element){
            if($(element).prop('checked'))
                addToBannerPosts(id);
            else
                deleteFromBannerPosts(id);
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
            // $('#mainTable thead tr').clone(true).appendTo( '#mainTable thead' );
            $('#mainTable thead tr:eq(0) th').each( function (i) {
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

                // $(this).html( '<input type="text" placeholder="Search '+title+'" style="color: black;"/>' );
                // $('input', this).on( 'keyup change', function () {
                //     if ( table.column(i).search() !== this.value ) {
                //         console.log(this.value)
                //         console.log(i)
                //         table
                //             .column(i)
                //             .search( this.value )
                //             .draw();
                //     }
                // } );
            } );

            var table = $('#mainTable').DataTable( {
                "order": [[ 5, "desc" ]],
                "scrollY": 400,
                "scrollX": true,
                orderCellsTop: true,
                fixedHeader: true,
            } );
        });
    </script>
@stop