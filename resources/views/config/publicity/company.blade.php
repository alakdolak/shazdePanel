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

    @include('layouts.modal')

    <div class="col-md-2"></div>

    <div class="col-md-8">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت شرکت های تبلیغاتی</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    @if(count($company) == 0)
                        <div class="col-xs-12">
                            <h4 class="warning_color">شرکتی وجود ندارد</h4>
                        </div>
                    @else
                        <form method="post" action="{{route('deleteCompany')}}">
                            {{csrf_field()}}
                            @foreach($company as $itr)
                                <div class="col-xs-12">
                                    <span>
                                        {{$itr->name}}
                                    </span>

                                    <button name="deleteCompany" value="{{$itr->id}}" class="sparkline9-collapse-close transparentBtn" data-toggle="tooltip" title="حذف سطح" style="width: auto">
                                        <span ><i class="fa fa-times"></i></span>
                                    </button>
                                </div>
                            @endforeach
                        </form>
                    @endif

                    <div class="col-xs-12">
                        <button id="addBtn" onclick="createModal('{{route('company')}}', [{'name': 'name', 'class': [], 'type': 'text', 'label': 'نام شرکت', 'value': ''}], 'افزودن شرکت جدید', '{{(isset($msg) ? $msg : '')}}')" class="btn btn-default transparentBtn" style="width: auto" data-toggle="modal" data-target="#InformationproModalalert">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>

                </center>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
@stop