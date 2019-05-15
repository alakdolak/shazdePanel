<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Adab;
use App\models\Amaken;
use App\models\Hotel;
use App\models\LogModel;
use App\models\Majara;
use App\models\PicItem;
use App\models\Restaurant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class AltController extends Controller {

    public function changeAlt($id, $mode) {

        $tmp = getPlaceAndFolderName($mode, $id);
        $place = $tmp[0];
        $folderName = $tmp[1];

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

        $aksActivityId = Activity::whereName('عکس')->first()->id;
        $userPhotos = DB::select("select log.id, text as pic, subject, alt, p.name as category, confirm from log, picItems p WHERE p.id = pic and activityId = " . $aksActivityId . " and placeId = " . $id . " and p.kindPlaceId = " . $mode . " and pic <> 0");

        foreach ($userPhotos as $userPhoto) {
            $userPhoto->pics[0] = URL::asset('userPhoto/' . $folderName . '/s-' . $userPhoto->pic);
            $userPhoto->pics[1] = URL::asset('userPhoto/' . $folderName . '/l-' . $userPhoto->pic);
            $userPhoto->confirm = ($userPhoto->confirm) ? 'تایید شده' : 'تایید نشده';
        }

        $acceptance = [["id" => 0, "name" => "تایید نشده"], ["id" => 1, "name" => "تایید شده"]];

        return view('content.changeAlt', ['photos' => $photos, 'alts' => $alts, 'metaPhoto' => $metaPhoto,
            'id' => $id, 'kindPlaceId' => $mode, 'picItems' => PicItem::whereKindPlaceId($mode)->get(),
            'userPhotos' => $userPhotos, 'acceptance' => $acceptance
        ]);
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
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-2.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-2.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-2.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-2.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-2.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-2.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-2.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-2.jpg');
                    break;
                case 2:
                    $place->pic_3 = false;
                    $place->alt3 = "";
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-3.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-3.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-3.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-3.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-3.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-3.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-3.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-3.jpg');
                    break;
                case 3:
                    $place->pic_4 = false;
                    $place->alt4 = "";

                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-4.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-4.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-4.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-4.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-4.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-4.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-4.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-4.jpg');
                    break;
                case 4:
                    $place->pic_5 = false;
                    $place->alt5 = "";

                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-5.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-5.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-5.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-5.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-5.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-5.jpg');
                    if(file_exists(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-5.jpg'))
                        unlink(__DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-5.jpg');
                    break;
            }

            $place->save();

        }

    }

    public function doChangePic($id, $kindPlaceId) {

        if(isset($_POST["idx"]) && isset($_FILES["pic"]) && isset($_POST["sizeIdx"])) {

            $tmp = getPlaceAndFolderName($kindPlaceId, $id);
            $place = $tmp[0];
            $folderName = $tmp[1];

            if($place == null)
                return Redirect::route('home');

            $sizeIdx = makeValidInput($_POST["sizeIdx"]);
            $idx = makeValidInput($_POST["idx"]);

            $pic = time() . $_FILES["pic"]["name"];

            switch ($sizeIdx) {
                case 0:
                default:
                    $prefix = __DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/s-';
                    break;
                case 1:
                    $prefix = __DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/l-';
                    break;
                case 2:
                    $prefix = __DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/t-';
                    break;
                case 3:
                    $prefix = __DIR__ . '/../../../../assets/_images/' . $folderName . '/' . $place->file . '/f-';
                    break;
            }

            $err = uploadCheck($prefix . $pic, "pic", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($prefix . $pic, "pic", "افزودن عکس جدید");
                if (empty($err)) {

                    if(file_exists($prefix . ($idx + 1) . '.jpg'))
                        unlink($prefix . ($idx + 1) . '.jpg');

                    rename($prefix . $pic, $prefix . ($idx + 1) . '.jpg');

                    switch ($idx) {
                        case 0:
                            $place->pic_1 = 1;
                            break;
                        case 1:
                            $place->pic_2 = 1;
                            break;
                        case 2:
                            $place->pic_3 = 1;
                            break;
                        case 3:
                            $place->pic_4 = 1;
                            break;
                        case 4:
                            $place->pic_5 = 1;
                            break;
                    }

                    $place->save();

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

    public function doChangeAltForUserPic() {

        if(isset($_POST["alt"]) && isset($_POST["id"]) && isset($_POST["subject"]) && isset($_POST["category"]) && isset($_POST["confirm"])) {

            $id = makeValidInput($_POST["id"]);
            $alt = makeValidInput($_POST["alt"]);

            $log = LogModel::whereId($id);
            if($log != null) {
                $log->alt = $alt;
                $log->subject = makeValidInput($_POST["subject"]);
                $log->pic = makeValidInput($_POST["category"]);
                $log->confirm = makeValidInput($_POST["confirm"]);
                $log->save();

                return Redirect::route('changeAlt', ['id' => $log->placeId, 'kindPlaceId' => $log->kindPlaceId]);
            }
        }

        return Redirect::route('home');
    }

    public function removeUserPic() {

        if(isset($_POST["id"])) {

            $log = LogModel::whereId(makeValidInput($_POST["id"]));

            if($log != null) {

                $redirect = route('changeAlt', ['id' => $log->placeId, 'kindPlaceId' => $log->kindPlaceId]);

                if(file_exists(__DIR__ . '/../../../../assets/userPhoto/' . getFolderName($log->kindPlaceId) . '/s-' . $log->text)) {
                    unlink(__DIR__ . '/../../../../assets/userPhoto/' . getFolderName($log->kindPlaceId) . '/s-' . $log->text);
                }

                if(file_exists(__DIR__ . '/../../../../assets/userPhoto/' . getFolderName($log->kindPlaceId) . '/l-' . $log->text)) {
                    unlink(__DIR__ . '/../../../../assets/userPhoto/' . getFolderName($log->kindPlaceId) . '/l-' . $log->text);
                }

                $log->delete();

                return Redirect::to($redirect);
            }
        }

        return Redirect::route('home');
    }

    public function doChangeUserPic() {

        if(isset($_POST["id"]) && isset($_POST["idx"]) && isset($_FILES["pic"])) {

            $id = makeValidInput($_POST["id"]);
            $idx = makeValidInput($_POST["idx"]);

            $log = LogModel::whereId($id);
            if($log == null)
                return Redirect::route('home');

            $pic = time() . $_FILES["pic"]["name"];
            if($idx == 0)
                $prefix = __DIR__ . '/../../../../assets/userPhoto/' . getFolderName($log->kindPlaceId) . '/s-';
            else
                $prefix = __DIR__ . '/../../../../assets/userPhoto/' . getFolderName($log->kindPlaceId) . '/l-';

            $err = uploadCheck($prefix . $pic, "pic", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($prefix . $pic, "pic", "افزودن عکس جدید");
                if (empty($err)) {
                    if(file_exists($prefix . $log->text))
                        unlink($prefix . $log->text);

                    rename($prefix . $pic, $prefix . $log->text);
                }
                else {
                    dd($err);
                }
            }
            else {
                dd($err);
            }

            return Redirect::route('changeAlt', ['id' => $log->placeId, 'mode' => $log->kindPlaceId]);
        }

        return Redirect::route('home');
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
                case getValueInfo('amaken'):
                    $place = Amaken::whereId($id);
                    break;
                case getValueInfo('restaurant'):
                    $place = Restaurant::whereId($id);
                    break;
                case getValueInfo('majara'):
                    $place = Majara::whereId($id);
                    break;
                case getValueInfo('adab'):
                    $place = Adab::whereId($id);
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
