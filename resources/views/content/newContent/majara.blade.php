<div class="row">
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="nazdik"> نزدیک به کجاست؟</label>
            <input type="text" class="form-control" name="nazdik" id="nazdik">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="dastresi"> دسترسی</label>
            <input type="text" class="form-control" name="dastresi" id="dastresi">
        </div>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="col-sm-2 f_r">
        <span style="direction: rtl" class="myLabel">مناسب برای:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کمپ</span>
        <label class="switch">
            <input type="checkbox" name="kamp" id="kamp" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کوه نوردی</span>
        <label class="switch">
            <input type="checkbox" name="koohnavardi" id="koohnavardi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">صحرانوردی</span>
        <label class="switch">
            <input type="checkbox" name="sahranavardi" id="sahranavardi" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">پیک نیک</span>
        <label class="switch">
            <input type="checkbox" name="piknik" id="piknik" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="eleman f_r" style="width: 100%">
        <span style="direction: rtl" class="myLabel">ویژگی های محیطی:</span>
    </div>

    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کوه</span>
        <label class="switch">
            <input type="checkbox" name="kooh" id="kooh" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">دریا</span>
        <label class="switch">
            <input type="checkbox" name="darya" id="darya" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">دریاچه</span>
        <label class="switch">
            <input type="checkbox" name="daryache" id="daryache" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">شهری</span>
        <label class="switch">
            <input type="checkbox" name="shahri" id="shahri" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">منطقه‌حفاظت شده</span>
        <label class="switch">
            <input type="checkbox" name="hefazat" id="hefazat" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">حیات وحش</span>
        <label class="switch">
            <input type="checkbox" name="hayatevahsh" id="hayatevahsh" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کویر</span>
        <label class="switch">
            <input type="checkbox" name="kavir" id="kavir" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">رمل</span>
        <label class="switch">
            <input type="checkbox" name="raml" id="raml" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">جنگل</span>
        <label class="switch">
            <input type="checkbox" name="jangal" id="jangal" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">آبشار</span>
        <label class="switch">
            <input type="checkbox" name="abshar" id="abshar" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">دره</span>
        <label class="switch">
            <input type="checkbox" name="darre" id="darre" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">بکر</span>
        <label class="switch">
            <input type="checkbox" name="bekr" id="bekr" value="on">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="eleman f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">دشت</span>
        <label class="switch">
            <input type="checkbox" name="dasht" id="dasht" value="on">
            <span class="slider round"></span>
        </label>
    </div>
</div>


<script>
    function checkForm(){
        var error_text = '';
        var error = false;

        var dastresi = document.getElementById('dastresi').value;
        if(dastresi == '' || dastresi == null || dastresi == ' '){
            error = true;
            error_text += '<li>دسترسی را کامل کنید.</li>';
            document.getElementById('dastresi').classList.add('error');
        }
        else
            document.getElementById('dastresi').classList.remove('error');

        var nazdik = document.getElementById('nazdik').value;
        if(nazdik == '' || nazdik == null || nazdik == ' '){
            error = true;
            error_text += '<li>نزدیک را کامل کنید.</li>';
            document.getElementById('nazdik').classList.add('error');
        }
        else
            document.getElementById('nazdik').classList.remove('error');

        showErrorDivOrsubmit(error_text, error);
    }
</script>
