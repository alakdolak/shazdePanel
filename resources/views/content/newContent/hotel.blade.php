
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

<hr>
<div class="row" style="display: flex">
    <div class="col-sm-2 f_r">
        غذای هتل:
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">ایرانی</span>
        <label class="switch">
            <input type="checkbox" name="food_irani" id="food_irani" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">محلی</span>
        <label class="switch">
            <input type="checkbox" name="food_mahali" id="food_mahali" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">فرنگی</span>
        <label class="switch">
            <input type="checkbox" name="food_farangi" id="food_farangi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کافی شاپ</span>
        <label class="switch">
            <input type="checkbox" name="coffeeshop" id="coffeeshop" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">محدوده قرار گیری:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">مرکز شهر</span>
        <label class="switch">
            <input type="radio" name="boundArea" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">حومه شهر</span>
        <label class="switch">
            <input type="radio" name="boundArea" value="2">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">خارج شهر</span>
        <label class="switch">
            <input type="radio" name="boundArea" value="3">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">داخل بافت تاریخی</span>
        <label class="switch">
            <input type="checkbox" name="tarikhi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">موقعیت:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">شلوغ</span>
        <label class="switch">
            <input type="radio" name="population" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">خلوت</span>
        <label class="switch">
            <input type="radio" name="population" value="2">
            <span class="slider round"></span>
        </label>
    </div>
    {{--this section for shologh and khalvat--}}
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">محیط:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">طبیعت</span>
        <label class="switch">
            <input type="checkbox" name="tabiat" id="tabiat" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کوه</span>
        <label class="switch">
            <input type="checkbox" name="kooh" id="kooh" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">دریا</span>
        <label class="switch">
            <input type="checkbox" name="darya" id="darya" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کویر</span>
        <label class="switch">
            <input type="checkbox" name="kavir" id="kavir" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">معماری:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">مدرن: </span>
        <label class="switch">
            <input type="checkbox" name="modern" id="modern" onchange="changeArchi('modern')" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">سنتی</span>
        <label class="switch">
            <input type="checkbox" name="sonnati" id="sonnati" onchange="changeArchi('sonnati')" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">قدیمی</span>
        <label class="switch">
            <input type="checkbox" name="ghadimi" id="ghadimi" onchange="changeArchi('ghadimi')" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">معمولی</span>
        <label class="switch">
            <input type="checkbox" name="mamooli" id="mamooli" onchange="changeArchi('mamooli')" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">امکانات غذایی هتل:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">صبحانه رایگان</span>
        <label class="switch">
            <input type="checkbox" name="breakfast" id="breakfast" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">نهار رایگان</span>
        <label class="switch">
            <input type="checkbox" name="lunch" id="lunch" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">شام رایگان</span>
        <label class="switch">
            <input type="checkbox" name="dinner" id="dinner" value="on">
            <span class="slider round"></span>
        </label>
    </div>

</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="eleman f_r" style="width: 100%; border: none;">
        <span style="direction: rtl" class="myLabel">امکانات هتل:</span>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">پارکینگ </span>
        <label class="switch">
            <input type="checkbox" name="parking" id="parking" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">باشگاه ورزشی </span>
        <label class="switch">
            <input type="checkbox" name="club" id="club" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">استخر</span>
        <label class="switch">
            <input type="checkbox" name="pool" id="pool" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">گرمایش و سرمایش در اتاق</span>
        <label class="switch">
            <input type="checkbox" name="tahviye" id="tahviye" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">امکانات ویژه معلولان</span>
        <label class="switch">
            <input type="checkbox" name="maalool" id="maalool" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">اینترنت در اتاق</span>
        <label class="switch">
            <input type="checkbox" name="internet" id="internet" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">انتن دهی در اتاق</span>
        <label class="switch">
            <input type="checkbox" name="anten" id="anten" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">رستوران</span>
        <label class="switch">
            <input type="checkbox" name="restaurant" id="restaurant" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">سوئیت</span>
        <label class="switch">
            <input type="checkbox" name="swite" id="swite" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">امکانات ماساژ</span>
        <label class="switch">
            <input type="checkbox" name="masazh" id="masazh" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">خدمات خشک شویی</span>
        <label class="switch">
            <input type="checkbox" name="laundry" id="laundry" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">گشت روزانه</span>
        <label class="switch">
            <input type="checkbox" name="gasht" id="gasht" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">گاوصندوق در اتاق</span>
        <label class="switch">
            <input type="checkbox" name="safe_box" id="safe_box" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">فروشگاه</span>
        <label class="switch">
            <input type="checkbox" name="shop" id="shop" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">روف گاردن</span>
        <label class="switch">
            <input type="checkbox" name="roof_garden" id="roof_garden" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">گیم نت</span>
        <label class="switch">
            <input type="checkbox" name="game_net" id="game_net" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="eleman f_r">
        <span style="direction: rtl" class="myLabel">اتاق کنفرانس</span>
        <label class="switch">
            <input type="checkbox" name="confrenss_room" id="confrenss_room" value="on">
            <span class="slider round"></span>
        </label>
    </div>

</div>


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

    function changeArchi(_id){
        if(_id == 'modern'){
            document.getElementById('sonnati').checked = false;
            document.getElementById('ghadimi').checked = false;
            document.getElementById('mamooli').checked = false;
        }
        else{
            document.getElementById('modern').checked = false;
        }
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
