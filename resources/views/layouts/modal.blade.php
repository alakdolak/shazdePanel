
<link rel="stylesheet" href="{{URL::asset('css/modals.css')}}">


<form method="post" id="modalForm">
    {{csrf_field()}}
    <div id="InformationproModalalert" class="modal modal-adminpro-general default-popup-PrimaryModal PrimaryModal-bgcolor fade bounceIn animated in" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-body">
                    <span class="fa fa-check modal-check-pro"> </span>
                    <h2 style="direction: rtl" id="modalTitle"></h2>
                    <div id="modalItems"></div>
                    <div id="modalMsg" style="color: white; margin-top: 10px"></div>
                </div>
                <div class="modal-footer footer-modal-admin">
                    <a style="margin-right: 10px" data-dismiss="modal" href="#">لغو</a>
                    <a style="cursor: pointer" onclick="$('#modalForm').submit()">تایید</a>
                </div>
            </div>
        </div>
    </div>
</form>


<div id="modalAjax">
    <div id="InformationproModalalertAjax" class="modal modal-adminpro-general default-popup-PrimaryModal PrimaryModal-bgcolor fade bounceIn animated in" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-body">
                    <span class="fa fa-check modal-check-pro"> </span>
                    <h2 style="direction: rtl" id="modalTitleAjax"></h2>
                    <div id="modalItemsAjax"></div>
                    <div id="modalMsgAjax" style="color: white; margin-top: 10px"></div>
                </div>
                <div class="modal-footer footer-modal-admin">
                    <a style="margin-right: 10px" data-dismiss="modal" href="#">لغو</a>
                    <a style="cursor: pointer" onclick="submitAjax()">تایید</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{URL::asset('js/modal-active.js')}}"></script>

<script>

    var selectedAction;
    var inputs = [];
    var checkSame = false;

    function createModal(action, items, title, msg, fileMode) {

        if(fileMode != undefined && fileMode) {
            $("#modalForm").attr('enctype', 'multipart/form-data');
        }

        $("#modalForm").attr('action', action);
        $("#modalTitle").empty().append(title);

        var newElement = "";

        for (var i = 0; i < items.length; i++) {
            newElement += '<div class="col-xs-12 ';
            for(var j = 0; j < items[i].class.length; j++)
                newElement += items[i].class[j];
            newElement += '">';
            newElement += '<label>';
            newElement += '<span>' + items[i].label + '</span>';
            if(items[i].type != "select")
                newElement += '<input type="' + items[i].type +'" name="' + items[i].name + '" value="'+ items[i].value +'" maxlength="100" required>';
            else {
                newElement += '<select name="' + items[i].name + '">';
                var options = JSON.parse(items[i].options);
                for(j = 0; j < options.length; j++) {
                    if(options[j].name == items[i].value)
                        newElement += '<option selected value="' + options[j].id + '">' + options[j].name + '</option>';
                    else
                        newElement += '<option value="' + options[j].id + '">' + options[j].name + '</option>';
                }
                newElement += '</select>';
            }
            newElement += '</label>';
            newElement += '</div>';
        }

        $("#modalItems").empty().append(newElement);
        $("#modalMsg").empty().append(msg);
    }

    function createAjaxModal(action, items, title, msg) {

        $("#modalTitleAjax").empty().append(title);
        selectedAction = action;

        var newElement = "";

        for (var i = 0; i < items.length; i++) {
            newElement += '<div class="col-xs-12 ';
            for(var j = 0; j < items[i].class.length; j++)
                newElement += items[i].class[j];
            newElement += '">';

            newElement += '<label>';
            newElement += '<span>' + items[i].label + '</span>';
            inputs[i] = items[i].name;

            if(items[i].type == "password")
                checkSame = true;

            if(items[i].type != "select")
                newElement += '<input type="' + items[i].type + '" id="' + items[i].name + '" value="' + items[i].value + '" maxlength="100" required>';
            else {
                newElement += '<select id="' + items[i].name + '">';
                var options = JSON.parse(items[i].options);
                for(j = 0; j < options.length; j++) {
                    if(options[j].name == items[i].value)
                        newElement += '<option selected value="' + options[j].id + '">' + options[j].name + '</option>';
                    else
                        newElement += '<option value="' + options[j].id + '">' + options[j].name + '</option>';
                }
                newElement += '</select>';
            }
            newElement += '</label>';
            newElement += '</div>';
        }

        $("#modalItemsAjax").empty().append(newElement);
        $("#modalMsgAjax").empty().append(msg);
    }

    function submitAjax() {

        var data = {};
        for (var i = 0; i < inputs.length; i++) {
            data[inputs[i]] = $("#" + inputs[i]).val();
        }

        if(checkSame == true) {
            if($("#" + inputs[0]).val() != $("#" + inputs[1]).val()) {
                $("#modalMsgAjax").empty().append("رمز و تکرار آن یکسان نیست");
                return;
            }
        }

        $.ajax({
            type: 'post',
            url: selectedAction,
            data: data
        });

        $(".close").click();

    }

</script>