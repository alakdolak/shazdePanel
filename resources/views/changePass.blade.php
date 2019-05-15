@extends('layouts.structure')

@section('header')
    @parent
    <link rel="stylesheet" href="{{URL::asset('css/form.css')}}">
@stop

@section('content')

    <div class="col-md-4"></div>

    <div class="col-md-4">
        <div class="sparkline11-list shadow-reset">
            <div class="sparkline11-hd">
                <div class="main-sparkline11-hd" style="direction: rtl">
                    <h1>تغییر رمزعبور</h1>
                </div>
            </div>

            <div class="sparkline12-graph">

                <div id="pwd-container1">

                    <div class="form-group">
                        <label for="old_password">رمزعبور فعلی</label>
                        <input type="password" class="form-control" id="old_password" value="12345">
                    </div>

                    <div class="form-group">
                        <label for="password">رمزعبور جدید</label>
                        <input type="password" class="form-control example1" id="new_password" value="12345">
                    </div>

                    <div class="form-group">
                        <label for="password">تکرار رمزعبور جدید</label>
                        <input type="password" class="form-control" id="conform_password" value="12345">
                    </div>

                    <div class="form-group">
                        <div class="pwstrength_viewport_progress"></div>
                    </div>

                    <div class="form-group">
                        <input onclick="changePass()" type="submit" class="btn btn-success" value="تغییر رمز">
                        <p style="margin: 10px" id="msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4"></div>

    <script>

        function changePass() {

            if($(".progress-bar").hasClass('progress-bar-warning')) {

                $.ajax({
                    type: 'post',
                    url: '{{route('doChangePass')}}',
                    data: {
                        'oldPass': $("#old_password").val(),
                        'newPass': $("#new_password").val(),
                        'confirmPass': $("#conform_password").val()
                    },
                    success: function (response) {

                        if(response == "ok") {
                            $("#msg").empty().append('عملیات مورد نظر با موفقیت انجام شد');
                        }
                        else if(response == "nok1") {
                            $("#msg").empty().append('رمز وارد شده با تکرار آن یکسان نیست');
                        }
                        else if(response == "nok2") {
                            $("#msg").empty().append('رمز وارد شده صحیح نمی باشد');
                        }

                    }
                });

            }
            else {
                $("#msg").empty().append('رمز وارد شده قوی نیست');
            }

        }
        
    </script>

@stop

@section('reminder')
    @parent
    <script src="{{URL::asset('js/password-meter/pwstrength-bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('js/password-meter/zxcvbn.js')}}"></script>
    <script src="{{URL::asset('js/password-meter/password-meter-active.js')}}"></script>
@stop