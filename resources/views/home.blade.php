@extends('layouts.structure')

@section('header')
    @parent

    <style>
        *{
            direction: rtl;
        }
    </style>
@stop

@section('content')

    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-hd">
                            <div style="direction: rtl" class="main-sparkline13-hd">
                                <div class="sparkline13-outline-icon">
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="income-order-visit-user-area">
                                        <div class="container-fluid">
                                            <div class="row">

                                                <?php
                                                    if(auth()->check())
                                                        $userACL = \App\models\ACL::where('userId', auth()->user()->id)->first();
                                                ?>

                                                @if(isset($userACL) && $userACL->comment == 1)
                                                    @if(count($photographerNotAgree) > 0)
                                                        <div class="col-lg-3">
                                                            <a href="{{route('photographer.index')}}">
                                                                <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                <div class="income-dashone-pro">
                                                                    <div class="income-rate-total">
                                                                        <div class="price-adminpro-rate">
                                                                            <h3>
                                                                                {{count($photographerNotAgree)}} عکس تایید نشده عکاسان
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($newCommentCount > 0)
                                                        <div class="col-lg-3">
                                                            <a href="{{route('comments.list')}}">
                                                                <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                <div class="income-dashone-pro">
                                                                    <div class="income-rate-total">
                                                                        <div class="price-adminpro-rate">
                                                                            <h3>
                                                                                {{$newCommentCount}} کامنت جدید
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($newReviews > 0)
                                                        <div class="col-lg-3">
                                                            <a href="{{route('reviews.index')}}">
                                                                <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                <div class="income-dashone-pro">
                                                                    <div class="income-rate-total">
                                                                        <div class="price-adminpro-rate">
                                                                            <h3>
                                                                                {{$newReviews}} نقد جدید
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($newReports > 0)
                                                        <div class="col-lg-3">
                                                            <a href="{{route('user.report.index')}}">
                                                                <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                <div class="income-dashone-pro">
                                                                    <div class="income-rate-total">
                                                                        <div class="price-adminpro-rate">
                                                                            <h3>
                                                                                {{$newReports}}گزارش جدید
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($newQuestions > 0)
                                                        <div class="col-lg-3">
                                                            <a href="{{route('user.quesAns.index')}}">
                                                                <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                    <div class="income-dashone-pro">
                                                                        <div class="income-rate-total">
                                                                            <div class="price-adminpro-rate">
                                                                                <h3>
                                                                                    {{$newQuestions}}سوال و جواب جدید
                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($newMsg > 0)
                                                        <div class="col-lg-3">
                                                            <a href="{{route('user.message.index')}}">
                                                                <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                    <div class="income-dashone-pro">
                                                                        <div class="income-rate-total">
                                                                            <div class="price-adminpro-rate">
                                                                                <h3>
                                                                                    {{$newMsg}} پیام کاربر جدید
                                                                                </h3>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif

                                                @if(isset($userACL) && $userACL->vod == 1 && $newVideoComments > 0)

                                                    <div class="col-lg-3">
                                                        <a href="{{route('vod.video.comments')}}">
                                                            <div class="income-dashone-total income-monthly shadow-reset nt-mg-b-30">
                                                                <div class="income-dashone-pro">
                                                                    <div class="income-rate-total">
                                                                        <div class="price-adminpro-rate">
                                                                            <h3>
                                                                                {{$newVideoComments}} کامنت ویدیو جدید
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
