@extends('layouts.structure')

@section('header')
    @parent
    <link rel="stylesheet" href="{{URL::asset('css/form.css')}}">
@stop

@section('content')

    <form method="post" action="{{route('doLogin')}}">

        {{csrf_field()}}

        <div class="login-form-area mg-t-30 mg-b-40">
        <div class="container-fluid">
            <div class="row" style="direction: rtl">
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    <div class="login-bg">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="logo">
                                    <a href="#">
                                        <img src="{{URL::asset('img/mainLogo.png')}}" alt="" />
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-title">
                                    <h1>فرم ورود</h1>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="login-input-area">
                                    <input type="text" name="username" />
                                    <i class="fa fa-user login-user" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="login-input-head">
                                    <p>نام کاربری</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="login-input-area">
                                    <input type="password" name="password" />
                                    <i class="fa fa-lock login-user"></i>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="login-input-head">
                                    <p>رمزعبور</p>
                                </div>
                            </div>
                        </div>
                        <center class="row">
                            <div class="login-button-pro">
                                <button type="submit" class="login-button login-button-lg">ورود</button>
                                <p style="margin-top: 10px">{{$msg}}</p>
                            </div>
                        </center>
                    </div>
                </div>
                <div class="col-lg-4"></div>
            </div>
        </div>
    </div>

    </form>
@stop
