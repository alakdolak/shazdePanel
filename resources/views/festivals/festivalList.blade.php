@extends('layouts.structure')

@section('header')
    @parent

    <style>
        *{
            direction: rtl;
        }
        thead tr th{
            text-align: right;
        }

        .newFestivalButton{
            margin-right: auto;
            font-size: 10px;
        }
        .festivalPicDiv{
            height: 100px;
        }
        .festivalPicDiv img{
            height: 100%;
        }
        .btn-koochitaLight{
            color: white;
            background: var(--koochita-light-green);
            border-color: var(--koochita-light-green);
        }

    </style>
@stop

@section('content')
    <div class="mainBody">
        <div class="header">
            لیست فستیوال ها
            <a href="{{route('festivals.edit', ['id' => 0])}}" class="btn btn-success newFestivalButton">ایجاد فستیوال جدید</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>نام</th>
                    <th>عکس</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($festivals as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>
                            <a href="{{$item->pageUrl}}">{{$item->name}}</a>
                        </td>
                        <td>
                            <div class="festivalPicDiv">
                                <img src="{{$item->pic}}">
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-{{$item->status == 1 ? 'success':'warning'}}" onclick="changeFestivalStatus({{$item->id}}, this)">{{$item->status == 1 ? 'فعال':'غیرفعال'}}</button>
                            <a href="{{route('festivals.content', ['id' => $item->id])}}" class="btn btn-koochitaLight">محتوای ارسالی</a>
                            <a href="{{route('festivals.edit', ['id' => $item->id])}}" class="btn btn-primary">ویرایش</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function changeFestivalStatus(_id, _element){

            $.ajax({
                type: 'post',
                url: '{{route("festival.update.stats")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    festivalId: _id
                },
                success: response => {
                    _element = $(_element);
                    if(response == '1')
                        _element.addClass('btn-success').removeClass('btn-warning').text('فعال');
                    else if(response == '0')
                        _element.addClass('btn-warning').removeClass('btn-success').text('غیرفعال');

                }
            })
        }
    </script>
@stop
