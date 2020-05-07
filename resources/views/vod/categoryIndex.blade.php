@extends('layouts.structure')

@section('header')
    @parent
    <style>
        th, td{
            text-align: right;
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
                                    <h1>
                                        دسته بندی ویدیوها
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <table id="table" class="table table-striped  table-bordered" dir="rtl">
                                @foreach($category as $item)
                                    <tr id="row_{{$item->id}}">
                                        <td>
                                            <input type="text" class="form-control" value="{{$item->name}}" onchange="editCategory({{$item->id}}, this)">
                                        </td>
                                        <td>
                                            <button class="btn btn-danger" onclick="deleteCategory({{$item->id}})">حذف</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="row">
                                <button class="btn btn-success" onclick="addNewVideoCategory()">افزودن</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newCategory" style="direction: rtl; text-align: right">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <input type="hidden" id="thumbnailModalId">
                <div class="modal-header" style="display: flex;">
                    <h4 class="modal-title" id="thumbnailModalHeader"></h4>
                    <button type="button" class="close" data-dismiss="modal" style="margin-right: auto">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="newCategoryInput">
                </div>

                <div class="modal-footer" style="display: flex; justify-content: center; align-items: center">
                    <button class="btn btn-success" data-dismiss="modal" onclick="editCategory(0, 'null')">ذخیره</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>

            </div>
        </div>
    </div>

        <script !src="">
            function editCategory(_id, _element){
                let name;
                if(_id != 0)
                    name = _element.value;
                else
                    name = $('#newCategoryInput').val();

                $.ajax({
                    type: 'post',
                    url: '{{route("vod.video.category.store")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        name: name,
                        id: _id
                    },
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok'){
                                if(_id == 0)
                                    createNewRow(response['id'], name)
                            }
                            else if(response['status'] == 'nok3'){
                                alert('دسته بندی با این نام موجود است');
                                if(response['id'] != 0)
                                    $(_element).val(response['name']);
                            }
                        }
                        catch (e) {
                            console.log(e)
                        }
                    },
                    error: function(err){
                        console.log(err);
                    }
                })
            }

            function createNewRow(_id, _name){
                text = '<tr id="row_' + _id + '">\n' +
                    '<td>\n' +
                    '<input type="text" class="form-control" value="' + _name + '" onchange="editCategory(' + _id + ', this)">\n' +
                    '</td>\n' +
                    '<td>\n' +
                    '<button class="btn btn-danger" onclick="deleteCategory(' + _id + ')">حذف</button>\n' +
                    '</td>\n' +
                    '</tr>';

                $('#table').append(text);
            }

            function addNewVideoCategory(){
                $('#newCategoryInput').val();
                $('#newCategory').modal('show');
            }

            function deleteCategory(_id){
                $.ajax({
                    type: 'post',
                    url: '{{route("vod.video.category.delete")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        id: _id
                    },
                    success: function(response){
                        response = JSON.parse(response);
                        if(response['status'] == 'ok')
                            $('#row_' + _id).remove();
                        else if(response['status'] == 'nok2')
                            alert('از این دسته بندی در ویدیو ها اسفاده شده است. ابتدا باید انهارا تغییر دهید');
                        else
                            console.log(response['msg']);
                    },
                })
            }
        </script>
@stop
