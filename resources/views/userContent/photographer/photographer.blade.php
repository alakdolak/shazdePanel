@extends('layouts.structure')

@section('header')
    @parent
    <style>
        th, td{
            text-align: right;
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
                                    <h3>عکس های تایید نشده</h3>
                                    <table class="table table-striped  table-bordered" dir="rtl">
                                        <thead>
                                            <tr>
                                                <th>نام کاربری</th>
                                                <th>نوع محل</th>
                                                <th>نام مکان</th>
                                                <th>شهر مکان</th>
                                                <th>عکس</th>
                                                <th>نام عکس</th>
                                                <th>توضیح عکس</th>
                                                <th>alt عکس</th>
                                                <th>تاریخ بارگزاری</th>
                                                <th>نمایش در slider</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for($i = 0; $i < count($photo); $i++)
                                                <tr>
                                                    <td>{{$photo[$i]->userName}}</td>
                                                    <td>{{$photo[$i]->kindPlace}}</td>
                                                    <td>
                                                        <a href="{{$photo[$i]->url}}" target="_blank">
                                                            {{$photo[$i]->placeName}}
                                                        </a>
                                                    </td>
                                                    <td>استان {{$photo[$i]->state->name}} شهر {{$photo[$i]->city->name}}</td>
                                                    <td>
                                                        <a onclick="showPics(0, {{$i}})"> مشاهده عکس </a>
                                                    </td>
                                                    <td>{{$photo[$i]->name}}</td>
                                                    <td style="max-width: 200px;">{{$photo[$i]->description}}</td>
                                                    <td>{{$photo[$i]->alt}}</td>
                                                    <td>{{$photo[$i]->uploadDate}}</td>
                                                    <td>
                                                        <input type="checkbox" onclick="showInSliderCheckBox({{$photo[$i]->id}}, this)" {{$photo[$i]->isSitePic == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-success" onclick="submitPic(0, {{$i}})">تایید</button>
                                                        <button class="btn btn-danger" onclick="deletePic(0, {{$i}})">حذف</button>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <hr>

                                <div class="row">
                                    <h3>عکس های تایید شده</h3>
                                    <table class="table table-striped  table-bordered" dir="rtl">
                                        <thead>
                                            <tr>
                                                <th>نام کاربری</th>
                                                <th>نوع محل</th>
                                                <th>نام مکان</th>
                                                <th>شهر مکان</th>
                                                <th>عکس</th>
                                                <th>نام عکس</th>
                                                <th>توضیح عکس</th>
                                                <th>alt عکس</th>
                                                <th>تاریخ بارگزاری</th>
                                                <th>نمایش در slider</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for($i = 0; $i < count($oldPhoto); $i++)
                                                <tr>
                                                    <td>{{$oldPhoto[$i]->userName}}</td>
                                                    <td>{{$oldPhoto[$i]->kindPlace}}</td>
                                                    <td>
                                                        <a href="{{$oldPhoto[$i]->url}}" target="_blank">
                                                            {{$oldPhoto[$i]->placeName}}
                                                        </a>
                                                    </td>
                                                    <td>استان {{$oldPhoto[$i]->state->name}} شهر {{$oldPhoto[$i]->city->name}}</td>
                                                    <td>
                                                        <a onclick="showPics(1, {{$i}})">
                                                            مشاهده عکس
                                                        </a>
                                                    </td>
                                                    <td>{{$oldPhoto[$i]->name}}</td>
                                                    <td style="max-width: 200px;">{{$oldPhoto[$i]->description}}</td>
                                                    <td>{{$oldPhoto[$i]->alt}}</td>
                                                    <td>{{$oldPhoto[$i]->uploadDate}}</td>
                                                    <td>
                                                        <input type="checkbox" onclick="showInSliderCheckBox({{$oldPhoto[$i]->id}}, this)" {{$oldPhoto[$i]->isSitePic == 1 ? 'checked' : ''}}>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger" onclick="deletePic(1, {{$i}})">حذف</button>
                                                    </td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showPics">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <img src="" id="mainPic">
                    </div>
                    <div class="row">
                        <img src="" id="sPic">
                    </div>
                    <div class="row">
                        <img src="" id="fPic">
                    </div>
                    <div class="row">
                        <img src="" id="lPic">
                    </div>
                    <div class="row">
                        <img src="" id="tPic">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">خروج</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="deletePic">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="text-align: right; padding-right: 40px; font-size: 20px;">
                    <div class="row">
                        ایا می خواهید عکس ها را پاک کنید؟
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: center">
                    <form id="deleteForm" method="post" action="{{route('photographer.delete')}}">
                        @csrf
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-danger">بله</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">لغو</button>
                </div>

            </div>
        </div>
    </div>

    <form action="{{route('photographer.submit')}}" method="post" id="submitForm">
        @csrf
        <input type="hidden" name="id" id="submitId">
    </form>

    <script>
        var photos = {!! $photo !!};
        var oldPhoto = {!! $oldPhoto !!};

        function showPics(_kind, _index) {
            var p;
            if(_kind == 1)
                p = oldPhoto[_index];
            else
                p = photos[_index];

            document.getElementById('mainPic').src = p['pics']['mainPic'];
            document.getElementById('sPic').src = p['pics']['s'];
            document.getElementById('fPic').src = p['pics']['f'];
            document.getElementById('tPic').src = p['pics']['t'];
            document.getElementById('lPic').src = p['pics']['l'];

            $('#showPics').modal('show');
        }

        function deletePic(_kind, _index){
            var p;
            if(_kind == 1)
                p = oldPhoto[_index];
            else
                p = photos[_index];

            document.getElementById('deleteId').value = p['id'];
            $('#deletePic').modal('show');
        }

        function submitPic(_kind, _index){
            var p;
            if(_kind == 1)
                p = oldPhoto[_index];
            else
                p = photos[_index];
            document.getElementById('submitId').value = p['id'];
            $('#submitForm').submit();
        }

        function showInSliderCheckBox(_id, _element){
            openLoading();

            $.ajax({
                type: "POST",
                url: '{{route("photographer.showInSlider")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: _id
                },
                complete: closeLoading,
                success: response =>{
                    if(response.status !== "ok") {
                        $(_element).prop('checked', !$(_element).prop('checked'));
                        alert('خطا در ویرایش');
                    }
                },
                error: err =>{
                    $(_element).prop('checked', !$(_element).prop('checked'));
                    alert('خطا در ویرایش')
                }
            })
        }
    </script>

@stop
