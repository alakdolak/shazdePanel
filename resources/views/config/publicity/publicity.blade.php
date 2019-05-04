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
    </style>
@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت سنین</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">
                    @if(count($ages) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">سنی وجود ندارد</h4>
                        </div>
                    @else
                        <form method="post" action="{{route('opOnAge')}}">
                            {{csrf_field()}}
                            @foreach($ages as $age)
                                <div class="col-xs-12">
                                    <span>
                                        {{$age->name}}
                                    </span>
                                    <button name="ageId" value="{{$age->id}}" class="sparkline9-collapse-close transparentBtn" data-toggle="tooltip" title="حذف سن" style="width: auto">
                                        <span ><i class="fa fa-times"></i></span>
                                    </button>
                                </div>
                            @endforeach
                        </form>
                    @endif

                    @if($mode == "see")
                        <div class="col-xs-12">
                            <a href="{{route('addAge')}}">
                                <button class=" btn btn-default transparentBtn" style="width: auto" data-toggle="tooltip" title="اضافه کردن سن جدید">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </a>
                        </div>
                    @elseif($mode == "add")
                        <form method="post" action="{{route('addAge')}}">
                            {{csrf_field()}}
                            <div class="col-xs-12">
                                <label>
                                    <span>نام سن</span>
                                    <input type="text" name="ageName" maxlength="40" required autofocus>
                                </label>
                            </div>
                            <div class="col-xs-12">
                                <p class="warning_color">{{$msg}}</p>
                                <input type="submit" name="addAge" value="اضافه کن" class="btn btn-primary" style="width: auto; margin-top: 10px">
                            </div>
                        </form>
                    @endif
                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
@stop