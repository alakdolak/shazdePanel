<?php

namespace App\Http\Controllers;

use App\models\ReportsType;
use App\models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('admin.reports.index', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $reports = ReportsType::where('kindPlaceId', $kind->id)->get();
            return view('admin.reports.index', compact(['kind', 'reports']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $reports= ReportsType::where('description', $request->description)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($reports == null){
            $reports =  new ReportsType();
            $reports->description = $request->description;
            $reports->kindPlaceId = $request->kindPlaceId;
            $reports->save();
        }
        else{
            Session::flash('error', 'این گزارش  قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'id' => 'required'
        ]);

        $reports = ReportsType::find($request->id);

        if($reports != null){
            $checkReportsType = ReportsType::where('description', $request->description)->first();
            if($checkReportsType == null) {
                $reports->description = $request->description;
                $reports->save();
            }
            else{
                Session::flash('error', 'این گزارش  موجود می باشد');
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

        ReportsType::find($request->id)->delete();

        return redirect()->back();
    }
}
