<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Amaken;
use App\models\Hotel;
use App\models\LogModel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Restaurant;
use App\models\ReviewPic;
use App\models\ReviewUserAssigned;
use App\models\SogatSanaie;
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

            $item->pics = ReviewPic::where('logId', $item->id)->get();
            $item->countPic = 0;
            $item->countVideo = 0;
            $item->count360 = 0;

            foreach ($item->pics as $item2){
                if($item2->is360 == 1)
                    $item->count360++;
                else if($item2->isVideo == 1)
                    $item->countVideo++;
                else
                    $item->countPic++;
            }

            switch ($item->kindPlaceId){
                case 1:
                    $item->file = 'amaken';
                    $item->place = Amaken::find($item->placeId);
                    $item->kindPlace = 'اماکن';
                    break;
                case 3:
                    $item->file = 'restaurant';
                    $item->place  = Restaurant::find($item->placeId);
                    $item->kindPlace = 'رستوران';
                    break;
                case 4:
                    $item->file = 'hotels';
                    $item->place  = Hotel::find($item->placeId);
                    $item->kindPlace = 'هتل';
                    break;
                case 6:
                    $item->file = 'majara';
                    $item->place  = Majara::find($item->placeId);
                    $item->kindPlace = 'ماجرا';
                    break;
                case 10:
                    $item->file = 'sogatsanaie';
                    $item->place  = SogatSanaie::find($item->placeId);
                    $item->kindPlace = 'سوغات/صنایع';
                    break;
                case 11:
                    $item->file = 'mahalifood';
                    $item->place  = MahaliFood::find($item->placeId);
                    $item->kindPlace = 'غذای محلی';
                    break;
            }

            $item->user = User::find($item->visitorId);

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

                    switch ($log->kindPlaceId){
                        case 1:
                            $location .= 'amaken/';
                            $place = Amaken::find($log->placeId);
                            break;
                        case 3:
                            $location .= 'restaurant/';
                            $place  = Restaurant::find($log->placeId);
                            break;
                        case 4:
                            $location .= 'hotels/';
                            $place  = Hotel::find($log->placeId);
                            break;
                        case 6:
                            $location .= 'majara/';
                            $place  = Majara::find($log->placeId);
                            break;
                        case 10:
                            $location .= 'sogatsanaie/';
                            $place  = SogatSanaie::find($log->placeId);
                            break;
                        case 11:
                            $location .= 'mahalifood/';
                            $place  = MahaliFood::find($log->placeId);
                            break;
                    }

                    $location .= $place->file;
                    $location .= '/' . $pic->pic;

                    if(file_exists($location))
                        unlink($location);

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
                $review->save();

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

                switch ($kindPlaceId){
                    case 1:
                        $location .= 'amaken/';
                        $place = Amaken::find($placeId);
                        break;
                    case 3:
                        $location .= 'restaurant/';
                        $place  = Restaurant::find($placeId);
                        break;
                    case 4:
                        $location .= 'hotels/';
                        $place  = Hotel::find($placeId);
                        break;
                    case 6:
                        $location .= 'majara/';
                        $place  = Majara::find($placeId);
                        break;
                    case 10:
                        $location .= 'sogatsanaie/';
                        $place  = SogatSanaie::find($placeId);
                        break;
                    case 11:
                        $location .= 'mahalifood/';
                        $place  = MahaliFood::find($placeId);
                        break;
                }

                $location .= $place->file .'/';

                foreach ($reviewPics as $item){
                    $file = $location;
                    $file .= $item->pic;
                    if(file_exists($file))
                        unlink($file);
                    $item->delete();
                }

                ReviewUserAssigned::where('logId', $id)->delete();

                $review->delete();
                echo 'ok';
            }
        }
        return;
    }
}
