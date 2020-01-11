
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
<div class="row" style="display: flex">
    <div class="col-sm-2 f_r">
        کاربری:
    </div>
    <div class="col-sm-2 f_r"  style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">تاریخی</span>
        <label class="switch">
            <input type="checkbox" name="tarikhi" id="tarikhi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">تفریحی</span>
        <label class="switch">
            <input type="checkbox" name="tafrihi" id="tafrihi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">طبیعی</span>
        <label class="switch">
            <input type="checkbox" name="tabiatgardi" id="tabiatgardi" value="on">
            <span class="slider round"></span>
        </label>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">تجاری</span>
        <label class="switch">
            <input type="checkbox" name="tejari" id="tejari" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">مذهبی</span>
        <label class="switch">
            <input type="checkbox" name="mazhabi" id="mazhabi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">صنعتی</span>
        <label class="switch">
            <input type="checkbox" name="sanati" id="sanati" value="on">
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
    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">خارج شهر</span>
        <label class="switch">
            <input type="radio" name="boundArea" value="3">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">موقعیت ترافیکی:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">پر ازدحام</span>
        <label class="switch">
            <input type="radio" name="population" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">کم ازدحام</span>
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
        <span style="direction: rtl" class="myLabel">کوهستان</span>
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

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">جنگل</span>
        <label class="switch">
            <input type="checkbox" name="jangal" id="jangal" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">شهری</span>
        <label class="switch">
            <input type="checkbox" name="shahri" id="shahri" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">روستایی</span>
        <label class="switch">
            <input type="checkbox" name="village" id="village" value="on">
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
            <input type="checkbox" name="modern" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">تاریخی</span>
        <label class="switch">
            <input type="checkbox" name="tarikhibana" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
        <span style="direction: rtl; border-left: solid gray;" class="myLabel">بومی</span>
        <label class="switch">
            <input type="checkbox" name="boomi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
        <span style="direction: rtl; border-left: solid gray;" class="myLabel">مذهبی</span>
        <label class="switch">
            <input type="checkbox" name="mazhabiArch" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">آب و هوا:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">مرطوب و سرد: </span>
        <label class="switch">
            <input type="radio" name="weather" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">گرم و خشک</span>
        <label class="switch">
            <input type="radio" name="weather" value="2">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">گرم و مرطوب</span>
        <label class="switch">
            <input type="radio" name="weather" value="3">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">معتدل</span>
        <label class="switch">
            <input type="radio" name="weather" value="4">
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

</script>