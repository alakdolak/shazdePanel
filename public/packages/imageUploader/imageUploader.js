// input sample = {
//     id :              id of element,
//     url:              url to upload,
//     csrf:             csrf_token,
//     haveAlt:          true or false
//     haveEdit:          true or false
//     data:             any data to send to server,
//     initPic:          initial pictures to show,
//     initCallBack:     function run after create,
//     callBack:         function run after any upload pic,
//     onDeletePic:      function run after click to delete
//     onEditPic:      function run after click to edit
//     onChangeAlt:      function run after change pic alt
// }

dropzoneSettings = [];

function createNewPicSection(_info) {
    let number = dropzoneSettings.length;
    dropzoneSettings.push({});

    let section = $('#' + _info['id']);
    section.append('<div id="dropzone' + number + '" class="dropzone"></div>');
    section.append('<div id="uploadedPicUploader' + number + '"></div>');

    let Data = JSON.stringify(_info['data']);

    let myDropzone = new Dropzone("div#dropzone" + number, {
        url: _info['url'],
        paramName: "pic",
        timeout: 60000,
        headers: {
            'X-CSRF-TOKEN': _info['csrf']
        },
        parallelUploads: 1,
        acceptedFiles: 'image/*',
        init: function() {
            this.on("sending", function(file, xhr, formData){
                formData.append("data", Data);
            });
            if(_info['initPic'] != '' && _info['initPic'] != null){
                for(x of _info['initPic'])
                    creteUploadedPicPictures(x['url'], x['id'], number, x['haveEdit'], x['haveAlt'], x['alt']);
            }

            if(typeof _info['initCallBack'] === 'function')
                _info['initCallBack'](number);
        },
    })
        .on('success', function(file, response){
            response = JSON.parse(response);
            if(response['status'] == 'ok'){

                creteUploadedPicPictures(file['dataURL'], response['result'], number, _info['haveEdit'], _info['haveAlt'], '');

                if(typeof _info['callBack'] === 'function')
                    _info['callBack'](response['result']);
            }
        });

    _info['index'] = number;
    _info['json_data'] = Data;
    _info['dropZone'] = myDropzone;

    dropzoneSettings[number] = _info;

    return dropzoneSettings[number];
}

function creteUploadedPicPictures(_url, _id, _number,_haveEdit , _haveAlt, _alt){
    let text =  '<div id="uploadedPic_' + _number + '_' + _id + '" class="col-md-3 uploadedPicUploader">\n' +
        '   <img src="' + _url + '" class="uploadedPicImgUploader">\n' +
        '   <div class="uploadedPicHoverUploader">\n' +
        '       <button type="button" class="btn btn-danger" onclick="deletePicUploadedPic(' + _id + ', ' + _number + ', this)">پاک کردن عکس</button>\n';
    if(_haveEdit)
        text += '       <button type="button" class="btn btn-primary" onclick="editPicUploadedPic(' + _id + ', ' + _number + ', this)">ویرایش عکس</button>\n';
    if(_haveAlt) {
        if(_alt == null)
            _alt = '';
        text += '       <input type="text" id="altPic_' + _id + '" class="form-control" value="' + _alt + '" onchange="changeAltPicUploadedPic(' + _id + ', ' + _number + ', this.value)" placeholder="alt">   \n';
    }
    text +=     '   </div>\n' +
        '</div>';

    $('#uploadedPicUploader' + _number).append(text);
}

function deletePicUploadedPic(_id, _number, _element){
    if(typeof dropzoneSettings[_number]['onDeletePic'] === 'function')
        dropzoneSettings[_number]['onDeletePic'](_id);
}

function editPicUploadedPic(_id, _number, _element){
    if(typeof dropzoneSettings[_number]['onEditPic'] === 'function') {
        let src = $(_element).parent().prev().attr('src');
        dropzoneSettings[_number]['onEditPic'](_id, src);
    }
}

function changeAltPicUploadedPic(_id, _number, _value){
    if(typeof dropzoneSettings[_number]['onChangeAlt'] == 'function')
        dropzoneSettings[_number]['onChangeAlt'](_id, _value);
}
