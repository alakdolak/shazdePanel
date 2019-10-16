<?php

namespace App\Http\Controllers;

use App\models\Place;
use App\models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TagsController extends Controller
{
    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('admin.tags.index', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $tags = Tag::where('kindPlaceId', $kind->id)->get();
            return view('admin.tags.index', compact(['kind', 'tags']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $place = Tag::where('name', $request->name)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($place == null){
            $place =  new Tag();
            $place->name = $request->name;
            $place->kindPlaceId = $request->kindPlaceId;
            $place->save();
        }
        else{
            Session::flash('error', 'این تگ قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $place = Tag::find($request->id);

        if($place != null){
            $checkPlace = Tag::where('name', $request->name)->first();
            if($checkPlace == null) {
                $place->name = $request->name;
                $place->save();
            }
            else{
                Session::flash('error', 'این تگ موجود می باشد');
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

        Tag::find($request->id)->delete();

        return redirect()->back();
    }
}
