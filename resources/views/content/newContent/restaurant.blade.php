<div class="row">
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="phone"> شماره تماس</label>
            <input type="text" class="form-control" name="phone" id="phone" minlength="8">
            <div class="inputDescription">
                شماره تماس را همراه با کد شهر وارد کنید.
            </div>
        </div>
    </div>
    <div class="col-md-6 f_r">
        <div class="form-group">
            <label for="site"> آدرس سایت</label>
            <input type="text" class="form-control" name="site" id="site" dir="ltr">
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
<div class="row" style="display: flex; align-items: center; height: 55px;">
    <div class="col-md-2">
        <span style="direction: rtl" class="myLabel">نوع رستوران </span>
        <select name="kind_id" id="kind_id" class="form-control">
            <option value="1">رستوران</option>
            <option value="2">فست فود</option>
        </select>
    </div>
</div>

@foreach($features as $feat)
    <hr>
    <div class="row" style="display: flex; flex-wrap: wrap">
        <div class="col-sm-12 f_r" style="font-weight: bold; margin-bottom: 10px"> {{$feat->name}}: </div>
        <?php $last = 0; ?>
        @foreach($feat->subFeat as $item)
            <?php $last++; ?>
            <div class="col-sm-2 f_r" style="{{$last == count($feat->subFeat) ? '' : 'border-left: solid gray; '}} display: flex; justify-content: space-around; margin-bottom: 5px">
                <span style="direction: rtl" class="myLabel">{{$item->name}}</span>

                @if($item->type == 'YN')
                    <label class="switch">
                        <input type="checkbox" name="features[]" value="{{$item->id}}">
                        <span class="slider round"></span>
                    </label>
                @endif

            </div>
        @endforeach
    </div>
@endforeach


<script>
    function checkForm(){
        var error_text = '';
        var error = false;

        var address = document.getElementById('address').value;

        if(address.trim().length < 5){
            error = true;
            error_text += '<li>ادرس را کامل کنید.</li>';
            document.getElementById('address').classList.add('error');
        }
        else
            document.getElementById('address').classList.remove('error');

        var C = document.getElementById('lat').value;
        var D = document.getElementById('lng').value;
        if (C == 0 || D == 0) {
            error = true;
            error_text += '<li>محل مکان را از روی نقشه انتخاب کنید.</li>';
        }

        showErrorDivOrsubmit(error_text, error);
    }

    function changeArchi(_id){
        if(_id == 'modern'){
            document.getElementById('sonnati').checked = false;
            document.getElementById('ghadimi').checked = false;
            document.getElementById('mamooli').checked = false;
        }
        else{
            document.getElementById('modern').checked = false;
        }
    }

</script>
