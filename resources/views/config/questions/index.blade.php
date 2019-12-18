@extends('layouts.structure')

@section('header')
    @parent

    <style>
        .row{
            direction: rtl;
            display: flex;
        }
        th{
            text-align: right;
        }
        td{
            text-align: right;
        }
    </style>
@stop

@section('content')
    <div class="col-md-12">

        @if(\Session::has('error'))
            <div class="alert alert-danger alert-dismissible " style="direction: rtl">
                <button type="button" class="close" data-dismiss="alert" style="float: left;">&times;</button>
                {{session('error')}}
            </div>
        @endif

        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style="display: flex; justify-content: space-between;">
                    <h1>سوالات نظرسنجی</h1>
                    <a href="{{route('questions.new')}}">
                        <button class="btn btn-success">سوال جدید</button>
                    </a>
                </div>
            </div>

            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages">
                <div class="row" style="padding: 10px; text-align: right">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">جستجو</label>
                            <input type="text" id="search" onkeyup="tableSearch(this.value)">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">جستجو نوع مکان:</label>
                            <select onchange="tableSearchKindPlace(this.value)">
                                @foreach($kindPlace as $item)
                                    <option>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="search">جستجو استانی:</label>
                            <input type="text" id="search" onkeyup="tableSearchOstan(this.value)">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin: 0px; min-height: 100vh">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>سوال</th>
                            <th>نوع سوال</th>
                            <th>در کجا</th>
                            <th>در چه استان ها و شهرهایی</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($questions as $quest)
                            <tr id="row_{{$quest->id}}">
                                <td>
                                    {{$quest->description}}
                                </td>
                                <td>
                                    @if($quest->ansType == 'multi')
                                        <a href="#" data-toggle="tooltip" title="{{$quest->ans}}">{{$quest->typeName}}</a>
                                    @else
                                        {{$quest->typeName}}
                                    @endif
                                </td>
                                <td>
                                    {{$quest->kindName}}
                                </td>
                                <td>
                                    @if($quest->fullState)
                                        {{$quest->stateName}}
                                    @else
                                        <ul>
                                            @foreach($quest->states as $item)
                                                <li>
                                                    @if($item['full'])
                                                        {{$item['stateName']}}
                                                    @else
                                                        <a href="#" data-toggle="tooltip" data-placement="left" title="{{$item['city']}}" style="z-index: 999">{{$item['stateName']}}</a>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </td>
                                <td style="text-align: center;">
                                    <a href="{{url('questions/edit/'.$quest->id)}}">
                                        <button class="btn btn-primary">ویرایش</button>
                                    </a>
                                    <button class="btn btn-danger" onclick="deleteQuestionModal({{$quest->id}})">حذف</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">پاک کردن سوال</h4>
                </div>
                <div class="modal-body">
                    <p>ایا می خواهید سوال را پاک کنید؟.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">خیر</button>
                    <button type="button" class="btn btn-danger" onclick="deleteQuestion()">بله</button>
                </div>
            </div>

        </div>

    <form id="deleteForm" action="{{route('questions.delete')}}" method="post">
        @csrf
        <input type="hidden" name="id" id="deleteId">
    </form>

    <script>
        var questions = {!! $questions !!};
        console.log(questions)

        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip({html: true});
        });

        function deleteQuestionModal(id){
            document.getElementById('deleteId').value = id;
            $('#deleteModal').modal('show');
        }

        function deleteQuestion(){
            $('#deleteForm').submit();
        }

        function tableSearch(_value){

            for(i = 0; i < questions.length; i++){
                var show = false;
                if(questions[i]['description'].includes(_value) || questions[i]['typeName'].includes(_value))
                    show = true;

                if(!show){
                    document.getElementById('row_' + questions[i]['id']).style.display = 'none';
                }
                else
                    document.getElementById('row_' + questions[i]['id']).style.display = 'table-row';

            }

        }

        function tableSearchKindPlace(_value){
            for(i = 0; i < questions.length; i++){
                var show = false;
                if(questions[i]['kindName'].includes(_value) || questions[i]['kindName'] == 'تمامی مکان ها')
                    show = true;

                if(!show){
                    document.getElementById('row_' + questions[i]['id']).style.display = 'none';
                }
                else
                    document.getElementById('row_' + questions[i]['id']).style.display = 'table-row';

            }

        }

        function tableSearchOstan(_value){
            for(i = 0; i < questions.length; i++){
                var show = false;
                if(inState(_value, questions[i]['states']) || questions[i]['stateName'] == "در تمامی استان ها")
                    show = true;

                if(!show){
                    document.getElementById('row_' + questions[i]['id']).style.display = 'none';
                }
                else
                    document.getElementById('row_' + questions[i]['id']).style.display = 'table-row';

            }

        }

        function inState(_value, _state){
            console.log(_state == null);
            if(_state == null)
                return true;
            else{

            }
        }



    </script>

@stop