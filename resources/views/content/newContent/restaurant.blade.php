<div class="row">
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="phone"> شماره تماس</label>
            <input type="text" class="form-control" name="phone" id="phone" minlength="8">
            <div class="inputDescription">
                شماره تماس را همراه با کد شهر وارد کنید.
            </div>
        </div>
    </div>
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="site"> آدرس سایت</label>
            <input type="text" class="form-control" name="site" id="site" dir="ltr">
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
        <span style="direction: rtl" class="myLabel">نوع رستوران </span>
        <select name="kind_id" id="kind_id" class="form-control">
            <option value="1">رستوران</option>
            <option value="2">فست فود</option>
        </select>
    </div>
</div>

<hr>
<div class="row" style="display: flex">
    <div class="col-sm-2 f_r">
        غذای رستوران:
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
            <input type="checkbox" name="modern" id="modern" value="on" onchange="changeArchi('modern')">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">سنتی</span>
        <label class="switch">
            <input type="checkbox" name="sonnati" id="sonnati" value="on" onchange="changeArchi('sonnati')">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">قدیمی</span>
        <label class="switch">
            <input type="checkbox" name="ghadimi" id="ghadimi" value="on" onchange="changeArchi('ghadimi')">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">معمولی</span>
        <label class="switch">
            <input type="checkbox" name="mamooli" id="mamooli" value="on" onchange="changeArchi('mamooli')">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<script>
    function checkForm(){
        var error_text = '';
        var error = false;

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

</script>