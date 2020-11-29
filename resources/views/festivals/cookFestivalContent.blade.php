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
        .selectTab{
            display: flex;
            justify-content: space-around;
            border-bottom: solid 1px gray;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }
        .selectTab .tab{
            width: 30%;
            text-align: center;
            padding: 5px 0px;
            cursor: pointer;
        }
        .selectTab .tab.selected{
            background: #15b3ac;
            color: white;
            border-radius: 30px;
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
                <span id="newContentCount" class="badge badge-success" style="background: green">{{count($newContent)}}</span>
                آثار جدید
            </div>
            <div class="tab" onclick="changeTab('confirmed', this)">
                آثار تاییدشده
                <span id="confirmedCount">{{count($confirmed)}}</span>
            </div>
            <div class="tab" onclick="changeTab('notAllowed', this)">
                آثار رد شده
                <span id="notConfirmedCount">{{count($notConfirmed)}}</span>
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

    <script>
        var confirmedContent = {!! json_encode($confirmed) !!};
        var notConfirmedContent = {!! json_encode($notConfirmed) !!};
        var newContent = {!! json_encode($newContent) !!};
        var kindShowContent;
        var contentFoodSearchId = 0;

        function changeTab(_kind, _element) {
            _element = $(_element);
            _element.parent().find('.selected').removeClass('selected');
            _element.addClass('selected');

            showContents(_kind);
        }

        function showContents(_kind){
            kindShowContent = _kind;

            var showContent;
            var tableRows = '';
            var buttons = '';
            var hasFood = '';

            if(kindShowContent == 'new')
                showContent = newContent;
            else if(kindShowContent == 'confirmed')
                showContent = confirmedContent;
            else
                showContent = notConfirmedContent;

            showContent.map((item, index) => {
                if(_kind == 'new')
                    buttons = `<button class="btn btn-success" onclick="changePicConfirm(1, ${index}, this)">تایید</button>
                                <button class="btn btn-danger" onclick="changePicConfirm(-1, ${index}, this)">رد کردن</button>`;
                else if(_kind == 'confirmed')
                    buttons = `<button class="btn btn-danger" onclick="changePicConfirm(-1, ${index}, this)">رد کردن</button>`;
                else
                    buttons = `<button class="btn btn-success" onclick="changePicConfirm(1, ${index}, this)">تایید</button>`;

                hasFood = item.newFood ? 'newFood' : 'hasFood';

                tableRows += `<tr>
                                <td>${item.id}</td>
                                <td>${item.user.username}</td>
                                <td><img src="${item.showPic}" style="height: 100px; width: auto; cursor: pointer;" onclick="showThisFile(${index})"></td>
                                <td><div class="${hasFood}" onclick="openFindFoodModal(${index}, ${item.id})">${item.foodName}</div></td>
                                <td>${buttons}</td>
                              </tr>`;
            });
            $("#tableBody").html(tableRows);
        }

        function changePicConfirm(_confirmed, _index, _element){
            var content;
            if(kindShowContent == 'new')
                content = newContent[_index];
            else if(kindShowContent == 'confirmed')
                content = confirmedContent[_index];
            else
                content = notConfirmedContent[_index];

            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("festival.content.updateConfirmed")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: content.id,
                    confirm: _confirmed,
                    festivalId: '{{$festival->id}}'
                },
                success: response => {
                    closeLoading();
                    if(response.status == 'ok'){

                        if(kindShowContent == 'new')
                            newContent.splice(_index, 1);
                        else if(kindShowContent == 'confirmed')
                            confirmedContent.splice(_index, 1);
                        else
                            notConfirmedContent.splice(_index, 1);

                        if(_confirmed == 1)
                            confirmedContent.unshift(content);
                        else
                            notConfirmedContent.unshift(content);

                        $("#newContentCount").text(newContent.length);
                        $("#confirmedCount").text(confirmedContent.length);
                        $("#notConfirmedCount").text(notConfirmedContent.length);

                        showContents(kindShowContent);
                    }
                },
                error: err => {
                    closeLoading();
                    console.log(err);
                    alert('Error 500');
                }
            });
        }

        function showThisFile(_index){
            var html = '';
            if(kindShowContent == 'new')
                var content = newContent[_index];
            else if(kindShowContent == 'confirmed')
                var content = confirmedContent[_index];
            else
                var content = notConfirmedContent[_index];
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
            $('#contentModalBody').html('');
            $('#foodSearchResult').html('');
            $(`#${_id}`).modal('hide');
        }

        function openFindFoodModal(_index, _id){
            contentFoodSearchId = { id: _id, index: _index };
            $("#foodModal").modal({ backdrop: 'static', keyboard: false });
        }

        var foodSearchAjax = false;
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
                    error: err => {
                        console.log(err);
                    }
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
                        if(kindShowContent == 'new'){
                            newContent[contentFoodSearchId.index].foodId = response.result.id;
                            newContent[contentFoodSearchId.index].foodName = response.result.name;
                            newContent[contentFoodSearchId.index].newFood = false;
                        }
                        else if(kindShowContent == 'confirmed'){
                            confirmedContent[contentFoodSearchId.index].foodId = response.result.id;
                            confirmedContent[contentFoodSearchId.index].foodName = response.result.name;
                            confirmedContent[contentFoodSearchId.index].newFood = false;
                        }
                        else{
                            notConfirmedContent[contentFoodSearchId.index].foodId = response.result.id;
                            notConfirmedContent[contentFoodSearchId.index].foodName = response.result.name;
                            notConfirmedContent[contentFoodSearchId.index].newFood = false;
                            showCntents(kindShowContent);
                        }
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
