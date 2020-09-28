@extends('layouts.structure')

@section('header')
    @parent

    <style>
        *{
            direction: rtl;
        }
        th{
            text-align: center;
        }
        .tdTabel:hover{
            background-color: #9aecff7a;
        }

        .tags{
            margin: 10px 10px;
            display: inline-block;
            font-size: 18px;
            border-bottom: solid gray 1px;
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

                                    <div class="income-order-visit-user-area">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-sm-12" style="text-align: right !important;">
                                                    {!! $post[0]->post_content !!}
                                                </div>
                                                <hr>
                                                <div class="col-sm-12">
                                                    <div class="row" style="margin-top: 10px; padding-top: 10px; border-top: solid black;">
                                                        <h2 style="margin-bottom: 20px; color: #4c72ff">nav_menu</h2>
                                                        <div style="text-align: right">
                                                            @foreach($tags as $item)
                                                                @if($item->taxonomy[0]->taxonomy == 'nav_menu')
                                                                    <div class="tags">
                                                                        {{$item->term[0]->name}}
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 10px; padding-top: 10px; border-top: solid black;">
                                                        <h2 style="margin-bottom: 20px; color: #4c72ff">category</h2>
                                                        <div style="text-align: right">
                                                            @foreach($tags as $item)
                                                                @if($item->taxonomy[0]->taxonomy == 'category')
                                                                    <div class="tags">
                                                                        {{$item->term[0]->name}}
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top: 10px; padding-top: 10px; border-top: solid black;">
                                                        <h2 style="margin-bottom:20px; color: #4c72ff">post_tag</h2>
                                                        <div style="text-align: right">
                                                            @foreach($tags as $item)
                                                                @if($item->taxonomy[0]->taxonomy == 'post_tag')
                                                                    <div class="tags">
                                                                        {{$item->term[0]->name}}
                                                                    </div>
                                                                @endif
                                                            @endforeach
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
            </div>
        </div>
    </div>

@stop