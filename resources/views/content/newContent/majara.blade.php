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

        var C = document.getElementById('lat').value;
        var D = document.getElementById('lng').value;
        if (C == 0 || D == 0) {
            error = true;
            error_text += '<li>محل مکان را از روی نقشه انتخاب کنید.</li>';
        }

        showErrorDivOrsubmit(error_text, error);
    }
</script>
