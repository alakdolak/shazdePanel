var tagError1 = false;
var tagError2 = false;

function descriptionCounter(value) {
    var charLen = value.length;
    var wordLen = value.split(" ");

    document.getElementById('descriptionWordCount').innerText = 'تعداد کلمات :' + wordLen.length;
    document.getElementById('descriptionCharCount').innerText = 'تعداد حروف : ' + charLen;
}

function setkeyWord(_value){
    keyword = _value;
    allCheck();
}

function findCity(_value){
    document.getElementById('citySearch').style.display = 'none';
    city = [];
    $.ajax({
        type: 'post',
        url: findCityUrl,
        data: {
            '_token' : _token,
            'id' : _value
        },
        success: function(response){
            response = JSON.parse(response);
            if(response != 'nok') {
                document.getElementById('city').value = '';
                document.getElementById('cityId').value = '';
                city = response;
            }
        }
    })
}

function searchCity(_value){
    var text = '';
    document.getElementById('citySearch').style.display = 'none';

    if(_value != ' ' && _value != '  ' && _value != '') {
        for (i = 0; i < city.length; i++) {
            if (city[i]['name'].includes(_value)) {
                text += '<li class="liSearch" onclick="chooseCity(' + i + ')">' + city[i].name + '</li>';
            }
        }

        document.getElementById('citySearch').style.display = 'block';
        document.getElementById('resultCity').innerHTML = text;
    }
}

function chooseCity(_i){
    document.getElementById('resultCity').innerHTML = '';
    document.getElementById('citySearch').style.display = 'none';
    document.getElementById('city').classList.remove('error');
    document.getElementById('city').value = city[_i].name;
    document.getElementById('cityId').value = city[_i].id;

}

function firstCheck(){
    var h1 = document.getElementById('h1').value;
    var meta = document.getElementById('meta').value;
    var desc = document.getElementById('description').value;

    changeH1(h1);
    metaCheck(meta);
    descriptionCheck(desc)
}

function checkTags(){
    tagError1 = false;
    tagError2 = false;

    for(i = 1; i < 6; i++){
        var tag0 = document.getElementById('tag' + i).value;
        if(tag0 == '' || tag0 == null){
            tagError2 = true;
            document.getElementById('tag' + i).classList.add('error');
        }
        else
            document.getElementById('tag' + i).classList.remove('error');
    }

    for(i = 1; i < 16; i++){
        var tag0 = document.getElementById('tag' + i).value;
        var tag0Error = false;

        if(tag0 != null && tag0 != '') {
            for (j = i + 1; j < 16; j++) {
                var tag1 = document.getElementById('tag' + j).value;
                if(tag1 != null && tag1 != '') {
                    if(tag1 == tag0) {
                        tag0Error = true;
                        tagError1 = true;
                        break;
                    }
                }
            }

            if(tag0Error)
                document.getElementById('tag' + i).classList.add('error');
            else
                document.getElementById('tag' + i).classList.remove('error');
        }
    }

}

function showErrorDivOrsubmit(error_text, error){
    if(tagError1){
        error = true;
        error_text += '<li>تگ های شما دارای مقادیر تکراری می باشد</li>';
    }
    if(tagError2){
        error = true;
        error_text += '<li>5 تگ اول باید پر شوند</li>';
    }

    var name = document.getElementById('name').value;
    if (name == '' || name == null || name == ' ') {
        error = true;
        error_text += '<li>نام ان را مشخص کنید.</li>';
        document.getElementById('name').classList.add('error');
    }
    else
        document.getElementById('name').classList.remove('error');

    var cityId = document.getElementById('cityId').value;
    var city = document.getElementById('city').value;
    if(cityId == '' || cityId == null  || cityId == 0 || city == '' || city == null){
        error = true;
        error_text += '<li>شهر مکان را مشخص کنید.</li>';
        document.getElementById('city').classList.add('error');
    }

    if(error){
        var text = '<div class="alert alert-danger alert-dismissible">\n' +
            '            <button type="button" class="close" data-dismiss="alert" style="float: left">&times;</button>\n' +
            '            <ul id="errorList">\n' + error_text +
            '            </ul>\n' +
            '        </div>';
        document.getElementById('errorDiv').style.display = 'block';
        document.getElementById('errorDiv').innerHTML = text;
    }
    else
        $('#form').submit();
}
checkTags();
