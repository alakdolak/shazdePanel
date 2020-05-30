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
                                <div class="sparkline13-outline-icon" style="display: flex; align-items: center">
                                    <h1>
                                        دسته بندی ویدیوها
                                    </h1>

                                    <div class="addIcon" onclick="newCategoryFunc(0)">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="row" style="height: 40px"></div>
                            @foreach($category as $cat)
                                <div class="row" style="padding: 10px; border: solid #ebebeb; border-radius: 10px;">
                                    <div class="col-md-12" style="display: flex; align-items: center; flex-direction: row-reverse; justify-content: space-between">
                                        <div style="display: flex; align-items: center; flex-direction: row-reverse">
                                            <h4 style="text-align: right">{{$cat->name}}</h4>
                                            <div class="addIcon" onclick="newCategoryFunc({{$cat->id}})">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-danger" onclick="deleteCategory({{$cat->id}})">حذف</button>
                                            <button class="btn btn-primary" onclick="editCategory({{$cat->id}})">ویرایش</button>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="padding-right: 40px">
                                        <table id="table" class="table table-striped table-bordered" dir="rtl">
                                            @foreach($cat->sub as $item)
                                                <tr id="row_{{$item->id}}">
                                                    <td>
                                                        {{$item->name}}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary" onclick="editCategory({{$item->id}})">ویرایش</button>
                                                        <button class="btn btn-danger" onclick="deleteCategory({{$item->id}})">حذف</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
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
                    <input type="hidden" id="categoryId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoryParent">نوع دسته بندی</label>
                                <select name="categoryParent" id="categoryParent" class="form-control" onchange="changeParent(this.value)">
                                    <option value="0">اصلی</option>
                                    @foreach($category as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoryName">نام دسته بندی</label>
                                <input type="text" id="categoryName" name="categoryName" class="form-control">
                            </div>
                        </div>

                    </div>

                    <div class="row" id="mainCategoryInputSection">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mainIconInput">ایکون اصلی</label>
                                <input type="file" name="mainIconInput" id="mainIconInput" onchange="showNewIconPic('mainIcon', this)">
                            </div>
                            <img src="#" id="mainIcon" style="width: 150px; height: 150px;">
                        </div>
                    </div>

                    <div class="row" id="categoryPicSection">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="onIconInput">حالت روشن</label>
                                <input type="file" name="onIconInput" id="onIconInput" onchange="showNewIconPic('onIcon', this)">
                            </div>
                            <img src="#" id="onIcon" style="width: 150px; height: 150px; background: gray;">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="offIconInput">حالت خاموش</label>
                                <input type="file" name="offIconInput" id="offIconInput" onchange="showNewIconPic('offIcon', this)">
                            </div>
                            <img src="#" id="offIcon" style="width: 150px; height: 150px">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="bannerInput">عکس بنر پس زمینه</label>
                            <input type="file" name="bannerInput" id="bannerInput" onchange="showNewIconPic('banner', this)">
                        </div>
                        <img src="#" id="banner" style="width: 100%; height: 150px; background: gray;">
                    </div>

                </div>

                <div class="modal-footer" style="display: flex; justify-content: center; align-items: center">
                    <button class="btn btn-success" onclick="storeCategory()">ذخیره</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>

            </div>
        </div>
    </div>

        <script>
            let allCategory = {!! $allCategory !!};

            let newOnIcon = null;
            let newOffIcon = null;
            let newBanner = null;
            let newMainIcon = null;
            function newCategoryFunc(_kind){
                $('#thumbnailModalHeader').text('افزودن دسته بندی جدید');
                $('#categoryId').val(0);
                $('#categoryName').val('');
                $('#categoryParent').val(_kind);
                changeParent(_kind);

                newOnIcon = null;
                newOffIcon = null;
                newBanner = null;
                newMainIcon = null;
                $('#onIconInput').val('');
                $('#bannerInput').val('');
                $('#mainIconInput').val('');
                $('#offIconInput').val('');
                $('#offIcon').attr('src', '');
                $('#onIcon').attr('src', '');
                $('#banner').attr('src', '');
                $('#mainIcon').attr('src', '');

                $('#newCategory').modal({backdrop: 'static', keyboard: false});
            }

            function changeParent(_value){
                if(_value == 0) {
                    $('#categoryPicSection').css('display', 'none');
                    $('#mainCategoryInputSection').css('display', 'flex');
                }
                else {
                    $('#categoryPicSection').css('display', 'flex');
                    $('#mainCategoryInputSection').css('display', 'none');
                }
            }

            function showNewIconPic(_id, _input){
                if (_input.files && _input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        if(_id == 'onIcon')
                            newOnIcon = _input.files[0];
                        else if(_id == 'offIcon')
                            newOffIcon = _input.files[0];
                        else if(_id == 'banner')
                            newBanner = _input.files[0];
                        else if(_id == 'mainIcon')
                            newMainIcon = _input.files[0];

                        $('#'+_id).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(_input.files[0]);
                }
            }

            function editCategory(_id){
                let cat = null;
                allCategory.forEach(item => {
                    if(item.id == _id)
                        cat = item;
                });

                if(cat != null){
                    $('#thumbnailModalHeader').text('ویرایش دسته بندی');
                    $('#categoryId').val(cat.id);
                    $('#categoryName').val(cat.name);
                    $('#categoryParent').val(cat.parent);
                    changeParent(cat.parent);

                    newOnIcon = null;
                    newOffIcon = null;
                    newBanner = null;
                    newMainIcon = null;

                    $('#onIconInput').val('');
                    $('#bannerInput').val('');
                    $('#mainIconInput').val('');
                    $('#offIconInput').val('');
                    $('#offIcon').attr('src', cat.offIcon);
                    $('#onIcon').attr('src', cat.onIcon);
                    $('#banner').attr('src', cat.banner);
                    $('#mainIcon').attr('src', cat.mainIcon);

                    $('#newCategory').modal({backdrop: 'static', keyboard: false});
                }
            }

            function storeCategory(){
                let id = $('#categoryId').val();
                let name = $('#categoryName').val();
                let parent = $('#categoryParent').val();

                let formData = new FormData;
                formData.append('_token', '{{csrf_token()}}');
                formData.append('id', id);
                formData.append('name', name);
                formData.append('parent', parent);

                if(name.trim().length == 0){
                    alert('پر کردن نام الزامی است');
                    return;
                }

                if(id == 0 && parent != 0 && (newOffIcon == null || newOnIcon == null)){
                    alert('عکس حالت روشن و خاموش اجباری است');
                    return;
                }

                if(parent != 0 && newOffIcon != null)
                    formData.append('offIcon', newOffIcon);
                if(parent != 0 && newOnIcon != null)
                    formData.append('onIcon', newOnIcon);
                if(newBanner != null)
                    formData.append('banner', newBanner);
                if(newMainIcon != null)
                    formData.append('mainIcon', newMainIcon);

                openLoading();
                $.ajax({
                    type: 'post',
                    url: '{{route("vod.video.category.store")}}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        try{
                            response = JSON.parse(response);
                            if(response['status'] == 'ok') {
                                location.reload();
                                return;
                            }
                            if(response['status'] == 'nok1')
                                alert('نام وارد شده تکراری است');
                            else
                                alert(response['status']);
                        }
                        catch (e) {
                            console.log(e);
                            alert('Try Error');
                        }
                        closeLoading();
                    },
                    error: function(err){
                        console.log(err);
                        alert('Server Error');
                        closeLoading();
                    }
                })
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
                        else if(response['status'] == 'nok3')
                            alert('ابتدا زیر دسته ها را پاک کنید');
                        else
                            console.log(response['msg']);
                    },
                })
            }
        </script>
@stop
