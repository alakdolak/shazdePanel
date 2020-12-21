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


        .newFood{
            color: white;
            background: #27a927;
            text-align: center;
            padding: 10px 0px;
            border-radius: 14px;
        }
        .newFood:before{
            content : 'غذای جدید : '
        }

        .mainFile{
            max-height: 70vh;
        }

        .searchResult{
            max-height: 50vh;
            overflow: auto;
            background: white;
            padding: 0px 4px;
        }
        .searchResult .res{
            margin: 5px 3px;
            cursor: pointer;
            padding: 5px;
            transition: .3s;
        }
        .searchResult .res:hover{
            background: #15b3ac;
            color: white;
        }

    </style>
@stop

@section('content')
    <div class="mainBody">
        <div class="header">
            اثار ارسالی فستیوال {{$festival->name}}
        </div>
        <div class="selectTab">
            <div class="tab selected" onclick="changeTab('new', this)">
                <span id="newContentCount" class="badge badge-success" style="background: green"></span>
                آثار جدید
            </div>
            <div class="tab" onclick="changeTab('confirmed', this)">
                آثار تاییدشده
                <span id="confirmedCount"></span>
            </div>
            <div class="tab" onclick="changeTab('notAllowed', this)">
                آثار رد شده
                <span id="notConfirmedCount"></span>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>id</th>
                <th>نام کاربری</th>
                <th>عکس</th>
                <th>غذا</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>

    <div class="modal" id="contentModal">
        <div class="modal-dialog" style="width: 95%; display: flex; justify-content: center; align-items: center;">
            <div class="modal-content">
                <div id="contentModalBody" class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeShowModal('contentModal')">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="foodModal">
        <div class="modal-dialog" style="width: 95%; display: flex; justify-content: center; align-items: center;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="نام غذا را وارد کنید..." onkeyup="searchInFood(this)">
                        <div id="foodSearchResult" class="searchResult"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeShowModal('foodModal')">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="failedReasonModal">
        <div class="modal-dialog" style="width: 95%; display: flex; justify-content: center; align-items: center;">
            <div class="modal-content" style="width: 100%; max-width: 700px">
                <div class="modal-body">
                    <div class="form-group">
                        <textarea id="failedReasonInput" rows="5" class="form-control" placeholder="دلیل رد کردن این محتوا را بنویسید..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="failedContentIndex">
                    <button type="button" class="btn btn-danger" onclick="submitFailedContent()">رد کردن این محتوا</button>
                    <button type="button" class="btn btn-secondery" onclick="closeShowModal('failedReasonModal')">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var allContents = {!! json_encode($content) !!};
        var kindShowContent;
        var foodSearchAjax = false;
        var contentFoodSearchId = 0;

        function changeTab(_kind, _element) {
            _element = $(_element);
            _element.parent().find('.selected').removeClass('selected');
            _element.addClass('selected');

            showContents(_kind);
        }

        function showContents(_kind){
            var showContent;
            var tableRows = '';
            var buttons = '';
            var newContentCount = 0;
            var failedContentCount = 0;
            var successContentCount = 0;

            kindShowContent = _kind;

            if(kindShowContent == 'new')
                showContent = 0;
            else if(kindShowContent == 'confirmed')
                showContent = 1;
            else
                showContent = -1;

            allContents.map((item, index) => {
                if(item.confirm == showContent){
                    if(_kind == 'new')
                        buttons = `<button class="btn btn-success" onclick="changePicConfirm(1, ${index})">تایید</button>
                                <button class="btn btn-danger" onclick="changePicConfirmToFailed(${index})">رد کردن</button>`;
                    else if(_kind == 'confirmed')
                        buttons = `<button class="btn btn-danger" onclick="changePicConfirmToFailed(${index})">رد کردن</button>`;
                    else
                        buttons = `<button class="btn btn-success" onclick="changePicConfirm(1, ${index})">تایید</button>`;

                    var hasFood = item.newFood ? 'newFood' : 'hasFood';

                    tableRows += `<tr id="row_${index}">
                                <td>${item.id}</td>
                                <td>${item.user.username}</td>
                                <td><img src="${item.showPic}" style="height: 100px; width: auto; cursor: pointer;" onclick="showThisFile(${index})"></td>
                                <td><div class="${hasFood}" onclick="openFindFoodModal(${index}, ${item.id})">${item.foodName}</div></td>
                                <td>${buttons}</td>
                              </tr>`;
                }

                if(item.confirm == 1)
                    successContentCount++;
                else if(item.confirm == 0)
                    newContentCount++ ;
                else
                    failedContentCount++;
            });


            $("#newContentCount").text(newContentCount);
            $("#confirmedCount").text(successContentCount);
            $("#notConfirmedCount").text(failedContentCount);
            $("#tableBody").html(tableRows);
        }

        function changePicConfirm(_confirmed, _index, _failedReason = ''){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("festival.content.updateConfirmed")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: allContents[_index].id,
                    confirm: _confirmed,
                    failedReason: _failedReason,
                    festivalId: '{{$festival->id}}'
                },
                success: response => {
                    closeLoading();
                    if(response.status == 'ok'){
                        allContents[_index].confirm = _confirmed;
                        showContents(kindShowContent);
                    }
                    else if(response.status == 'error2')
                        alert('برای تایید غذا ابتدا باید غذا را از سایت انتخاب کنید');
                    else
                        alert('Error');
                },
                error: err => {
                    closeLoading();
                    console.log(err);
                    alert('Error 500');
                }
            });
        }

        function changePicConfirmToFailed(_index){
            $('#failedContentIndex').val(_index);
            $('#failedReasonModal').modal('show');
        }

        function submitFailedContent() {
            var index = $('#failedContentIndex').val();
            var reason = $('#failedReasonInput').val();
            $('#failedReasonModal').modal('hide');

            changePicConfirm(-1, index, reason);
        }

        function showThisFile(_index){
            var html = '';
            var content = allContents[_index];

            if(content.type == 'image')
                html = `<img src="${content.file}" class="mainFile">`;
            else
                html = `<video src="${content.file}" class="mainFile" controls>`;

            $('#contentModalBody').html(html);

            $("#contentModal").modal({
                backdrop: 'static',
                keyboard: false
            });
        }

        function closeShowModal(_id){
            contentFoodSearchId = {
                id: 0,
                index: 0,
            };
            $('#failedContentIndex').val(0)
            $('#failedReasonInput').val('');
            $('#contentModalBody').html('');
            $('#foodSearchResult').html('');
            $(`#${_id}`).modal('hide');
        }

        function openFindFoodModal(_index, _id){
            contentFoodSearchId = { id: _id, index: _index };
            $("#foodModal").modal({ backdrop: 'static', keyboard: false });
        }

        function searchInFood(_element){
            var value = $(_element).val();

            if(value.trim().length > 2){
                if(foodSearchAjax != false)
                    foodSearchAjax.abort();

                foodSearchAjax = $.ajax({
                    type: 'GET',
                    url: '{{route("search.placesAndCity")}}?text='+value,
                    success: response => {
                        if(response.status == 'ok'){
                            var result = response.result;
                            var html = '';
                            result.map(item => {
                                if(item.kind == 11)
                                    html += `<div class="res" data-id="${item.id}" onclick="changeFoodId(${item.id})">${item.name}</div>`;
                            });
                            $('#foodSearchResult').html(html);
                        }
                    },
                    error: err => console.log(err)
                })
            }
            else
                $('#foodSearchResult').html('');
        }

        function changeFoodId(_foodId){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("festival.cook.content.updateFood")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    foodId: _foodId,
                    contentId: contentFoodSearchId.id
                },
                success: response => {
                    closeLoading();
                    if(response.status == 'ok'){
                        allContents[contentFoodSearchId.index].foodId = response.result.id;
                        allContents[contentFoodSearchId.index].foodName = response.result.name;
                        allContents[contentFoodSearchId.index].newFood = false;

                        closeShowModal('foodModal');
                        showContents(kindShowContent);
                    }
                    else
                        alert('Error');
                },
                error: err => {
                    closeLoading();
                    alert('Error 500');
                }
            });
        }

        showContents('new');

    </script>
@stop
