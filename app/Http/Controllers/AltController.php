<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\Amaken;
use App\models\Hotel;
use App\models\Majara;
use App\models\Restaurant;
use Illuminate\Support\Facades\Redirect;

class AltController extends Controller {

    public function changeAlt($id, $mode) {

        $folderName = "";

        switch ($mode) {
            case getValueInfo('hotel'):
            default:
                $place = Hotel::whereId($id);
                $folderName = "hotels";
                break;
            case getValueInfo('amaken'):
                $place = Amaken::whereId($id);
                $folderName = "amaken";
                break;
            case getValueInfo('restaurant'):
                $place = Restaurant::whereId($id);
                $folderName = "restaurant";
                break;
            case getValueInfo('majara'):
                $place = Majara::whereId($id);
                $folderName = "majara";
                break;
            case getValueInfo('adab'):
                $place = Adab::whereId($id);
                break;
        }

        $photos = [];
        $alts = [];
        $metaPhoto = [];

        $alts[count($alts)] = $place->alt1;
        $metaPhoto[count($metaPhoto)] = [
            "تصویر " .  persianNumber(1). '  سایت' . ' سایز s',
            "تصویر " .  persianNumber(1). '  سایت' . ' سایز l',
            "تصویر " .  persianNumber(1). '  سایت' . ' سایز t',
            "تصویر " .  persianNumber(1). '  سایت' . ' سایز f',
        ];

        $alts[count($alts)] = $place->alt2;
        $metaPhoto[count($metaPhoto)] = [
            "تصویر " .  persianNumber(2). '  سایت' . ' سایز s',
            "تصویر " .  persianNumber(2). '  سایت' . ' سایز l',
            "تصویر " .  persianNumber(2). '  سایت' . ' سایز t',
            "تصویر " .  persianNumber(2). '  سایت' . ' سایز f',
        ];

        $alts[count($alts)] = $place->alt3;
        $metaPhoto[count($metaPhoto)] = [
            "تصویر " .  persianNumber(3). '  سایت' . ' سایز s',
            "تصویر " .  persianNumber(3). '  سایت' . ' سایز l',
            "تصویر " .  persianNumber(3). '  سایت' . ' سایز t',
            "تصویر " .  persianNumber(3). '  سایت' . ' سایز f',
        ];

        $alts[count($alts)] = $place->alt4;
        $metaPhoto[count($metaPhoto)] = [
            "تصویر " .  persianNumber(4). '  سایت' . ' سایز s',
            "تصویر " .  persianNumber(4). '  سایت' . ' سایز l',
            "تصویر " .  persianNumber(4). '  سایت' . ' سایز t',
            "تصویر " .  persianNumber(4). '  سایت' . ' سایز f',
        ];

        $alts[count($alts)] = $place->alt5;
        $metaPhoto[count($metaPhoto)] = [
            "تصویر " .  persianNumber(5). '  سایت' . ' سایز s',
            "تصویر " .  persianNumber(5). '  سایت' . ' سایز l',
            "تصویر " .  persianNumber(5). '  سایت' . ' سایز t',
            "تصویر " .  persianNumber(5). '  سایت' . ' سایز f',
        ];

        for ($i = 1; $i < 6; $i++)
            $photos[$i - 1] = getMainPic($place, $i, $folderName);

        return view('content.changeAlt', ['photos' => $photos, 'alts' => $alts, 'metaPhoto' => $metaPhoto,
            'id' => $id, 'kindPlaceId' => $mode]);
    }

