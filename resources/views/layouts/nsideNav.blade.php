<style>
    .nSideBar{

    }
    .nSideBar .logoSide{
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: solid white 2px;
        padding-bottom: 10px;
    }
    .nSideBar .logoSide img{
        width: 100%;
    }

    .sideLinksSection{
        overflow-y: auto;
        height: calc(100vh - 80px);
    }

    .sideLinksSection .navs{
        color: white;
        font-size: 14px;
        margin: 5px;
        cursor: pointer;
        padding: 5px;
        transition: .3s;
        display: flex;
        flex-direction: column;
    }

    .sideLinksSection div.navs .header:after {
        content: "\f107";
        font: normal normal normal 14px/1 FontAwesome;
    }

    .sideLinksSection div.navs.active .header:after {
        content: "\f106";
        font: normal normal normal 14px/1 FontAwesome;
    }

    .sideLinksSection .navs:hover{
        text-align: center;
        background: white;
        color: #4dc7bc;
        border-radius: 29px;
    }

    .sideLinksSection .navs.active{
        text-align: center;
        background: white;
        color: #4dc7bc;
        border-radius: 29px;
    }

    .sideLinksSection .navs.active .header{
        border-bottom: solid;
    }

    .sideLinksSection .navs .header{
        padding-bottom: 5px;
    }
    .sideLinksSection .navs.active .subMenu{
        display: flex;
    }
    .sideLinksSection .navs .subMenu{
        display: none;
        flex-direction: column;
        text-align: right;
        padding-right: 13px;
    }
    .sideLinksSection .navs .subMenu .subNavs{
        color: #4dc7bc;
        padding: 5px;
        margin: 2px 0px;
        font-size: 13px;
    }

    .sideLinksSection .navs .subMenu .subNavs:hover{
        background: #0076a3;
        border-radius: 10px;
        color: white;
    }
    .closeMobileMenu{
        display: none;
    }

    @media (max-width: 991px) {
        .closeMobileMenu{
            display: flex;
            color: white;
            font-size: 35px;
        }
        .nSideBar .logoSide img{
            display: none;
        }
    }
</style>

