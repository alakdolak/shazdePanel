<?php

namespace App\Http\Controllers;

use App\models\TripStyle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TripStyleController extends Controller
{
    public function index()
    {
        $style = TripStyle::all();

        return view('admin.tripStyle.index', compact(['style']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $place = TripStyle::where('name', $request->name)->first();

        if($place == null){
            $place =  new TripStyle();
            $place->name = $request->name;
            $place->save();
        }
        else{
            Session::flash('error', 'این سبک سفر قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $place = TripStyle::find($request->id);

        if($place != null){
            $checkPlace = TripStyle::where('name', $request->name)->first();
            if($checkPlace == null) {
                $place->name = $request->name;
                $place->save();
            }
            else{
                Session::flash('error', 'سبک سفر با این نام موجود می باشد');
            }
        }
        else{
            Session::flash('error', 'مشکلی در ویرایش به وجود امد. لطفا دوباره تلاش کنید');
        }
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        TripStyle::find($request->id)->delete();

        return redirect()->back();
    }
}
