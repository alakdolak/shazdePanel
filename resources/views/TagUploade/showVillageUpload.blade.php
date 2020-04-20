<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Village</title>
    <script src="{{URL::asset('js/vendor/jquery-1.11.3.min.js')}}"></script>
</head>
<body>
    <input type="text" id="number">
    <button onclick="insert()">insert</button>

    <h1>row added <input id="rowAdded" type="number" value="0" readonly></h1>

    <div>
        <h2>State Error</h2>
        <ul id="stateErr">
        </ul>
    </div>
    <hr>
    <div>
        <h2>City Error</h2>
        <ul id="cityErr">
        </ul>
    </div>
    <hr>

    <div>
        <h2>Duplicated Error</h2>
        <ul id="dupErr">
        </ul>
    </div>
</body>

<script !src="">
    let stateErr = [];
    let cityErr = [];
    let dupErr = [];

    function insert(){
        let number = $("#number").val();
        $.ajax({
            type: 'post',
            url: '{{url("insertVillageToDB")}}',
            data: {
                _token: '{{csrf_token()}}',
                number: number,
                stateErr: stateErr,
                cityErr: cityErr,
                dupErr: dupErr,
            },
            success: function(response){
                try{
                    response = JSON.parse(response);
                    if(response['status'] == 'ok'){
                        if(response['stateErr'].length > 0){
                            text = '';
                            stateErr = [];
                            for(i = 0; i < response['stateErr'].length; i++) {
                                stateErr.push(response["stateErr"][i]);
                                text += '<li>' + response["stateErr"][i] + '</li>';
                            }
                            $('#stateErr').html(text);
                        }
                        if(response['cityErr'].length > 0){
                            text = '';
                            cityErr = [];
                            for(i = 0; i < response['cityErr'].length; i++){
                                cityErr.push(response["cityErr"][i]);
                                text += '<li>' + response["cityErr"][i] + '</li>';
                            }
                            $('#cityErr').html(text);
                        }
                        if(response['dupErr'].length > 0){
                            text = '';
                            dupErr = [];
                            for(i = 0; i < response['dupErr'].length; i++){
                                dupErr.push(response["dupErr"][i]);
                                text += '<li>' + response["dupErr"][i] + '</li>';
                            }
                            $('#dupErr').html(text);
                        }
                        if(response['added'] != 0) {
                            let added = parseInt($('#rowAdded').val());
                            $('#rowAdded').val(added + response['added']);
                            $("#number").val(response['lastGetRow']);
                            insert();
                        }
                        else
                            alert('0 added');
                    }
                    else{
                        alert('status error');
                        console.log(response);
                    }
                }
                catch (e) {
                    alert('catch error');
                    console.log(e)
                }
            },
            error: function(err){
                alert('server error');
                console.log(err)
            }
        })
    }
</script>
</html>
