<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\MainSuggestion;
use App\models\Majara;
use App\models\Place;
use App\models\Restaurant;
use App\models\SogatSanaie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainSuggestionController extends Controller
{
    public function index()
    {
        $suggest = MainSuggestion::all();

        foreach ($suggest as $item){
            switch ($item->kindPlaceId){
                case 1:
                    $item->place = Amaken::select(['id', 'name'])->find($item->placeId);
                    break;
                case 3:
                    $item->place = Restaurant::select(['id', 'name'])->find($item->placeId);
                    break;
                case 4:
                    $item->place = Hotel::select(['id', 'name'])->find($item->placeId);
                    break;
                case 6:
                    $item->place = Majara::select(['id', 'name'])->find($item->placeId);
                    break;
                case 10:
                    $item->place = SogatSanaie::select(['id', 'name'])->find($item->placeId);
                    break;
                case 11:
                    $item->place = MahaliFood::select(['id', 'name'])->find($item->placeId);
                    break;
            }
        }

        return view('content.MainSuggestion.index', compact(['suggest']));
    }

    public function search(Request $request)
    {
        if(isset($request->value) && isset($request->section)){
            switch ($request->section){
                case 'محبوب‌ترین غذا‌ها':
                    $place = DB::select('SELECT * FROM mahaliFood WHERE `name` LIKE "%' . $request->value . '%"');
                    foreach ($place as $item)
                        $item->kindPlaceId = Place::where('name', 'غذای محلی')->first()->id;
                    break;
                case 'سفر طبیعت‌گردی':
                    $place = DB::select('SELECT * FROM majara WHERE `name` LIKE "%' . $request->value . '%"');
                    foreach ($place as $item)
                        $item->kindPlaceId = Place::where('name', 'ماجرا')->first()->id;
                    break;
                case 'محبوب‌ترین رستوران‌ها':
                    $place = DB::select('SELECT * FROM restaurant WHERE `name` LIKE "%' . $request->value . '%"');
                    foreach ($place as $item)
                        $item->kindPlaceId = Place::where('name', 'رستوران')->first()->id;
                    break;
                case 'سفر تاریخی-فرهنگی':
                    $place = DB::select('SELECT * FROM amaken WHERE tarikhi = 1 AND `name` LIKE "%' . $request->value . '%"');
                    foreach ($place as $item)
                        $item->kindPlaceId = Place::where('name', 'اماکن')->first()->id;
                    break;
                case 'مراکز خرید':
                    $place = DB::select('SELECT * FROM amaken WHERE markazkharid = 1 AND `name` LIKE "%' . $request->value . '%"');
                    foreach ($place as $item)
                        $item->kindPlaceId = Place::where('name', 'اماکن')->first()->id;
                    break;
            }

            echo json_encode($place);
        }
        else
            echo 'nok1';

        return;
    }

    public function chooseId(Request $request)
    {
        if(isset($request->section) && isset($request->id) && isset($request->kindPlaceId)){
            $condition = ['kindPlaceId' => $request->kindPlaceId, 'placeId' => $request->id, 'section' => $request->section];
            $check = MainSuggestion::where($condition)->first();

            if($check == null){
                $new = new MainSuggestion();
                $new->kindPlaceId = $request->kindPlaceId;
                $new->placeId = $request->id;
                $new->section = $request->section;
                $new->save();

                echo json_encode(['ok', $new->id]);
            }
            else
                echo json_encode(['nok2', 0]);
        }
        else
            echo json_encode(['nok1', 0]);

        return;
    }

    public function deleteRecord(Request $request)
    {
        if(isset($request->id)){
            MainSuggestion::find($request->id)->delete();
            echo 'ok';
        }
        else
            echo 'nok1';

        return;
    }
}
