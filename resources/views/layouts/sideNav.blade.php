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

                <li>
                    <a href="{{route('changePass')}}" aria-expanded="false">
                        <i class="fa big-icon fa-home"></i>
                        <span class="mini-dn">تغییر رمزعبور</span>
                    </a>
                </li>

                @if(isset($userACL) && $userACL->vod == 1)
                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fa big-icon fa-envelope"></i>
                            <span class="mini-dn">مدیریت ویدیوها</span>
                            <span class="indicator-right-menu mini-dn">
                                    <i class="fa indicator-mn fa-angle-left"></i>
                                </span>
                            <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                <a href="{{route('vod.index')}}" class="dropdown-item">لیست ویدیوها</a>
                                <a href="{{route('vod.video.category.index')}}" class="dropdown-item">دسته بندی ها</a>
                                <a href="{{route('vod.live.index')}}" class="dropdown-item">مدیریت LIVE</a>
                                <a href="{{route('vod.video.comments')}}" class="dropdown-item">کامنت ها</a>
                            </div>
                        </a>
                    </li>
                @endif

                @if(isset($userACL) && $userACL->post == 1)
                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fa big-icon fa-envelope"></i>
                            <span class="mini-dn">محتوای کوچیتا</span>
                            <span class="indicator-right-menu mini-dn">
                                        <i class="fa indicator-mn fa-angle-left"></i>
                                    </span>
                            <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                <a href="{{route('city.index')}}" class="dropdown-item">اطلاعات شهرها</a>
                                <a href="{{url('topInCity')}}" class="dropdown-item">پیشنهاد ویژه شهر</a>
                                <a href="{{route('newChangeContent', ['cityId' => 0, 'mode' => 1, 'cityMode' => 'country'])}}" class="dropdown-item">تغییر محتوای صفحات</a>
                                <a href="{{route('safarnameh.index')}}" class="dropdown-item">مدیریت سفرنامه ها</a>
                                <a href="{{route('gardeshNameList')}}" class="dropdown-item">مقاله‌های گردشنامه</a>

                                <a href="{{route('seoTester')}}" class="dropdown-item">تست  سئو صفحات</a>
                            </div>
                        </a>
                    </li>
                @endif

                @if(isset($userACL) && $userACL->comment == 1)
                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fa big-icon fa-envelope"></i>
                            <span class="mini-dn">کنترل محتوای کاربران</span>
                            <span class="indicator-right-menu mini-dn">
                                    <i class="fa indicator-mn fa-angle-left"></i>
                                </span>
                            <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                <a href="{{route('userAddPlace.list')}}" class="dropdown-item">مدیریت مکان های افزوده شدها</a>
                                <a href="{{route('comments.list')}}" class="dropdown-item">مدیریت کامنت ها</a>
                                <a href="{{route('reviews.index')}}" class="dropdown-item">مدیریت پست‌ها</a>
                                <a href="{{route('user.quesAns.index')}}" class="dropdown-item">مدیریت پرسش و پاسخ ها</a>
                                <a href="{{route('photographer.index')}}" class="dropdown-item">عکس های عکاسان</a>
                                <a href="{{route('user.report.index')}}" class="dropdown-item">گزارش های کاربران</a>
                                <a href="#" class="dropdown-item">Recycle Bin</a>
                            </div>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="fa big-icon fa-envelope"></i>
                        <span class="mini-dn">پیام های کاربران پنل</span>
                        <span class="indicator-right-menu mini-dn">
                                        <i class="fa indicator-mn fa-angle-left"></i>
                                    </span>
                    </a>
                </li>

                @if(isset($userACL) && $userACL->config == 1)
                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fa big-icon fa-envelope"></i>
                            <span class="mini-dn">مدیریت</span>
                            <span class="indicator-right-menu mini-dn">
                                        <i class="fa indicator-mn fa-angle-left"></i>
                                    </span>
                            <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                <a href="{{route('mainSuggestion.index')}}" class="dropdown-item">پیشنهادهای صفحه اول</a>
                                <a href="{{route('slider.index')}}" class="dropdown-item">عکس اسلاید صفحه اول</a>
                                <a href="{{url('questions')}}" class="dropdown-item">سئوالات نظرسنجی</a>

                                <a href="{{url('reports')}}" class="dropdown-item">گزارشات</a>
                                <a href="{{route('manageNoFollow')}}" class="dropdown-item">مدیریت لینک ها</a>
                                <a href="{{route('lastActivities')}}" class="dropdown-item">فعالیت های اخیر</a>
                                <a href="{{route('systemLastActivities')}}" class="dropdown-item">آخرین فعالیت های سیستمی</a>

                                <a href="{{route('levels')}}" class="dropdown-item">تعیین سطوح</a>
                                <a href="{{route('medals')}}" class="dropdown-item">تعیین مدال ها</a>

                                <a href="{{route('users')}}" class="dropdown-item">کاربران</a>
                                <a href="{{route('register')}}" class="dropdown-item">افزودن ادمین جدید</a>

                                <a href="{{route('uploadMainContent')}}" class="dropdown-item">افزودن محتوا به صورت گروهی</a>
                            </div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fa big-icon fa-envelope"></i>
                            <span class="mini-dn">تنظیمات</span>
                            <span class="indicator-right-menu mini-dn">
                                        <i class="fa indicator-mn fa-angle-left"></i>
                                    </span>
                            <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                <a href="{{url('ageSentences')}}" class="dropdown-item">توضیحات سن برای بلیت</a>
                                <a href="{{route('determineRadius')}}" class="dropdown-item">تعیین شعاع مکان های نزدیک</a>
                                <a href="{{route('ages')}}" class="dropdown-item">مدیریت سنین</a>
                                <a href="{{route('backup')}}" class="dropdown-item">مدیریت پشتیبانی</a>
                                <a href="{{route('offers')}}" class="dropdown-item">مدیریت کد های تخفیف</a>
                            </div>
                        </a>
                    </li>

                    <li class="nav-item"><a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i class="fa big-icon fa-table"></i> <span class="mini-dn">مدیریت تبلیغات</span> <span class="indicator-right-menu mini-dn"><i class="fa indicator-mn fa-angle-left"></i></span></a>
                        <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                            <a href="{{route('company')}}" class="dropdown-item">شرکت های تبلیغاتی</a>
                            <a href="{{route('section')}}" class="dropdown-item">قسمت های تبلیغاتی</a>
                            <a href="{{route('seeAds')}}" class="dropdown-item">تبلیغات</a>

                            <a href="{{route('sendMsg')}}" class="dropdown-item">ارسال پیام</a>
                            <a href="{{route('msgs')}}" class="dropdown-item">پیام های ارسال شده</a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fa big-icon fa-envelope"></i>
                            <span class="mini-dn">بی گروه</span>
                            <span class="indicator-right-menu mini-dn">
                                        <i class="fa indicator-mn fa-angle-left"></i>
                                    </span>
                            <div role="menu" class="dropdown-menu left-menu-dropdown animated flipInX">
                                <a href="{{route('activities.index')}}" class="dropdown-item">فعالیت ها</a>
                                <a href="{{route('defaultPics.index')}}" class="dropdown-item">تصاویر پیش فرض</a>
                                <a href="{{route('places.index')}}" class="dropdown-item">اماکن</a>
                                <a href="{{route('tripStyle.index')}}" class="dropdown-item">سبک های سفر</a>
                                <a href="{{url('tags')}}" class="dropdown-item">تگ ها</a>
                                <a href="{{url('placeStyle')}}" class="dropdown-item">سبک مکان</a>
                                <a href="{{url('picItems')}}" class="dropdown-item">آیتم تصاویر</a>
                                <a href="{{route('uiFeatures')}}" class="dropdown-item">UI Features</a>

                            </div>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{route('logout')}}"  role="button" class="nav-link dropdown-toggle">
                        <i class="fa big-icon fa-table"></i>
                        <span class="mini-dn">خروج</span>
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
