@extends('layouts.structure')

@section('header')
    @parent
    <link rel="stylesheet" href="{{URL::asset('css/form.css')}}">
@stop

@section('content')

    <div class="login-form-area mg-t-30 mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3"></div>
                <form method="post" action="{{route('addAdmin')}}" class="adminpro-form">

                    {{csrf_field()}}

                    <div class="col-lg-6">
                        <div class="login-bg">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="login-title">
                                        <h1>فرم ثبت نام</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="login-input-area register-mg-rt">
                                                <input tabindex="2" type="text" name="lastName" placeholder="نام خانوادگی"/>
                                                <i class="fa fa-user login-user"></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="login-input-area">
                                                <input autofocus tabindex="1" type="text" name="firstName" placeholder="نام"/>
                                                <i class="fa fa-user login-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="login-input-head">
                                        <p>نام و نام خانوادگی</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="login-input-area">
                                        <input tabindex="3" placeholder="حداقل 8 کاراکتر" type="text" name="username" />
                                        <i class="fa fa-user login-user"></i>
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
                                        <input tabindex="4" type="email" name="email" />
                                        <i class="fa fa-envelope login-user" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="login-input-head">
                                        <p>آدرس ایمیل</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="login-input-area">
                                        <input tabindex="5" type="tel" onkeypress="validateNumber(event)" name="phone" />
                                        <i class="fa fa-phone login-user" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="login-input-head">
                                        <p>شماره همراه</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="login-input-area">
                                        <input tabindex="6" placeholder="حداقل8 کاراکتر" type="password" name="password" />
                                        <i class="fa fa-lock login-user"></i>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="login-input-head">
                                        <p>رمزعبور</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="login-input-area">
                                        <input tabindex="7" type="password" name="confirm_password" />
                                        <i class="fa fa-lock login-user"></i>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="login-input-head">
                                        <p>تکرار رمزعبور</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-8">
                                    <div class="login-button-pro">
                                        <button tabindex="8" type="submit" class="login-button login-button-lg">ثبت نام</button>
                                        <p style="margin-top: 10px">{{$msg}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($errors->all() as $error)
                                    <li style="direction: rtl; text-align: right" class="alert alert-warning">{{ $error }}</li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </div>
@stop