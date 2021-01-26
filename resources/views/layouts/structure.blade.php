<!doctype html>
<html class="no-js" lang="en">
<?php
    if(auth()->check())
        $userACL = \App\models\ACL::where('userId', auth()->user()->id)->first();
?>
<head>

    @section('header')
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>پنل شازده</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('img/favicon.png')}}">
        <link rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/adminpro-custon-icon.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/meanmenu.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/jquery.mCustomScrollbar.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/animate.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/data-table/bootstrap-table.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/data-table/bootstrap-editable.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/normalize.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/c3.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/style.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/responsive.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/icons.css')}}">

        <link rel="stylesheet" href="{{URL::asset('css/pages/all.css')}}">

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="{{URL::asset('js/vendor/modernizr-2.8.3.min.js')}}"></script>
        <script src="{{URL::asset('js/jquery.min.js')}}"></script>

        <script>
            function validateNumber(evt) {
                var theEvent = evt || window.event;

                // Handle paste
                if (theEvent.type === 'paste') {
                    key = event.clipboardData.getData('text/plain');
                } else {
                    // Handle key press
                    var key = theEvent.keyCode || theEvent.which;
                    key = String.fromCharCode(key);
                }
                var regex = /[0-9]|\./;
                if( !regex.test(key) ) {
                    theEvent.returnValue = false;
                    if(theEvent.preventDefault) theEvent.preventDefault();
                }
            }
        </script>
    @show

        <style>
            .loaderDiv{
                flex-direction: column;
            }
            .loaderDiv .logo{

            }
            .loaderDiv .logo img{

            }
            .loaderDiv .uploadProcessInLoading{
                width: 90%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .loaderDiv .uploadProcessInLoading .procBack{
                width: 100%;
                background: white;
                border-radius: 10px;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
                overflow: hidden;
            }
            .loaderDiv .uploadProcessInLoading .procBack .procBar{
                position: absolute;
                left: 0px;
                width: 20%;
                background: var(--koochita-yellow);
                height: 100%;
                border-radius: 10px;
            }
            .loaderDiv .uploadProcessInLoading .procBack .procText{

            }
            .loaderDiv .uploadProcessInLoading .text{
                color: white;
                margin-top: 13px;
                font-size: 25px;
            }
        </style>
</head>

<body class="materialdesign">
    <div id="loader" class="loaderDiv" style="display: none">
        <div class="logo">
            <img src="{{URL::asset('img/loading.gif')}}">
        </div>
        <div id="loaderProcessSection" class="uploadProcessInLoading">
            <div class="procBack">
                <div id="loaderProcessBar" class="procBar"></div>
                <div id="loaderProcessNumber" class="procText">10%</div>
            </div>
            <div id="loaderProcessText" class="text">در حال بارگزاری ویدیو</div>
        </div>
    </div>

    <div class="allPageDiv">
        <div class="mobileHeader">
            <img src="{{URL::asset('img/mainLogo.png')}}">
            <div class="mobileMenuButton" onclick="openMenuSide()">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </div>
        </div>
        <div id="sideNav" class="sideNavs">
            @include('layouts.nsideNav')
        </div>
        <div class="mainContentDiv">
            @yield('content')
        </div>
    </div>

    <div class="wrapper-pro">

{{--        <div class="left-sidebar-pro">--}}
{{--            @include('layouts.sideNav')--}}
{{--        </div>--}}

{{--        <div class="content-inner-all">--}}
{{--            @yield('content')--}}
{{--        </div>--}}


        @section('reminder')
        <!-- jquery
        ============================================ -->
            <script src="{{URL::asset('js/vendor/jquery-1.11.3.min.js')}}"></script>
            <!-- bootstrap JS
                ============================================ -->
            <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
            <!-- meanmenu JS
                ============================================ -->
            <script src="{{URL::asset('js/jquery.meanmenu.js')}}"></script>
            <!-- mCustomScrollbar JS
                ============================================ -->
            <script src="{{URL::asset('js/jquery.mCustomScrollbar.concat.min.js')}}"></script>
            <!-- sticky JS
                ============================================ -->
            <script src="{{URL::asset('js/jquery.sticky.js')}}"></script>
            <!-- scrollUp JS
                ============================================ -->
            <script src="{{URL::asset('js/jquery.scrollUp.min.js')}}"></script>
            <!-- scrollUp JS
                ============================================ -->
            <script src="{{URL::asset('js/wow/wow.min.js')}}"></script>
            <!-- counterup JS
                ============================================ -->
            <script src="{{URL::asset('js/counterup/jquery.counterup.min.js')}}"></script>
            <script src="{{URL::asset('js/counterup/waypoints.min.js')}}"></script>
            <script src="{{URL::asset('js/counterup/counterup-active.js')}}"></script>
            <!-- jvectormap JS
                ============================================ -->
            {{--<script src="{{URL::asset('js/jvectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>--}}
            {{--<script src="{{URL::asset('js/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>--}}
            {{--<script src="{{URL::asset('js/jvectormap/jvectormap-active.js')}}"></script>--}}
            <!-- peity JS
                ============================================ -->
            <script src="{{URL::asset('js/peity/jquery.peity.min.js')}}"></script>
            <script src="{{URL::asset('js/peity/peity-active.js')}}"></script>
            <!-- sparkline JS
                ============================================ -->
            <script src="{{URL::asset('js/sparkline/jquery.sparkline.min.js')}}"></script>
            <script src="{{URL::asset('js/sparkline/sparkline-active.js')}}"></script>
            <!-- flot JS
                ============================================ -->
            <script src="{{URL::asset('js/flot/Chart.min.js')}}"></script>
            <script src="{{URL::asset('js/flot/dashtwo-flot-active.js')}}"></script>
            <!-- data table JS
                ============================================ -->
            <script src="{{URL::asset('js/data-table/bootstrap-table.js')}}"></script>
            <script src="{{URL::asset('js/data-table/tableExport.js')}}"></script>
            <script src="{{URL::asset('js/data-table/data-table-active.js')}}"></script>
            <script src="{{URL::asset('js/data-table/bootstrap-table-editable.js')}}"></script>
            <script src="{{URL::asset('js/data-table/bootstrap-editable.js')}}"></script>
            <script src="{{URL::asset('js/data-table/bootstrap-table-resizable.js')}}"></script>
            <script src="{{URL::asset('js/data-table/colResizable-1.5.source.js')}}"></script>
            <script src="{{URL::asset('js/data-table/bootstrap-table-export.js')}}"></script>
            <!-- main JS
                ============================================ -->
            <script src="{{URL::asset('js/main.js')}}"></script>

            <script type="text/javascript">
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            </script>
        @show
    </div>
</body>

<script>
    function openLoading(_hasProcess = false, _text = ''){
        $('#loader').css('display', 'flex');
        if(_hasProcess){
            $('#loaderProcessSection').removeClass('hidden');
            $('#loaderProcessBar').css('width', '0px');
            $('#loaderProcessNumber').text('0%');
            $('#loaderProcessText').text(_text);
        }
        else
            $('#loaderProcessSection').addClass('hidden');
    }

    function updateLoadingProcess(_percent) {
        $('#loaderProcessBar').css('width', _percent+'%');
        $('#loaderProcessNumber').text(_percent+'%');
    }

    function closeLoading(){
        $('#loader').css('display', 'none');
    }

    function openMenuSide(){
        $('#sideNav').toggleClass('activeMenu')
    }
</script>
@yield('script')


</html>