<div class="nSideBar">
    <div class="logoSide">
        <img src="{{URL::asset('img/mainLogo.png')}}" >
        <div class="closeMobileMenu" onclick="openMenuSide()">
            <i class="fa fa-times" aria-hidden="true"></i>
        </div>
    </div>
    <div class="sideLinksSection">
        @if(auth()->check())
            <a href="{{url('/')}}" class="navs">
                <div class="header">
                    <i class="fa big-icon fa-home icon"></i>
                    <span class="text">خانه</span>
                </div>
            </a>

            <a class="navs" href="{{route('changePass')}}">
                <div class="header">
                    <i class="fa big-icon fa-home icon"></i>
                    <span class="text">تغییر رمزعبور</span>
                </div>
            </a>

            @if(isset($userACL) && $userACL->vod == 1)
                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa fa-video-camera icon"></i>
                        <span class="text">مدیریت ویدیوها</span>
                    </div>
                    <div class="subMenu">
                        <a href="{{route('vod.index')}}" class="subNavs">لیست ویدیوها</a>
                        <a href="{{route('vod.video.category.index')}}" class="subNavs">دسته بندی ها</a>
                        <a href="{{route('vod.live.index')}}" class="subNavs">مدیریت LIVE</a>
                        <a href="{{route('vod.video.comments')}}" class="subNavs">کامنت ها</a>
                    </div>
                </div>
            @endif

            @if(isset($userACL) && $userACL->post == 1)
                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa big-icon fa-envelope icon"></i>
                        <span class="text">محتوای کوچیتا</span>
                    </div>
                    <div class="subMenu">
                        <a href="{{route('city.index')}}" class="subNavs">اطلاعات شهرها</a>
                        <a href="{{url('topInCity')}}" class="subNavs">پیشنهاد ویژه شهر</a>
                        <a href="{{route('newChangeContent', ['cityId' => 0, 'mode' => 1, 'cityMode' => 'country'])}}" class="subNavs">تغییر محتوای صفحات</a>
                        <a href="{{route('safarnameh.index')}}" class="subNavs">مدیریت سفرنامه ها</a>
                        <a href="{{route('gardeshNameList')}}" class="subNavs">مقاله‌های گردشنامه</a>
                        <a href="{{route('seoTester')}}" class="subNavs">تست  سئو صفحات</a>
                    </div>
                </div>
            @endif

            @if(isset($userACL) && $userACL->comment == 1)
                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa fa-user icon"></i>
                        <span class="text">کنترل محتوای کاربران</span>
                    </div>
                    <div class="subMenu">
                        <a href="{{route('userAddPlace.list')}}" class="subNavs">مدیریت مکان های افزوده شدها</a>
                        <a href="{{route('comments.list')}}" class="subNavs">مدیریت کامنت ها</a>
                        <a href="#" class="subNavs">مدیریت پست‌ها</a>
                        <a href="{{route('user.quesAns.index')}}" class="subNavs">مدیریت پرسش و پاسخ ها</a>
                        <a href="{{route('photographer.index')}}" class="subNavs">عکس های عکاسان</a>
                        <a href="#" class="subNavs">سفرنامه ها</a>
                        <a href="{{route('user.report.index')}}" class="subNavs">گزارش های کاربران</a>
                        <a href="#" class="subNavs">Recycle Bin</a>
                    </div>
                </div>
            @endif

            <div class="navs" onclick="openSubMenu(this)">
                <div class="header">
                    <i class="fa fa-comments icon"></i>
                    <span class="text">پیام های کاربران</span>
                </div>
                <div class="subMenu">
                    <a href="{{route('user.message.index')}}" class="subNavs">پیام رسان کوچیتا</a>
{{--                    <a href="{{route('sendMsg')}}" class="subNavs">ارسال پیام</a>--}}
{{--                    <a href="{{route('msgs')}}" class="subNavs">پیام های ارسال شده</a>--}}
                </div>
            </div>

            @if(isset($userACL) && $userACL->config == 1)

                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa fa-newspaper-o icon"></i>
                        <span class="text">مدیریت تبلیغات</span>
                    </div>

                    <div class="subMenu">
                        <a href="{{route('company')}}" class="subNavs">شرکت های تبلیغاتی</a>
                        <a href="{{route('section')}}" class="subNavs">قسمت های تبلیغاتی</a>
                        <a href="{{route('seeAds')}}" class="subNavs">تبلیغات</a>
                    </div>
                </div>

                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa fa-tasks icon"></i>
                        <span class="text">مدیریت</span>
                    </div>

                    <div class="subMenu">
                        <a href="{{route('mainSuggestion.index')}}" class="subNavs">پیشنهادهای صفحه اول</a>
                        <a href="{{route('slider.index')}}" class="subNavs">عکس اسلاید صفحه اول</a>
                        <a href="{{url('questions')}}" class="subNavs">سئوالات نظرسنجی</a>
                        <a href="{{url('reports')}}" class="subNavs">گزارشات</a>
                        <a href="{{route('manageNoFollow')}}" class="subNavs">مدیریت لینک ها</a>
                        <a href="{{route('lastActivities')}}" class="subNavs">فعالیت های اخیر</a>
                        <a href="{{route('systemLastActivities')}}" class="subNavs">آخرین فعالیت های سیستمی</a>
                        <a href="{{route('levels')}}" class="subNavs">تعیین سطوح</a>
                        <a href="{{route('medals')}}" class="subNavs">تعیین مدال ها</a>
                        <a href="{{route('users')}}" class="subNavs">کاربران</a>
                        <a href="{{route('register')}}" class="subNavs">افزودن ادمین جدید</a>
                        <a href="{{route('uploadMainContent')}}" class="subNavs">افزودن محتوا به صورت گروهی</a>
                    </div>

                </div>

                <div class="navs" onclick="openSubMenu(this)">
                <div class="header">
                    <i class="fa fa-cog icon"></i>
                    <span class="text">تنظیمات</span>
                </div>

                <div class="subMenu">
                    <a href="{{url('ageSentences')}}" class="subNavs">توضیحات سن برای بلیت</a>
                    <a href="{{route('determineRadius')}}" class="subNavs">تعیین شعاع مکان های نزدیک</a>
                    <a href="{{route('ages')}}" class="subNavs">مدیریت سنین</a>
                    <a href="{{route('backup')}}" class="subNavs">مدیریت پشتیبانی</a>
                    <a href="{{route('offers')}}" class="subNavs">مدیریت کد های تخفیف</a>
                </div>
            </div>

                <div class="navs" onclick="openSubMenu(this)">
                    <div class="header">
                        <i class="fa big-icon fa-envelope icon"></i>
                        <span class="text">بی گروه</span>
                    </div>

                    <div class="subMenu">
                        <a href="{{route('activities.index')}}" class="subNavs">فعالیت ها</a>
                        <a href="{{route('defaultPics.index')}}" class="subNavs">تصاویر پیش فرض</a>
                        <a href="{{route('places.index')}}" class="subNavs">اماکن</a>
                        <a href="{{route('tripStyle.index')}}" class="subNavs">سبک های سفر</a>
                        <a href="{{url('tags')}}" class="subNavs">تگ ها</a>
                        <a href="{{url('placeStyle')}}" class="subNavs">سبک مکان</a>
                        <a href="{{url('picItems')}}" class="subNavs">آیتم تصاویر</a>
                        <a href="{{route('uiFeatures')}}" class="subNavs">UI Features</a>
                    </div>
                </div>

            @endif


            <a href="{{route('logout')}}"  role="button" class="navs">
                <div class="header">
                    <i class="fa fa-sign-out icon"></i>
                    <span class="mini-dn text">خروج</span>
                </div>
            </a>
        @else
            <a href="{{route('login')}}" aria-expanded="false" class="navs">
                <div class="header">
                    <i class="fa big-icon fa-login icon"></i>
                    <span class="text">ورود</span>
                    <span class="indicator-right-menu mini-dn">
                    <i class="fa indicator-mn fa-angle-left"></i>
                </span>
                </div>
            </a>
        @endif


    </div>
</div>

<script>
    function openSubMenu(_element) {
        if($(_element).hasClass('active'))
            $('.sideLinksSection').find('.active').removeClass('active');
        else{
            $('.sideLinksSection').find('.active').removeClass('active');
            $(_element).addClass('active')
        }
    }
</script>
