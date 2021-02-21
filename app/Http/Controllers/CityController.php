<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Cities;
use App\models\CityPic;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\PostCityRelation;
use App\models\QuestionSection;
use App\models\Restaurant;
use App\models\SogatSanaie;
use App\models\State;
use Illuminate\Http\Request;
use Illuminate\Queue\RedisQueue;

class CityController extends Controller
{

    public $cityFolderLocation = __DIR__ . '/../../../../assets/_images/city/';

    public function indexCity()
    {
        return view('content.city.indexCity');
    }

    public function searchForEdit()
    {
        $value = $_GET['value'];
        $kind = $_GET['kind'];

        if($kind === "state"){
            $result = State::where('name', 'LIKE', "%{$value}%")->where('isCountry', 0)->get();
            foreach($result as $item)
                $item->text = "استان {$item->name}";
        }
        else if($kind === "country"){
            $result = State::where('name', 'LIKE', "%{$value}%")->where('isCountry', 1)->get();
            foreach($result as $item)
                $item->text = "کشور {$item->name}";
        }
        else if($kind === "city"){
            $result = Cities::join('state', 'state.id', 'cities.stateId')
                    ->where('cities.name', 'LIKE', "%{$value}%")
                    ->where('cities.isVillage', 0)
                    ->select(['cities.id', 'cities.name', 'state.name AS stateName', 'state.isCountry'])
                    ->get();

            foreach($result as $item) {
                $title = $item->isCountry === 1 ? 'کشور' : 'استان';
                $item->text = "شهر {$item->name} در {$title} {$item->stateName}";
            }
        }
        else{
            $result = Cities::join('state', 'state.id', 'cities.stateId')
                    ->where('cities.name', 'LIKE', "%{$value}%")
                    ->where('cities.isVillage', '!=', 0)
                    ->select(['cities.id', 'cities.name', 'state.name AS stateName', 'state.isCountry'])
                    ->get();

            foreach($result as $item) {
                $title = $item->isCountry === 1 ? 'کشور' : 'استان';
                $item->text = "روستا {$item->name} در {$title} {$item->stateName}";
            }

        }

        return response()->json(['status' => 'ok', 'result' => $result]);

    }

    public function addCity($type)
    {
        $state = [];
        $country = [];
        if($type === "city" || $type === "village") {
            $state = State::where('isCountry', 0)->get();
            $country = State::where('isCountry', 1)->get();
        }

        $mode = 'add';
        if($type === "country")
            $text =  'کشور';
        else if($type === "state")
            $text =  'استان';
        else if($type === "city")
            $text =  'شهر';
        else
            $text = 'روستا';

        return view('content.city.add_editCity', compact(['mode', 'state', 'country', 'type', 'text']));
    }

    public function editCity($id, $type)
    {
        if($type === "country" || $type === "state"){
            $city = State::find($id);
            $city->image = \URL::asset("_images/city/{$city->folder}/{$city->image}");
            $state = [];
            $country = [];
        }
        else{
            $city = Cities::find($id);
            $state = State::where('isCountry', 0)->get();
            $country = State::where('isCountry', 1)->get();
            $city->image = \URL::asset("_images/city/{$city->id}/{$city->image}");

            $pics = CityPic::where('cityId', $city->id)->get();
            foreach ($pics as $pic)
                $pic->pic = \URL::asset("_images/city/{$city->id}/{$pic->pic}");
            $city->pic = $pics;

            if($city->isVillage != 0){
                $city->villageCityName = Cities::find($city->isVillage);
                if($city->villageCityName != null)
                    $city->villageCityName = $city->villageCityName->name;
            }
        }

        if($type === "country")
            $text =  'کشور';
        else if($type === "state")
            $text =  'استان';
        else if($type === "city")
            $text =  'شهر';
        else
            $text = 'روستا';

        $mode = 'edit';

        return view('content.city.add_editCity', compact(['city', 'mode', 'state', 'country', 'type', 'text']));
    }

