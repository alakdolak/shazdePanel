@extends('layouts.structure')

@section('header')
    @parent

    <style>

        .main-sparkline8-hd {
            direction: rtl;
        }

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

    @include('layouts.modal')

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>سطوح</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    @if(count($levels) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">سطحی وجود ندارد</h4>
                        </div>
                    @else
                        <form method="post" action="{{route('opOnLevel')}}">
                            {{csrf_field()}}
                            @foreach($levels as $level)
                                <div class="col-xs-12">

                                    <span>
                                        {{$level["name"]}}
                                    </span>
                                    <span>حداقل امتیاز </span>
                                    <span> {{$level["floor"]}} </span>
                                    <button name="editLevel" value="{{$level["id"]}}" class="sparkline9-collapse-link transparentBtn" data-toggle="tooltip" title="ویرایش سطح" style="width: auto">
                                        <span class="fa fa-wrench"></span>
                                    </button>
                                    <button name="deleteLevel" value="{{$level["id"]}}" class="sparkline9-collapse-close transparentBtn" data-toggle="tooltip" title="حذف سطح" style="width: auto">
                                        <span ><i class="fa fa-times"></i></span>
                                    </button>
                                </div>
                            @endforeach
                        </form>
                    @endif

                    @if($mode == "add" || $mode == "see")

                        <div class="col-xs-12">
                            <button id="addBtn" onclick="createModal('{{route('addLevel')}}', [{'name': 'levelName', 'class': [], 'type': 'text', 'label': 'نام سطح', 'value': ''}, {'name': 'floor', 'class': [], 'type': 'number', 'label': 'حداقل امتیاز', 'value': ''}], 'افزودن سطح جدید', '{{(isset($msg) ? $msg : '')}}')" class="btn btn-default transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>

                    @elseif($mode == "add")
                        <script>
                            setTimeout(function(){
                                $("#addBtn").click();
                            }, 1);
                        </script>

                    @elseif($mode == "edit")

                        <div class="col-xs-12">
                            <button id="addBtn" onclick="createModal('{{route('opOnLevel')}}', [{'name': 'levelId', 'type': 'text', 'label': '', 'class': ['hidden'], 'value': '{{$selectedLevel->id}}'}, {'name': 'levelName', 'class': [], 'type': 'text', 'label': 'نام سطح', 'value': '{{$selectedLevel->name}}'}, {'name': 'floor', 'class': [], 'type': 'number', 'label': 'حداقل امتیاز', 'value': '{{$selectedLevel->floor}}'}], 'افزودن سطح جدید', '{{(isset($msg) ? $msg : '')}}')" class="btn btn-default transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert">
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>

                        <script>
                            setTimeout(function(){
                                $("#addBtn").click();
                            }, 1);
                        </script>
                    @endif
                </center>

            </div>
        </div>
    </div>

    <div class="col-md-2"></div>

@stop