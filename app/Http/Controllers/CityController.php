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

class CityController extends Controller
{
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

        $pics = CityPic::where('cityId', $city->id)->get();
        foreach ($pics as $pic)
            $pic->pic = \URL::asset('_images/city/' . $city->id . '/' . $pic->pic);
        $city->pic = $pics;

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
            $newfilename = $request->city_name . '.jpg';

            $pic = __DIR__ . '/../../../../assets/_images/city/' . $newfilename;

            compressImage($_FILES['image']['tmp_name'], $pic, 80);

//            $err = uploadCheck($pic, "image", "افزودن عکس جدید", 3000000, -1);
//            if(empty($err)) {
//                $err = upload($pic, "image", "افزودن عکس جدید");
//                if (!empty($err))
//                    dd($err);
//            }
//            else {
//                dd($err);
//            }
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

        if(isset($request->id) && $request->id == 0){
            $city = new Cities();
        }
        else if($request->id != 0){
            $city = Cities::find($request->id);
            if($city == null){
                echo json_encode([ 'status' => 'nok1' ]);
                return;
            }
        }
        //        $pic = __DIR__ . '/../../../../assets/_images/city/' . $newfilename;

        $city->name = $request->city_name;
        $city->x = $request->city_x;
        $city->y = $request->city_y;
        $city->stateId = $request->state;
        $city->description = $request->comment;
        $city->save();

        echo json_encode(['status' => 'ok', 'result' => ['id' => $city->id]]);
        return;
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

}
