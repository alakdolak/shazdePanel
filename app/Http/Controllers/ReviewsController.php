<?php

namespace App\Http\Controllers;

use App\Events\ActivityLogEvent;
use App\models\Activity;
use App\models\Alert;
use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\LogModel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\QuestionUserAns;
use App\models\Restaurant;
use App\models\ReviewPic;
use App\models\ReviewUserAssigned;
use App\models\SogatSanaie;
use App\models\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewsController extends Controller
{
    public function index()
    {
        $acitvity = Activity::where('name', 'نظر')->first();
        $newReviews = LogModel::where('activityId', $acitvity->id)->where('confirm', 0)->get();

        foreach ($newReviews as $item){

            $item->assigned = ReviewUserAssigned::where('logId', $item->id)->get();

            if($item->kindPlaceId != 0 && $item->placeId != 0) {
                $kindPlace = Place::find($item->kindPlaceId);
                $item->file = $kindPlace->fileName;
                $item->place = \DB::table($kindPlace->tableName)->find($item->placeId);
                $item->kindPlace = $kindPlace->name;

                $item->placeName = $item->place->name;

                $item->fileName = "{$kindPlace->fileName}/{$item->place->file}";
            }
            else{
                $item->placeName = 'آزاد';
                $item->fileName = 'nonePlaces';
            }

            $item->pics = ReviewPic::where('logId', $item->id)->get();
            $item->countPic = 0;
            $item->countVideo = 0;
            $item->count360 = 0;

            foreach ($item->pics as $item2){
                if($item2->is360 == 1) $item->count360++;
                else if($item2->isVideo == 1) $item->countVideo++;
                else $item->countPic++;

                $item2->url = \URL::asset("userPhoto/{$item->fileName}/{$item2->pic}");
            }


            $item->user = User::find($item->visitorId);
            $item->username = $item->user->username;

            $item->dateTime = gregorianToJalali($item->date)[0] .'/'. gregorianToJalali($item->date)[1] . '/' . gregorianToJalali($item->date)[2] . '  ' . substr($item->time, 0, 2) . ':' . substr($item->time, 2, 2);
        }

        return view('userContent.review.index', compact(['newReviews']));
    }

    public function deleteReviewPic(Request $request)
    {
        if(isset($request->id)){
            $pic = ReviewPic::find($request->id);
            if($pic !=  null){
                $log = LogModel::find($pic->logId);
                if($log != null){
                    $location = __DIR__ . '/../../../../assets/userPhoto/';

                    $kindPlace = Place::find($log->kindPlaceId);
                    $location .= $kindPlace->fileName . '/';
                    $place = \DB::table($kindPlace->tableName)->find($log->placeId);
                    $location .= $place->file .'/'. $pic->pic;

                    if(file_exists($location))
                        unlink($location);

                    $newAlert = new Alert();
                    if($pic->isVideo == 1)
                        $newAlert->subject = 'deleteReviewVideo';
                    else
                        $newAlert->subject = 'deleteReviewPic';
                    $newAlert->referenceTable = 'log';
                    $newAlert->referenceId = $log->id;
                    $newAlert->userId = $log->visitorId;
                    $newAlert->seen = 0;
                    $newAlert->click = 0;
                    $newAlert->save();

                    $pic->delete();

                    echo 'ok';
                }
            }
        }
        return;
    }

    public function confirmReview(Request $request)
    {
        if(isset($request->id)){
            $review = LogModel::find($request->id);
            if($review != null){
                $review->confirm = 1;
                $review->seen = 0;
                $review->save();

                event(new ActivityLogEvent($review->visitorId, $review->id,'نقد', $review->kindPlaceId));

                if(QuestionUserAns::where('logId', $review->id)->first() != null)
                    event(new ActivityLogEvent($review->visitorId, $review->id,'placeQA', $review->kindPlaceId));

                $reviewPics = ReviewPic::where('logId', $review->id)->get();
                foreach ($reviewPics as $pic) {
                    if($pic->is360 == 1)
                        event(new ActivityLogEvent($review->visitorId, $pic->id, 'reviewVideo360', $review->kindPlaceId));
                    elseif($pic->isVideo == 1)
                        event(new ActivityLogEvent($review->visitorId, $pic->id, 'reviewVideo', $review->kindPlaceId));
                    else
                        event(new ActivityLogEvent($review->visitorId, $pic->id, 'reviewPic', $review->kindPlaceId));
                }

                $newAlert = new Alert();
                $newAlert->subject = 'confirmReview';
                $newAlert->referenceTable = 'log';
                $newAlert->referenceId = $review->id;
                $newAlert->userId = $review->visitorId;
                $newAlert->seen = 0;
                $newAlert->click = 0;
                $newAlert->save();

                $kindPlace = Place::find($review->kindPlaceId);
                if($kindPlace != null && $kindPlace->tableName != null && $kindPlace->tableName != ''){
                    $place = \DB::table($kindPlace->tableName)->find($review->placeId);
                    \DB::table($kindPlace->tableName)->where('id', $review->placeId)->update(['reviewCount' => $place->reviewCount+1]);
                }

                echo 'ok';
            }
        }

        return;
    }

    public function deleteReview(Request $request)
    {
        if(isset($request->id)){
            $id = $request->id;
            $review = LogModel::find($request->id);
            if($review != null){
                $reviewPics = ReviewPic::where('logId', $id)->get();
                $kindPlaceId = $review->kindPlaceId;
                $placeId = $review->placeId;

                $location = __DIR__ . '/../../../../assets/userPhoto/';
                if($kindPlaceId != 0 && $placeId != 0) {
                    $kindPlace = Place::find($kindPlaceId);
                    $place = \DB::table($kindPlace->tableName)->find($placeId);
                    $location .= "{$kindPlace->fileName}/{$place->file}/";
                }
                else
                    $location .= "nonePlaces/";

                foreach ($reviewPics as $item){
                    if(file_exists($location.$item->pic))
                        unlink($location.$item->pic);
                    $item->delete();
                }

                ReviewUserAssigned::where('logId', $id)->delete();

                if($review->confirm == 1 && $kindPlaceId != 0 && $placeId != 0){
                    $kindPlace = Place::find($review->kindPlaceId);
                    if($kindPlace != null && $kindPlace->tableName != null && $kindPlace->tableName != '') {
                        \DB::select('UPDATE `' . $kindPlace->tableName . '` SET `reviewCount`= `reviewCount`-1  WHERE `id` = ' . $review->placeId);

                        $avgRate = getRate($kindPlace->id, $review->placeId);
                        \DB::select('UPDATE `' . $kindPlace->tableName . '` SET `fullRate`= ' . $avgRate . '  WHERE `id` = ' . $review->placeId);
                    }
                }

                $newAlert = new Alert();
                $newAlert->subject = 'deleteReview';
                $newAlert->referenceTable = 'free';
                $newAlert->referenceId = 0;
                $newAlert->userId = $review->visitorId;
                $newAlert->seen = 0;
                $newAlert->click = 0;
                $newAlert->save();

                $review->delete();
                echo 'ok';
            }
        }
        return;
    }
}
