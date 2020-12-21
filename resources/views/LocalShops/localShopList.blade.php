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
    </style>
@stop

@section('content')
    <div class="mainBody">
        <div class="header">
            لیست کسب و کارها
        </div>
        <div class="selectTab">
            <div id="notConfirmTab" class="tab" onclick="createTable('not')">
                کسب و کارهای تایید نشده
                <span id="notContentCount" class="badge badge-success" style="background: green"></span>
            </div>
            <div id="confirmTab" class="tab" onclick="createTable('confirm')">
                کسب و کارهای تاییدشده
                <span id="confirmedCount"></span>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>دسته بندی</th>
                <th>شهر</th>
                <th>مالک</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="contentTableBody"></tbody>
        </table>
    </div>

    <script>
        var localShops = {};
        var nowShowPage;

        function getAllLocalShops(_kind){
            openLoading();

            $.ajax({
                type: "GET",
                url: '{{route("localShop.getAll")}}',
                complete: () => closeLoading(),
                success: response => {
                    if(response.status == 'ok'){
                        localShops = {
                            'confirm' : response.result[1],
                            'not' : response.result[0],
                        };

                        $('#notContentCount').text(localShops.not.length);
                        $('#confirmedCount').text(localShops.confirm.length);

                        createTable(_kind);
                    }
                    else
                        alert('خطا در گرفتن')
                },
                error: err =>{
                    console.log(err);
                }
            })
        }

        function createTable(_kind){
            nowShowPage = _kind;

            var text = '';
            $('.selectTab').find('.tab').removeClass('selected');
            if(_kind == 'not')
                $('#notConfirmTab').addClass('selected');
            else
                $('#confirmTab').addClass('selected');

            localShops[_kind].map(item => {
               text += `
                    <tr data-id="${item.id}">
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.categoryName}</td>
                        <td>${item.cityName}</td>
                        <td>${item.username}</td>
                        <td>
                            ${_kind == 'not' ?
                                `<button class="myBtn btn btn-success" onclick="changeConfirmed(this, 1)">تایید</button>`
                                :
                                `<button class="myBtn btn btn-warning" onclick="changeConfirmed(this, 0)">لغو</button>`
                            }
                            <a href="{{route('localShop.edit.page')}}/${item.id}" class="myBtn btn btn-primary">ویرایش</a>
                            <a href="{{route('localShop.edit.pics')}}/${item.id}" class="myBtn btn btn-secondery">ویرایش عکس</a>
<!--                            <button class="myBtn btn btn-danger" onclick="deleteThisLocalShopModal()">حذف</button>-->
                        </td>
                    </tr>
               `
            });

            $('#contentTableBody').html(text);
        }

        function changeConfirmed(_element, _kind){
            var id = $(_element).parent().parent().attr('data-id');
            openLoading();
            $.ajax({
                type: 'POST',
                url: '{{route("localShop.edit.confirm")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    id: id,
                    kind: _kind,
                },
                complete: () => closeLoading(),
                success: response =>{
                    if(response.status == 'ok')
                        getAllLocalShops(nowShowPage);
                    else
                        alert(response.status);
                },
                error: err => {
                    alert('serv Error');
                    console.log(err);
                }
            })
        }

        function deleteThisLocalShopModal(){

        }

        $(window).ready(() => {
            getAllLocalShops('not');
        });
    </script>
@stop
