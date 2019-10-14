@extends('layouts.structure')

@section('header')
    <link rel="stylesheet" href="{{URL::asset('css/switch.css')}}">
    @parent

    <style>

        .myLabel {
            padding: 10px;
            min-width: 200px;
            display: inline-block;
            direction: rtl;
        }

    </style>

@stop

@section('content')


    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>مدیریت لینک ها</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="row">

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('nearby')" type="checkbox" {{($access->nearbyNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">مکان های نزدیک در صفحه hotel-datail</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('similar')" type="checkbox" {{($access->similarNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span style="direction: rtl" class="myLabel">مکان های مشابه در صفحه hotel-datail</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('panel')" type="checkbox" {{($access->panelNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک به سایت پنل</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('profile')" type="checkbox" {{($access->profileNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه پروفایل</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('comment')" type="checkbox" {{($access->writeCommentNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه نوشتن کامنت</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('trip')" type="checkbox" {{($access->myTripNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه ایجاد سفر</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('hotelList')" type="checkbox" {{($access->hotelListNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه hotel-list</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('bookmark')" type="checkbox" {{($access->bookmarkNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه نشانه های من</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('policy')" type="checkbox" {{($access->policyNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه راهنما و قوانین</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('site')" type="checkbox" {{($access->externalSiteNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های خارجی به صفحات دارنده محتوا</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('otherProfile')" type="checkbox" {{($access->otherProfileNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه پروفایل دیگران</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('allAns')" type="checkbox" {{($access->allAnsNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه تمام پاسخ ها</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('allComments')" type="checkbox" {{($access->allCommentsNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه تمام کامنت ها</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('backToHotelList')" type="checkbox" {{($access->backToHotelListNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های بازگشت به hotel-list </span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('showReview')" type="checkbox" {{($access->showReviewNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">صفحه رویت کامنت کاربر </span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('facebook')" type="checkbox" {{($access->facebookNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به facebook</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('telegram')" type="checkbox" {{($access->telegramNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به telegram</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('googlePlus')" type="checkbox" {{($access->googlePlusNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به googlePlus</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('twitter')" type="checkbox" {{($access->twitterNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به twitter</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('aparat')" type="checkbox" {{($access->aparatNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به aparat</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('instagram')" type="checkbox" {{($access->instagramNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به instagram</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('bogen')" type="checkbox" {{($access->bogenNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به بوگن دیزاین</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('gardeshname')" type="checkbox" {{($access->gardeshnameNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به گردشنامه</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('linkedin')" type="checkbox" {{($access->linkedinNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به linkedin</span>
                    </div>

                    <div class="col-xs-12">
                        <label class="switch">
                            <input onchange="changeNoFollow('pinterest')" type="checkbox" {{($access->pinterestNoFollow) ? 'checked' : ''}}>
                            <span class="slider round"></span>
                        </label>
                        <span class="myLabel">لینک های به pinterest</span>
                    </div>

                </center>
            </div>

        </div>
    </div>

    <div class="col-md-2"></div>

    <script>

        function changeNoFollow(val) {

            $.ajax({
                type: 'post',
                url: '{{route('changeNoFollow')}}',
                data: {
                    'val': val
                }
            });

        }

    </script>

@stop