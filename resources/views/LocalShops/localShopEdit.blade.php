@extends('layouts.structure')

@section('header')
    @parent

    <style>
        *{
            direction: rtl;
        }
        th{
            text-align: right;
        }

        .contentBody{
            background: white;
            margin: 0;
            padding: 10px;
            width: 100%;
        }
        .row{
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }
        .form-control{
            border-color: lightgray !important;
            border-radius: 0px !important;
            margin-top: 5px;
        }
        /*.form-control.notBorder{*/
        /*    box-shadow: none;*/
        /*    border-top: none;*/
        /*    border-right: none;*/
        /*    border-left: none;*/
        /*    border-radius: 0px !important;*/
        /*}*/
        label{
            margin-bottom: 0px;
        }
        .resultDiv{
            border: solid 1px lightgray;
            max-height: 300px;
            overflow: auto;
        }
        .resultDiv .res{
            padding: 5px;
            cursor: pointer;
        }
        .resultDiv .res:hover{
            background: #15b3ac;
            color: white;
        }
        .tagsSec{
            display: flex;
            flex-wrap: wrap;
        }
        .tagsSec .tagIn{
            width: 200px;
            margin: 10px;
        }
        .addButton{
            color: white;
            background: green;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin-right: 10px;
        }

        .cheSeoBo{
            display: flex;
            flex-direction: column;
        }
        .cheSeoBo div{
            text-align: right;
            border-bottom: solid 1px #ececec;
            padding-bottom: 10px;
            margin-bottom: 10px;
            font-size: 16px;
        }
    </style>
@stop

