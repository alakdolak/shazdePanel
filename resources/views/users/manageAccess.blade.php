@extends('layouts.structure')

@section('header')
    <link rel="stylesheet" href="{{URL::asset('css/switch.css')}}">
    @parent

    <style>

        .myLabel {
            padding: 10px;
            min-width: 200px;
            display: inline-block;
        }

    </style>

@stop

@section('content')


    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1> مدیریت دسترسی های کاربر {{$user->username}}</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <div class="row">
                    @foreach($accessList as $item)
                        <div class="col-xs-12">
                            <label class="switch">
                                <input onchange="changeAccess('{{$item->id}}')" type="checkbox" {{$item->userAccess ? 'checked' : ''}}>
                                <span class="slider round"></span>
                            </label>
                            <span class="myLabel">{{$item->name}}</span>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        function changeAccess(_id) {

            $.ajax({
                type: 'post',
                url: '{{route('manageAccess.change')}}',
                data: {
                    _token: '{{csrf_token()}}',
                    userId: '{{$user->id}}',
                    aclId: _id
                }
            });

        }

    </script>

@stop
