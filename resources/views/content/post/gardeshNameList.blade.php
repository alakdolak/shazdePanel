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
                    <h1>پست های گردشنامه</h1>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="col-xs-12" style="direction: rtl;">
                    @if(count($gardeshname) == 0)
                        <p>پستی موجود نیست</p>
                    @else
                        <table class="table" data-toggle="table" data-pagination="true" data-search="true">
                            <thead style="background: black; color: white;">
                            <tr>
                                <th style="text-align: right">
                                    عنوان
                                </th>
                                <th>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($gardeshname as $post)
                                <tr>
                                    <td>{{$post->post_title}}</td>
                                    <td style="display: flex">
                                        <button class="btn btn-primary" onclick="window.location.href = '{{url("gardeshEdit/". $post->ID)}}'">ویرایش پست</button>
                                        <button class="btn btn-danger" onclick="deleteGardesh({{$post->ID}}, this)">حذف پست</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </center>
            </div>
        </div>

    </div>

    <script>
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
                "scrollY": 400,
                "scrollX": true,
                orderCellsTop: true,
                fixedHeader: true,
            } );
        });
    </script>
@stop