@section('content')
    <input type="hidden" id="localShopId" value="{{$localShop->id}}">
    <div class="mainBody">
        <div class="header">
            ویرایش کسب و کار
        </div>
        <div class="container contentBody">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name">نام</label>
                    <input type="text" id="name" class="form-control notBorder" value="{{$localShop->name}}">
                </div>
                <div class="col-md-6">
                    <label for="categoryName">دسته بندی</label>
                    <input type="text" id="categoryName" class="form-control notBorder" value="{{$localShop->categoryName}}" parent-id="{{$localShop->categoryParent}}" onclick="openCategoryModal(this)" readonly>
                    <input type="hidden" id="categoryId" value="{{$localShop->categoryId}}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="address">آدرس</label>
                    <input type="text" id="address" class="form-control notBorder" value="{{$localShop->address}}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="phone">تلفن</label>
                    <input type="text" id="phone" class="form-control notBorder" value="{{$localShop->phone}}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="cityName">شهر</label>
                    <input type="text" id="cityName" class="form-control notBorder" value="{{$localShop->cityName}}" onclick="openStateModal()" readonly>
                    <input type="hidden" id="cityId" value="{{$localShop->cityId}}">
                    <input type="hidden" id="stateId" value="{{$localShop->stateId}}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="instagram">اینستاگرام</label>
                    <input type="text" id="instagram" class="form-control notBorder" value="{{$localShop->instagram}}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="website">وب سایت</label>
                    <input type="text" id="website" class="form-control notBorder" value="{{$localShop->website}}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 form-group">
                    <label for="lat">lat</label>
                    <input type="text" id="lat" class="form-control notBorder" value="{{$localShop->lat}}">
                </div>
                <div class="col-md-5 form-group">
                    <label for="lng">lng</label>
                    <input type="text" id="lng" class="form-control notBorder" value="{{$localShop->lng}}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" onclick="openMapModal()">محل روی نقشه</button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="description">توضیحات</label>
                    <textarea type="text" id="description" rows="5" class="form-control">{{$localShop->description}}</textarea>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3">
                    <label for="isBoarding">آیا شبانه روزی هست؟</label>
                    <div>
                        بله
                        <input type="radio" id="isBoarding" name="isBoarding" value="1" {{$localShop->isBoarding == 1 ? 'checked' : ''}}>
                    </div>
                    <div>
                        خیر
                        <input type="radio" id="isBoarding" name="isBoarding" value="0" {{$localShop->isBoarding == 0 ? 'checked' : ''}}>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row" style="display: inline-block">
                        <div class="col-md-12">
                            روزهای هفته
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="inWeekOpenTime">از ساعت</label>
                            <input type="text" id="inWeekOpenTime" class="form-control notBorder" value="{{$localShop->inWeekOpenTime}}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="inWeekCloseTime">تا ساعت</label>
                            <input type="text" id="inWeekCloseTime" class="form-control notBorder" value="{{$localShop->inWeekCloseTime}}">
                        </div>
                    </div>
                    <div class="row" style="display: inline-block">
                        <div class="col-md-12">روزهای قبل تعطیلی</div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="afterClosedDayOpenTime">از ساعت</label>
                                    <input type="text" id="afterClosedDayOpenTime" class="form-control notBorder" value="{{$localShop->afterClosedDayOpenTime}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="afterClosedDayCloseTime">تا ساعت</label>
                                    <input type="text" id="afterClosedDayCloseTime" class="form-control notBorder" value="{{$localShop->afterClosedDayCloseTime}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="afterClosedDayIsOpen">ایا باز است؟</label>
                                    <div style="display: flex; justify-content: space-around;">
                                        <div>
                                            بله
                                            <input type="radio" id="afterClosedDayIsOpen" name="afterClosedDayIsOpen" value="1" {{$localShop->afterClosedDayIsOpen == 1 ? 'checked' : ''}}>
                                        </div>
                                        <div>
                                            خیر
                                            <input type="radio" id="afterClosedDayIsOpen" name="afterClosedDayIsOpen" value="0" {{$localShop->afterClosedDayIsOpen == 0 ? 'checked' : ''}}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: inline-block">
                        <div class="col-md-12">روزهای تعطیل</div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="closedDayOpenTime">از ساعت</label>
                                    <input type="text" id="closedDayOpenTime" class="form-control notBorder" value="{{$localShop->closedDayOpenTime}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="closedDayCloseTime">تا ساعت</label>
                                    <input type="text" id="closedDayCloseTime" class="form-control notBorder" value="{{$localShop->closedDayCloseTime}}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="closedDayIsOpen">ایا باز است؟</label>
                                    <div style="display: flex; justify-content: space-around;">
                                        <div>
                                            بله
                                            <input type="radio" id="closedDayIsOpen" name="closedDayIsOpen" value="1" {{$localShop->closedDayIsOpen == 1 ? 'checked' : ''}}>
                                        </div>
                                        <div>
                                            خیر
                                            <input type="radio" id="closedDayIsOpen" name="closedDayIsOpen" value="0" {{$localShop->closedDayIsOpen == 0 ? 'checked' : ''}}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="placeRelName">نام محل مرتبط سایت</label>
                    <input id="placeRelName" type="text" class="form-control notBorder" onclick="openKindPlaceRel()" value="{{$localShop->placeName}}" readonly/>
                    <input type="hidden" id="placeRelId" value="{{$localShop->placeId}}">
                    <input type="hidden" id="kindPlaceId" value="{{$localShop->kindPlaceId}}">
                </div>

                <div class="col-md-6">
                    <button class="btn btn-danger" onclick="deletePlaceRelated()">حذف محل مرتبط</button>
                </div>

                <div class="col-md-6 form-group">
                    <label for="ownerRelName">ماکلیت کسب و کار</label>
                    <input id="ownerRelName" type="text" class="form-control notBorder" onclick="openOwnerSelect()" value="{{$localShop->ownerUserName}}" readonly/>
                    <input type="hidden" id="ownerId" value="{{$localShop->userId}}">
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="keyword">کلمه کلیدی</label>
                            <input type="text" id="keyword" class="form-control notBorder" value="{{$localShop->keyword}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="slug">نامک</label>
                            <input type="text" id="slug" class="form-control notBorder" value="{{$localShop->slug}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <label for="seoTitle">عنوان سئو</label>
                            <input type="text" id="seoTitle" class="form-control notBorder" value="{{$localShop->seoTitle}}">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="meta">متا</label>
                    <textarea type="text" id="meta" rows="5" class="form-control">{{$localShop->meta}}</textarea>
                    <button class="btn btn-primary" onclick="openCheckSeoModal()">بررسی سئو مطالب</button>
                </div>
                <div class="col-md-12">
                    <div style="font-weight: bold; font-size: 20px; display: flex">
                        <div>تگ ها</div>
                        <div class="addButton glyphicon-plus" onclick="addNewTag()"></div>
                    </div>
                    <div class="tagsSec">
                        @foreach($localShop->tags as $item)
                            <div class="tagIn form-group">
                                <input type="text" data-id="{{$item->id}}" class="tagInput form-control" onkeyup="findTag(this)" onchange="chooseThisTag(this, 'input')" value="{{$item->name}}">
                                <div class="resultDiv"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row" style="justify-content: center">
                <button class="btn btn-success" onclick="submitThisData()">ثبت اطلاعات</button>
            </div>

        </div>
    </div>

    <div id="categoryModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">انتخاب دسته بندی</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="mainCategory">دسته بندی اصلی</label>
                            <select id="mainCategory" class="form-control" onchange="changeMainCategory(this.value)"></select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="mainCategory">زیر دسته بندی</label>
                            <select id="subCategory" class="form-control"></select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="submitCategory()">تایید</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>

            </div>
        </div>
    </div>

    <div id="cityModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">انتخاب شهر</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="mainCategory">استان</label>
                            <select id="stateIdInput" class="form-control" onchange="changeState()"></select>
                        </div>
                        <div class="col-md-6 form-group" style="position: relative;">
                            <label for="mainCategory">شهر</label>
                            <input type="text" class="form-control notBorder" id="cityNameInput" onkeyup="searchCity(this.value)">
                            <div class="resultDiv"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="submitCity()">تایید</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>

            </div>
        </div>
    </div>

    <div id="placeRelModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">انتهاب ارتباط</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="kindPlaceIdInput">دسته بندی</label>
                            <select id="kindPlaceIdInput" class="form-control" onchange="changeKindPlaceId(this.value)"></select>
                        </div>
                        <div class="col-md-6 form-group" style="position: relative;">
                            <label for="placeRelInput">نام محل</label>
                            <input type="text" class="form-control notBorder" id="placeRelInput" onkeyup="searchInPlaces(this.value)">
                            <div class="resultDiv"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <div id="changeOwnerModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">تغییر مالیکت کسب و کار</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group" style="position: relative;">
                            <label for="usernameInput">نام کاربری</label>
                            <input type="text" class="form-control notBorder" id="usernameInput" onkeyup="searchInUsers(this.value)">
                            <div class="resultDiv"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <div id="errorModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color: white; background: red">
                    <h4 class="modal-title">خطا ها</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="errorBody" class="row" style="display: flex; flex-direction: column; font-size: 22px; text-align: center;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <div id="checkSeoModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">بررسی سئو</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="seoCheckBody" class="row cheSeoBo"></div>
                    <button class="btn btn-primary" onclick="doCheckSeo()">بررسی سئو</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>


    <div id="mapModal" class="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" style="direction: rtl">
                    <div id="map" style="width: 100%; height: 500px; background-color: red"></div>
                </div>
                <div class="modal-footer" style="text-align: center">
                    <button class="btn nextStepBtnTourCreation" data-dismiss="modal">تایید</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        var categories = {!! $categories !!};
        var states = {!! $states !!};
        var kindPlaces = {!! $kindPlaces !!};

        var searchAjax;

        function openCategoryModal(_element){
            var id = $(_element).next().val();
            var parentId = $(_element).attr('parent-id');

            changeMainCategory(parentId, id);
            $('#categoryModal').modal('show');
        }
        function changeMainCategory(_id, _selected = 0){
            var mainCat = '';
            var text = "";
            var isGet = false;

            categories.map(item => {
                mainCat += `<option value="${item.id}" ${_id == item.id ? 'selected' : ''}>${item.name}</option>`;
                if(!isGet){
                    text = "<option value='0'>...</option>";
                    item.sub.map(sub => text += `<option value="${sub.id}" ${_selected == sub.id ? 'selected' : ''}>${sub.name}</option>`);
                    isGet = true;
                }
               if(item.id == _id) {
                   text = "<option value='0'>...</option>";
                   item.sub.map(sub => text += `<option value="${sub.id}" ${_selected == sub.id ? 'selected' : ''}>${sub.name}</option>`);
               }
            });

            $('#mainCategory').html(mainCat);
            $('#subCategory').html(text);
        }
        function submitCategory(){
            var id = $('#subCategory').val();
            var parentId = $('#mainCategory').val();
            var text = '';

            if(id != 0){
                categories.map(item => {
                    if(item.id == parentId){
                        item.sub.map(sub => {
                            if(sub.id == id)
                                text = sub.name;
                        });
                    }
                });

                $('#categoryName').attr('parent-id', parentId).val(text);
                $('#categoryId').val(id);
                $('#categoryModal').modal('hide');
            }
        }

        function openStateModal(){
            var name = $('#cityName').val();
            var stateId = $('#stateId').val();
            var text = '';

            states.map(item => text += `<option value="${item.id}" ${item.id == stateId ? 'selected' : ''}>${item.name}</option>`);
            $('#stateIdInput').html(text);
            $('#cityNameInput').val(name);
            $('.resultDiv').html('');

            $('#cityModal').modal('show');
        }
        function changeState(){
            $('#cityNameInput').val('');
        }
        function searchCity(_value){
            var stateId = $('#stateIdInput').val();

            if(_value.trim().length > 1){
                if(searchAjax != null)
                    searchAjax.abort();

                searchAjax = $.ajax({
                    type: 'POST',
                    url: '{{route("searchForCity")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        key: _value
                    },
                    complete: () => {searchAjax = null},
                    success: response => {
                        response = JSON.parse(response);
                        var text = '';
                        response.map(item => {
                            if(item.stateId == stateId)
                                text += `<div class="res" data-id="${item.id}" onclick="chooseThisCity(this)">${item.cityName}</div>`;
                        });

                        $(".resultDiv").html(text);
                    }
                })
            }
        }
        function chooseThisCity(_element){
            _element = $(_element);
            var cityId = _element.attr('data-id');
            var cityName = _element.text();

            $('#cityName').val(cityName);
            $('#cityId').val(cityId);
            $('#stateId').val($('#stateIdInput').val());
            $('#cityModal').modal('hide');
        }

        function openKindPlaceRel(){
            var name = $('#placeRelName').val();
            var kindPlaceId = $('#kindPlaceId').val();
            var text = '';

            kindPlaces.map(item => text += `<option value="${item.id}" ${item.id == kindPlaceId ? 'selected' : ''}>${item.name}</option>`);
            $('#kindPlaceIdInput').html(text);
            $('#placeRelInput').val(name);
            $('.resultDiv').html('');

            $('#placeRelModal').modal('show');
        }
        function changeKindPlaceId(){
            $('#placeRelInput').val('');
        }
        function searchInPlaces(_value){
            var kindPlaceId = $('#kindPlaceIdInput').val();

            if(_value.trim().length > 1){
                if(searchAjax != null)
                    searchAjax.abort();

                searchAjax = $.ajax({
                    type: 'POST',
                    url: '{{route("totalSearch")}}',
                    data: {
                        _token: '{{csrf_token()}}',
                        kindPlaceId: kindPlaceId,
                        key: _value,
                    },
                    complete: () => {searchAjax = null},
                    success: response => {
                        var text = '';
                        response = JSON.parse(response);
                        response.map(item => text += `<div class="res" data-id="${item.id}" onclick="chooseThisPlace(this)">${item.targetName}</div>`);
                        $(".resultDiv").html(text);
                    }
                })
            }
        }
        function chooseThisPlace(_element){
            _element = $(_element);
            var placeId = _element.attr('data-id');
            var placeName = _element.text();

            $('#placeRelName').val(placeName);
            $('#placeRelId').val(placeId);
            $('#kindPlaceId').val($('#kindPlaceIdInput').val());
            $('#placeRelModal').modal('hide');
        }
        function deletePlaceRelated(){
            $('#placeRelName').val('');
            $('#kindPlaceId').val(0);
            $('#placeRelId').val(0);
        }

        function openOwnerSelect(_element){
            $('#usernameInput').val('');
            $('#changeOwnerModal').modal('show');
        }
        function searchInUsers(_value){
            if(_value.trim().length > 1){
                if(searchAjax != null)
                    searchAjax.abort();

                $.ajax({
                    type: 'GET',
                    url: '{{route("search.users")}}?username='+_value,
                    complete: () => {searchAjax = null},
                    success: response =>{
                        var text = '';
                        response.result.map(item => text += `<div class="res" data-id="${item.id}" onclick="chooseThisUser(this)">${item.username}</div>`);
                        $(".resultDiv").html(text);
                    }
                })
            }
        }
        function chooseThisUser(_element){
            _element = $(_element);
            $('#ownerRelName').val(_element.text());
            $('#ownerId').val(_element.attr('data-id'));
            $('#changeOwnerModal').modal('hide');
        }

        function findTag(_element){
            var value = $(_element).val();
            if(value.trim().length > 1){
                if(searchAjax != null)
                    searchAjax.abort();

                searchAjax = $.ajax({
                    type: 'GET',
                    url: '{{route('search.tag')}}?value='+value,
                    complete: () => {searchAjax = null},
                    success: response =>{
                        var text = '';
                        response.result.map(item => text += `<div class="res" onclick="chooseThisTag(this, 'exist')">${item.name}</div>`);
                        $(_element).next().html(text);
                    }
                })
            }
            else
                $(_element).next().html('');
        }
        function chooseThisTag(_element, _kind){
            if(_kind == 'exist'){
                $(_element).parent().prev().val($(_element).text());
                $(_element).parent().html('');
            }
            else
                setTimeout(() => $(_element).next().html(''), 200);
        }
        function addNewTag(){
            var text = `<div class="tagIn form-group">
                                <input type="text" data-id="0" class="tagInput form-control" onkeyup="findTag(this)" onchange="chooseThisTag(this, 'input')" value="">
                                <div class="resultDiv"></div>
                            </div>`;
            $('.tagsSec').append(text);
        }

        function openCheckSeoModal(){
            $('#checkSeoModal').modal('show');
        }
        function doCheckSeo(){
            openLoading();
            $.ajax({
                type: 'post',
                url : '{{route("placeSeoTest")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    keyword: $('#keyword').val(),
                    meta: $('#meta').val(),
                    seoTitle: $('#seoTitle').val(),
                    slug: $('#slug').val(),
                    text: $('#description').val(),
                    name: $('#name').val(),
                    id: {{$localShop->id}},
                    kindPlaceId: 13
                },
                complete: () => closeLoading(),
                success: function(response){
                    response = JSON.parse(response);
                    console.log(response);
                    $('#seoCheckBody').empty().append(response[2]).append(response[0]).append(response[1]);

                    // uniqueKeyword = response[5];
                    // uniqueSlug = response[6];
                    // uniqueTitle = response[7];
                    // uniqueSeoTitle = response[8];
                    //
                    // errorCount = response[3];
                    // warningCount = response[4];
                    //
                    // inlineSeoCheck(kind);
                }
            })
        }

        function submitThisData(){
            var tags = [];
            var tagsElement = $('.tagInput');
            for(var i = 0; i < tagsElement.length; i++){
                if($(tagsElement[i]).val().trim().length > 1)
                tags.push({
                    name: $(tagsElement[i]).val().trim(),
                    id: $(tagsElement[i]).attr('data-id')
                });
            }
            console.log(tags);
            var data = {
                id: $('#localShopId').val(),
                tags: tags,
                name: $('#name').val(),
                categoryId: $('#categoryId').val(),
                address: $('#address').val(),
                phone: $('#phone').val(),
                cityId: $('#cityId').val(),
                instagram: $('#instagram').val(),
                website: $('#website').val(),
                lat: $('#lat').val(),
                lng: $('#lng').val(),
                description: $('#description').val(),
                ownerId: $('#ownerId').val(),
                placeRelId: $('#placeRelId').val(),
                kindPlaceId: $('#kindPlaceId').val(),
                seoTitle: $('#seoTitle').val(),
                keyword: $('#keyword').val(),
                meta: $('#meta').val(),
                slug: $('#slug').val(),
                inWeekCloseTime: $('#inWeekCloseTime').val(),
                inWeekOpenTime: $('#inWeekOpenTime').val(),
                afterClosedDayOpenTime: $('#afterClosedDayOpenTime').val(),
                afterClosedDayCloseTime: $('#afterClosedDayCloseTime').val(),
                closedDayOpenTime: $('#closedDayOpenTime').val(),
                closedDayCloseTime: $('#closedDayCloseTime').val(),
                closedDayIsOpen: $('input[name="closedDayIsOpen"]:checked').val(),
                afterClosedDayIsOpen: $('input[name="afterClosedDayIsOpen"]:checked').val(),
                isBoarding: $('input[name="isBoarding"]:checked').val(),
            };

            var errors = [];

            if(data.name.length <= 2)
                errors.push('پر کردن نام اجباری است');
            if(data.address.length <= 2)
                errors.push('پر کردن آدرس اجباری است');
            if(data.categoryId == 0 || data.categoryId == null)
                errors.push('پر کردن دسته بندی اجباری است');
            if(data.cityId == 0 || data.cityId == null)
                errors.push('انتخاب شهر اجباری است');
            if(data.lat == 0 || data.lat == null || data.lng == 0 || data.lng == null)
                errors.push('انتخاب محل روی نقشه اجباری است');

            if(errors.length == 0){
                openLoading();
                $.ajax({
                    type: 'POST',
                    url: '{{route("localShop.edit.doEdit")}}',
                    data:{
                        _token: '{{csrf_token()}}',
                        data: data,
                    },
                    complete: () => closeLoading(),
                    success: response =>{
                        if(response.status == 'ok')
                            alert('با موفقیت ثبت شد');
                        else
                            alert(response.status);
                    },
                    error: err =>{
                        alert('Error');
                        console.log(err);
                    }
                })
            }
            else{
                var text = '';
                errors.map(item => text += `<div>${item}</div>`);
                $('#errorBody').html(text);
                $('#errorModal').modal('show');
            }
        }

    </script>


    <script>
        var map;
        var marker = 0;

        function openMapModal(){
            setNewMarker();
            $('#mapModal').modal('show');
        }
        function initMap(){
            var mapOptions = {
                zoom: 5,
                center: new google.maps.LatLng(32.42056639964595, 54.00537109375),
                // How you would like to style the map.
                // This is where you would paste any style found on Snazzy Maps.
                styles: [{
                    "featureType": "landscape",
                    "stylers": [{"hue": "#FFA800"}, {"saturation": 0}, {"lightness": 0}, {"gamma": 1}]
                }, {
                    "featureType": "road.highway",
                    "stylers": [{"hue": "#53FF00"}, {"saturation": -73}, {"lightness": 40}, {"gamma": 1}]
                }, {
                    "featureType": "road.arterial",
                    "stylers": [{"hue": "#FBFF00"}, {"saturation": 0}, {"lightness": 0}, {"gamma": 1}]
                }, {
                    "featureType": "road.local",
                    "stylers": [{"hue": "#00FFFD"}, {"saturation": 0}, {"lightness": 30}, {"gamma": 1}]
                }, {
                    "featureType": "water",
                    "stylers": [{"hue": "#00BFFF"}, {"saturation": 6}, {"lightness": 8}, {"gamma": 1}]
                }, {
                    "featureType": "poi",
                    "stylers": [{"hue": "#679714"}, {"saturation": 33.4}, {"lightness": -25.4}, {"gamma": 1}]
                }]
            };
            var mapElementSmall = document.getElementById('map');
            map = new google.maps.Map(mapElementSmall, mapOptions);

            google.maps.event.addListener(map, 'click', event => getLat(event.latLng));
        }
        function getLat(location){
            if(marker != 0)
                marker.setMap(null);
            marker = new google.maps.Marker({
                position: location,
                map: map,
            });

            document.getElementById('lat').value = marker.getPosition().lat();
            document.getElementById('lng').value = marker.getPosition().lng();
        }
        function setNewMarker(){
            if(marker != 0)
                marker.setMap(null);

            var lat = document.getElementById('lat').value;
            var lng = document.getElementById('lng').value;

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat, lng),
                map: map,
            });
        }

    </script>

    <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCdVEd4L2687AfirfAnUY1yXkx-7IsCER0&callback=initMap"></script>
@stop
