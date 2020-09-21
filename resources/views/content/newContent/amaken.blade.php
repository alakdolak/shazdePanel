
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

@foreach($features as $feat)
    <hr>
    <div class="row" style="display: flex; flex-wrap: wrap">
        <div class="col-sm-12 f_r" style="font-weight: bold; margin-bottom: 10px">
            {{$feat->name}}:
        </div>
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
        if(address == '' || address == null || address == ' '){
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

</script>
