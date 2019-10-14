@extends('layouts.structure')

@section('header')
    @parent

    <style>

        .col-xs-6 {
            float: right;
        }

    </style>

@stop

@section('content')

    <div class="col-md-2"></div>

    <div class="col-md-8">

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd">
                    <h1>پست های منتخب</h1>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">

                <center class="col-xs-12">

                    @if(count($posts) == 0)
                        <p>پستی موجود نیست</p>
                    @else
                        @foreach($posts as $post)
                            <div id="post_{{$post->id}}" class="col-xs-6" style="padding: 10px">
                                <p>{{$post->title}}</p>
                                <img onmouseenter="" style="width: 80%" src="{{URL::asset('posts/' . $post->pic)}}">
                                <div id="addToFavorite_{{$post->id}}" class="col-xs-12 {{($post->favorited) ? 'hidden' : ''}}" style="margin-top: 10px">
                                    <button onclick="addToFavoritePosts('{{$post->id}}')" class="btn btn-success">انتخاب به عنوان پست منتخب</button>
                                </div>
                                <div id="deleteFromFavorite_{{$post->id}}" class="col-xs-12 {{(!$post->favorited) ? 'hidden' : ''}}" style="margin-top: 10px">
                                    <button onclick="deleteFromFavoritePosts('{{$post->id}}')" class="btn btn-warning">حذف از پست منتخب</button>
                                </div>

                                <div id="addToBanner_{{$post->id}}" class="col-xs-12 {{($post->bannered ? 'hidden' : '')}}" style="margin-top: 10px">
                                    <button onclick="addToBannerPosts('{{$post->id}}')" class="btn btn-success">انتخاب به عنوان پست بنر</button>
                                </div>

                                <div id="deleteFromBanner_{{$post->id}}" class="col-xs-12 {{(!$post->bannered ? 'hidden' : '')}}" style="margin-top: 10px">
                                    <button onclick="deleteFromBannerPosts('{{$post->id}}')" class="btn btn-danger">حذف از پست بنر</button>
                                </div>

                                <div class="col-xs-12" style="margin-top: 10px">
                                    <button onclick="document.location.href = '{{route('editPost', ['id' => $post->id])}}'" class="btn btn-primary">ویرایش پست</button>
                                </div>
                                <div class="col-xs-12" style="margin-top: 10px">
                                    <button onclick="deletePost('{{$post->id}}')" class="btn btn-danger">حذف پست</button>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </center>

                <center class="col-xs-12">
                    <button onclick="document.location.href = '{{route('createPost')}}'" class="btn btn-primary">افزودن پست جدید</button>
                    <button class="btn btn-primary">افزودن دسته ای پست</button>
                </center>

            </div>
        </div>

    </div>

    <div class="col-md-2"></div>

    <script>

        function deletePost(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('deletePost')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok")
                        $("#post_" + postId).remove();

                }
            });
        }

        function deleteFromFavoritePosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('deleteFromFavoritePosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok") {
                        $("#deleteFromFavorite_" + postId).addClass('hidden');
                        $("#addToFavorite_" + postId).removeClass('hidden');
                    }

                }
            });
        }

        function deleteFromBannerPosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('deleteFromBannerPosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok") {
                        $("#deleteFromBanner_" + postId).addClass('hidden');
                        $("#addToBanner_" + postId).removeClass('hidden');
                    }

                }
            });
        }

        function addToBannerPosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('addToBannerPosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok") {
                        $("#deleteFromBanner_" + postId).removeClass('hidden');
                        $("#addToBanner_" + postId).addClass('hidden');
                    }

                }
            });

        }

        function addToFavoritePosts(postId) {

            $.ajax({
                type: 'post',
                url: '{{route('addToFavoritePosts')}}',
                data: {
                    'postId': postId
                },
                success: function (res) {
                    if(res == "ok"){
                        $("#deleteFromFavorite_" + postId).removeClass('hidden');
                        $("#addToFavorite_" + postId).addClass('hidden');
                    }

                }
            });

        }
        
    </script>
    
@stop