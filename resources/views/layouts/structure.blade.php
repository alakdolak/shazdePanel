<!doctype html>
<html class="no-js" lang="en">

<head>

    @section('header')
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>پنل شازده</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- favicon
            ============================================ -->
        <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('img/favicon.png')}}">
        <!-- Google Fonts
            ============================================ -->
        {{--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">--}}
        <!-- Bootstrap CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">
        <!-- Bootstrap CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/font-awesome.min.css')}}">

        <!-- adminpro icon CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/adminpro-custon-icon.css')}}">

        <!-- meanmenu icon CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/meanmenu.min.css')}}">

        <!-- mCustomScrollbar CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/jquery.mCustomScrollbar.min.css')}}">

        <!-- animate CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/animate.css')}}">

        <!-- jvectormap CSS
            ============================================ -->
{{--        <link rel="stylesheet" href="{{URL::asset('css/jvectormap/jquery-jvectormap-2.0.3.css')}}">--}}

        <!-- normalize CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/data-table/bootstrap-table.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/data-table/bootstrap-editable.css')}}">

        <!-- normalize CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/normalize.css')}}">
        <!-- charts CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/c3.min.css')}}">
        <!-- style CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/style.css')}}">
        <!-- responsive CSS
            ============================================ -->
        <link rel="stylesheet" href="{{URL::asset('css/responsive.css')}}">

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- modernizr JS
            ============================================ -->
        <script src="{{URL::asset('js/vendor/modernizr-2.8.3.min.js')}}"></script>
        <script src="{{URL::asset('js/jquery.min.js')}}"></script>

        <style>
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
                border-color: #333333 !important;
                border-radius: 10px !important;
            }
            select{
                border-color: #333333 !important;
                border-radius: 10px !important;
            }
            .left-menu-dropdown{
                width: 100% !important;
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

</head>

<body class="materialdesign">

    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <!-- Header top area start-->
    <div class="wrapper-pro">
        <div class="left-sidebar-pro">
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>پنل کوچیتا</h3>
                </div>
                <div class="left-custom-menu-adp-wrap">
                    <ul class="nav navbar-nav left-sidebar-menu-pro">
                        @if(\Illuminate\Support\Facades\Auth::check())
                            <li>
                                <a href="{{route('home')}}" aria-expanded="false">
                                    <i class="fa big-icon fa-home"></i>
                                    <span class="mini-dn">خانه</span>
                                </a>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-envelope"></i> <span class="mini-dn">مدیریت محتوا</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('city.index')}}" class="dropdown-item">اطلاعات شهرها</a>
                                    <a href="{{url('topInCity')}}" class="dropdown-item">پیشنهاد ویژه شهر</a>
                                    <a href="{{route('manageNoFollow')}}" class="dropdown-item">مدیریت لینک ها</a>
                                    <a href="{{route('chooseCity', ['mode' => 'content'])}}" class="dropdown-item">تغییر محتوای صفحات</a>
                                    <a href="{{route('chooseCity', ['mode' => 'content2'])}}" class="dropdown-item">2تغییر محتوای صفحات</a>
                                    <a href="{{route('lastActivities')}}" class="dropdown-item">فعالیت های اخیر</a>
                                    <a href="{{route('posts')}}" class="dropdown-item">مدیریت پست ها</a>
                                    <a href="{{route('uploadMainContent')}}" class="dropdown-item">افزودن محتوا</a>
                                    <a href="{{route('seoTester')}}" class="dropdown-item">تست  سئو صفحات</a>
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-pie-chart"></i> <span class="mini-dn">تنظیمات سیستمی</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{url('reports')}}" class="dropdown-item">گزارشات</a>
                                    <a href="{{url('goyeshTags')}}" class="dropdown-item">تگ گویش ها</a>
                                    <a href="{{url('ageSentences')}}" class="dropdown-item">توضیحات سن برای بلیت</a>
                                    <a href="{{route('determineRadius')}}" class="dropdown-item">تعیین شعاع مکان های نزدیک</a>
                                    <a href="{{route('ages')}}" class="dropdown-item">مدیریت سنین</a>
                                    <a href="{{route('backup')}}" class="dropdown-item">مدیریت پشتیبانی</a>
                                    <a href="{{route('offers')}}" class="dropdown-item">مدیریت کد های تخفیف</a>
                                    <a href="{{route('photographer.index')}}" class="dropdown-item">عکس های عکاسان</a>
                                    <a href="{{route('slider.index')}}" class="dropdown-item">عکس اسلاید صفحه اول</a>
                                    <a href="project-details.html" class="dropdown-item">Project Details</a>
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-pie-chart"></i> <span class="mini-dn">تنظیمات سیستمی2</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('activities.index')}}" class="dropdown-item">فعالیت ها</a>
                                    <a href="{{route('defaultPics.index')}}" class="dropdown-item">تصاویر پیش فرض</a>
                                    <a href="{{route('places.index')}}" class="dropdown-item">اماکن</a>
                                    <a href="{{route('tripStyle.index')}}" class="dropdown-item">سبک های سفر</a>
                                    <a href="{{url('tags')}}" class="dropdown-item">تگ ها</a>
                                    <a href="{{url('placeStyle')}}" class="dropdown-item">سبک مکان</a>
                                    <a href="{{url('picItems')}}" class="dropdown-item">آیتم تصاویر</a>
                                    <a href="{{url('questions')}}" class="dropdown-item">سئوالات نظرسنجی</a>
                                </div>
                            </li>

                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-flask"></i> <span class="mini-dn">مدال ها</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('levels')}}" class="dropdown-item">تعیین سطوح</a>
                                    <a href="{{route('medals')}}" class="dropdown-item">تعیین مدال ها</a>
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-bar-chart-o"></i> <span class="mini-dn">مدیریت کاربران</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown chart-left-menu-std animated flipInX">
                                    <a href="{{route('users')}}" class="dropdown-item">کاربران</a>
                                    <a href="{{route('register')}}" class="dropdown-item">افزودن ادمین جدید</a>
                                    <a href="area-charts.html" class="dropdown-item">Area Charts</a>
                                    <a href="rounded-chart.html" class="dropdown-item">Rounded Charts</a>
                                    <a href="c3.html" class="dropdown-item">C3 Charts</a>
                                    <a href="sparkline.html" class="dropdown-item">Sparkline Charts</a>
                                    <a href="peity.html" class="dropdown-item">Peity Charts</a>
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span class="mini-dn">مدیریت تبلیغات</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('company')}}" class="dropdown-item">شرکت های تبلیغاتی</a>
                                    <a href="{{route('section')}}" class="dropdown-item">قسمت های تبلیغاتی</a>
                                    <a href="{{route('seeAds')}}" class="dropdown-item">تبلیغات</a>
                                </div>
                            </li>
                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span class="mini-dn">گزارشات</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('systemLastActivities')}}" class="dropdown-item">آخرین فعالیت های سیستمی</a>
                                </div>
                            </li>

                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span class="mini-dn">پیام رسانی</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('sendMsg')}}" class="dropdown-item">ارسال پیام</a>
                                    <a href="{{route('msgs')}}" class="dropdown-item">پیام های ارسال شده</a>
                                </div>
                            </li>

                            <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span class="mini-dn">پروفایل کاربری</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                                <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                    <a href="{{route('changePass')}}" class="dropdown-item">تغییر رمزعبور</a>
                                    <a href="{{route('logout')}}" class="dropdown-item">خروج</a>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('uiFeatures')}}"  role="button" class="nav-link dropdown-toggle">
                                    <i class="fa big-icon fa-table"></i>
                                    <span class="mini-dn">UI Features</span>
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{route('login')}}" aria-expanded="false"><i class="fa big-icon fa-login"></i> <span class="mini-dn">ورود</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>

        <div class="content-inner-all">
            @yield('content')
        </div>


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