<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\PhotographersLog;
use App\models\PhotographersPic;
use App\models\Restaurant;
use App\models\SogatSanaie;
use App\models\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UserContentController extends Controller
{
    public function photographerIndex()
    {
        $photo = PhotographersPic::where('status', 0)->get();
        foreach ($photo as $item){

            switch ($item->kindPlaceId){
                case 1:
                    $file = 'amaken';
                    $place = Amaken::find($item->placeId);
                    $item->kindPlace = 'اماکن';
                    $url ='https://koochita.com/amaken-details/' ;
                    break;
                case 3:
                    $file = 'restaurant';
                    $place = Restaurant::find($item->placeId);
                    $item->kindPlace = 'رستوران';
                    $url ='https://koochita.com/restaurant-details/' ;
                    break;
                case 4:
                    $file = 'hotels';
                    $place = Hotel::find($item->placeId);
                    $item->kindPlace = 'هتل';
                    $url ='https://koochita.com/hotel-details/' ;
                    break;
                case 6:
                    $file = 'majara';
                    $place = Majara::find($item->placeId);
                    $item->kindPlace = 'ماجرا';
                    $url ='https://koochita.com/majara-details/' ;
                    break;
                case 10:
                    $file = 'sogatsanaie';
                    $place = SogatSanaie::find($item->placeId);
                    $item->kindPlace = 'سوغات/صنایع';
                    $url ='https://koochita.com/hotel-details/' ;
                    break;
                case 11:
                    $file = 'mahalifood';
                    $place = MahaliFood::find($item->placeId);
                    $item->kindPlace = 'غذای محلی';
                    $url ='https://koochita.com/hotel-details/' ;
                    break;
            }

            $item->placeName = $place->name;
            $item->placeId = $place->id;
            $item->url = $url . $item->placeId . '/' . $item->placeName;

            $item->city = Cities::find($place->cityId);
            $item->state = State::find($item->city->stateId);

            $item->pics = [
                's' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/s-' . $item->pic),
                'f' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/f-' . $item->pic),
                'l' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/l-' . $item->pic),
                't' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/t-' . $item->pic),
                'mainPic' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/' . $item->pic),
            ];

            $item->uploadDate = convertDate($item->created_at);


            $user = User::find($item->userId);
            $item->userName = '';
            $item->userName = $user->first_name . ' ' . $user->last_name;
            if($item->userName == ' ')
                $item->userName = $user->username;
        }

        $oldPhoto = PhotographersPic::where('status', 1)->orderBy('created_at', 'DESC')->get();
        foreach ($oldPhoto as $item){

            switch ($item->kindPlaceId){
                case 1:
                    $file = 'amaken';
                    $place = Amaken::find($item->placeId);
                    $item->kindPlace = 'اماکن';
                    $url ='https://koochita.com/amaken-details/' ;
                    break;
                case 3:
                    $file = 'restaurant';
                    $place = Restaurant::find($item->placeId);
                    $item->kindPlace = 'رستوران';
                    $url ='https://koochita.com/restaurant-details/' ;
                    break;
                case 4:
                    $file = 'hotels';
                    $place = Hotel::find($item->placeId);
                    $item->kindPlace = 'هتل';
                    $url ='https://koochita.com/hotel-details/' ;
                    break;
                case 6:
                    $file = 'majara';
                    $place = Majara::find($item->placeId);
                    $item->kindPlace = 'ماجرا';
                    $url ='https://koochita.com/majara-details/' ;
                    break;
                case 10:
                    $file = 'sogatsanaie';
                    $place = SogatSanaie::find($item->placeId);
                    $item->kindPlace = 'سوغات/صنایع';
                    $url ='https://koochita.com/hotel-details/' ;
                    break;
                case 11:
                    $file = 'mahalifood';
                    $place = MahaliFood::find($item->placeId);
                    $item->kindPlace = 'غذای محلی';
                    $url ='https://koochita.com/hotel-details/' ;
                    break;
            }

            $item->placeName = $place->name;
            $item->placeId = $place->id;
            $item->url = $url . $item->placeId . '/' . $item->placeName;

            $item->city = Cities::find($place->cityId);
            $item->state = State::find($item->city->stateId);

            $item->pics = [
                's' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/s-' . $item->pic),
                'f' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/f-' . $item->pic),
                'l' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/l-' . $item->pic),
                't' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/t-' . $item->pic),
                'mainPic' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/' . $item->pic),
            ];

            $item->uploadDate = convertDate($item->created_at);


            $user = User::find($item->userId);
            $item->userName = '';
            $item->userName = $user->first_name . ' ' . $user->last_name;
            if($item->userName == ' ')
                $item->userName = $user->username;
        }

        return view('userContent.photographer.photographer', compact(['photo', 'oldPhoto']));
    }

    public function photographerDelete(Request $request)
    {
        if(isset($request->id)){
            $photo = PhotographersPic::find($request->id);

            switch ($photo->kindPlaceId){
                case 1:
                    $file = 'amaken';
                    $place = Amaken::find($photo->placeId);
                    break;
                case 3:
                    $file = 'restaurant';
                    $place = Restaurant::find($photo->placeId);
                    break;
                case 4:
                    $file = 'hotels';
                    $place = Hotel::find($photo->placeId);
                    break;
                case 6:
                    $file = 'majara';
                    $place = Majara::find($photo->placeId);
                    break;
                case 10:
                    $file = 'sogatsanaie';
                    $place = SogatSanaie::find($photo->placeId);
                    break;
                case 11:
                    $file = 'mahalifood';
                    $place = MahaliFood::find($photo->placeId);
                    break;
            }

            $logs = PhotographersLog::where('picId', $request->id)->get();
            foreach ($logs as $item)
                $item->delete();

            $location = __DIR__ . '/../../../../assets/userPhoto/' . $file . '/' . $place->file;

            $check = deletePlacePicFiles($location, $photo->pic);
            if($check)
                $photo->delete();
        }

        return redirect()->back();
    }

    public function photographerSubmit(Request $request)
    {
        if(isset($request->id)) {
            $photo = PhotographersPic::find($request->id);
            $photo->status = 1;
            $photo->save();
        }

        return redirect()->back();
    }

}