    public function removeMainPic($id, $kindPlaceId) {

        if(isset($_POST["idx"])) {

            $idx = makeValidInput($_POST["idx"]);

            if($idx == 0)
                return;

            switch ($kindPlaceId) {

                case getValueInfo('hotel'):
                default:
                    $place = Hotel::whereId($id);
                    $folderName = "hotels";
                    break;
                case getValueInfo("amaken"):
                    $place = Amaken::whereId($id);
                    $folderName = "amaken";
                    break;
                case getValueInfo("majara"):
                    $place = Majara::whereId($id);
                    $folderName = "majara";
                    break;
                case getValueInfo("restaurant"):
                    $place = Restaurant::whereId($id);
                    $folderName = "restaurant";
                    break;
                case getValueInfo("adab"):
                    $place = Adab::whereId($id);
                    $folderName = "adab";
                    break;
            }

            if($place == null)
                return;

            switch ($idx) {
                case 1:
                    $place->pic_2 = false;
                    $place->alt2 = "";
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-2.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-2.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-2.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-2.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-2.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-2.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-2.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-2.jpg');
                    break;
                case 2:
                    $place->pic_3 = false;
                    $place->alt3 = "";
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-3.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-3.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-3.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-3.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-3.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-3.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-3.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-3.jpg');
                    break;
                case 3:
                    $place->pic_4 = false;
                    $place->alt4 = "";

                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-4.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-4.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-4.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-4.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-4.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-4.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-4.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-4.jpg');
                    break;
                case 4:
                    $place->pic_5 = false;
                    $place->alt5 = "";

                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-5.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/f-5.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-5.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/l-5.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-5.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/s-5.jpg');
                    if(file_exists(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-5.jpg'))
                        unlink(__DIR__ . '/../../../../_images/' . $folderName . '/' . $place->file . '/t-5.jpg');
                    break;
            }

            $place->save();

        }

    }

    public function doChangePic($id, $kindPlaceId) {

        if(isset($_POST["idx"]) && isset($_FILES["pic"]) && isset($_POST["sizeIdx"])) {

            switch ($kindPlaceId) {

                case getValueInfo('hotel'):
                default:
                    $place = Hotel::whereId($id);
                    break;

            }

            if($place == null)
                return Redirect::route('home');

            $sizeIdx = makeValidInput($_POST["sizeIdx"]);
            $idx = makeValidInput($_POST["idx"]);

            $pic = time() . $_FILES["pic"]["name"];

            switch ($sizeIdx) {
                case 0:
                default:
                    $prefix = __DIR__ . '/../../../../_images/hotels/' . $place->file . '/s-';
                    break;
                case 1:
                    $prefix = __DIR__ . '/../../../../_images/hotels/' . $place->file . '/l-';
                    break;
                case 2:
                    $prefix = __DIR__ . '/../../../../_images/hotels/' . $place->file . '/t-';
                    break;
                case 3:
                    $prefix = __DIR__ . '/../../../../_images/hotels/' . $place->file . '/f-';
                    break;
            }

            $err = uploadCheck($prefix . $pic, "pic", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($prefix . $pic, "pic", "افزودن عکس جدید");
                if (empty($err)) {

                    if(file_exists($prefix . ($idx + 1) . '.jpg'))
                        unlink($prefix . ($idx + 1) . '.jpg');

                    rename($prefix . $pic, $prefix . ($idx + 1) . '.jpg');
                    
                }
                else {
                    dd($err);
                }
            }
            else {
                dd($err);
            }
        }

        return Redirect::route('changeAlt', ['id' => $id, 'mode' => $kindPlaceId]);

    }

    public function doChangeAlt($id, $kindPlaceId) {

        if(isset($_POST["alt"]) && isset($_POST["idx"])) {
            
            $idx = makeValidInput($_POST["idx"]);
            $alt = makeValidInput($_POST["alt"]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                default:
                    $place = Hotel::whereId($id);
                    break;
            }

            switch ($idx) {
                case 0:
                    $place->alt1 = $alt;
                    break;
                case 1:
                    $place->alt2 = $alt;
                    break;
                case 2:
                    $place->alt3 = $alt;
                    break;
                case 3:
                    $place->alt4 = $alt;
                    break;
                case 4:
                    $place->alt5 = $alt;
                    break;
            }

            try {
                $place->save();
            }
            catch (\Exception $x) {
                dd($x->getMessage());
            }
        }

        return Redirect::route('changeAlt', ['id' => $id, 'mode' => $kindPlaceId]);
    }

}
