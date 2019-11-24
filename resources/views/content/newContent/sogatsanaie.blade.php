
<hr>
<div class="row" style="margin-top: 10px;">
    <div class=" f_r" style="margin-left: 10px;">
        <label for="kind">
            نوع
        </label>
        <select id="kind" name="eatable" onchange="changeKind(this.value)">
            <option value="1">خوراکی</option>
            <option value="0">غیر خوراکی</option>
        </select>
    </div>
</div>

<div id="eatableDiv">
    <hr>
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-12 f_r" style="margin-bottom: 10px; font-weight: bold">
            <span style="direction: rtl" class="myLabel">مزه خوراکی:</span>
        </div>

        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">ترش</span>
            <label class="switch">
                <input type="checkbox" name="torsh" id="torsh" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">شیرین</span>
            <label class="switch">
                <input type="checkbox" name="shirin" id="shirin" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">تلخ</span>
            <label class="switch">
                <input type="checkbox" name="talkh" id="talkh" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">ملس</span>
            <label class="switch">
                <input type="checkbox" name="malas" id="malas" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">شور</span>
            <label class="switch">
                <input type="checkbox" name="shor" id="shor" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">تند</span>
            <label class="switch">
                <input type="checkbox" name="tond" id="tond" value="on">
                <span class="slider round"></span>
            </label>
        </div>

    </div>
</div>

<div id="notEatableDiv" style="display: none">
    <hr>
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-2 f_r">
            <span style="direction: rtl; font-weight: bold" class="myLabel">نوع:</span>
        </div>

        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">زیورآلات</span>
            <label class="switch">
                <input type="checkbox" name="jewelry" id="jewelry" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">پارچه و پوشیدنی</span>
            <label class="switch">
                <input type="checkbox" name="cloth" id="cloth" value="on" >
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">لوازم تزئینی</span>
            <label class="switch">
                <input type="checkbox" name="decorative" id="decorative" value="on">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">لوازم کاربردی منزل</span>
            <label class="switch">
                <input type="checkbox" name="applied" id="applied" value="on">
                <span class="slider round"></span>
            </label>
        </div>
    </div>

    <hr>
    <div class="row" style="margin-top: 10px;">
        <div class="col-sm-2 f_r">
            <span style="direction: rtl; font-weight: bold" class="myLabel">سبک:</span>
        </div>

        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">سنتی</span>
            <label class="switch">
                <input type="radio" name="style" value="1">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">مدرن</span>
            <label class="switch">
                <input type="radio" name="style" value="2" >
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">تلفیقی</span>
            <label class="switch">
                <input type="radio" name="style" value="3">
                <span class="slider round"></span>
            </label>
        </div>
    </div>

    <hr>
    <div class="row" style="margin-top: 10px;">

        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">شکستنی</span>
            <label class="switch">
                <input type="radio" name="fragile" value="1">
                <span class="slider round"></span>
            </label>
        </div>
        <div class="col-sm-2 f_r" style="border-left: solid gray;">
            <span style="direction: rtl" class="myLabel">غیر شکستنی</span>
            <label class="switch">
                <input type="radio" name="fragile" value="0" >
                <span class="slider round"></span>
            </label>
        </div>

    </div>

    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="site">جنس</label>
                <input type="text" class="form-control" name="material" id="material" placeholder="جنس ان را توضیح دهید" value="{{old('material')}}">
            </div>
        </div>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-2 f_r">
        <span style="direction: rtl; font-weight: bold" class="myLabel">ابعاد:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">کوچک</span>
        <label class="switch">
            <input type="radio" name="size" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">متوسط</span>
        <label class="switch">
            <input type="radio" name="size" value="2" >
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">بزرگ</span>
        <label class="switch">
            <input type="radio" name="size" value="3">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-2 f_r">
        <span style="direction: rtl; font-weight: bold" class="myLabel">وزن:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">سبک</span>
        <label class="switch">
            <input type="radio" name="weight" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">متوسط</span>
        <label class="switch">
            <input type="radio" name="weight" value="2" >
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">سنگین</span>
        <label class="switch">
            <input type="radio" name="weight" value="3">
            <span class="slider round"></span>
        </label>
    </div>
</div>
<hr>
<div class="row" style="margin-top: 10px;">
    <div class="col-sm-2 f_r">
        <span style="direction: rtl; font-weight: bold" class="myLabel">کلاس قیمتی:</span>
    </div>

    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">ارزان</span>
        <label class="switch">
            <input type="radio" name="price" value="1">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">متوسط</span>
        <label class="switch">
            <input type="radio" name="price" value="2" >
            <span class="slider round"></span>
        </label>
    </div>
    <div class="col-sm-2 f_r" style="border-left: solid gray;">
        <span style="direction: rtl" class="myLabel">گران</span>
        <label class="switch">
            <input type="radio" name="price" value="3">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<script>
    var kind = 1;

    function changeKind(_value){
        kind = _value;
        if(_value == 1){
            document.getElementById('notEatableDiv').style.display = 'none';
            document.getElementById('eatableDiv').style.display = 'block';
        }
        else{
            document.getElementById('eatableDiv').style.display = 'none';
            document.getElementById('notEatableDiv').style.display = 'block';
        }
    }

    function checkForm() {
        var error_text = '';
        var error = false;

        if (kind != 1){
            var material = document.getElementById('material').value;
            if (material == '' || material == null || material == ' ') {
                error = true;
                error_text += '<li>جنس ان را مشخص کنید.</li>';
                document.getElementById('material').classList.add('error');
            }
            else
                document.getElementById('material').classList.remove('error');
        }

        showErrorDivOrsubmit(error_text, error);
    }

</script>
