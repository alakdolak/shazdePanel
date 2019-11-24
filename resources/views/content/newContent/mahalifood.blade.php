<hr>
<div class="row" style="margin-top: 10px;">

    <div class=" f_r" style="margin-left: 10px;">
        <label for="kind">
            نوع غذا
        </label>
        <select id="kind" name="kind">
            <option value="1">چلوخورش</option>
            <option value="2">خوراک</option>
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
<div class="row" style="margin-top: 10px;">

    <div class="f_r" style="margin-left: 20px">
        <span style="direction: rtl; text-align: right" class="myLabel" >نوع غذا:</span>
    </div>

    <div class=" f_r" style="margin-left: 10px;">
        <span style="direction: rtl" class="myLabel">گیاهی</span>
        <label class="switch">
            <input type="radio" name="veg" id="veg" value="1" checked>
            <span class="slider round"></span>
        </label>
    </div>
    <div class="f_r">
        <span style="direction: rtl" class="myLabel">غیرگیاهی</span>
        <label class="switch">
            <input type="radio" name="veg" id="veg" value="0">
            <span class="slider round"></span>
        </label>
    </div>
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

        showErrorDivOrsubmit(error_text, error);
    }
</script>