
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

<script src="{{URL::asset('js/modal-active.js')}}"></script>

<script>
    
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
            newElement += '<input type="' + items[i].type +'" name="' + items[i].name + '" value="'+ items[i].value +'" maxlength="40" required>';
            newElement += '</label>';
            newElement += '</div>';
        }

        $("#modalItems").empty().append(newElement);
        $("#modalMsg").empty().append(msg);
    }
    
</script>