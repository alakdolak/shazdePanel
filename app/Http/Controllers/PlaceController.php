<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\Restaurant;
use App\models\SogatSanaie;
use App\models\SpecialAdvice;
use App\models\State;
use App\models\TopInCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use PHPExcel_IOFactory;
use ZipArchive;
use Illuminate\Support\Facades\Session;


class PlaceController extends Controller {

    public function newChangeContent($cityId, $mode, $cityMode){

        $state = State::all();

        switch ($mode) {
            case getValueInfo("hotel"):
            default:
                $kind = 'hotels';
                break;
            case getValueInfo('amaken'):
                $kind = 'amaken';
                break;
            case getValueInfo('restaurant'):
                $kind = 'restaurant';
                break;
            case getValueInfo('majara'):
                $kind = 'majara';
                break;
            case 10:
                $kind = 'sogatsanaies';
                break;
            case 11:
                $kind = 'mahalifood';
                break;
        }
        if($kind == 'mahalifood' || $kind == 'sogatsanaies'){
            if($cityMode == 0){
                $state2 = State::find($cityId);
                $name = $state2->name;
                $id = $state2->id;
                $stateId = $state2->id;

                $places = DB::select('select h.name, h.cityId, h.id, h.mainPic from ' . $kind . ' h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
            }
            else{
                $city = Cities::find($cityId);
                $name = $city->name;
                $id = $cityId;
                $stateId = $city->stateId;

                $places = DB::select('select name, cityId, id, mainPic FROM ' . $kind . ' WHERE cityId = ' . $id);
            }
            foreach ($places as $item){
                $item->pic = URL::asset('_images/nopic/blank.jpg');
            }
        }
        else {
            if ($cityMode == 0) {
                $state2 = State::find($cityId);
                $name = $state2->name;
                $id = $state2->id;
                $stateId = $state2->id;

                $places = DB::select('select h.name, h.cityId, h.pic_1, h.file, h.id from ' . $kind . ' h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
            } else {
                $city = Cities::find($cityId);
                $name = $city->name;
                $id = $cityId;
                $stateId = $city->stateId;

                $places = DB::select('SELECT name, cityId, pic_1, file, id FROM ' . $kind . ' WHERE cityId = ' . $cityId);
            }

            foreach ($places as $item){
                if($item->pic_1 != 0 || ($item->file != '' && $item->file != null))
                    $item->pic = URL::asset('_images/' . $kind . '/' . $item->file . '/f-1.jpg');
                else
                    $item->pic = URL::asset('_images/nopic/blank.jpg');
            }
        }

        $city = Cities::where('stateId',$stateId)->get();

        $jsonPlace = json_encode($places);

        return view('content.newChangeContent', compact(['name', 'cityMode', 'places', 'mode', 'id', 'jsonPlace', 'kind', 'state', 'stateId', 'city']));
    }

    public function editContent($mode, $id)
    {
        $kind = 'edit';
        $allState = State::all();

        switch ($mode){
            case 1:
                $place = Amaken::find($id);
                $city = Cities::find($place->cityId);
                $cities = Cities::where('stateId', $city->stateId)->get();
                $state = State::find($city->stateId);
                $place->city = $city->name;
                $place->stateId = $city->stateId;
                $place->tags = [
                    $place->tag1,
                    $place->tag2,
                    $place->tag3,
                    $place->tag4,
                    $place->tag5,
                    $place->tag6,
                    $place->tag7,
                    $place->tag8,
                    $place->tag9,
                    $place->tag10,
                    $place->tag11,
                    $place->tag12,
                    $place->tag13,
                    $place->tag14,
                    $place->tag15,
                ];

                return view('content.editContent.editAmaken', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState']));
            case 4:
                $place = Hotel::find($id);
                $city = Cities::find($place->cityId);
                $state = State::find($city->stateId);
                $cities = Cities::where('stateId', $city->stateId)->get();

                $place->city = $city->name;
                $place->stateId = $city->stateId;
                $place->tags = [
                    $place->tag1,
                    $place->tag2,
                    $place->tag3,
                    $place->tag4,
                    $place->tag5,
                    $place->tag6,
                    $place->tag7,
                    $place->tag8,
                    $place->tag9,
                    $place->tag10,
                    $place->tag11,
                    $place->tag12,
                    $place->tag13,
                    $place->tag14,
                    $place->tag15,
                ];

                return view('content.editContent.editHotels', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState']));
                break;
            case 3:
                $place = Restaurant::find($id);
                $city = Cities::find($place->cityId);

                $cities = Cities::where('stateId', $city->stateId)->get();

                $place->city = $city->name;
                $place->stateId = $city->stateId;
                $place->tags = [
                    $place->tag1,
                    $place->tag2,
                    $place->tag3,
                    $place->tag4,
                    $place->tag5,
                    $place->tag6,
                    $place->tag7,
                    $place->tag8,
                    $place->tag9,
                    $place->tag10,
                    $place->tag11,
                    $place->tag12,
                    $place->tag13,
                    $place->tag14,
                    $place->tag15,
                ];

                return view('content.editContent.editRestaurant', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState']));
                break;
            case 6:
                $place = Majara::find($id);
                $city = Cities::find($place->cityId);
                $place->city = $city->name;
                $place->stateId = $city->stateId;
                $place->tags = [
                    $place->tag1,
                    $place->tag2,
                    $place->tag3,
                    $place->tag4,
                    $place->tag5,
                    $place->tag6,
                    $place->tag7,
                    $place->tag8,
                    $place->tag9,
                    $place->tag10,
                    $place->tag11,
                    $place->tag12,
                    $place->tag13,
                    $place->tag14,
                    $place->tag15,
                ];

                $cities = Cities::where('stateId', $city->stateId)->get();

                return view('content.editContent.editMajara', compact(['place', 'kind', 'state', 'mode', 'cities', 'allState']));
                break;
            case 10:
                $sogat = SogatSanaie::find($id);
                $city = Cities::find($sogat->cityId);
                $sogat->city = $city->name;
                $sogat->stateId = $city->stateId;
                $state = State::find($city->stateId);
                $sogat->tags = [
                    $sogat->tag1,
                    $sogat->tag2,
                    $sogat->tag3,
                    $sogat->tag4,
                    $sogat->tag5,
                    $sogat->tag6,
                    $sogat->tag7,
                    $sogat->tag8,
                    $sogat->tag9,
                    $sogat->tag10,
                    $sogat->tag11,
                    $sogat->tag12,
                    $sogat->tag13,
                    $sogat->tag14,
                    $sogat->tag15,
                ];

                $cities = Cities::where('stateId', $city->stateId)->get();

                return view('content.editContent.editSogatSanaie', compact(['sogat', 'kind', 'state', 'allState', 'mode', 'cities', 'city']));
                break;
            case 11:
                $food = MahaliFood::find($id);
                $city = Cities::find($food->cityId);
                $food->city = $city->name;
                $food->stateId = $city->stateId;
                $state = State::find($city->stateId);
                $food->material = json_decode($food->material);
                $food->tags = [
                    $food->tag1,
                    $food->tag2,
                    $food->tag3,
                    $food->tag4,
                    $food->tag5,
                    $food->tag6,
                    $food->tag7,
                    $food->tag8,
                    $food->tag9,
                    $food->tag10,
                    $food->tag11,
                    $food->tag12,
                    $food->tag13,
                    $food->tag14,
                    $food->tag15,
                ];

                $cities = Cities::where('stateId', $city->stateId)->get();

                return view('content.editContent.editMahaliFood', compact(['food', 'kind', 'state', 'allState', 'mode', 'cities', 'city']));
                break;
            default:
                return \redirect()->back();
        }
    }

    public function newContent($cityMode, $cityId, $mode)
    {
        if($cityMode == 1){
            $city = Cities::find($cityId);
            $state = State::find($city->stateId);
            $allState = State::all();
            $cities = Cities::where('stateId', $state->id)->get();
        }
        else{
            $city = null;
            $state = State::find($cityId);
            $allState = State::all();
            $cities = Cities::where('stateId', $state->id)->get();
        }

        switch ($mode){
            case 1:
                $titleName = 'مکان دیدنی جدید';
                $url = route('storeAmaken');
                $kind = 'amaken';
                break;
            case 3:
                $titleName = 'رستوران جدید';
                $url = route('storeRestaurant');
                $kind = 'restaurant';
            case 4:
                $titleName = 'هتل جدید';
                $url = route('storeHotel');
                $kind = 'hotels';
                break;
            case 6:
                $titleName = 'ماجرای جدید';
                $url = route('storeMajara');
                $kind = 'majara';
            case 10:
                $titleName = 'سوغات/صنایع جدید';
                $url = route('storeSogatSanaie');
                $kind = 'sogatsanaie';
                break;
            case 11:
                $titleName = 'غذای محلی جدید';
                $url = route('storeMahaliFood');
                $kind = 'mahalifood';
                break;
        }

        return view('content.newContent.newContentCore', compact(['city', 'state', 'allState', 'allCity', 'mode', 'cities', 'kind', 'url', 'titleName']));

    }

    public function storeAmaken(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'keyword' => 'required',
            'address' => 'required',
            'h1' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $amaken = new Amaken();

            $amaken->file = 'none';
            $amaken->farhangi = 0;
            $amaken->ghadimi = 0;
            $amaken->emkanat = 0;
            $amaken->markaz = 0;
            $amaken->hoome = 0;
            $amaken->class = 0;
            $amaken->author = \auth()->user()->id;

        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $amaken = Amaken::find($request->id);

            if($amaken == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $amaken->name = $request->name;
        $amaken->cityId = $request->cityId;
        $amaken->C = $request->C;
        $amaken->D = $request->D;
        $amaken->meta = $request->meta;
        $amaken->keyword = $request->keyword;
        $amaken->h1 = $request->h1;
        $amaken->description = $request->description;

        if($request->address != null)
            $amaken->address = $request->address;
        else
            $amaken->address = '';

        if($request->phone != null)
            $amaken->phone = $request->phone;
        else
            $amaken->phone = '';

        if($request->site != null)
            $amaken->site = $request->site;
        else
            $amaken->site = '';

        if(isset($request->tarikhi) && $request->tarikhi == 'on')
            $amaken->tarikhi = 1;
        else
            $amaken->tarikhi = 0;

        if(isset($request->mooze) && $request->mooze == 'on')
            $amaken->mooze = 1;
        else
            $amaken->mooze = 0;

        if(isset($request->tafrihi) && $request->tafrihi == 'on')
            $amaken->tafrihi = 1;
        else
            $amaken->tafrihi = 0;

        if(isset($request->tabiatgardi) && $request->tabiatgardi == 'on')
            $amaken->tabiatgardi = 1;
        else
            $amaken->tabiatgardi = 0;

        if(isset($request->markazkharid) && $request->markazkharid == 'on')
            $amaken->markazkharid = 1;
        else
            $amaken->markazkharid = 0;

        if(isset($request->baftetarikhi) && $request->baftetarikhi == 'on')
            $amaken->baftetarikhi = 1;
        else
            $amaken->baftetarikhi = 0;

        $amaken->boundArea = $request->boundArea;

        if($request->population == 1){
            $amaken->shologh = 1;
            $amaken->khalvat = 0;
        }
        else{
            $amaken->shologh = 0;
            $amaken->khalvat = 1;
        }

        if(isset($request->tabiat) && $request->tabiat == 'on')
            $amaken->tabiat = 1;
        else
            $amaken->tabiat = 0;

        if(isset($request->kooh) && $request->kooh == 'on')
            $amaken->kooh = 1;
        else
            $amaken->kooh = 0;

        if(isset($request->darya) && $request->darya == 'on')
            $amaken->darya = 1;
        else
            $amaken->darya = 0;

        if(isset($request->kavir) && $request->kavir == 'on')
            $amaken->kavir = 1;
        else
            $amaken->kavir = 0;

        switch ($request->archi){
            case 1:
                $amaken->modern = 1;
                $amaken->tarikhibana = 0;
                $amaken->mamooli = 0;
                break;
            case 2:
                $amaken->modern = 0;
                $amaken->tarikhibana = 1;
                $amaken->mamooli = 0;
                break;
            case 3:
                $amaken->modern = 0;
                $amaken->tarikhibana = 0;
                $amaken->mamooli = 1;
                break;
        }

        $amaken->tag1 = $request->tag[0];
        $amaken->tag2 = $request->tag[1];
        $amaken->tag3 = $request->tag[2];
        $amaken->tag4 = $request->tag[3];
        $amaken->tag5 = $request->tag[4];
        $amaken->tag6 = $request->tag[5];
        $amaken->tag7 = $request->tag[6];
        $amaken->tag8 = $request->tag[7];
        $amaken->tag9 = $request->tag[8];
        $amaken->tag10 = $request->tag[9];
        $amaken->tag11 = $request->tag[10];
        $amaken->tag12 = $request->tag[11];
        $amaken->tag13 = $request->tag[12];
        $amaken->tag14 = $request->tag[13];
        $amaken->tag15 = $request->tag[14];

        $amaken->save();

        return \redirect(\url('newChangeContent/' . $amaken->cityId . '/1/1'));
    }

    public function storeHotel(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'address' => 'required',
            'keyword' => 'required',
            'h1' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $hotel = new Hotel();

            $hotel->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $hotel = Hotel::find($request->id);

            if($hotel == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $hotel->name = $request->name;
        $hotel->cityId = $request->cityId;
        $hotel->C = $request->C;
        $hotel->D = $request->D;
        $hotel->meta = $request->meta;
        $hotel->h1 = $request->h1;
        $hotel->description = $request->description;
        $hotel->keyword = $request->keyword;
        $hotel->kind_id = $request->kind;
        $hotel->rate_int = $request->rate_int;
        if($request->rate_int > 0){
            switch ($request->rate_int){
                case 1:
                    $hotel->rate = 'یک ستاره';
                    break;
                case 2:
                    $hotel->rate = 'دو ستاره';
                    break;
                case 3:
                    $hotel->rate = 'سه ستاره';
                    break;
                case 4:
                    $hotel->rate = 'چهار ستاره';
                    break;
                case 5:
                    $hotel->rate = 'پنج ستاره';
                    break;
            }
        }
        $hotel->room_num = $request->room_num;

        if(isset($request->momtaz) && $request->momtaz == 'on')
            $hotel->momtaz = 1;
        else
            $hotel->momtaz = 0;

        if($request->address != null)
            $hotel->address = $request->address;
        else
            $hotel->address = '';

        if($request->phone != null)
            $hotel->phone = $request->phone;
        else
            $hotel->phone = '';

        if($request->site != null)
            $hotel->site = $request->site;
        else
            $hotel->site = '';

        if(isset($request->isVabastegi) && $request->isVabastegi == 'on')
            $hotel->vabastegi = $request->vabastegi;
        else
            $hotel->vabastegi = '';

        if(isset($request->food_irani) && $request->food_irani == 'on')
            $hotel->food_irani = 1;
        else
            $hotel->food_irani = 0;

        if(isset($request->food_mahali) && $request->food_mahali == 'on')
            $hotel->food_mahali = 1;
        else
            $hotel->food_mahali = 0;

        if(isset($request->food_farangi) && $request->food_farangi == 'on')
            $hotel->food_farangi = 1;
        else
            $hotel->food_farangi = 0;

        if(isset($request->coffeeshop) && $request->coffeeshop == 'on')
            $hotel->coffeeshop = 1;
        else
            $hotel->coffeeshop = 0;

        if(isset($request->tarikhi) && $request->tarikhi == 'on')
            $hotel->tarikhi = 1;
        else
            $hotel->tarikhi = 0;

        $hotel->boundArea = $request->boundArea;

        if($request->population == 1){
            $hotel->shologh = 1;
            $hotel->khalvat = 0;
        }
        else{
            $hotel->shologh = 0;
            $hotel->khalvat = 1;
        }

        if(isset($request->tabiat) && $request->tabiat == 'on')
            $hotel->tabiat = 1;
        else
            $hotel->tabiat = 0;

        if(isset($request->kooh) && $request->kooh == 'on')
            $hotel->kooh = 1;
        else
            $hotel->kooh = 0;

        if(isset($request->darya) && $request->darya == 'on')
            $hotel->darya = 1;
        else
            $hotel->darya = 0;

        if(isset($request->kavir) && $request->kavir == 'on')
            $hotel->kavir = 1;
        else
            $hotel->kavir = 0;

        if(isset($request->modern) && $request->modern == 'on'){
            $hotel->modern = 1;
            $hotel->sonnati = 0;
            $hotel->ghadimi = 0;
            $hotel->mamooli = 0;
        }
        else {
            $hotel->modern = 0;

            if(isset($request->sonnati) && $request->sonnati == 'on')
                $hotel->sonnati = 1;
            else
                $hotel->sonnati = 0;

            if(isset($request->ghadimi) && $request->ghadimi == 'on')
                $hotel->ghadimi = 1;
            else
                $hotel->ghadimi = 0;

            if(isset($request->mamooli) && $request->mamooli == 'on')
                $hotel->mamooli = 1;
            else
                $hotel->mamooli = 0;
        }

        if(isset($request->breakfast) && $request->breakfast == 'on')
            $hotel->breakfast = 1;
        else
            $hotel->breakfast = 0;

        if(isset($request->lunch) && $request->lunch == 'on')
            $hotel->lunch = 1;
        else
            $hotel->lunch = 0;

        if(isset($request->dinner) && $request->dinner == 'on')
            $hotel->dinner = 1;
        else
            $hotel->dinner = 0;

        if(isset($request->parking) && $request->parking == 'on')
            $hotel->parking = 1;
        else
            $hotel->parking = 0;

        if(isset($request->club) && $request->club == 'on')
            $hotel->club = 1;
        else
            $hotel->club = 0;

        if(isset($request->pool) && $request->pool == 'on')
            $hotel->pool = 1;
        else
            $hotel->pool = 0;

        if(isset($request->tahviye) && $request->tahviye == 'on')
            $hotel->tahviye = 1;
        else
            $hotel->tahviye = 0;

        if(isset($request->maalool) && $request->maalool == 'on')
            $hotel->maalool = 1;
        else
            $hotel->maalool = 0;

        if(isset($request->internet) && $request->internet == 'on')
            $hotel->internet = 1;
        else
            $hotel->internet = 0;

        if(isset($request->anten) && $request->anten == 'on')
            $hotel->anten = 1;
        else
            $hotel->anten = 0;

        if(isset($request->restaurant) && $request->restaurant == 'on')
            $hotel->restaurant = 1;
        else
            $hotel->restaurant = 0;

        if(isset($request->swite) && $request->swite == 'on')
            $hotel->swite = 1;
        else
            $hotel->swite = 0;

        if(isset($request->masazh) && $request->masazh == 'on')
            $hotel->masazh = 1;
        else
            $hotel->masazh = 0;

        if(isset($request->laundry) && $request->laundry == 'on')
            $hotel->laundry = 1;
        else
            $hotel->laundry = 0;

        if(isset($request->gasht) && $request->gasht == 'on')
            $hotel->gasht = 1;
        else
            $hotel->gasht = 0;

        if(isset($request->safe_box) && $request->safe_box == 'on')
            $hotel->safe_box = 1;
        else
            $hotel->safe_box = 0;

        if(isset($request->shop) && $request->shop == 'on')
            $hotel->shop = 1;
        else
            $hotel->shop = 0;

        if(isset($request->roof_garden) && $request->roof_garden == 'on')
            $hotel->roof_garden = 1;
        else
            $hotel->roof_garden = 0;

        if(isset($request->game_net) && $request->game_net == 'on')
            $hotel->game_net = 1;
        else
            $hotel->game_net = 0;

        if(isset($request->confrenss_room) && $request->confrenss_room == 'on')
            $hotel->confrenss_room = 1;
        else
            $hotel->confrenss_room = 0;



        $hotel->tag1 = $request->tag[0];
        $hotel->tag2 = $request->tag[1];
        $hotel->tag3 = $request->tag[2];
        $hotel->tag4 = $request->tag[3];
        $hotel->tag5 = $request->tag[4];
        $hotel->tag6 = $request->tag[5];
        $hotel->tag7 = $request->tag[6];
        $hotel->tag8 = $request->tag[7];
        $hotel->tag9 = $request->tag[8];
        $hotel->tag10 = $request->tag[9];
        $hotel->tag11 = $request->tag[10];
        $hotel->tag12 = $request->tag[11];
        $hotel->tag13 = $request->tag[12];
        $hotel->tag14 = $request->tag[13];
        $hotel->tag15 = $request->tag[14];

        $hotel->save();

        return \redirect(\url('newChangeContent/' . $hotel->cityId . '/4/1'));
    }

    public function storeRestaurant(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'address' => 'required',
            'keyword' => 'required',
            'h1' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $restaurant = new Restaurant();

            $restaurant->file = 'none';
            $restaurant->markaz = 0;
            $restaurant->hoome = 0;
            $restaurant->class = 0;
            $restaurant->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $restaurant = Restaurant::find($request->id);

            if($restaurant == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $restaurant->name = $request->name;
        $restaurant->cityId = $request->cityId;
        $restaurant->C = $request->C;
        $restaurant->D = $request->D;
        $restaurant->meta = $request->meta;
        $restaurant->h1 = $request->h1;
        $restaurant->description = $request->description;
        $restaurant->keyword = $request->keyword;
        $restaurant->kind_id = $request->kind_id;

        if($request->address != null)
            $restaurant->address = $request->address;
        else
            $restaurant->address = '';

        if($request->phone != null)
            $restaurant->phone = $request->phone;
        else
            $restaurant->phone = '';

        if($request->site != null)
            $restaurant->site = $request->site;
        else
            $restaurant->site = '';

        if(isset($request->food_irani) && $request->food_irani == 'on')
            $restaurant->food_irani = 1;
        else
            $restaurant->food_irani = 0;

        if(isset($request->food_mahali) && $request->food_mahali == 'on')
            $restaurant->food_mahali = 1;
        else
            $restaurant->food_mahali = 0;

        if(isset($request->food_farangi) && $request->food_farangi == 'on')
            $restaurant->food_farangi = 1;
        else
            $restaurant->food_farangi = 0;

        if(isset($request->coffeeshop) && $request->coffeeshop == 'on')
            $restaurant->coffeeshop = 1;
        else
            $restaurant->coffeeshop = 0;

        if(isset($request->tarikhi) && $request->tarikhi == 'on')
            $restaurant->tarikhi = 1;
        else
            $restaurant->tarikhi = 0;

        $restaurant->boundArea = $request->boundArea;

        if($request->population == 1){
            $restaurant->shologh = 1;
            $restaurant->khalvat = 0;
        }
        else{
            $restaurant->shologh = 0;
            $restaurant->khalvat = 1;
        }

        if(isset($request->tabiat) && $request->tabiat == 'on')
            $restaurant->tabiat = 1;
        else
            $restaurant->tabiat = 0;

        if(isset($request->kooh) && $request->kooh == 'on')
            $restaurant->kooh = 1;
        else
            $restaurant->kooh = 0;

        if(isset($request->darya) && $request->darya == 'on')
            $restaurant->darya = 1;
        else
            $restaurant->darya = 0;

        if(isset($request->kavir) && $request->kavir == 'on')
            $restaurant->kavir = 1;
        else
            $restaurant->kavir = 0;

        if(isset($request->modern) && $request->modern == 'on'){
            $restaurant->modern = 1;
            $restaurant->sonnati = 0;
            $restaurant->ghadimi = 0;
            $restaurant->mamooli = 0;
        }
        else {
            $restaurant->modern = 0;

            if(isset($request->sonnati) && $request->sonnati == 'on')
                $restaurant->sonnati = 1;
            else
                $restaurant->sonnati = 0;

            if(isset($request->ghadimi) && $request->ghadimi == 'on')
                $restaurant->ghadimi = 1;
            else
                $restaurant->ghadimi = 0;

            if(isset($request->mamooli) && $request->mamooli == 'on')
                $restaurant->mamooli = 1;
            else
                $restaurant->mamooli = 0;
        }

        $restaurant->tag1 = $request->tag[0];
        $restaurant->tag2 = $request->tag[1];
        $restaurant->tag3 = $request->tag[2];
        $restaurant->tag4 = $request->tag[3];
        $restaurant->tag5 = $request->tag[4];
        $restaurant->tag6 = $request->tag[5];
        $restaurant->tag7 = $request->tag[6];
        $restaurant->tag8 = $request->tag[7];
        $restaurant->tag9 = $request->tag[8];
        $restaurant->tag10 = $request->tag[9];
        $restaurant->tag11 = $request->tag[10];
        $restaurant->tag12 = $request->tag[11];
        $restaurant->tag13 = $request->tag[12];
        $restaurant->tag14 = $request->tag[13];
        $restaurant->tag15 = $request->tag[14];

        $restaurant->save();

        return \redirect(\url('newChangeContent/' . $restaurant->cityId . '/3/1'));
    }

    public function storeMajara(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'keyword' => 'required',
            'h1' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'D' => 'required',
            'C' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $majara = new Majara();

            $majara->manategh = 0;
            $majara->class = 0;
            $majara->file = 'none';
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $majara = Majara::find($request->id);

            if($majara == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $majara->name = $request->name;
        $majara->cityId = $request->cityId;
        $majara->C = $request->C;
        $majara->D = $request->D;
        $majara->meta = $request->meta;
        $majara->h1 = $request->h1;
        $majara->description = $request->description;
        $majara->keyword = $request->keyword;

        if($request->dastresi != null)
            $majara->dastresi = $request->dastresi;
        else
            $majara->dastresi = '';

        if($request->nazdik != null)
            $majara->nazdik = $request->nazdik;
        else
            $majara->nazdik = '';

        if(isset($request->kooh) && $request->kooh == 'on')
            $majara->kooh = 1;
        else
            $majara->kooh = 0;

        if(isset($request->darya) && $request->darya == 'on')
            $majara->darya = 1;
        else
            $majara->darya = 0;

        if(isset($request->daryache) && $request->daryache == 'on')
            $majara->daryache = 1;
        else
            $majara->daryache = 0;

        if(isset($request->hayatevahsh) && $request->hayatevahsh == 'on')
            $majara->hayatevahsh = 1;
        else
            $majara->hayatevahsh = 0;

        if(isset($request->shahri) && $request->shahri == 'on')
            $majara->shahri = 1;
        else
            $majara->shahri = 0;

        if(isset($request->hefazat) && $request->hefazat == 'on')
            $majara->hefazat = 1;
        else
            $majara->hefazat = 0;

        if(isset($request->kavir) && $request->kavir == 'on')
            $majara->kavir = 1;
        else
            $majara->kavir = 0;

        if(isset($request->raml) && $request->raml == 'on')
            $majara->raml = 1;
        else
            $majara->raml = 0;

        if(isset($request->jangal) && $request->jangal == 'on')
            $majara->jangal = 1;
        else
            $majara->jangal = 0;

        if(isset($request->kamp) && $request->kamp == 'on')
            $majara->kamp = 1;
        else
            $majara->kamp = 0;

        if(isset($request->koohnavardi) && $request->koohnavardi == 'on')
            $majara->koohnavardi = 1;
        else
            $majara->koohnavardi = 0;

        if(isset($request->sahranavardi) && $request->sahranavardi == 'on')
            $majara->sahranavardi = 1;
        else
            $majara->sahranavardi = 0;

        if(isset($request->abshar) && $request->abshar == 'on')
            $majara->abshar = 1;
        else
            $majara->abshar = 0;

        if(isset($request->darre) && $request->darre == 'on')
            $majara->darre = 1;
        else
            $majara->darre = 0;

        if(isset($request->piknik) && $request->piknik == 'on')
            $majara->piknik = 1;
        else
            $majara->piknik = 0;

        if(isset($request->bekr) && $request->bekr == 'on')
            $majara->bekr = 1;
        else
            $majara->bekr = 0;

        if(isset($request->dasht) && $request->dasht == 'on')
            $majara->dasht = 1;
        else
            $majara->dasht = 0;


        $majara->tag1 = $request->tag[0];
        $majara->tag2 = $request->tag[1];
        $majara->tag3 = $request->tag[2];
        $majara->tag4 = $request->tag[3];
        $majara->tag5 = $request->tag[4];
        $majara->tag6 = $request->tag[5];
        $majara->tag7 = $request->tag[6];
        $majara->tag8 = $request->tag[7];
        $majara->tag9 = $request->tag[8];
        $majara->tag10 = $request->tag[9];
        $majara->tag11 = $request->tag[10];
        $majara->tag12 = $request->tag[11];
        $majara->tag13 = $request->tag[12];
        $majara->tag14 = $request->tag[13];
        $majara->tag15 = $request->tag[14];

        $majara->save();

        return \redirect(\url('newChangeContent/' . $majara->cityId . '/6/1'));
    }

    public function storeMahaliFood(Request $request)
    {
//        dd($request->all());

        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'kind' => 'required',
            'keyword' => 'required',
            'h1' => 'required',
            'meta' => 'required',
            'description' => 'required',
            'tag' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $newMahali = new MahaliFood();
            $newMahali->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $newMahali = MahaliFood::find($request->id);

            if($newMahali == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $newMahali->name = $request->name;
        $newMahali->cityId = $request->cityId;
        $newMahali->kind = $request->kind;
        $newMahali->keyword = $request->keyword;
        $newMahali->h1 = $request->h1;
        $newMahali->meta = $request->meta;
        $newMahali->description = $request->description;
        $newMahali->alt = $request->keyword;

        if(isset($request->veg) && $request->veg == 1)
            $newMahali->veg = 1;
        else
            $newMahali->veg = 0;

        if(isset($request->hotOrCold))
            $newMahali->hotOrCold = $request->hotOrCold;

        if(isset($request->recipes) && $request->recipes != null)
            $newMahali->recipes = $request->recipes;
        else
            $newMahali->recipes = null;

        $material = array();
        for ($i = 0; $i < count($request->matName) && $i < count($request->matValue); $i++){
            if(isset($request->matName[$i]) && $request->matName[$i] != null && isset($request->matValue[$i]) && $request->matValue[$i] != null ){
                $mat = [$request->matName[$i], $request->matValue[$i]];
                array_push($material, $mat);
            }
        }
        $newMahali->material = json_encode($material);

        $newMahali->tag1 = $request->tag[0];
        $newMahali->tag2 = $request->tag[1];
        $newMahali->tag3 = $request->tag[2];
        $newMahali->tag4 = $request->tag[3];
        $newMahali->tag5 = $request->tag[4];
        $newMahali->tag6 = $request->tag[5];
        $newMahali->tag7 = $request->tag[6];
        $newMahali->tag8 = $request->tag[7];
        $newMahali->tag9 = $request->tag[8];
        $newMahali->tag10 = $request->tag[9];
        $newMahali->tag11 = $request->tag[10];
        $newMahali->tag12 = $request->tag[11];
        $newMahali->tag13 = $request->tag[12];
        $newMahali->tag14 = $request->tag[13];
        $newMahali->tag15 = $request->tag[14];

        $newMahali->save();

        return \redirect(\url('newChangeContent/' . $newMahali->cityId . '/11/1'));
    }

    public function storeSogatSanaie(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cityId' => 'required',
            'eatable' => 'required',
            'keyword' => 'required',
            'h1' => 'required',
            'meta' => 'required',
            'description' => 'required',
        ]);

        if(isset($request->inputType) && $request->inputType == 'new'){
            $newSogat = new SogatSanaie();
            $newSogat->author = \auth()->user()->id;
        }
        else if(isset($request->id) && $request->inputType == 'edit'){
            $newSogat = SogatSanaie::find($request->id);

            if($newSogat == null)
                return \redirect()->back();
        }
        else
            return \redirect()->back();

        $newSogat->name = $request->name;
        $newSogat->cityId = $request->cityId;
        $newSogat->keyword = $request->keyword;
        $newSogat->h1 = $request->h1;
        $newSogat->meta = $request->meta;
        $newSogat->description = $request->description;
        $newSogat->alt = $request->keyword;

        $newSogat->weight = $request->weight;
        $newSogat->size = $request->size;
        $newSogat->price = $request->price;

        $newSogat->eatable = $request->eatable;

        if (isset($request->jewelry) && $request->jewelry == 'on')
            $newSogat->jewelry = 1;
        else
            $newSogat->jewelry = 0;

        if (isset($request->cloth) && $request->cloth == 'on')
            $newSogat->cloth = 1;
        else
            $newSogat->cloth = 0;

        if (isset($request->decorative) && $request->decorative == 'on')
            $newSogat->decorative = 1;
        else
            $newSogat->decorative = 0;

        if (isset($request->applied) && $request->applied == 'on')
            $newSogat->applied = 1;
        else
            $newSogat->applied = 0;

        if (isset($request->fragile) && $request->fragile == '1')
            $newSogat->fragile = 1;
        else
            $newSogat->fragile = 0;

        if (isset($request->style))
            $newSogat->style = $request->style;


        if (isset($request->torsh) && $request->torsh == 'on')
            $newSogat->torsh = 1;
        else
            $newSogat->torsh = 0;

        if (isset($request->shirin) && $request->shirin == 'on')
            $newSogat->shirin = 1;
        else
            $newSogat->shirin = 0;

        if (isset($request->talkh) && $request->talkh == 'on')
            $newSogat->talkh = 1;
        else
            $newSogat->talkh = 0;

        if (isset($request->malas) && $request->malas == 'on')
            $newSogat->malas = 1;
        else
            $newSogat->malas = 0;

        if (isset($request->shor) && $request->shor == 'on')
            $newSogat->shor = 1;
        else
            $newSogat->shor = 0;

        if (isset($request->tond) && $request->tond == 'on')
            $newSogat->tond = 1;
        else
            $newSogat->tond = 0;

        if(isset($request->material) && $request->material != null)
            $newSogat->material = $request->material;

        $newSogat->tag1 = $request->tag[0];
        $newSogat->tag2 = $request->tag[1];
        $newSogat->tag3 = $request->tag[2];
        $newSogat->tag4 = $request->tag[3];
        $newSogat->tag5 = $request->tag[4];
        $newSogat->tag6 = $request->tag[5];
        $newSogat->tag7 = $request->tag[6];
        $newSogat->tag8 = $request->tag[7];
        $newSogat->tag9 = $request->tag[8];
        $newSogat->tag10 = $request->tag[9];
        $newSogat->tag11 = $request->tag[10];
        $newSogat->tag12 = $request->tag[11];
        $newSogat->tag13 = $request->tag[12];
        $newSogat->tag14 = $request->tag[13];
        $newSogat->tag15 = $request->tag[14];

        $newSogat->author = \auth()->user()->id;
        $newSogat->save();

        return \redirect(\url('newChangeContent/' . $newSogat->cityId . '/10/1'));
    }



    public function index()
    {
        $places = Place::all();

        return view('config.places', compact(['places']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $place = Place::where('name', $request->name)->first();

        if($place == null){
            $place =  new Place();
            $place->name = $request->name;
            $place->visibility = 1;
            $place->save();
        }
        else{
            Session::flash('error', 'این مکان قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required'
        ]);

        $place = Place::find($request->id);

        if($place != null){
            $checkPlace = Place::where('name', $request->name)->first();
            if($checkPlace == null) {
                $place->name = $request->name;
                $place->save();
            }
            else{
                Session::flash('error', 'مکانی با این نام موجود می باشد');
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

        Place::find($request->id)->delete();

        return redirect()->back();
    }

    public function changeContent($city, $mode, $cityMode, $wantedKey = -1, $filter = -1) {

        switch ($mode) {
            case getValueInfo("hotel"):
            default:
                    return $this->changeHotelContent($city, $wantedKey, $cityMode);
            case getValueInfo('amaken'):
                    return $this->changeAmakenContent($city, $wantedKey, $cityMode);
            case getValueInfo('restaurant'):
                    return $this->changeRestaurantContent($city, $wantedKey, $cityMode);
            case getValueInfo('majara'):
                    return $this->changeMajaraContent($city, $wantedKey, $cityMode);
            case getValueInfo("adab"):
                    return $this->changeAdabContent($city, $wantedKey, $filter);
        }
    }

    private function changeAdabContent($stateId, $wantedKey, $filter) {

        if($filter == -1)
            $places = Adab::whereStateId($stateId)->get();
        else
            $places = Adab::whereStateId($stateId)->whereCategory($filter)->get();

        $categories = [
            ['name' => 'غذا محلی', 'id' => getValueInfo('ghaza')],
            ['name' => 'سوغات', 'id' => getValueInfo('soghat')],
            ['name' => 'صنایع دستی', 'id' => getValueInfo('sanaye')]
        ];

        return view('content.changeAdab', ['places' => $places, 'wantedKey' => $wantedKey,
            'categories' => json_encode($categories), 'selectedMode' => $filter, 'modes' => $categories,
            'pageURL' => route('changeContent', ['city' => $stateId, 'mode' => getValueInfo('adab'), 'cityMode' => 0, 'wantedKey' => $wantedKey])]);
    }

    private function changeHotelContent($cityId, $wantedKey, $mode) {

        if($mode) {
            $places = Hotel::whereCityId($cityId)->get();
        }
        else {
            $places = DB::select('select h.* from hotels h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
        }

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        $kind_ids = [
            ['name' => 'هتل', 'id' => getValueInfo('hotelMode')],
            ['name' => 'هتل آپارتمان', 'id' => getValueInfo('aparteman')],
            ['name' => 'مهمان سرا', 'id' => getValueInfo('mehmansara')],
            ['name' => 'ویلا', 'id' => getValueInfo('vila')],
            ['name' => 'متل', 'id' => getValueInfo('motel')],
            ['name' => 'مجتمع تفریحی', 'id' => getValueInfo('tafrihi')],
            ['name' => 'پانسیون', 'id' => getValueInfo('pansion')],
            ['name' => 'بوم گردی', 'id' => getValueInfo('bom')]
        ];

        return view('content.changeHotel', ['cityId' => $cityId,'places' => $places, 'kind_ids' => json_encode($kind_ids), 'mode' => $mode,
            'wantedKey' => $wantedKey, 'cities' => json_encode($citiesOut)
        ]);
    }

    private function changeMajaraContent($cityId, $wantedKey, $mode) {

        if($mode)
            $places = Majara::whereCityId($cityId)->get();
        else
            $places = DB::select('select m.* from majara m, cities c WHERE m.cityId = c.id and c.stateId = ' . $cityId);

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        return view('content.changeMajara', ['places' => $places, 'cities' => json_encode($citiesOut),
            'wantedKey' => $wantedKey]);
    }

    private function changeRestaurantContent($cityId, $wantedKey, $mode) {

        if($mode)
            $places = Restaurant::whereCityId($cityId)->get();
        else
            $places = DB::select('select h.* from restaurant h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);

        $kind_ids = [
            ['name' => 'رستوران', 'id' => getValueInfo('restaurantMode')],
            ['name' => 'فست فود', 'id' => getValueInfo('fastfood')]
        ];

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        return view('content.changeRestaurant', ['places' => $places, 'kind_ids' => json_encode($kind_ids),
            'wantedKey' => $wantedKey, 'cities' => json_encode($citiesOut)]);
    }

    private function changeAmakenContent($cityId, $wantedKey, $mode) {

        if($mode)
            $places = Amaken::whereCityId($cityId)->get();
        else {
            $places = DB::select('select h.* from amaken h, cities c WHERE h.cityId = c.id and c.stateId = ' . $cityId);
        }

        $citiesOut = [];
        $counter = 0;

        $states = State::all();

        foreach ($states as $state) {

            $cities = Cities::whereStateId($state->id)->get();
            $tmp = [];

            foreach ($cities as $city) {
                $tmp[count($tmp)] = ['name' => $city->name, 'id' => $city->id];
            }

            $citiesOut[$counter++] = ['name' => $state->name, 'nodes' => $tmp];
        }

        return view('content.changeAmaken', ['places' => $places, 'cities' => json_encode($citiesOut),
            'wantedKey' => $wantedKey]);
    }

    public function doChangePlace() {

        if(isset($_POST["id"]) && isset($_POST["kindPlaceId"]) && isset($_POST["val"]) &&
            isset($_POST["mode"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $id = makeValidInput($_POST["id"]);
            $mode = makeValidInput($_POST["mode"]);
            $val = makeValidInput($_POST["val"]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                default:
                    DB::update('update hotels set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('adab'):
                    DB::update('update adab set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('amaken'):
                    DB::update('update amaken set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('restaurant'):
                    DB::update('update restaurant set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
                case getValueInfo('majara'):
                    DB::update('update majara set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    break;
            }
        }
    }

    public function choosePlace($mode) {

        $url = route('root');

        switch ($mode) {
            case "alt":
                $url = $url . "/changeAlt/";
                break;
        }

        return view('content.choosePlace', ['url' => $url, 'places' => Place::whereVisibility(true)->get()]);
    }

    public function chooseCity($mode) {

        $url = route('root');

        switch ($mode) {
            case "seo":
                $url = $url . "/changeSeo/";
                break;
            case "content":
                $url = $url . "/changeContent/";
                return view('content.chooseCity', ['url' => $url, 'mode' => 2, 'places' => Place::all()]);
            case "content2":
                $url = $url . "/newChangeContent/";
                return view('content.chooseCity', ['url' => $url, 'mode' => 2, 'places' => Place::all()]);
        }

        return view('content.chooseCity', ['url' => $url, 'mode' => 1]);
    }

    public function searchForCity() {
        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT cities.id, cities.name as cityName, state.name as stateName FROM cities, state WHERE cities.stateId = state.id and  cities.name LIKE '%$key%' ");
        echo json_encode($cities);
    }

    public function searchForCityAndState() {

        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT 'city' as mode, cities.id, cities.name as cityName, state.name as stateName FROM cities, state WHERE cities.stateId = state.id and  cities.name LIKE '%$key%' ");
        $states = DB::select("SELECT 'state' as mode, id, name FROM state WHERE name LIKE '%$key%' ");

        echo json_encode(array_merge($cities, $states));
    }

    public function searchForState() {
        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT id, name FROM state WHERE name LIKE '%$key%' ");
        echo json_encode($cities);
    }

    private function uploadHotel($contents) {

        $err = "";
        $counter = 1;

        foreach ($contents as $content) {
            $tmp = new Hotel();
            $j = 0;

            $tmp->name = $content[$j++];
            $tmp->address = $content[$j++];
            $tmp->phone = $content[$j++];
            $tmp->site = $content[$j++];
            $tmp->description = $content[$j++];
            $tmp->food_irani = $content[$j++];
            $tmp->food_mahali = $content[$j++];
            $tmp->food_farangi = $content[$j++];
            $tmp->coffeeshop = $content[$j++];
            $tmp->tarikhi = $content[$j++];
            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];
            $tmp->vabastegi = $content[$j++];
            $tmp->parking = $content[$j++];
            $tmp->club = $content[$j++];
            $tmp->pool = $content[$j++];
            $tmp->tahviye = $content[$j++];
            $tmp->maalool = $content[$j++];
            $tmp->internet = $content[$j++];
            $tmp->anten = $content[$j++];
            $tmp->breakfast = $content[$j++];
            $tmp->restaurant = $content[$j++];
            $tmp->swite = $content[$j++];
            $tmp->masazh = $content[$j++];
            $tmp->mahali = $content[$j++];
            $tmp->rate = $content[$j++];
            $tmp->room_num = $content[$j++];
            $tmp->modern = $content[$j++];
            $tmp->sonnati = $content[$j++];
            $tmp->ghadimi = $content[$j++];
            $tmp->mamooli = $content[$j++];
            $tmp->laundry = $content[$j++];
            $tmp->gasht = $content[$j++];
            $tmp->safe_box = $content[$j++];
            $tmp->shop = $content[$j++];
            $tmp->roof_garden = $content[$j++];
            $tmp->game_net = $content[$j++];
            $tmp->confrenss_room = $content[$j++];
            $tmp->kind_id = $content[$j++];
            $tmp->file = $content[$j];

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-1.jpg'))
                $tmp->pic_1 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-2.jpg'))
                $tmp->pic_2 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-3.jpg'))
                $tmp->pic_3 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-4.jpg'))
                $tmp->pic_4 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-5.jpg'))
                $tmp->pic_5 = 1;

            recurse_copy(__DIR__ . '/../../../public/tmpZip/' . $content[$j],
                __DIR__ . '/../../../../assets/_images/hotels/' . $content[$j]);

            deleteDir(__DIR__ . '/../../../public/tmpZip/' . $content[$j]);

            $j++;
            $tmp->meta = $content[$j++];
            $tmp->cityId = $content[$j++];
            $tmp->C = $content[$j++];
            $tmp->D = $content[$j++];
            $tmp->fasele = $content[$j++];
            $tmp->rate_int = $content[$j++];
            $tmp->keyword = $content[$j++];
            $tmp->h1 = $content[$j++];
            $tmp->alt1 = $content[$j++];
            $tmp->alt2 = $content[$j++];
            $tmp->alt3 = $content[$j++];
            $tmp->alt4 = $content[$j++];
            $tmp->alt5 = $content[$j++];
            $tmp->tag1 = $content[$j++];
            $tmp->tag2 = $content[$j++];
            $tmp->tag3 = $content[$j++];
            $tmp->tag4 = $content[$j++];
            $tmp->tag5 = $content[$j++];
            $tmp->tag6 = $content[$j++];
            $tmp->tag7 = $content[$j++];
            $tmp->tag8 = $content[$j++];
            $tmp->tag9 = $content[$j++];
            $tmp->tag10 = $content[$j++];
            $tmp->tag11 = $content[$j++];
            $tmp->tag12 = $content[$j++];
            $tmp->tag13 = $content[$j++];
            $tmp->tag14 = $content[$j++];
            $tmp->tag15 = $content[$j];

            $tmp->onlineReservation = 0;
            $tmp->authorized = 0;
            $tmp->author = Auth::user()->id;

            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                $err .= "خطا در خط " . ($counter) . "<br/>" . $x->getMessage() . "<br/>";
            }
            finally {
                $counter++;
            }
        }

        return $err;
    }

    private function uploadAmaken($contents) {

        $err = "";
        $counter = 1;

        foreach ($contents as $content) {

            $tmp = new Amaken();
            $j = 0;

            $tmp->name = $content[$j++];
            $tmp->address = $content[$j++];
            $tmp->phone = $content[$j++];
            $tmp->site = $content[$j++];
            $tmp->description = $content[$j++];

            $tmp->emkanat = $content[$j++];
            $tmp->tarikhi = $content[$j++];
            $tmp->mooze = $content[$j++];
            $tmp->tafrihi = $content[$j++];
            $tmp->tabiatgardi = $content[$j++];
            $tmp->markazkharid = $content[$j++];
            $tmp->baftetarikhi = $content[$j++];

            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];

            $tmp->modern = $content[$j++];
            $tmp->tarikhibana = $content[$j++];
            $tmp->mamooli = $content[$j++];

            $tmp->file = $content[$j];

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-1.jpg'))
                $tmp->pic_1 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-2.jpg'))
                $tmp->pic_2 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-3.jpg'))
                $tmp->pic_3 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-4.jpg'))
                $tmp->pic_4 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-5.jpg'))
                $tmp->pic_5 = 1;

            recurse_copy(__DIR__ . '/../../../public/tmpZip/' . $content[$j],
                __DIR__ . '/../../../../assets/_images/amaken/' . $content[$j]);

            deleteDir(__DIR__ . '/../../../public/tmpZip/' . $content[$j]);

            $j++;

            $tmp->meta = $content[$j++];
            $tmp->cityId = $content[$j++];
            $tmp->C = $content[$j++];
            $tmp->D = $content[$j++];

            $tmp->farhangi = $content[$j++];
            $tmp->ghadimi = $content[$j++];

            $tmp->keyword = $content[$j++];
            $tmp->h1 = $content[$j++];
            $tmp->alt1 = $content[$j++];
            $tmp->alt2 = $content[$j++];
            $tmp->alt3 = $content[$j++];
            $tmp->alt4 = $content[$j++];
            $tmp->alt5 = $content[$j++];
            $tmp->tag1 = $content[$j++];
            $tmp->tag2 = $content[$j++];
            $tmp->tag3 = $content[$j++];
            $tmp->tag4 = $content[$j++];
            $tmp->tag5 = $content[$j++];
            $tmp->tag6 = $content[$j++];
            $tmp->tag7 = $content[$j++];
            $tmp->tag8 = $content[$j++];
            $tmp->tag9 = $content[$j++];
            $tmp->tag10 = $content[$j++];
            $tmp->tag11 = $content[$j++];
            $tmp->tag12 = $content[$j++];
            $tmp->tag13 = $content[$j++];
            $tmp->tag14 = $content[$j++];
            $tmp->tag15 = $content[$j];

            $tmp->authorized = 0;
            $tmp->author = Auth::user()->id;
            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                $err .= "خطا در خط " . ($counter) . "<br/>" . $x->getMessage() . "<br/>";
            }
            finally {
                $counter++;
            }
        }

        return $err;
    }

    private function uploadRestaurant($contents) {

        $err = "";
        $counter = 1;

        foreach ($contents as $content) {

            $tmp = new Restaurant();
            $j = 0;

            $tmp->name = $content[$j++];
            $tmp->address = $content[$j++];
            $tmp->phone = $content[$j++];
            $tmp->site = $content[$j++];
            $tmp->description = $content[$j++];

            $tmp->food_irani = $content[$j++];
            $tmp->food_mahali = $content[$j++];
            $tmp->food_farangi = $content[$j++];
            $tmp->coffeeshop = $content[$j++];
            $tmp->tarikhi = $content[$j++];
            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];

            $tmp->markaz = $content[$j++];
            $tmp->hoome = $content[$j++];
            $tmp->shologh = $content[$j++];
            $tmp->khalvat = $content[$j++];
            $tmp->tabiat = $content[$j++];
            $tmp->kooh = $content[$j++];
            $tmp->darya = $content[$j++];
            $tmp->class = $content[$j++];

            $tmp->modern = $content[$j++];
            $tmp->sonnati = $content[$j++];
            $tmp->ghadimi = $content[$j++];
            $tmp->mamooli = $content[$j++];
            $tmp->kind_id = $content[$j++];

            $tmp->file = $content[$j];

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-1.jpg'))
                $tmp->pic_1 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-2.jpg'))
                $tmp->pic_2 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-3.jpg'))
                $tmp->pic_3 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-4.jpg'))
                $tmp->pic_4 = 1;

            if(file_exists(__DIR__ . '/../../../public/tmpZip/' . $content[$j] . '/s-5.jpg'))
                $tmp->pic_5 = 1;

            recurse_copy(__DIR__ . '/../../../public/tmpZip/' . $content[$j],
                __DIR__ . '/../../../../assets/_images/restaurant/' . $content[$j]);

            deleteDir(__DIR__ . '/../../../public/tmpZip/' . $content[$j]);

            $j++;

            $tmp->meta = $content[$j++];
            $tmp->cityId = $content[$j++];
            $tmp->C = $content[$j++];
            $tmp->D = $content[$j++];

            $tmp->keyword = $content[$j++];
            $tmp->h1 = $content[$j++];
            $tmp->alt1 = $content[$j++];
            $tmp->alt2 = $content[$j++];
            $tmp->alt3 = $content[$j++];
            $tmp->alt4 = $content[$j++];
            $tmp->alt5 = $content[$j++];
            $tmp->tag1 = $content[$j++];
            $tmp->tag2 = $content[$j++];
            $tmp->tag3 = $content[$j++];
            $tmp->tag4 = $content[$j++];
            $tmp->tag5 = $content[$j++];
            $tmp->tag6 = $content[$j++];
            $tmp->tag7 = $content[$j++];
            $tmp->tag8 = $content[$j++];
            $tmp->tag9 = $content[$j++];
            $tmp->tag10 = $content[$j++];
            $tmp->tag11 = $content[$j++];
            $tmp->tag12 = $content[$j++];
            $tmp->tag13 = $content[$j++];
            $tmp->tag14 = $content[$j++];
            $tmp->tag15 = $content[$j];

            $tmp->authorized = 0;
            $tmp->author = Auth::user()->id;
            try {
                $tmp->save();
            }
            catch (\Exception $x) {
                $err .= "خطا در خط " . ($counter) . "<br/>" . $x->getMessage() . "<br/>";
            }
            finally {
                $counter++;
            }
        }

        return $err;
    }

    private function preprocess($neededCol) {

        $excel_file = $_FILES["content"]["name"];

        if(!empty($excel_file)) {

            $path = __DIR__ . '/../../../public/tmp/' . $excel_file;
            $err = uploadCheck($path, "content", "اکسل افزودن دسته ای محتوا", 20000000, "xlsx", "xls");

            if (empty($err)) {

                upload($path, "content", "اکسل افزودن دسته ای محتوا");

                $excelReader = PHPExcel_IOFactory::createReaderForFile($path);
                $excelObj = $excelReader->load($path);
                $workSheet = $excelObj->getSheet(0);
                $contents = [];
                $lastRow = $workSheet->getHighestRow();
                $cols = $workSheet->getHighestColumn();

                $char = 'A';
                $charArr = [];

                for($i = 0; $i < $neededCol; $i++) {
                    $charArr[$i] = $char;
                    $char++;
                }
                if ($cols < $charArr[count($charArr) - 1]) {
                    unlink($path);
                    return ['status' => 'nok', 'msg' => "تعداد ستون های فایل شما معتبر نمی باشد"];
                }

                for ($row = 2; $row <= $lastRow; $row++) {
                    if($workSheet->getCell($charArr[0] . $row)->getValue() == null)
                        break;
                    for ($j = 0; $j < count($charArr); $j++) {
                        $tmp = $workSheet->getCell($charArr[$j] . $row)->getValue();
                        $contents[$row - 2][$j] = ($tmp == null) ? 0 : $tmp;
                    }
                }
                unlink($path);

                $file = $_FILES["photos"]["name"];

                if(!empty($file)) {
                    $path = __DIR__ . '/../../../public/tmp/' . $file;
                    $err = uploadCheck($path, "photos", "فایل زیپ تصاویر", 20000000, "zip");
                    if (empty($err)) {
                        upload($path, "photos", "فایل زیپ تصاویر");
                        $zip = new ZipArchive;
                        $res = $zip->open(__DIR__ . "/../../../public/tmp/" . $file);
                        if ($res) {
                            $zip->extractTo(__DIR__ . "/../../../public/tmpZip/");
                            $zip->close();
                            unlink(__DIR__ . "/../../../public/tmp/" . $file);
                        }
                        else
                            return ['status' => 'nok', 'msg' => "اشکالی در باز کردن فایل زیپ داده شده به وجود آمده است"];

                        return ['status' => 'ok', 'contents' => $contents];
                    }

                    return ['status' => 'nok', 'msg' => $err];
                }

                return ['status' => 'nok', 'msg' => "لطفا فایل تصاویر را آپلود نمایید"];
            }
            return ['status' => 'nok', 'msg' => $err];
        }

        return ["status" => 'nok', 'msg' => "لطفا فایل اکسل محتوا را آپلود نمایید"];
    }

    public function doUploadMainContent() {

        if(isset($_POST["kindPlaceId"]) && isset($_FILES["content"]) && isset($_FILES["photos"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $neededCol = -1;

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                    $neededCol = 74;
                    break;
                case getValueInfo('amaken'):
                    $neededCol = 52;
                    break;
                case getValueInfo('restaurant'):
                    $neededCol = 50;
                    break;
            }

            if($neededCol == -1)
                return Redirect::route('uploadMainContent');
                
            $msg = $this->preprocess($neededCol);
            if($msg["status"] == "nok")
                return view('config.uploadMainContent', ['msg' => $msg["msg"], 'kindPlaceId' => $kindPlaceId, 'places' => Place::all()]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                    $msg = $this->uploadHotel($msg["contents"]);
                    break;
                case getValueInfo("amaken"):
                    $msg = $this->uploadAmaken($msg["contents"]);
                    break;
                case getValueInfo("restaurant"):
                    $msg = $this->uploadRestaurant($msg["contents"]);
                    break;
            }

            if(!empty($msg)) {
                return view('config.uploadMainContent', ['msg' => $msg, 'kindPlaceId' => $kindPlaceId, 'places' => Place::all()]);
            }

        }

        return Redirect::route('uploadMainContent');
    }

    public function uploadMainContent() {
        return view('config.uploadMainContent', ['msg' => '', 'kindPlaceId' => getValueInfo('hotel'),
            'places' => Place::all()]);
    }

    public function indexCity()
    {
        $place = Place::all();
        return view('content.city.indexCity', compact(['place']));

    }

    public function addCity()
    {
        $state = State::all();
        $mode = 'add';

        return view('content.city.add_editCity', compact(['mode', 'state']));
    }

    public function editCity($id)
    {
        $city = Cities::find($id);
        $state = State::all();
        $mode = 'edit';

        return view('content.city.add_editCity', compact(['city', 'mode', 'state']));
    }

    public function deleteCity(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $city = Cities::find($request->id);
        if($city->image != null){
            \File::delete(__DIR__ . '/../../../../assets/_images/city/' . $city->image);
        }
        $city->delete();

        return \redirect(route('city.index'));
    }

    public function doEditCity(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'city_name' => 'required',
            'city_x' => 'required',
            'city_y' => 'required',
            'state' => 'required',
        ]);

        $city = Cities::find($request->id);

        $newfilename = null;
        if(isset($_FILES["image"]) && $_FILES["image"]['error'] == 0) {

            if($city->image != null){
                \File::delete(__DIR__ . '/../../../../assets/_images/city/' . $city->image);
            }

            $temp = explode(".", $_FILES["image"]["name"]);
            $newfilename = $request->city_name . '.' . end($temp);

            $pic = __DIR__ . '/../../../../assets/_images/city/' . $newfilename;

            $err = uploadCheck($pic, "image", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($pic, "image", "افزودن عکس جدید");
                if (!empty($err))
                    dd($err);
            }
            else {
                dd($err);
            }
        }

        $city->name = $request->city_name;
        $city->x = $request->city_x;
        $city->y = $request->city_y;
        $city->stateId = $request->state;
        $city->description = $request->comment;
        if($newfilename)
            $city->image = $newfilename;

        $city->save();
        return \redirect()->back();
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'city_name' => 'required',
            'city_x' => 'required',
            'city_y' => 'required',
            'state' => 'required',
            'comment' => 'required',
        ]);

        $newfilename = null;

        if(isset($_FILES["image"]) && $_FILES["image"]['error'] == 0) {
//            $name = time() . '_' . $_FILES["image"]["name"];
            $temp = explode(".", $_FILES["image"]["name"]);
            $newfilename = $request->city_name . '.' . end($temp);

            $pic = __DIR__ . '/../../../../assets/_images/city/' . $newfilename;

            $err = uploadCheck($pic, "image", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($pic, "image", "افزودن عکس جدید");
                if (!empty($err))
                    dd($err);
            }
            else {
                dd($err);
            }
        }

        $city = new Cities();

        $city->name = $request->city_name;
        $city->x = $request->city_x;
        $city->y = $request->city_y;
        $city->stateId = $request->state;
        $city->description = $request->comment;
        if($newfilename)
            $city->image = $newfilename;

        $city->save();
        return \redirect(route('city.index'));
    }

    public function topInCity($cityId = '')
    {
        if($cityId == ''){
            return view('content.city.topInCity.choose');
        }
        else{
            $city = Cities::find($cityId);

            if($city == null){
                return \redirect(url('topInCity'));
            }
            else{
                $specialAdvise = SpecialAdvice::where('cityId', $cityId)->get();
                $placeId = Place::where('name', 'اماکن')->OrWhere('name','رستوران')->OrWhere('name','هتل')->get();

                $amakenId = Place::where('name', 'اماکن')->first();
                $restaurantId = Place::where('name', 'رستوران')->first();
                $hotelId = Place::where('name', 'هتل')->first();

                foreach ($specialAdvise as $item){
                    if($item->kindPlaceId == $amakenId->id) {
                        $item->kindPlaceName = $amakenId->name;
                        $item->name = Amaken::find($item->placeId)->name;
                    }
                    elseif($item->kindPlaceId == $restaurantId->id) {
                        $item->kindPlaceName = $restaurantId->name;
                        $item->name = Restaurant::find($item->placeId)->name;
                    }
                    elseif($item->kindPlaceId == $hotelId->id) {
                        $item->kindPlaceName = $hotelId->name;
                        $item->name = Hotel::find($item->placeId)->name;
                    }
                }

                return view('content.city.topInCity.specialInCity', compact(['specialAdvise', 'city', 'placeId']));
            }
        }
    }

    public function findInCity(Request $request)
    {

        $amakenId = Place::where('name', 'اماکن')->first();
        $restaurantId = Place::where('name', 'رستوران')->first();
        $hotelId = Place::where('name', 'هتل')->first();

        if($request->kind == $amakenId->id){
            $find = DB::select('SELECT name, id FROM amaken WHERE name LIKE "%' . $request->name . '%" and cityId = ' . $request->cityId);
        }
        elseif($request->kind == $restaurantId->id){
            $find = DB::select('SELECT name, id FROM restaurant WHERE name LIKE "%' . $request->name . '%" and cityId = ' . $request->cityId);
        }
        elseif($request->kind == $hotelId->id){
            $find = DB::select('SELECT name, id FROM hotels WHERE name LIKE "%' . $request->name . '%" and cityId = ' . $request->cityId);
        }

        echo json_encode($find);
        return;
    }

    public function topInCityStore(Request $request)
    {
        if($request->replace == 0){
            $new = new SpecialAdvice();
            $new->cityId = $request->cityId;
            $new->placeId = $request->placeId;
            $new->kindPlaceId = $request->kindPlaceId;
            $new->save();
        }
        else{
            $change = SpecialAdvice::find($request->replace);

            if($change != null){
                $change->cityId = $request->cityId;
                $change->placeId = $request->placeId;
                $change->kindPlaceId = $request->kindPlaceId;
                $change->save();
            }

        }

        return \redirect()->back();
    }

}
