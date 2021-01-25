@extends('layouts.structure')

@section('header')
    @parent

@stop

@section('content')

    <div class="col-md-12">
        <div class="sparkline8-list shadow-reset mg-tb-30">
            <div class="sparkline8-hd">
                <div class="main-sparkline8-hd" style=" width: 100%; display: flex; justify-content: space-between;">
                    <h1>{{isset($adv) ? 'ویرایش تبلیغ' : 'ایجاد تبلیغ جدید'}}</h1>
                    <div>
                        <button onclick="document.location.href = '{{route('advertisement', ['kind' => $kind])}}'" class="btn btn-primary">بازگشت</button>
                    </div>
                </div>
            </div>
            <div class="sparkline8-graph dashone-comment messages-scrollbar dashtwo-messages" style="height: auto!important; text-align: right;">
                <div class="col-md-12" style="direction: rtl; margin-bottom: 40px;">
                    <div class="row">
                        <div class="col-md-4" style="float: right">
                            <div class="form-group">
                                <label for="advTitle">عنوان تبلیغ</label>
                                <input type="text" class="form-control" id="advTitle" value="{{isset($adv) ? $adv->title : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4" style="float: right">
                            <div class="form-group">
                                <label for="advUrl">لینک تبلیغ</label>
                                <input type="text" class="form-control" id="advUrl" value="{{isset($adv) ? $adv->url : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2" style="float: right">
                            <div class="form-group">
                                <label for="advWeight">درجه اهمیت</label>
                                <input type="number" class="form-control" id="advWeight" value="{{isset($adv) ? $adv->weight : 1 }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3" style="float: right">
                            <div class="form-group">
                                <label for="advKind">نوع تبلیغ</label>
                                <select id="advKind" class="form-control">
                                    <option value="0">نوع تبلیغ را انتخاب کنید...</option>
                                    @foreach($advKinds as $item)
                                        <option value="{{$item->id}}" {{isset($adv) && $adv->kindId == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="border-top: solid 1px lightgray; margin-top: 10px; padding-top: 10px">
                        <div class="col-md-6" style="float: right">
                            <div class="form-group">
                                <label for="advPcPic">تصویر برای کامپیوتر</label>
                                <input type="file" id="advPcPic" onchange="changePic(this, 'prevPcPic')">

                                <img src="{{isset($adv) ? $adv->pics->pc : '#'}}" id="prevPcPic">
                            </div>
                        </div>
                        <div class="col-md-6" style="float: right">
                            <div class="form-group">
                                <label for="advMobilePic">تصویر برای گوشی</label>
                                <input type="file" id="advMobilePic" onchange="changePic(this, 'prevMobilePic')">

                                <img src="{{isset($adv) ? $adv->pics->mobile : '#' }}" id="prevMobilePic">
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: flex; justify-content: center; align-items: center;">
                        <button class="btn btn-success" onclick="doUpload()">ارسال</button>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <script>
        var advId = {!! isset($adv) ? $adv->id : 0 !!};
        var section = '{{$kind}}';
        function changePic(_input, _id){
            var file = _input.files[0];
            if(file){
                var reader = new FileReader();
                reader.onload = e => {
                    console.log(_id);
                    console.log(e);
                    $('#'+_id).attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        }

        function doUpload(){
            var title = $('#advTitle').val();
            var url = $('#advUrl').val();
            var kind = $('#advKind').val();
            var weight = $('#advWeight').val();

            var pcPic = document.getElementById('advPcPic').files[0];
            var mobilePic = document.getElementById('advMobilePic').files[0];

            if(title.trim().length == 0 || url.trim().length == 0 || kind == 0 || weight.trim().length == 0 || (advId == 0 && (pcPic == undefined || mobilePic == undefined)))
                alert('پر کردن تمامی فیلدها اچباری است');
            else{
                openLoading();

                var formData = new FormData();
                formData.append('section', section);
                formData.append('title', title);
                formData.append('url', url);
                formData.append('kind', kind);
                formData.append('weight', weight);
                formData.append('pcPic', pcPic);
                formData.append('advId', advId);
                formData.append('mobilePic', mobilePic);

                openLoading();
                $.ajax({
                    method: 'POST',
                    url: '{{route("advertisement.store")}}',
                    data: formData,
                    processData: false,
                    contentType: false,
                    complete: closeLoading,
                    success: response => {
                        if(response.status == 'ok'){
                            alert('موفق');
                            location.href = '{{url("/advertise/edit/")}}/'+response.result;
                        }
                        else{
                            alert('آپلود با مشکل مواجه شد');
                        }
                    },
                    error: err => {
                        alert('آپلود با مشکل مواجه شد')
                    }
                })
            }

        }
    </script>

@stop
