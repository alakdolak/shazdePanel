
<style>
    .eleman{
        margin-left: 30px;
        width: 200px;
        margin-bottom: 10px;
        border-left: solid gray;
    }
</style>

<div class="row">
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="phone"> شماره تماس</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{old('phone')}}" minlength="8">
            <div class="inputDescription">
                شماره تماس را همراه با کد شهر وارد کنید.
            </div>
        </div>
    </div>
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="site"> آدرس سایت</label>
            <input type="text" class="form-control" name="site" id="site" value="{{old('site')}}" dir="ltr">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="address"> آدرس</label>
            <input type="text" class="form-control" name="address" id="address" value="{{old('address')}}">
        </div>
    </div>
</div>

<hr>
<div class="row" style="display: flex; align-items: center; height: 55px;">
    <div class="col-md-2">
        <span style="direction: rtl" class="myLabel">نوع هتل </span>
        <select name="kind" id="kind" class="form-control">
            <option value="1">هتل</option>
            <option value="2">هتل آپارتمان</option>
            <option value="3">مهمان سرا</option>
            <option value="4">ویلا</option>
            <option value="5">متل</option>
            <option value="6">مجتمع تفریحی</option>
            <option value="7">پانسیون</option>
            <option value="8">بوم گردی</option>
        </select>
    </div>
    <div class="col-md-2">
        <span style="direction: rtl" class="myLabel">ستاره هتل </span>
        <select name="rate_int" id="rate_int" class="form-control">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>
    <div class="col-md-2">
        <span style="direction: rtl" class="myLabel">ایا هتل ممتاز است؟</span>
        <label class="switch">
            <input type="checkbox" name="momtaz" id="momtaz">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="display: flex; justify-content: center; align-items: center; height: 55px;">

    <div class="col-md-2">
        <span style="direction: rtl" class="myLabel">وابستگی سازمانی:</span>
        <label class="switch">
            <input type="checkbox" name="isVabastegi" id="isVabastegi" onchange="vabastegiFunc()">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-md-3">
        <div id="vabastegiInput" class="form-group">
            <input type="text" class="form-control" name="vabastegi" id="vabastegi" placeholder="نام سازمان">
        </div>
    </div>

    <div class="col-md-3">
        <span style="direction: rtl" class="myLabel">تعداد اتاق:</span>
        <input type="number" class="form-control" name="room_num" id="room_num">
    </div>

</div>

@foreach($features as $feat)
    <hr>
    <div class="row" style="display: flex; flex-wrap: wrap">
        <div class="col-sm-12 f_r" style="font-weight: bold; margin-bottom: 10px">
            {{$feat->name}}:
        </div>
        <?php $last = 0; ?>
        @foreach($feat->subFeat as $item)
            <?php $last++; ?>
            <div class="col-sm-2 f_r" style="{{$last == count($feat->subFeat) ? '' : 'border-left: solid gray; '}} display: flex; justify-content: space-around; margin-bottom: 5px">
                <span style="direction: rtl" class="myLabel">{{$item->name}}</span>

                @if($item->type == 'YN')
                    <label class="switch">
                        <input type="checkbox" name="features[]" value="{{$item->id}}">
                        <span class="slider round"></span>
                    </label>
                @endif

            </div>
        @endforeach
    </div>
@endforeach

<script>
    var vabas = 0;

    function vabastegiFunc(){
        if(vabas == 0){
            document.getElementById('vabastegiInput').style.display = 'none';
            vabas = 1;
        }
        else{
            document.getElementById('vabastegiInput').style.display = 'inline-block';
            vabas = 0;
        }
    }

    if(vabas == 0){
        document.getElementById('vabastegiInput').style.display = 'none';
        vabas = 1;
    }
    else{
        document.getElementById('vabastegiInput').style.display = 'inline-block';
        vabas = 0
    }

    function checkForm(){
        var error_text = '';
        var error = false;

        var room_num = document.getElementById('room_num').value;
        if(room_num == '' || room_num == null){
            error = true;
            error_text += '<li>تعداد اتاق را کامل کنید.</li>';
            document.getElementById('room_num').classList.add('error');
        }
        else
            document.getElementById('room_num').classList.remove('error');

        var address = document.getElementById('address').value;
        if(address == '' || address == null || address == ' '){
            error = true;
            error_text += '<li>ادرس را کامل کنید.</li>';
            document.getElementById('address').classList.add('error');
        }
        else
            document.getElementById('address').classList.remove('error');

        showErrorDivOrsubmit(error_text, error);
    }

</script>
