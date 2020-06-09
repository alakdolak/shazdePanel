@extends('layouts.structure')

@section('header')
    @parent
    <style>
        th, td{
            text-align: right;
        }
        th{
            background-color: #333333;
            color: white;
        }
        .butMain{
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            border-radius: 8px;
            align-items: center;
            cursor: pointer;
            color: white;
        }
        .nav-tabs>li{
            float: right;
        }
        .buttonIcon{
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            font-size: 20px;
            justify-content: center;
            align-items: center;
            color: white;
            cursor: pointer;
        }
        .buttonIcon:hover{
            color: white;
        }
        .picSection{

        }
        .picRow{
            margin-bottom: 30px;
            height: 500px;
            text-align: left;
            display: flex;
        }
        .picButton{
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <link rel="stylesheet" href="{{URL::asset('css/DataTable/jquery.dataTables.css')}}">
@stop

@section('content')

    <div class="data-table-area mg-b-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline13-list shadow-reset">
                        <div class="sparkline13-graph">
                            <div>
                                <h2>
                                    عکس های {{$place->name}}
                                </h2>
                            </div>
                            <hr>
                            <div class="picSection">
                                @foreach($place->pics as $pic)
                                    <div id="pic_{{$pic['name']}}" class="picRow">
                                        <img src="{{$pic['url']}}" style="max-width: 50%; max-height: 100%">
                                        <div class="picButton">
                                            <button class="btn btn-danger" onclick="deletePic('{{ $pic['name'] }}')">
                                                حذف عکس
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        let placeId = {{$place->id}};
        function deletePic(_name){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route('userAddPlace.pics.delete')}}',
                data:{
                    _token: '{{csrf_token()}}',
                    placeId: placeId,
                    name: _name
                },
                success: function(response){
                    closeLoading();
                    response = JSON.parse(response);
                    if(response['status'] == 'ok')
                        document.getElementById('pic_' + _name).style.display = 'none';
                    else
                        alert('مشکلی در حذف پیش امد دوباره تلاش کنید.');
                },
                error: function(err){
                    closeLoading();
                    alert('مشکلی در حذف پیش امد دوباره تلاش کنید.')
                }
            })
        }
    </script>
@endsection
