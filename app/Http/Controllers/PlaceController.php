<?php

namespace App\Http\Controllers;

use App\models\Amaken;
use App\models\Hotel;
use App\models\Majara;
use App\models\Place;
use App\models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use PHPExcel_IOFactory;
use ZipArchive;

class PlaceController extends Controller {

    public function changeContent($city, $mode) {

        switch ($mode) {
            case getValueInfo("hotel"):
            default:
                return $this->changeHotelContent($city);
            case getValueInfo('amaken'):
                return $this->changeAmakenContent($city);
            case getValueInfo('restaurant'):
                return $this->changeRestaurantContent($city);
            case getValueInfo('majara'):
                return $this->changeMajaraContent($city);

        }
    }

    private function changeHotelContent($cityId) {

        $places = Hotel::whereCityId($cityId)->get();

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

        return view('content.changeHotel', ['places' => $places, 'kind_ids' => json_encode($kind_ids)]);
    }

    private function changeMajaraContent($cityId) {

        $places = Majara::whereCityId($cityId)->get();

        return view('content.changeMajara', ['places' => $places]);

    }

    private function changeRestaurantContent($cityId) {

        $places = Restaurant::whereCityId($cityId)->get();

        $kind_ids = [
            ['name' => 'رستوران', 'id' => getValueInfo('restaurantMode')],
            ['name' => 'فست فود', 'id' => getValueInfo('fastfood')]
        ];

        return view('content.changeRestaurant', ['places' => $places, 'kind_ids' => json_encode($kind_ids)]);
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

    private function changeAmakenContent($cityId) {

        $places = Amaken::whereCityId($cityId)->get();

        return view('content.changeAmaken', ['places' => $places]);
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
        }

        return view('content.chooseCity', ['url' => $url, 'mode' => 1]);
    }

    public function searchForCity() {
        $key = makeValidInput($_POST["key"]);
        $cities = DB::select("SELECT cities.id, cities.name as cityName, state.name as stateName FROM cities, state WHERE cities.stateId = state.id and  cities.name LIKE '%$key%' ");
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

}
