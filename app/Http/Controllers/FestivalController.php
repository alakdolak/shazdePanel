<?php

namespace App\Http\Controllers;

use App\models\festival\Festival;
use App\models\festival\FestivalContent;
use App\models\festival\FestivalCookImage;
use App\models\MahaliFood;
use App\models\Place;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class FestivalController extends Controller
{
    public function festivalList()
    {
        $festivals = Festival::where('parent', 0)->get();
        foreach ($festivals as $item){
            $item->pageUrl = $item->pageUrl != null ? $item->pageUrl : '#';
            $item->pic = URL::asset('_images/festival/mainPics/'.$item->picture);
        }

        return view('festivals.festivalList', compact(['festivals']));
    }

    public function festivalEdit($id)
    {
        $festival = null;
        $pic = URL::asset('img/uploadPic.png');
        if($id != 0){
            $festival = Festival::find($id);
            if($festival == null)
                return redirect(route('festivals'));

            if($festival->picture != null)
                $pic = URL::asset('_images/festival/mainPics/'.$festival->picture);
        }

        return view('festivals.createEditFestival', compact(['festival', 'pic']));
    }

    public function festivalStatus(Request $request)
    {
        if(isset($request->festivalId)){
            $festival = Festival::find($request->festivalId);
            if($festival != null){
                $festival->update(['status' => $festival->status == 1 ? 0 : 1]);
                return response($festival->status);
            }
            else
                return response('error2');
        }
        else
            return response('error1');
    }

    public function festivalUpdate(Request $request)
    {
        if(isset($request->id) && isset($request->name)){
            if($request->id == 0){
                $festival = new Festival();
                $festival->parent = 0;
            }
            else
                $festival = Festival::find($request->id);

            if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
                $location = __DIR__.'/../../../../assets/_images/festival/mainPics';
                if(!is_dir($location))
                    mkdir($location);

                $fileType = explode('.',$_FILES['file']['name']);
                $fileType = end($fileType);
                $fileName = time().rand(100, 999).'.'.$fileType;

                $result = move_uploaded_file($_FILES['file']['tmp_name'], $location.'/'.$fileName);
                if($result){
                    if($festival->picture != null && is_file($location.'/'.$festival->picture))
                        unlink($location.'/'.$festival->picture);

                    $festival->picture = $fileName;
                }
            }

            $festival->name = $request->name;
            $festival->description = $request->description;
            $festival->pageUrl = $request->url;
            $festival->save();

            return response()->json(['status' => 'ok', 'result' => $festival->id]);
        }
        else
            return response()->json(['status' => 'error1']);
    }

    public function festivalContent($id)
    {
        $confirmed = [];
        $newContent = [];
        $notConfirmed = [];

        $festival = Festival::find($id);
        if($festival == null)
            return redirect(route('festival'));

//        $contents = FestivalContent::where('festivalId', $festival->id)->get();
//        foreach ($contents as $item){
//            $item->user = User::select(['id', 'username'])->find($item->userId);
//            $kindPlace = Place::find($item->kindPlaceId);
//            if($kindPlace != null){
//                $item->place = \DB::table($kindPlace->tableName)->select(['id', 'name'])->find($item->placeId);
//
//            }
//        }

        if($id == 4){
            $content = FestivalCookImage::all();
            foreach ($content as $item){
                $item->user = User::select(['id', 'username'])->find($item->userId);
                $item->file = URL::asset('_images/festival/cook/'.$item->file);
                $item->showPic = $item->file;
                if($item->thumbnail != null){
                    $item->thumbnail = URL::asset('_images/festival/cook/'.$item->thumbnail);
                    $item->showPic = $item->thumbnail;
                }

                if($item->foodId == null)
                    $item->newFood = true;
                else{
                    $item->newFood = false;
                    $item->food = MahaliFood::select(['id', 'name'])->find($item->foodId);
                    if($item->food != null)
                        $item->foodName = $item->food->name;
                    else
                        $item->newFood = true;
                }

                if($item->confirm == 0)
                    array_push($newContent, $item);
                else if($item->confirm == 1)
                    array_push($confirmed, $item);
                else
                    array_push($notConfirmed, $item);
            }

            return view('festivals.cookFestivalContent', compact(['confirmed', 'notConfirmed', 'newContent', 'festival']));
        }

        return view('festivals.festivalContent', compact(['confirmed', 'notConfirmed', 'newContent', 'festival']));
    }

    public function festivalUpdateConfirmed(Request $request)
    {
        if(isset($request->id) && isset($request->confirm) && isset($request->festivalId)){
            if($request->festivalId == 4)
                $content = FestivalCookImage::find($request->id);

            if($content != null){
                $content->confirm = $request->confirm;
                $content->save();
                return response()->json(['status' => 'ok']);
            }
        }
        else
            return response()->json(['status' => 'error1']);
    }

    public function festivalCookUpdateFood(Request $request)
    {
        if(isset($request->foodId) && isset($request->contentId)){
            $content = FestivalCookImage::find($request->contentId);
            $food = MahaliFood::find($request->foodId);
            if($content != null && $food != null){
                $content->foodId = $food->id;
                $content->foodName = null;
                $content->save();

                return response()->json(['status' => 'ok', 'result' => ['id' => $food->id, 'name' => $food->name]]);
            }
            else
                return response()->json(['status' => 'error2']);
        }
        else
            return response()->json(['status' => 'error1']);
    }
}
