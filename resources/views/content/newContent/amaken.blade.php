
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
        <span style="direction: rtl" class="myLabel">موزه</span>
        <label class="switch">
            <input type="checkbox" name="mooze" id="mooze" value="on">
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
        <span style="direction: rtl" class="myLabel">طبیعت گردی</span>
        <label class="switch">
            <input type="checkbox" name="tabiatgardi" id="tabiatgardi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">مرکز خرید</span>
        <label class="switch">
            <input type="checkbox" name="markazkharid" id="markazkharid" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">بافت تاریخی</span>
        <label class="switch">
            <input type="checkbox" name="baftetarikhi" id="baftetarikhi" value="on">
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
        <span style="direction: rtl" class="myLabel">موقعیت:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">شلوغ</span>
        <label class="switch">
            <input type="radio" name="population" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
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
    <div class="col-sm-2 f_r">
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
            <input type="radio" name="archi" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">تاریخی</span>
        <label class="switch">
            <input type="radio" name="archi" value="2">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">معمولی</span>
        <label class="switch">
            <input type="radio" name="archi" value="3">
            <span class="slider round"></span>
        </label>
    </div>
    {{--this section for mamooli and tarikhibana and modern--}}
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