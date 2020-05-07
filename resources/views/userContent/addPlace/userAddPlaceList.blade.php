@extends('layouts.structure')

@section('header')
    @parent
    <style>
        th, td{
            text-align: right;
        }
        .butMain{
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            border-radius: 8px;
            align-items: center;
            cursor: pointer;
            color: white;
        }
        .nav-tabs>li{
            float: right;
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
                            <ul class="nav nav-tabs">
                                <li class="nav-item active">
                                    <a class="nav-link active" href="#home">
                                        <span id="newCommentCount"  class="label label-success">
                                            0
                                        </span>
                                        مکان های جدید
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#menu1">
                                        مکان های تایید شده
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content border mb-3">

                                <div id="home" class="container tab-pane active">
                                    <br>
                                    <div class="container-fluid">
                                        <div class="row">

                                            <h2>بیبیب</h2>
                                            <div style="max-height: 80vh; overflow-y: auto">
                                                @foreach($showPlaces as $place)
                                                    <ul style="text-align: right; padding: 0px 20px; font-size: 20px">
                                                        @foreach($place as $item)
                                                            <li>
                                                                {{ $item[0] }} :
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <hr>
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

        <script>

            $(document).ready(function(){
                $(".nav-tabs a").click(function(){
                    $(this).tab('show');
                });
                $('.nav-tabs a').on('shown.bs.tab', function(event){
                    var x = $(event.target).text();         // active tab
                    var y = $(event.relatedTarget).text();  // previous tab
                    $(".act span").text(x);
                    $(".prev span").text(y);
                });
            });

        </script>
@stop
