@extends('layouts.structure')

@section('header')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{asset('js/ckeditor5/ckeditorUpload.js')}}"></script>
    <script src="{{asset('js/ckeditor5/ckeditor.js')}}"></script>

    <script src="{{URL::asset('js/jalali.js')}}"></script>
    <script src="{{URL::asset('js/calendar/persian-date.min.js')}}"></script>
    <script src="{{URL::asset('js/calendar/persian-datepicker.js')}}"></script>
    <script src= {{URL::asset("js/clockPicker/clockpicker.js") }}></script>

    <link rel="stylesheet" href="{{URL::asset('css/calendar/persian-datepicker.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/clockPicker/clockpicker.css')}}"/>
    <link rel="stylesheet" href="{{URL::asset('css/pages/safarnamehPage.css')}}">

@stop

@section('content')

    <input type="hidden" id="safarnamehId" value="{{isset($safarnameh) ? $safarnameh->id : '0'}}">
        <input type="hidden" id="gardeshName" value="{{isset($safarnameh) && isset($safarnameh->gardeshName) ? $safarnameh->gardeshName : '0'}}">

    <div class="col-md-3 leftSection" style="padding-right: 0px;">
        <div class="sparkline8-list shadow-reset mg-tb10">
            <div class="sparkline8-hd" style="padding: 5px 10px;">
                <div class="main-sparkline8-hd">
                    <h5>زمان انتشار</h5>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages">
                <div class="form-group">
                    <select class="form-control botBorderInput" id="releaseType" name="release" onchange="changeRelease(this.value)">
                        <option value="released" {{isset($safarnameh) ? ($safarnameh->release == 'released' ? 'selected' : '')  : ''}}>منتشرشده</option>
                        <option value="draft" {{isset($safarnameh) ? ($safarnameh->release == 'draft' ? 'selected' : '')  : 'selected'}}>پیش نویس</option>
                        <option value="future" {{isset($safarnameh) ? ($safarnameh->release == 'future' ? 'selected' : '')  : ''}}>آینده</option>
                    </select>
                </div>

                <div id="futureDiv" style="display: {{isset($safarnameh) && $safarnameh->release == 'future' ? '' : 'none'}}">
                    <div class="form-group" style="display: flex">
                        <label for="date" style="font-size: 10px;">تاریخ انتشار</label>
                        <input name="date" id="date" class="observer-example inputBoxInput" value="{{isset($safarnameh) ? $safarnameh->date : ''}}" readonly/>
                    </div>
                    <div class="form-group" style="display: flex;">
                        <label for="time" style="font-size: 10px;">ساعت انتشار</label>
                        <input name="time" id="time" class="inputBoxInput" style="width: 73%;" value="{{isset($safarnameh) ? $safarnameh->time : ''}}" readonly/>
                    </div>
                </div>
            </div>
        </div>
        <div class="sparkline8-list shadow-reset mg-tb-10">
            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h5>دسته بندی ها</h5>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto; padding-top: 0px;">
                <div class="row">
                    <div class="selectCategSec">
                        <div class="inputSec">
                            <input type="text" placeholder="نام دسته بندی..." onfocus="openCategoryResult()" onkeyup="searchInSafarnamehCategory(this.value)">
                            <button class="arrowDonwIcon" onclick="openCategoryResult()"></button>
                        </div>
                        <div id="categoryResultDiv" class="resultSec"></div>
                    </div>
                    <div id="selectedCategory" class="selectedCategoryDiv"></div>
                </div>
            </div>
        </div>
        <div class="sparkline8-list shadow-reset mg-tb-10">
            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h5>برچسب ها</h5>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto">
                <div class="row">
                    <div class="form-group">
                        <div class="inputBorder newTagSection">
                            <div class="inputGroup newTagInputDiv">
                                <input type="text"
                                       class="newTag"
                                       id="newTag"
                                       placeholder="برچسپ جدید...">
                                <i class="fa fa-check checkIconTag" onclick="selectTag()"></i>
                            </div>
                            <div id="searchTagResultDiv" class="searchTagResultDiv"></div>
                        </div>
                    </div>
                    <div>
                        <div id="selectedTags" class="col-md-12"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="sparkline8-list shadow-reset mg-tb-10">
            <div class="sparkline8-hd" style="padding: 5px 10px">
                <div class="main-sparkline8-hd">
                    <h5>محل های مرتبط</h5>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto">
                <div class="row">
                    <div class="form-group">
                        <div class="inputBorder newTagSection">
                            <div class="inputGroup newTagInputDiv">
                                <input type="text"
                                       class="newTag"
                                       id="newPlace"
                                       placeholder="نام محل مرتبط با سفرنامه..."
                                       style="width: 100%; font-size: 12px"
                                       onkeyup="searchPlaces(this.value)">
                            </div>

                            <div id="searchPlaceResult" class="searchTagResultDiv"></div>
                        </div>
                    </div>
                    <div>
                        <div id="selectedPlaces" class="col-md-12"></div>
                    </div>

                </div>
            </div>
        </div>
        <div class="sparkline8-list shadow-reset mg-tb-10">
            <div class="sparkline8-hd" style="padding: 5px 10px">
                    <div class="main-sparkline8-hd">
                        <h5>عکس اصلی</h5>
                    </div>
                </div>
            <div class="sparkline8-graph dashone-comment  dashtwo-messages" style="height: auto">
                <div class="row">
                    <div class="showImg {{ isset($safarnameh)? 'open' : ''}}">
                        <img id="mainPicShow" src="{{isset($safarnameh)? $safarnameh->pic : URL::asset('img/uploadPic.png')}}">

                        <label for="imgInput" class="btn btn-success" style="width: 100%; text-align: center; margin-top: 10px">
                            انتخاب عکس
                            <input type="file" id="imgInput" name="mainPic" accept="image/*" style="display: none;" onchange="changePic(this)">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9" style="padding-left: 0px;">
        <div class="col-md-12">
            <div class="sparkline8-list shadow-reset mg-tb-10">
                <div class="sparkline8-hd">
                    <div class="main-sparkline8-hd" STYLE="display: flex; justify-content: space-between; color: white">
                        @if(isset($safarnameh))
                            <h4>ویرایش سفرنامه</h4>
                            <button class="btn btn-success noneSeoTestButton" onclick="storePost(false)">ثبت بدون تست سئو</button>
                        @else
                            <h4>افزودن سفرنامه جدید</h4>
                        @endif
                    </div>
                </div>

                <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                    <div class="row" style="text-align: right">

                        <div class="col-xs-12">
                            <div class="form-group">
{{--                                <label for="title" style="margin: 0px">عنوان سفرنامه</label>--}}
                                <input class="form-control titleInputClass"
                                       type="text"
                                       name="title"
                                       id="title" value="{{(isset($safarnameh) ? $safarnameh->title : '')}}"
                                        placeholder="عنوان سفرنامه">
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div class="adjoined-bottom">
                                <div class="grid-container">
                                    <div class="grid-width-100">
                                        <div id="safarnamehText" class="textEditor">
                                            @if(isset($safarnameh))
                                                {!! html_entity_decode($safarnameh->description) !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" style="margin-top: 10px;">

            <div class="sparkline8-list shadow-reset">
                <div class="sparkline8-hd" style="padding: 5px 10px;">
                    <div class="main-sparkline8-hd">
                        <h5>سئو</h5>
                    </div>
                </div>
                <div style="height: auto !important;" class="sparkline8-graph dashone-comment  dashtwo-messages">
                    <div class="row" style="text-align: right">
                        <div class="col-md-12 floR mg-tb-10">
                            <div class="form-group">
                                <label for="keyword">کلمه کلیدی</label>
                                <input class="form-control botBorderInput"
                                       type="text"
                                       id="keyword"
                                       name="keyword"
                                       placeholder="کلمه کلیدی را اینجا بنویسید..."
                                       value="{{isset($safarnameh)? $safarnameh->keyword: ''}}">
                            </div>
                        </div>
                        <div class="col-md-12 floR">
                            <div class="form-group">
                                <label for="seoTitle">عنوان سئو:
                                    <span id="seoTitleNumber" style="font-weight: 200;"></span>
                                </label>
                                <input type="text"
                                       class="form-control botBorderInput"
                                       id="seoTitle"
                                       name="seoTitle"
                                       placeholder="عنوان سئو را اینجا بنویسید (عنوان سئو باید بین 60 حرف تا 85 حرف باشد)"
                                       onkeyup="changeSeoTitle(this.value)" value="{{isset($safarnameh)? $safarnameh->seoTitle: ''}}">
                            </div>
                        </div>
                        <div class="col-md-12 floR mg-tb-10">
                            <div class="form-group">
                                <label for="slug">نامک</label>
                                <input class="form-control botBorderInput"
                                       type="text"
                                       id="slug"
                                       placeholder="نامک را اینجا بنویسید..."
                                       name="slug"
                                       value="{{isset($safarnameh)? $safarnameh->slug: ''}}">
                            </div>
                        </div>
                        <div class="col-md-12 floR">
                            <div class="form-group">
                                <label for="meta">متا: <span id="metaNumber" style="font-weight: 200;"></span></label>
                                <textarea class="form-control botBorderInput" type="text" id="meta" name="meta" onkeyup="changeMeta(this.value)" rows="3">{{isset($safarnameh)? $safarnameh->meta: ''}}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="row" style="text-align: center">
                        <button class="btn btn-primary" onclick="checkSeo(0)">تست سئو</button>
                    </div>
                    <div class="row" style="text-align: right">
                        <div id="errorResult"></div>
                        <div id="warningResult"></div>
                        <div id="goodResult"></div>
                    </div>
                </div>

                <div style="padding: 10px; width: 100%; display: flex; justify-content: center; align-items: center;">
                    <input type="button" onclick="checkSeo(1)"  value="ثبت" class="btn btn-success">
                    <input type="button" onclick="window.location.href='{{route("safarnameh.index")}}'"  value="بازگشت" class="btn btn-secondry">
                </div>

            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="warningModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">اخطارها</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div style="font-size: 18px; margin-bottom: 20px;">
                        در سفرنامه شما اخطارهای زیر موجود است . ایا از ثبت سفرنامه خود اطمینان دارید؟
                    </div>

                    <div id="warningContentModal" style="padding-right: 5px;"></div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer" style="text-align: center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">خیر اصلاح می کنم.</button>
                    <button type="button" class="btn btn-success"  data-dismiss="modal" onclick="storePost()">بله سفرنامه ثبت شود</button>
                </div>

            </div>
        </div>
    </div>

    <img id="beforeSaveImg" src="" style="display: none;">

    <script>
        var tagsName = [];
        var safarnamehId;
        var mainDataForm = new FormData();
        var warningCount = 0;
        var errorCount = 0;
        var uniqueKeyword;
        var uniqueTitle;
        var uniqueSeoTitle;
        var uniqueSlug;
        var safarname = null;
        var errorInUploadImage = false;
        var selectedCat = [];
        window.limboIds = [];
        var selectedPlaces = [];
        var searchPlaceAjax = null;
        var allSafarnamehCategoryList = [];
        var selectedSafarnamehCategory = [];
        var safarnamehCategory = {!! $category !!};

        $('.observer-example').persianDatepicker({
            minDate: new Date().getTime(),
            format: 'YYYY/MM/DD',
            autoClose: true,
        });

        $('#time').clockpicker({
            donetext: 'تایید',
            autoclose: true,
        });
        BalloonBlockEditor.create( document.querySelector('#safarnamehText'), {
            placeholder: 'متن سفرنامه خود را اینجا وارد کنید...',
            toolbar: {
                items: [
                    'bold',
                    'italic',
                    'link',
                    'highlight'
                ]
            },
            language: 'fa',
            blockToolbar: [
                'blockQuote',
                'heading',
                'indent',
                'outdent',
                'numberedList',
                'bulletedList',
                'insertTable',
                'imageUpload',
                'undo',
                'redo'
            ],
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells'
                ]
            },
            licenseKey: '',
        } )
            .then( editor => {
                window.editor = editor;
                window.uploaderClass = editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
                    let data = { code: {{$code}} };
                    data = JSON.stringify(data);
                    return new MyUploadAdapter( loader, '{{route('safarnameh.uploadDescPic')}}', '{{csrf_token()}}', data);
                };


            } )
            .catch( error => {
                console.error( 'Oops, something went wrong!' );
                console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
                console.warn( 'Build id: wgqoghm20ep6-7otme29let2s' );
                console.error( error );
            } );

        @if(isset($safarnameh))
            safarname = {!! json_encode($safarnameh) !!};
            safarname['category'].map(item => selectedCat.push({
                id: item.categoryId,
                isMain: item.isMain,
            }));

            $(window).ready(() => {
                safarname['tags'].map(item => chooseTag(item));
                safarname.places.map(item => choosePlace(item.id, item.name, item.kind));
            });

        @endif

        function changeRelease(value){
            if(value == 'future')
                document.getElementById('futureDiv').style.display = 'block';
            else
                document.getElementById('futureDiv').style.display = 'none';
        }

        function changePic(input){
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                $('.showImg').addClass('open');
                reader.onload = e => $('#mainPicShow').attr('src', e.target.result)
                reader.readAsDataURL(input.files[0]);
            }
        }

        function storePost(_checkSeo = true){

            var id = document.getElementById('safarnamehId').value;
            var gardeshName = document.getElementById('gardeshName').value;
            var title = document.getElementById('title').value;
            var release = document.getElementById('releaseType').value;
            var date = document.getElementById('date').value;
            var time = document.getElementById('time').value;
            var inputMainPic = document.getElementById('imgInput');
            var keyword = document.getElementById('keyword').value;
            var seoTitle = document.getElementById('seoTitle').value;
            var slug = document.getElementById('slug').value;
            var meta = document.getElementById('meta').value;

            if(title.trim().length < 2){
                alert('عنوان مقاله را مشخص کنید');
                return;
            }

            if(selectedSafarnamehCategory.length == 0){
                alert('دسته بندی را مشخص کنید');
                return;
            }

            var hasMain = 0;
            selectedSafarnamehCategory.map(item => {
                if(item.thisIsMain == 1)
                    hasMain = 1;
            });

            if(hasMain == 0){
                alert('دسته بندی اصلی را مشخص کنید');
                return;
            }

            if(release == 'future' && (date == null || date == null || time == null || time == '')){
                alert('تاریخ و ساعت انتشار را مشخص کنید.');
                return;
            }
            else if(release != 'release'){
                var d = new Date();
                date = d.getJalaliFullYear() + '/' + (d.getJalaliMonth() + 1) + '/' + d.getJalaliDate();
            }

            if(release != 'draft'){
                let errInIf = '';

                if(_checkSeo) {
                    if (keyword.trim().length < 2)
                        errInIf += 'کلمه کلیدی مقاله را مشخص کنید';
                }
                if (meta.trim().length < 2)
                    errInIf += 'متا مقاله را مشخص کنید';
                if (seoTitle.trim().length < 2)
                    errInIf += 'عنوان سئو مقاله را مشخص کنید';
                if (slug.trim().length < 2)
                    errInIf += 'نامک مقاله را مشخص کنید';

                if(errInIf != ''){
                    alert(errInIf);
                    return;
                }
            }

            mainDataForm.append('id', id);
            mainDataForm.append('title', title);
            mainDataForm.append('keyword', keyword);
            mainDataForm.append('seoTitle', seoTitle);
            mainDataForm.append('slug', slug);
            mainDataForm.append('meta', meta);
            mainDataForm.append('gardeshName', gardeshName);
            mainDataForm.append('description', window.editor.getData());
            mainDataForm.append('limboPicIds', window.limboIds);
            mainDataForm.append('releaseType', release);
            mainDataForm.append('date', date);
            mainDataForm.append('time', time);
            mainDataForm.append('tags', JSON.stringify(tagsName));
            mainDataForm.append('category', JSON.stringify(selectedSafarnamehCategory));
            mainDataForm.append('places', JSON.stringify(selectedPlaces));
            mainDataForm.append('warningCount', warningCount);

            if (id == 0) {
                if(inputMainPic.files && inputMainPic.files[0]){
                    mainDataForm.append('pic', inputMainPic.files[0]);
                    ajaxPost();
                }
                else if(release != 'draft'){
                    alert('لطفا عکس اصلی را مشخص کنید.');
                    return;
                }
                else if(release == 'draft')
                    ajaxPost();
            }
            else {
                if (inputMainPic.files && inputMainPic.files[0])
                    mainDataForm.append('pic', inputMainPic.files[0]);

                ajaxPost();
            }
        }

        function ajaxPost(){
            openLoading();
            $.ajax({
                type: 'post',
                url: '{{route("safarnameh.store")}}',
                data: mainDataForm,
                processData: false,
                contentType: false,
                success: function(response){
                    if(response.status == 'ok'){
                        alert('تغییرات با موفقیت ثبت شد');
                        safarnamehId = response.result;
                        var location = window.location.href;
                        if(location.includes('safarnameh/new') || location.includes('gardeshEdit'))
                            window.location.href = '{{url("safarnameh/edit")}}/' + safarnamehId;
                        else
                            closeLoading();
                    }
                }
            });
        }

        function checkSeo(kind){
            var value = document.getElementById('keyword').value;
            var seoTitle = document.getElementById('seoTitle').value;
            var slug = document.getElementById('slug').value;
            var meta = document.getElementById('meta').value;
            var title = document.getElementById('title').value;
            var safarnamehId = document.getElementById('safarnamehId').value;
            var desc = window.editor.getData();

            $.ajax({
                type: 'post',
                url : '{{route("seoTesterSafarnamehContent")}}',
                data: {
                    _token: '{{csrf_token()}}',
                    keyword: value,
                    meta: meta,
                    seoTitle: seoTitle,
                    slug: slug,
                    title: title,
                    id: safarnamehId,
                    desc: desc
                },
                success: function(response){
                    response = JSON.parse(response);
                    document.getElementById('errorResult').innerHTML = '';
                    document.getElementById('warningResult').innerHTML = '';
                    document.getElementById('goodResult').innerHTML = '';


                    $('#warningResult').append(response[0]);
                    $('#goodResult').append(response[1]);
                    $('#errorResult').append(response[2]);
                    uniqueKeyword = response[5];
                    uniqueSlug = response[6];
                    uniqueTitle = response[7];
                    uniqueSeoTitle = response[8];

                    errorCount = response[3];
                    warningCount = response[4];

                    inlineSeoCheck(kind);
                }
            })
        }

        function inlineSeoCheck(kind){
            var tags = tagsName;
            if(tags.length == 0){
                errorCount++;
                text = '<div style="color: red;">شما باید برای متن خود برچسب انتخاب نمایید</div>';
                $('#errorResult').append(text);
            }
            else if(tags.length < 10){
                warningCount++;
                text = '<div style="color: #dec300;">پیشنهاد می گردد حداقل ده برچسب انتخاب نمایید.</div>';
                $('#warningResult').append(text);
            }
            else{
                text = '<div style="color: green;">تعداد برچسب های متن مناسب می باشد.</div>';
                $('#goodResult').append(text);
            }

            var cityError = false;
            if(selectedPlaces.length == 0)
                cityError = true;

            if(cityError){
                warningCount++;
                text = '<div style="color: #dec300;">آیا مطمئن هستید متن شما به شهر یا استان خاصی اختصاص ندارد.</div>';
                $('#warningResult').append(text);
            }
            else{
                text = '<div style="color: green;">متن شما دارای دسته بندی جغرافیایی می باشد.</div>';
                $('#goodResult').append(text);
            }

            var inputMainPic = document.getElementById('imgInput');

            if(!(inputMainPic.files && inputMainPic.files[0]) && (safarname == null || safarname['pic'] == null)){
                errorCount++;
                text = '<div style="color: red;">مقاله باید حتما دارای عکس اصلی باشد.</div>';
                $('#errorResult').append(text);
            }
            else{
                text = '<div style="color: green;">متن دارای عکس اصلی است.</div>';
                $('#goodResult').append(text);
            }

            if(kind == 1) {
                var release = document.getElementById('releaseType').value;

                if (release != 'draft' && errorCount > 0)
                    alert('برای ثبت سفرنامه باید ارورها رابرطرف کرده و یا انتشار را به حالت پیش نویس دراوردی.');
                if(!uniqueTitle)
                    alert('عنوان مقاله یکتا نیست');
                else if(!uniqueSlug)
                    alert('نامک مقاله یکتا نیست');
                else if(!uniqueKeyword)
                    alert('کلمه کلیدی مقاله یکتا نیست');
                else if(!uniqueSeoTitle)
                    alert('عنوان سئو مقاله یکتا نیست');
                else {
                    if (warningCount > 0) {
                        $('#warningContentModal').html('');
                        $('#warningResult').children().each(function (){
                            text = '<li style="margin-bottom: 5px">' + $(this).text() + '</li>';
                            $('#warningContentModal').append(text);
                        });
                        $('#warningModal').modal('show');
                        return;
                    }
                    else
                        storePost();
                }
            }
        }

        function changeSeoTitle(_value){
            var text = _value.length + ' حرف';
            $('#seoTitleNumber').text(text);
            if(_value.length > 60 && _value.length <= 85)
                $('#seoTitleNumber').css('color', 'green');
            else
                $('#seoTitleNumber').css('color', 'red');

        }

        function changeMeta(_value){
            var text = _value.length + ' حرف';
            $('#metaNumber').text(text);
            if(_value.length > 120 && _value.length <= 156)
                $('#metaNumber').css('color', 'green');
            else
                $('#metaNumber').css('color', 'red');
        }


        safarnamehCategory.map(item => {
            allSafarnamehCategoryList.push({
                id: item.id,
                main: 1,
                name: item.name,
                show: 1,
                selected: 0,
            });
            item.sub.map(sub => {
                allSafarnamehCategoryList.push({
                    id: sub.id,
                    main: 0,
                    name: sub.name,
                    show: 1,
                    selected: 0,
                });
            });
        });
        closeCategoryResult = () => $('#categoryResultDiv').removeClass('open');
        $(window).on('click', () => setTimeout(() => closeCategoryResult(), 100));

        function openCategoryResult(){
            if(!$('#categoryResultDiv').hasClass('open'))
                setTimeout(() => $('#categoryResultDiv').addClass('open'), 200);
        }
        function createCategoryResult(_result){
            var text = '';
            _result.map((item, index) => {
                if(item.show)
                    text += `<div class="resRow ${item.main == 1 ? 'mainCat' : 'sideCat'} ${item.selected == 1 ? 'selected' : ''}" onclick="selectedThisSafarnamehCategory(${index})">${item.name}</div>`;
            });
            $('#categoryResultDiv').html(text);
        }
        function searchInSafarnamehCategory(_value){
            allSafarnamehCategoryList.map(item => item.show = item.name.search(_value) > -1 ? 1 : 0);
            createCategoryResult(allSafarnamehCategoryList);
        }
        function selectedThisSafarnamehCategory(_index){
            allSafarnamehCategoryList.map(item => item.show = 1);
            if(allSafarnamehCategoryList[_index].selected == 1)
                return;

            allSafarnamehCategoryList[_index].selected = 1;
            selectedSafarnamehCategory.push(allSafarnamehCategoryList[_index]);
            selectedSafarnamehCategory[selectedSafarnamehCategory.length - 1].thisIsMain = 0;
            selectedSafarnamehCategory[selectedSafarnamehCategory.length - 1].indexList = _index;

            if(selectedSafarnamehCategory.length == 1)
                selectedSafarnamehCategory[0].thisIsMain = 1;

            createSelectedCategoryRow();
            createCategoryResult(allSafarnamehCategoryList);
        }
        function createSelectedCategoryRow(){
            var text = '';
            selectedSafarnamehCategory.map((item, index) => {
                text += `<div class="scRow">
                            <div class="name">${item.name}</div>
                            <div class="isMain ${item.thisIsMain == 1 ? 'selected' : ''}" onclick="chooseThisForMainCat(${index})" >اصلی</div>
                            <span class="closeWithCircleIcon" onclick="deleteFromSelectedCategory(${index})"></span>
                        </div>`;
            });

            $('#selectedCategory').html(text);
        }
        function chooseThisForMainCat(_index){
            selectedSafarnamehCategory.map(item => item.thisIsMain = 0);
            selectedSafarnamehCategory[_index].thisIsMain = 1;
            createSelectedCategoryRow();
        }
        function deleteFromSelectedCategory(_index){
            allSafarnamehCategoryList[selectedSafarnamehCategory[_index].indexList].selected = 0;
            selectedSafarnamehCategory.splice(_index, 1);
            allSafarnamehCategoryList.map(item => item.show = 1);
            createSelectedCategoryRow();
            createCategoryResult(allSafarnamehCategoryList);
        }
        createCategoryResult(allSafarnamehCategoryList);

        selectedCat.map(item => {
            allSafarnamehCategoryList.map((ascl, index) => {
                if(item.id == ascl.id) {
                    selectedThisSafarnamehCategory(index);
                    if(item.isMain == 1)
                        chooseThisForMainCat(selectedSafarnamehCategory.length-1);
                }
            });
        });
    </script>

    <script>
        $('#newTag').on('keyup', e => e.keyCode == 13 ? selectTag() : searchTag(e.target.value));
        function searchTag(value){
            if(value.trim().length > 1) {
                $('#searchTagResultDiv').empty();
                $.ajax({
                    type: 'get',
                    url: '{{route("safarnameh.tag.search")}}?text=' + value,
                    success: function (response) {
                        if (response.status == 'ok') {
                            var text = '';
                            if(value.trim().length  == 0){
                                $('#searchTagResultDiv').empty();
                                return;
                            }

                            if (response.value == value) {
                                response.result.map(item => text += `<div class="row searchTagResult" onclick="chooseTag(this.innerText)">${item}</div>`);
                                $('#searchTagResultDiv').html(text);
                            }
                        }
                    }
                });
            }
        }
        function chooseTag(_value){
            $('#searchTagResultDiv').empty();
            $('#newTag').val(_value);
            selectTag();
        }
        function selectTag() {
            var value = $('#newTag').val();
            if(tagsName.indexOf(value) == -1) {
                tagsName.push(value);
                if (value.trim().length != 0) {
                    $('#newTag').val('');
                    var text = `<div class="scRow">
                                <div class="name tagNameInputs">${value}</div>
                                <span class="closeWithCircleIcon" onclick="deleteTag(this)"></span>
                                </div>`;

                    $('#selectedTags').append(text);
                }
            }
        }
        function deleteTag(element){
            var value = $(element).prev().text();
            var index = tagsName.indexOf(value);
            if(index != -1)
                tagsName.splice(index, 1);

            $(element).parent().remove();
        }

        function searchPlaces(_value){
            if(_value.trim().length > 1){
                if(searchPlaceAjax != null)
                    searchPlaceAjax.abort();

                $('#searchPlaceResult').empty();

                searchPlaceAjax = $.ajax({
                    type: 'get',
                    url: '{{route("search.placesAndCity")}}?text='+_value,
                    success: response => {
                        if(response.status == 'ok')
                            createPlaceSearchResult(response.result);
                    },
                })
            }
            else
                $('#searchPlaceResult').empty();
        }
        function createPlaceSearchResult(_result){
            var text = '';
            _result.map(item => text += `<div class="row searchTagResult" onclick="choosePlace(${item.id}, '${item.name}', '${item.kind}')">${item.name}</div>`);
            $('#searchPlaceResult').html(text);
        }
        function choosePlace(_id, _name, _kind){
            var index = selectedPlaces.push({
                id: _id,
                name: _name,
                kind: _kind
            });
            var text = `<div class="scRow">
                                                    <div class="name tagNameInputs">${_name}</div>
                                                    <span class="closeWithCircleIcon" onclick="deletePlace(${index}, this)"></span>
                                                </div>`;

            $('#newPlace').val('');
            $('#searchPlaceResult').empty();
            $('#selectedPlaces').append(text);
        }
        function deletePlace(_index, _element){
            $(_element).parent().remove();
            selectedPlaces.splice(_index, 1);
        }
    </script>


@stop
