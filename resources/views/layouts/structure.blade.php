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

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="{{URL::asset('js/vendor/modernizr-2.8.3.min.js')}}"></script>
        <script src="{{URL::asset('js/jquery.min.js')}}"></script>

        <style>

            :root {
                --koochita-blue: #0076a3;
                --koochita-light-green: #4dc7bc;
                --koochita-dark-green: #053a3e;
                --koochita-green: #0d6650;
                --koochita-red: #720d19;
                --koochita-yellow: #fcc156;
            }

            .row{
                width: 100%;
                margin: 0px;
            }
            .loaderDiv{
                position: fixed;
                width: 100%;
                height: 100%;
                z-index: 99999;
                left: 0px;
                top: 0px;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #000000c7;
            }
            .loader {
            }

            .dropdown-item {
                text-align: right;
            }

            .main-sparkline8-hd {
                direction: rtl;
            }

            .hidden {
                display: none !important;
            }

            .calendar > table {
                width: 100%;
            }

            input{
                border-color: gray !important;
                border-radius: 10px !important;
                border: solid;
                outline: none;
                padding: 3px 10px;
            }
            select{
                border-color: gray !important;
                border-radius: 10px !important;
                border: solid;
            }
            .left-menu-dropdown{
                width: 100% !important;
            }
            .form-control{
                border-color: gray !important;
                border-radius: 5px;
            }

            .addIcon{
                font-size: 20px;
                color: white;
                background: green;
                width: 30px;
                height: 30px;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                margin: 0 20px;
                cursor: pointer;
            }
            .allPageDiv{
                position: fixed;
                top: 0px;
                left: 0px;
                width: 100%;
                height: 100%;
                overflow: hidden;
                display: flex;
                align-items:center;
                background: #4dc7bc;
                background: #fcc156;
                background: #15b3ac;
                direction: rtl;
            }
            .sideNavs{
                width: 235px;
                height: 100%;
                padding: 10px;
            }
            .mainContentDiv{
                width: 95%;
                height: 98vh;
                margin-left: 15px;
                background: #f9f9f9;
                border-radius: 30px;
                padding: 10px 0px;
                overflow-y: auto;
            }
            .modal.fade, .modal.in{
                background-color: rgba(0, 0, 0, 0.64);
            }
            .modal-backdrop.fade.in{
                display: none;
            }
            .modal-backdrop.in{
                display: none;
            }

            .mobileHeader{
                display: none;
            }
            .mobileHeader .mobileMenuButton{
                position: absolute;
                right: 20px;
                color: white;
                font-size: 30px;
                cursor: pointer;
            }
            .mobileHeader img{
                height: 35px;
            }
            .shadow-reset{
                border-radius: 20px;
                overflow: hidden;
            }

            .mainBody{
                margin: 0px 20px;
            }
            .mainBody .header{
                font-size: 22px;
                font-weight: normal;
                display: flex;
                border-bottom: solid 1px #e0e0e0;
                padding-bottom: 10px;
                margin-bottom: 10px;
                align-items: center
            }

            @media (max-width: 991px) {
                .allPageDiv{
                    flex-direction: column;
                }
                .mobileHeader{
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: 10px 0px;

                }
                .sideNavs{
                    position: fixed;
                    right: 0px;
                    z-index: 99;
                    background-color: #15b3ac;
                    margin-right: -240px;
                    transition: .4s;
                    border-radius: 25px 0px 0px 25px;
                    box-shadow: 0 0 17px 2px #00504f;
                }
                .sideNavs.activeMenu{
                    margin-right: 0px;
                }
                .mainContentDiv{
                    width: 98%;
                    height: calc(100vh - 65px);
                    margin: 0px auto;
                    overflow-x: hidden;
                }
            }
            .selectTab{
                display: flex;
                justify-content: space-around;
                border-bottom: solid 1px gray;
                margin-bottom: 15px;
                padding-bottom: 5px;
            }
            .selectTab .tab{
                width: 30%;
                text-align: center;
                padding: 5px 0px;
                cursor: pointer;
            }
            .selectTab .tab.selected{
                background: #15b3ac;
                color: white;
                border-radius: 30px;
            }
        </style>
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
            /*scrollbar*/
            ::-webkit-scrollbar {
                width: 7px !important;
                height: 7px !important;
            }

            /* Track */
            ::-webkit-scrollbar-track {
                box-shadow: inset 0 0 5px grey !important;
                border-radius: 10px !important;
            }

            /* Handle */
            ::-webkit-scrollbar-thumb {
                background: gray !important;
                border-radius: 10px !important;
            }

            /* Handle on hover */
            ::-webkit-scrollbar-thumb:hover {
                background: darkgray !important;
            }
        </style>
</head>

<body class="materialdesign">
    <div class="loaderDiv" id="loader" style="display: none">
        <img src="{{URL::asset('img/loading.gif')}}" >
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
    function openLoading(){
        $('#loader').css('display', 'flex');
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
