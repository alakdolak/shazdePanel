@extends('layouts.structure')

@section('header')
    @parent

    <style>
        *{
            direction: rtl;
        }

        .picLabel{
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .picLabel img{
            width: 200px;
            height: auto;
        }
        .picLabel.hasPic{
            opacity: 1;
        }
        .picLabel.nonePic{
            opacity: .1;
        }
        .footerButtons{
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: solid 1px gray;
        }
    </style>
@stop

@section('content')
    <div class="mainBody">
        <div class="header">
            {{$festival != null ? 'ویرایش فستیوال '.$festival->name : 'ایجاد فستیوال جدید'}}
        </div>

        <div class="body">
            <div class="container" style="width: 100%">
                <input id="festivalId" type="hidden" name="id" value="{{isset($festival->id) ? $festival->id : 0}}">
                <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="festivalName">نام فستیوال</label>
                                <input id="festivalName" name="name" type="text" class="form-control" value="{{isset($festival->name) ? $festival->name : ''}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="festivalUrl">URL</label>
                                <input id="festivalUrl" name="url" type="text" class="form-control" value="{{isset($festival->pageUrl) ? $festival->pageUrl : ''}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="festivalDescription">توضیحات</label>
                                <textarea id="festivalDescription" name="description" class="form-control">{!! isset($festival->description) ? $festival->description : '' !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>عکس</label>
                                <label id="picLabel" for="picFile" class="picLabel {{isset($festival->picture) != null ? 'hasPic' : 'nonePic'}}">
                                    <img src="{{$pic}}">
                                </label>
                                <input type="file" accept="image/*" id="picFile" name="pic" style="display: none;" onchange="changePicture(this)">
                            </div>
                        </div>
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="festivalSubs">زیرمجموعه ها</label>--}}
{{--                                <div class="subsSec">--}}
{{--                                    --}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="col-md-12 footerButtons">
                            <button type="button" class="btn btn-success" onclick="checkSubmit()">ثبت</button>
                            <a href="{{route('festivals')}}" type="button" class="btn btn-secondery">بازگشت</a>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script>
        function changePicture(_input){
            if (_input.files && _input.files[0]) {
                var reader = new FileReader();
                reader.onload = e => {
                    $('#picLabel').addClass('hasPic').removeClass('nonePic');
                    $('#picLabel').find('img').attr('src', e.target.result)
                };
                reader.readAsDataURL(_input.files[0]);
            }
        }

        function checkSubmit(){
            var id = $('#festivalId').val();
            var name = $('#festivalName').val();
            var url = $('#festivalUrl').val();
            var description = $('#festivalDescription').val();
            var file = document.getElementById('picFile').files[0];

            if(name.trim().length < 2){
                alert('نام فسیوال را مشخص کنید.');
                return;
            }
            if(file && id == 0){
                alert('عکس فسیوال را مشخص کنید.');
                return;
            }

            var formData = new FormData();
            formData.append('id', id);
            formData.append('name', name);
            formData.append('url', url);
            formData.append('description', description);
            formData.append('file', file);

            openLoading();

            $.ajax({
                type: 'post',
                url: '{{route('festival.update')}}',
                data:formData,
                processData: false,
                contentType: false,
                success: response => {
                    closeLoading();
                    console.log(response);
                    if(response.status == 'ok'){
                        alert('تغییرات با موفقیت ثبت شد');
                        if(id == 0)
                            location.href = '{{url("festivals/edit/")}}/'+response.result;
                    }
                    else
                        alert('Error');
                },
                error: err => {
                    closeLoading();
                    console.log(err);
                    alert('Error 500');
                }
            })


        }
    </script>

@stop
