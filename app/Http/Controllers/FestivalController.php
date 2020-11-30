<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\festival\Festival;
use App\models\festival\FestivalContent;
use App\models\festival\FestivalCookImage;
use App\models\LogModel;
use App\models\MahaliFood;
use App\models\Message;
use App\models\Place;
use App\models\ReviewPic;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

            }

            return view('festivals.cookFestivalContent', compact(['content', 'festival']));
        }

        return view('festivals.festivalContent', compact(['confirmed', 'notConfirmed', 'newContent', 'festival']));
    }

    public function festivalUpdateConfirmed(Request $request)
    {
        if(isset($request->id) && isset($request->confirm) && isset($request->festivalId)){
            $festival = Festival::find($request->festivalId);
            $content = \DB::table($festival->tableName)->find($request->id);

            if($content != null){
                if($content->foodId == null && $request->confirm == 1)
                    return response()->json(['status' => 'error2']);

                $kindPlace = Place::find($content->kindPlaceId);
                $place = \DB::table($kindPlace->tableName)->find($content->foodId);
                if($place == null)
                    return response()->json(['status' => 'error2']);

                $failedReason = $request->confirm == -1 ? $request->failedReason : null;
                \DB::table($festival->tableName)
                    ->where('id', $content->id)
                    ->update(['confirm' => $request->confirm, 'failedReason' => $failedReason]);

                if($request->confirm == -1){
                    $newMsg = new Message();
                    $newMsg->senderId = 0;
                    $newMsg->receiverId = $content->userId;
                    $newMsg->message = "اثر شما برای فستیوال ".$festival->name." به دلیل: ".$failedReason." رد شد.";
                    $newMsg->date = verta()->format('Y-m-d');
                    $newMsg->time = verta()->format('H:i');
                    $newMsg->save();
                }
                else if($request->confirm == 1){
                    $reviewId = Activity::where('name', 'نظر')->first();
                    $review = LogModel::where('activityId', $reviewId->id)
                        ->where('kindPlaceId', $kindPlace->id)
                        ->where('placeId', $place->id)
                        ->where('visitorId', $content->userId)
                        ->where('subject', 'festival_'.$festival->id)
                        ->first();

                    if ($review == null) {
                        $review = new LogModel();
                        $review->placeId = $place->id;
                        $review->kindPlaceId = $kindPlace->id;
                        $review->activityId = $reviewId->id;
                        $review->visitorId = $content->userId;
                        $review->text = 'شرکت در فستیوال آشپزی';
                        $review->subject = 'festival_'.$festival->id;
                        $review->relatedTo = 0;
                        $review->confirm = 1;
                    }
                    $review->date = Carbon::now()->format('Y-m-d');
                    $review->time = getToday()['time'];
                    $review->save();

                    $source = __DIR__ . "/../../../../assets/_images/festival/$festival->folderName";
                    $location = __DIR__ . "/../../../../assets/userPhoto/$kindPlace->fileName";
                    if (!file_exists($location))
                        mkdir($location);

                    $location .= '/' . $place->file;
                    if (!file_exists($location))
                        mkdir($location);

                    if(is_file($source.'/'.$content->file))
                        copy($source.'/'.$content->file, $location.'/'.$content->file);
                    if($content->thumbnail != null && is_file($source.'/'.$content->thumbnail))
                        copy($source.'/'.$content->thumbnail, $location.'/'.$content->thumbnail);

                    $newPic = new ReviewPic();
                    $newPic->logId = $review->id;
                    $newPic->code = $content->userId.'_'.rand(10, 100);
                    $newPic->is360 = 0;
                    $newPic->isVideo = $content->type == 'image' ? 0 : 1;
                    $newPic->pic = $content->file;
                    $newPic->thumbnail = $content->thumbnail;
                    $newPic->save();
                }

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