    public function storeCity(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'city_name' => 'required',
            'type' => 'required'
        ]);

        if($request->type === "country" || $request->type === "state")
            $id = $this->storeCountryAndState($request->all());
        else if($request->type === "city" || $request->type === "village")
            $id = $this->storeCityAndVillage($request->all());
        else
            return response()->json(['status' => 'error']);

        if($id != 'error')
            return response()->json(['status' => 'ok', 'id' => $id]);
        else
            return response()->json(['status' => 'error1']);
    }

    public function storeMainPicCity(Request $request)
    {
        if(isset($request->id) && isset($request->type)){
            if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
                if($request->type === "country" || $request->type === "state"){

                    $state = State::find($request->id);
                    if($state->folder == null){
                        $folderName = rand(10000000, 99999999);
                        while(is_dir($this->cityFolderLocation.$folderName))
                            $folderName = rand(10000000, 99999999);

                        mkdir($this->cityFolderLocation.$folderName);

                        $state->folder = $folderName;
                        $state->save();
                    }

                    $location = $this->cityFolderLocation.$state->folder;
                    if(!is_dir($location))
                        mkdir($location);

                    $size = [
                        [
                            'width' => null,
                            'height' => 1080,
                            'name' => '',
                            'destination' => $location
                        ],
                        [
                            'width' => null,
                            'height' => 400,
                            'name' => 'small-',
                            'destination' => $location
                        ]
                    ];

                    $fileName = resizeImage($request->file('pic'), $size);

                    if($fileName != 'error'){
                        if($state->image != null){
                            if(is_file("{$location}/{$state->image}"))
                                unlink("{$location}/{$state->image}");

                            if(is_file("{$location}/small-{$state->image}"))
                                unlink("{$location}/small-{$state->image}");
                        }

                        $state->image = $fileName;
                        $state->save();

                        return response()->json(['status' => 'ok']);
                    }
                    else
                        return response()->json(['status' => 'resizeError']);
                }
            }
            else
                return response()->json(['status' => 'uploadError']);
        }
        else
            return response()->json(['status' => 'error']);
    }

    public function storeCityImage(Request $request)
    {
        $data = json_decode($request->data);

        if(isset($data) && $data->id != 0 && $_FILES['pic'] && $_FILES['pic']['error'] == 0){
            $city = Cities::find($data->id);
            if($city != null){
                $fileName = time().$_FILES['pic']['name'];
                $location = __DIR__ . '/../../../../assets/_images/city';
                if(!is_dir($location))
                    mkdir($location);

                $location .= '/' . $city->id;
                if(!is_dir($location))
                    mkdir($location);

                $location .= '/' . $fileName;
                if(move_uploaded_file($_FILES['pic']['tmp_name'], $location)){
                    $newPic = new CityPic();
                    $newPic->cityId = $city->id;
                    $newPic->pic = $fileName;
                    $newPic->save();

                    echo json_encode(['status' => 'ok', 'result' => $newPic->id]);
                }
                else
                    echo json_encode(['status' => 'cantUpload']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function chooseMainPic(Request $request)
    {
        if(isset($request->cityId) && isset($request->id)){
            $city = Cities::find($request->cityId);
            $pic = CityPic::find($request->id);
            if($city != null && $pic != null){
                $city->image = $pic->pic;
                $city->save();

                $url = \URL::asset("_images/city/{$city->id}/{$city->image}");
                return response()->json(['status' => 'ok', 'result' => $url]);
            }
            else
                return response()->json(['status' => 'error1']);
        }
        else
            return response()->json(['status' => 'error']);
    }


    public function deleteCity(Request $request)
    {
        dd(' این امکان از دسترس خارج است. به مدیر سایت اطلاع دهید');
        $request->validate([
            'id' => 'required'
        ]);

        $city = Cities::find($request->id);
        if($city->image != null){
            \File::delete(__DIR__ . '/../../../../assets/_images/city/' . $city->image);
        }

        PostCityRelation::where('cityId', $city->id)->delete();
        QuestionSection::where('cityId', $city->id)->delete();

        $kinPlace = Place::all();
        foreach ($kinPlace as $kind){
            if($kind->tableName != null){
                $places = \DB::table($kind->tableName)->where('cityId', $city->id)->get();
                foreach ($places as $place) {
                    switch ($kind->id){
                        case 1:
                            Amaken::fullDelete($place->id);
                            break;
                        case 3:
                            Restaurant::fullDelete($place->id);
                            break;
                        case 4:
                            Hotel::fullDelete($place->id);
                            break;
                        case 6:
                            Majara::fullDelete($place->id);
                            break;
                        case 10:
                            SogatSanaie::fullDelete($place->id);
                            break;
                        case 11:
                            MahaliFood::fullDelete($place->id);
                            break;
                    }
                }
            }
        }
        $city->delete();

        return \redirect(route('city.index'));
    }

    public function deleteCityImage(Request $request)
    {
        if(isset($request->id)){
            $cityPic = CityPic::find($request->id);
            if($cityPic != null){
                if(CityPic::deleteWithPic($cityPic->id))
                    echo json_encode(['status' => 'ok']);
                else
                    echo json_encode(['status' => 'nok2']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function sizeCityImage(Request $request)
    {
        if(isset($request->id)){
            $cityPic = CityPic::find($request->id);
            if($cityPic != null){
                $location = __DIR__ . '/../../../../assets/_images/city/' . $cityPic->cityId . '/' . $cityPic->pic;
                if(is_file($location)){
                    $filesize = filesize($location);
                    $filesize = floor(($filesize/1000)) . ' KB';
                    echo json_encode(['status' => 'ok', 'result' => $filesize]);
                }
                else
                    echo json_encode(['status' => 'nok2']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['stauts' => 'nok']);

        return;
    }

    public function storeCityImageAlt(Request $request)
    {
        if(isset($request->id) && isset($request->value)){
            $pic = CityPic::find($request->id);
            if($pic != null){
                $pic->alt = $request->value;
                $pic->save();
                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }


    private function storeCountryAndState($req){
        if(isset($req['id']) && $req['id'] == 0)
            $state = new State();
        else{
            $state = State::find($req['id']);
            if($state == null)
                return 'error';
        }

        $state->name = $req['city_name'];
        $state->description = $req['description'];
        $state->isCountry = $req['type'] === "country" ? 1 : 0;
        $state->save();

        if($state->folder == null){
            $location = __DIR__.'/../../../../assets/_images/city/';
            $folderName = rand(10000000, 99999999);
            while(is_dir($location.$folderName))
                $folderName = rand(10000000, 99999999);

            mkdir($location.$folderName);

            $state->folder = $folderName;
            $state->save();
        }

        return $state->id;
    }

    private function storeCityAndVillage($request){
        if(isset($request['id']) && $request['id'] == 0){
            $city = new Cities();
        }
        else if($request['id'] != 0){
            $city = Cities::find($request['id']);
            if($city == null)
                return 'error';
        }

        $city->name = $request['city_name'];
        $city->x = $request['city_x'];
        $city->y = $request['city_y'];
        $city->stateId = $request['state'];
        $city->description = $request['description'];
        $city->isVillage = isset($request['villageCityId']) ? $request['villageCityId'] : 0;
        $city->save();

        $location = $this->cityFolderLocation;
        if(!is_dir($location.$city->id))
            mkdir($location.$city->id);

        return $city->id;
    }
}
