<hr>
<div class="row" style="margin-top: 10px;">

    <div class=" f_r" style="margin-left: 10px;">
        <label for="kind">
            نوع غذا
        </label>
        <select id="kind" name="kind">
            <option value="1">چلوخورش</option>
            <option value="2">خوراک</option>
            <option value="8">سوپ و آش</option>
            <option value="3">سالاد و پیش غذا</option>
            <option value="4">ساندویچ</option>
            <option value="5">کباب</option>
            <option value="6">دسر</option>
            <option value="7">نوشیدنی</option>
        </select>
    </div>

</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class="f_r" style="margin-left: 20px">
        <span style="direction: rtl; text-align: right" class="myLabel" >غذا سرد است و یا گرم?</span>
    </div>

    <div class=" f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">گرم</span>
        <label class="switch">
            <input type="radio" name="hotOrCold" id="hotOrCold" value="1" checked>
            <span class="slider round"></span>
        </label>
    </div>
    <div class="f_r">
        <span style="direction: rtl" class="myLabel">سرد</span>
        <label class="switch">
            <input type="radio" name="hotOrCold" id="hotOrCold" value="2">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div id="firForWho" class="row" style="margin-top: 10px;">

    <div class="f_r" style="margin-left: 20px">
        <span style="direction: rtl; text-align: right" class="myLabel" >مناسب برای:</span>
    </div>

    <div class=" f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">افراد گیاه‌خوار</span>
        <label class="switch">
            <input type="checkbox" name="vegetarian" id="vegetarian" value="on" onchange="fitFor(1)">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">وگان</span>
        <label class="switch">
            <input type="checkbox" name="vegan" id="vegan" value="on" onchange="fitFor(2)">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">افراد مبتلا به دیابت</span>
        <label class="switch">
            <input type="checkbox" name="diabet" id="diabet" value="on" onchange="fitFor(3)">
            <span class="slider round"></span>
        </label>
    </div>
    <div class="f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">هیچ کدام</span>
        <label class="switch">
            <input type="checkbox" name="commonEat" id="commonEat" value="on" onchange="fitFor(4)">
            <span class="slider round"></span>
        </label>
    </div>
</div>

<hr>
<div class="row" style="margin-top: 10px;">

    <div class=" f_r" style="margin-left: 10px;">
        <span>
            <input type="text" class="form-control" name="energy" id="energy" placeholder="مقدار کالری"
                   style="width: 100px; display: inline-block">
        </span>
        <span>
            کیلوکالری در هر
        </span>
        <span>
            <input type="text" class="form-control" name="volume" id="volume" placeholder="مقدار مرجع"
                   style="width: 100px; display: inline-block">
        </span>
        <span>
            <select name="source" class="form-control" style="width: 100px; display: inline-block">
                <option value="1">قاشق</option>
                <option value="2">گرم</option>
            </select>
        </span>
    </div>

    <span class=" f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">برنج</span>
        <label class="switch">
            <input type="checkbox" name="rice" id="rice" value="on">
            <span class="slider round"></span>
        </label>
    </span>

    <span class=" f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">نان</span>
        <label class="switch">
            <input type="checkbox" name="bread" id="bread" value="on">
            <span class="slider round"></span>
        </label>
    </span>

</div>

<hr>
<div class="row">
    <div class="f_r" style="font-weight: bold">
        مواد مورد نیاز
    </div>
    <div id="material" class="col-md-12">
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-3 f_r">
                <label>
                    نام مواد
                </label>
                <input type="text" name="matName[0]">
            </div>
            <div class="col-md-3 f_r">
                <label>
                    مقدار مواد
                </label>
                <input type="text" name="matValue[0]">
            </div>
        </div>
    </div>
    <div class="col-md-12" style="margin-top: 20px;">
        <button type="button" class="btn btn-success" onclick="addMaterial()">
            افزودن
        </button>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="site">دستور پخت</label>
            <textarea class="form-control" name="recipes" id="recipes" rows="10" value="{{old('recipes')}}"></textarea>
        </div>
    </div>
</div>

<script>
    var materialNum = 1;
    function addMaterial(){
        var text = '<div class="row" style="margin-top: 10px;">\n' +
            '                                                <div class="col-md-3 f_r">\n' +
            '                                                    <label>\n' +
            '                                                        نام مواد\n' +
            '                                                    </label>\n' +
            '                                                    <input type="text" name="matName[' + materialNum + ']">\n' +
            '                                                </div>\n' +
            '                                                <div class="col-md-3 f_r">\n' +
            '                                                    <label>\n' +
            '                                                        مقدار مواد\n' +
            '                                                    </label>\n' +
            '                                                    <input type="text" name="matValue[' + materialNum + ']">\n' +
            '                                                </div>\n' +
            '                                            </div>';
        $('#material').append(text);
        materialNum++;
    }

    function checkForm(){
        var error_text = '';
        var error = false;

        var diabet = document.getElementById('diabet').checked;
        var vegetarian = document.getElementById('vegetarian').checked;
        var vegan = document.getElementById('vegan').checked;
        var commonEat = document.getElementById('commonEat').checked;

        if(!diabet && !vegetarian && !vegan && !commonEat ){
            error = true;
            error_text += '<li>حتما باید یکی از گزینه های "مناسب برای" را انتخاب کنید</li>';
            document.getElementById('firForWho').classList.add('error');

        }

        var volume = document.getElementById('volume').value;
        var energy = document.getElementById('energy').value;
        if(volume == '' || energy == ''){
            error = true;
            error_text += '<li>فیلدهای کالری باید پر شود.</li>';
        }

        showErrorDivOrsubmit(error_text, error, false);
    }

    function fitFor(num){
        if(num == 4){
            document.getElementById('diabet').checked = false;
            document.getElementById('vegetarian').checked = false;
            document.getElementById('vegan').checked = false;
        }
        else
            document.getElementById('commonEat').checked = false;
    }
</script>
