<?php

namespace App\Http\Controllers;

use App\models\Cities;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function findCityWithState(Request $request)
    {
        if(isset($request->id)){
            $city = Cities::where('stateId', $request->id)->get();
            echo json_encode($city);
        }
        else
            echo json_encode('nok');

        return ;
    }
}
