<?php

namespace App\Http\Controllers;

use App\models\GoyeshTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoyeshTagsController extends Controller
{
    public function index()
    {
        $tags = GoyeshTag::all();

        return view('admin.goyeshTags.index', compact(['tags']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $tag = GoyeshTag::where('name', $request->name)->first();

        if($tag == null){
            $tag =  new GoyeshTag();
            $tag->name = $request->name;
            $tag->save();
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

        $tag = GoyeshTag::find($request->id);

        if($tag != null){
            $checkTag = GoyeshTag::where('name', $request->name)->first();
            if($checkTag == null) {
                $tag->name = $request->name;
                $tag->save();
            }
            else{
                Session::flash('error', 'تگی به این صورت موجود می باشد');
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

        GoyeshTag::find($request->id)->delete();

        return redirect()->back();
    }

}
