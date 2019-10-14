@extends('layouts.structure')

@section('header')
    @parent

    <style>

        .col-xs-12 {
            margin: 5px;
        }

        button {
            min-width: 200px;
        }

    </style>

@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>آخرین مطالب کاربران</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <center class="row">
                    <div class="col-xs-12">
                        <h3>فعالیت های موجود</h3>
                    </div>
                    @if(count($activities) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">فعالیتی وجود ندارد</h4>
                        </div>
                    @else
                        @foreach($activities as $activity)
                            <div class="col-xs-12">
                                <button onclick="document.location.href = '{{route('controlActivityContent', ['activityId' => $activity->id])}}'" class="btn btn-primary" data-toggle="tooltip" title="" style="width: auto">
                                    {{$activity->name}}
                                </button>
                            </div>
                        @endforeach
                        <div class="col-xs-12">
                            <button onclick="document.location.href = '{{route('controlActivityContent', ['activityId' => 'post'])}}'" class="btn btn-primary" data-toggle="tooltip" title="" style="width: auto">
                                دیدگاه های پست ها
                            </button>
                        </div>
                    @endif
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
@stop
