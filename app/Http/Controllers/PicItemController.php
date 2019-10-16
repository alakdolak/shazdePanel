<?php

namespace App\Http\Controllers;

use App\models\PicItem;
use App\models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PicItemController extends Controller
{
    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('admin.picItem.index', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $picItems = PicItem::where('kindPlaceId', $kind->id)->get();
            return view('admin.picItem.index', compact(['kind', 'picItems']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $picItem= PicItem::where('name', $request->name)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($picItem == null){
            $picItem =  new PicItem();
            $picItem->name = $request->name;
            $picItem->kindPlaceId = $request->kindPlaceId;
            $picItem->save();
        }
        else{
            Session::flash('error', 'این آیتم قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $picItem = PicItem::find($request->id);

        if($picItem != null){
            $checkPicItem = PicItem::where('name', $request->name)->first();
            if($checkPicItem == null) {
                $picItem->name = $request->name;
                $picItem->save();
            }
            else{
                Session::flash('error', 'این آیتم موجود می باشد');
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

        PicItem::find($request->id)->delete();

        return redirect()->back();
    }
}
