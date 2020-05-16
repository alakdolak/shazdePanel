<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\PhotographersLog;
use App\models\PhotographersPic;
use App\models\Place;
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
            $kindPlace = Place::find($item->kindPlaceId);
            $place = \DB::table($kindPlace->tableName)->find($item->placeId);
            $file = $kindPlace->fileName;
            $item->kindPlace = $kindPlace->name;

            $item->placeName = $place->name;
            $item->placeId = $place->id;
            $item->url = 'https://koochita.com/place-details/' . $kindPlace->id. '/' . $place->id;

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

            $kindPlace = Place::find($item->kindPlaceId);
            $place = \DB::table($kindPlace->tableName)->find($item->placeId);
            $file = $kindPlace->fileName;
            $item->kindPlace = $kindPlace->name;

            $item->placeName = $place->name;
            $item->placeId = $place->id;
            $item->url = 'https://koochita.com/place-details/' . $kindPlace->id. '/' . $place->id;

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
            PhotographersPic::deletePhotographer($photo->id);
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
