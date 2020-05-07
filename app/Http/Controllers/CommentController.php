<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Adab;
use App\models\AdminLog;
use App\models\Amaken;
use App\models\Cities;
use App\models\Comment;
use App\models\Hotel;
use App\models\LogModel;
use App\models\Majara;
use App\models\PicItem;
use App\models\Place;
use App\models\PlaceFeatureRelation;
use App\models\PlaceFeatures;
use App\models\PlaceStyle;
use App\models\Post;
use App\models\PostComment;
use App\models\Restaurant;
use App\models\State;
use App\models\User;
use App\models\UserAddPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CommentController extends Controller {

    public function listComments()
    {
        $activityId = Activity::where('name', 'پاسخ')->first();

        $nLogsComment = LogModel::where('confirm', 0)->where('activityId', $activityId->id)->orderBy('date', 'DESC')->get();
        foreach ($nLogsComment as $item){
            $date = gregorianToJalali($item->date);
            $item->date = $date[0] . '/' . $date[1] . '/' . $date[2];
            $u = User::find($item->visitorId);
            if($u == null) {
                $item->delete();
                continue;
            }
            else
                $item->username = $u->username;

            $kindPlace = Place::find($item->kindPlaceId);
            $place = DB::table($kindPlace->tableName)->find($item->placeId);

            if($kindPlace == null || $place == null){
                $item->delete();
                continue;
            }
            else {
                $item->show = true;
                $item->kindName = $kindPlace->name;
                $item->place = $place->name;
                $item->url = 'https://koochita.com/show-place-details/' . $kindPlace->fileName . '/' . $place->slug;
            }
        }

        $nPostComment = PostComment::where('status', 0)->get();
        foreach ($nPostComment as $item) {
            $date = explode(' ', $item->created_at)[0];
            $date = gregorianToJalali($date);
            $item->date = $date[0] . '/' . $date[1] . '/' . $date[2];

            $u = User::find($item->userId);
            if($u == null) {
                $item->delete();
                continue;
            }
            else
                $item->username = $u->username;

            $post = Post::find($item->postId);
            if($post == null)
                $item->delete();
            else
                $item->post = $post->slug;
        }


        $logsComment = LogModel::where('confirm', 1)->where('activityId', $activityId->id)->orderBy('date', 'DESC')->get();
        foreach ($logsComment as $item){
            $date = gregorianToJalali($item->date);
            $item->date = $date[0] . '/' . $date[1] . '/' . $date[2];
            $u = User::find($item->visitorId);
            if($u == null) {
                $item->delete();
                continue;
            }
            else
                $item->username = $u->username;

            $kindPlace = Place::find($item->kindPlaceId);
            $place = DB::table($kindPlace->tableName)->find($item->placeId);

            if($kindPlace == null || $place == null){
                $item->delete();
                continue;
            }
            else {
                $item->show = true;
                $item->kindName = $kindPlace->name;
                $item->place = $place->name;
                $item->url = 'https://koochita.com/show-place-details/' . $kindPlace->fileName . '/' . $place->slug;
            }
        }

        $postComment = PostComment::where('status', 1)->get();
        foreach ($postComment as $item) {
            $date = explode(' ', $item->created_at)[0];
            $date = gregorianToJalali($date);
            $item->date = $date[0] . '/' . $date[1] . '/' . $date[2];

            $u = User::find($item->userId);
            if($u == null) {
                $item->delete();
                continue;
            }
            else
                $item->username = $u->username;

            $post = Post::find($item->postId);
            if($post == null)
                $item->delete();
            else
                $item->post = $post->slug;
        }

        return view('/userContent/comments/newComments', compact(['nPostComment', 'nLogsComment', 'logsComment', 'postComment']));

    }

    public function submitComment(Request $request)
    {
        if(isset($request->kind) && isset($request->id)){
            if($request->kind == 'article'){
                $com = PostComment::find($request->id);
                $com->status = 1;
                $com->save();
            }
            else if($request->kind == 'log'){
                $com = LogModel::find($request->id);
                $com->confirm = 1;
                $com->save();
            }
            else
                echo json_encode(['status' => 'nok1']);

            $activityId = Activity::where('name', 'پاسخ')->first();
            $sum = 0;
            $sum += LogModel::where('confirm', 0)->where('activityId', $activityId->id)->count();
            $sum += PostComment::where('status', 0)->count();
            echo json_encode(['status' => 'ok', 'result' => $sum]);
        }
        else
            echo json_encode(['status' => 'nok0']);

        return;
    }

    public function deleteComment(Request $request)
    {
        if(isset($request->kind) && isset($request->id)){
            if($request->kind == 'article'){
                $com = PostComment::find($request->id);
                if($com->haveAns == 1)
                    $this->deleteRelatedComment('article', $com->id);
                $com->delete();
            }
            else if($request->kind == 'log'){
                $com = LogModel::find($request->id);
                if($com->subject == 'ans')
                    $this->deleteRelatedComment('log', $com->id);
                $com->delete();
            }
            else
                echo json_encode(['status' => 'nok1']);

            $activityId = Activity::where('name', 'پاسخ')->first();
            $sum = 0;
            $sum += LogModel::where('confirm', 0)->where('activityId', $activityId->id)->count();
            $sum += PostComment::where('status', 0)->count();
            echo json_encode(['status' => 'ok', 'result' => $sum]);
        }
        else
            echo json_encode(['status' => 'nok0']);

        return ;
    }
    private function deleteRelatedComment($type, $id){

        if($type == 'article'){
            $coms = PostComment::where('ansTo', $id)->get();
            foreach ($coms as $com){
                if($com->haveAns == 1)
                    $this->deleteRelatedComment($type, $com->id);
                $com->delete();
            }
        }
        else if($type == 'log'){
            $coms = LogModel::where('relatedTo', $id)->get();
            foreach ($coms as $com) {
                if ($com->subject == 'ans')
                    $this->deleteRelatedComment($type, $com->id);
                $com->delete();
            }
        }

        return;
    }


    public function lastActivities() {
        return view('content.user.controlContent', ['activities' => Activity::whereControllerNeed(true)->get()]);
    }

    public function posts($confirm) {

        $confirm = ($confirm) ? "1" : "0";

        $posts = DB::select('select pc.id, p.title, pc.msg, u.username, pc.created_at, pc.status from users u, post p, postComment pc WHERE u.id = pc.userId and p.id = pc.postId and pc.status = ' . $confirm . ' ORDER by created_at DESC');
        $dates = [];
        $counter = 0;

        foreach ($posts as $post) {
            $post->created_at = convertDate($post->created_at);
            $dates[$counter++] = $post->created_at;
        }

        return view('content.user.controlPostComment', ['activityName' => 'دیدگاه های پست', 'logs' => $posts,
            'confirm' => $confirm, 'dates' => $dates
        ]);
    }

    public function controlActivityContent($activityId, $confirm = false) {

        if($activityId == "post")
            return $this->posts($confirm);

        $activity = Activity::whereId($activityId);

        if($activity == null)
            return Redirect::route('controlContent');

        $logs = LogModel::whereActivityId($activityId)->whereConfirm($confirm)->paginate(20);

        foreach ($logs as $log) {

            $delete = false;

            $log->visitorId = User::whereId($log->visitorId)->username;

            switch ($log->kindPlaceId) {
                case getValueInfo('amaken'):
                default:
                    $place = Amaken::whereId($log->placeId);
                    break;
                case getValueInfo('restaurant'):
                    $place = Restaurant::whereId($log->placeId);
                    break;
                case getValueInfo('hotel'):
                    $place = Hotel::whereId($log->placeId);
                    break;
                case getValueInfo('majara'):
                    $place = Majara::whereId($log->placeId);
                    break;
                case getValueInfo('adab'):
                    $place = Adab::whereId($log->placeId);
                    break;
            }

            $log->name = $place->name;
            $log->date = convertDate($log->date);

            if($activity->name == "پاسخ") {
                $ans = LogModel::whereId($log->relatedTo);
                if($ans == null)
                    $delete = true;
                else
                    $log->relatedTo = $ans->text;
            }
            else if($activity->name == "نظر") {
                $comment = Comment::whereLogId($log->id)->first();
                if($comment == null)
                    $delete = true;

                if(!$delete) {
                    $comment->placeStyleId = PlaceStyle::whereId($comment->placeStyleId)->name;
                    $log->comment = $comment;
                }
            }
            else if($activity->name == "عکس") {
                switch ($log->kindPlaceId) {
                    case getValueInfo('amaken'):
                    default:
                        $log->userPic = URL::asset('userPhoto/amaken/l-' . $log->text);
                        if(!file_exists(__DIR__ . '/../../../../assets/userPhoto/amaken/l-' . $log->text))
                            $delete = true;
                        break;
                    case getValueInfo('restaurant'):
                        $log->userPic = URL::asset('userPhoto/restaurant/l-' . $log->text);
                        if(!file_exists(__DIR__ . '/../../../../assets/userPhoto/restaurant/l-' . $log->text))
                            $delete = true;
                        break;
                    case getValueInfo('hotel'):
                        $log->userPic = URL::asset('userPhoto/hotels/l-' . $log->text);
                        if(!file_exists(__DIR__ . '/../../../../assets/userPhoto/hotels/l-' . $log->text))
                            $delete = true;
                        break;
                    case getValueInfo('majara'):
                        $log->userPic = URL::asset('userPhoto/majara/l-' . $log->text);
                        if(!file_exists(__DIR__ . '/../../../../assets/userPhoto/majara/l-' . $log->text))
                            $delete = true;
                        break;
                    case getValueInfo('adab'):
                        $log->userPic = URL::asset('userPhoto/adab/l-' . $log->text);
                        if(!file_exists(__DIR__ . '/../../../../assets/userPhoto/adab/l-' . $log->text))
                            $delete = true;
                        break;
                }

                $log->photoCategory = PicItem::find($log->pic)->name;
            }

            $log->kindPlaceName = Place::whereId($log->kindPlaceId)->name;

            if($delete)
                $log->delete();
        }

        return view('content.user.controlActivityContent', ['activityName' => $activity->name, 'logs' => $logs,
            'activityId' => $activityId, 'confirm' => $confirm
        ]);
    }

    public function unSubmitLogs() {

        if(isset($_POST["logs"])) {

            $logs = $_POST["logs"];

            if(isset($_POST["activity"]) && $_POST["activity"] == "post") {
                try {
                    DB::transaction(function () use ($logs) {
                        foreach ($logs as $log) {

                            $tmp = PostComment::whereId(makeValidInput($log));

                            if ($tmp == null)
                                continue;

                            $tmp->status = 0;
                            $tmp->save();

                            $tmp2 = new AdminLog();
                            $tmp2->uId = Auth::user()->id;
                            $tmp2->mode = getValueInfo('unSubmitPost');
                            $tmp2->additional1 = $tmp->id;
                            $tmp2->save();
                        }

                        echo "ok";
                    });
                } catch (\Exception $x) {
                    echo $x->getMessage();
                }
            }
            else {
                try {
                    DB::transaction(function () use ($logs) {
                        foreach ($logs as $log) {
                            $tmp = LogModel::whereId(makeValidInput($log));
                            if($tmp == null)
                                continue;
                            $tmp->confirm = false;
                            $tmp->save();

                            $tmp2 = new AdminLog();
                            $tmp2->uId = Auth::user()->id;
                            $tmp2->mode = getValueInfo('unSubmitLog');
                            $tmp2->additional1 = $tmp->id;
                            $tmp2->save();
                        }

                        echo "ok";
                    });
                }
                catch (\Exception $x) {
                    echo $x->getMessage();
                }
            }
        }
    }

    public function submitLogs() {

        if(isset($_POST["logs"])) {

            $logs = $_POST["logs"];

            if(isset($_POST["activity"]) && $_POST["activity"] == "post") {
                try {
                    DB::transaction(function () use ($logs) {
                        foreach ($logs as $log) {

                            $tmp = PostComment::whereId(makeValidInput($log));

                            if ($tmp == null)
                                continue;

                            $tmp->status = 1;
                            $tmp->save();

                            $tmp2 = new AdminLog();
                            $tmp2->uId = Auth::user()->id;
                            $tmp2->mode = getValueInfo('submitPost');
                            $tmp2->additional1 = $tmp->id;
                            $tmp2->save();
                        }

                        echo "ok";
                    });
                } catch (\Exception $x) {
                    echo $x->getMessage();
                }
            }
            else {
                try {
                    DB::transaction(function () use ($logs) {
                        foreach ($logs as $log) {
                            $tmp = LogModel::whereId(makeValidInput($log));
                            if ($tmp == null)
                                continue;
                            $tmp->confirm = 1;
                            $tmp->save();

                            $tmp2 = new AdminLog();
                            $tmp2->uId = Auth::user()->id;
                            $tmp2->mode = getValueInfo('submitLog');
                            $tmp2->additional1 = $tmp->id;
                            $tmp2->save();
                        }

                        echo "ok";
                    });
                } catch (\Exception $x) {
                    echo $x->getMessage();
                }
            }
        }
    }

    public function changeUserContent() {

        if(isset($_POST["logId"]) && isset($_POST["content"])) {

            $log = LogModel::whereId(makeValidInput($_POST["logId"]));

            if($log != null) {
                $log->text = makeValidInput($_POST["content"]);
                $log->save();

                $tmp = new AdminLog();
                $tmp->uId = Auth::user()->id;
                $tmp->mode = getValueInfo('changeUserContent');
                $tmp->additional1 = $log->id;
                $tmp->save();
            }

        }
    }

    public function deleteLogs() {

        if(isset($_POST["logs"])) {

            $logs = $_POST["logs"];

            try {
                DB::transaction(function () use ($logs) {

                    foreach ($logs as $log) {
                        $tmp = LogModel::whereId(makeValidInput($log));
                        if($tmp == null)
                            continue;
                        switch ($tmp->activitiId) {
                            case "عکس":
                                switch ($tmp->kindPlaceId) {
                                    case getValueInfo('amaken'):
                                    default:
                                        $userPic = URL::asset('userPhoto/amaken/l-' . $tmp->text);
                                        break;
                                    case getValueInfo('restaurant'):
                                        $userPic = URL::asset('userPhoto/restaurant/l-' . $tmp->text);
                                        break;
                                    case getValueInfo('hotel'):
                                        $userPic = URL::asset('userPhoto/hotels/l-' . $tmp->text);
                                        break;
                                    case getValueInfo('majara'):
                                        $userPic = URL::asset('userPhoto/majara/l-' . $tmp->text);
                                        break;
                                    case getValueInfo('adab'):
                                        $userPic = URL::asset('userPhoto/adab/l-' . $tmp->text);
                                        break;
                                }
                                if(file_exists($userPic))
                                    unlink($userPic);
                                break;
                            case "سوال":
                                LogModel::whereRelatedTo($tmp->id)->delete();
                                break;
                            case "نظر":
                                Comment::whereLogId($tmp->id)->delete();
                                break;
                        }

                        $tmp2 = new AdminLog();
                        $tmp2->uId = Auth::user()->id;
                        $tmp2->mode = getValueInfo('deleteLog');
                        $tmp2->additional1 = $tmp->id;
                        $tmp2->save();

                        $tmp->delete();
                    }

                    echo "ok";
                });
            }
            catch (\Exception $x) {}
        }
    }


    public function userAddPlaceList()
    {
        $showPlaces = [];
        $places = UserAddPlace::all();

        foreach ($places as $place){

            $u = User::find($place->userId);
            if($u == null)
                $u->delete;
            else{
                $kindPlace = Place::find($place->kindPlaceId);
                $place->kindPlace = $kindPlace->name;
                $place->state = State::find($place->stateId);
                if(is_numeric($place->city))
                    $place->city = Cities::find($place->city)->name;
                else
                    $place->newCity = true;

                $addPic = [];
                $pics = json_decode($place->pics);

                if($pics != null){
                    foreach ($pics as $pic){
                        if($pic != null) {
                            $pic = URL::asset('_images/addPlaceByUser/' . $pic);
                            array_push($addPic, $pic);
                        }
                    }
                }

                $place->addPic = $addPic;

                $feat = [];
                $features = json_decode($place->features);

                if($place->kindPlaceId != 10 && $place->kindPlaceId != 11){
                    $featuresId = $features->featuresId;
                    $tfeatures = PlaceFeatures::where('kindPlaceId', $kindPlace->id)->where('parent', 0)->get();
                    foreach ($tfeatures as $item)
                        $item->sub = PlaceFeatures::whereIn('id', $featuresId)->where('parent', $item->id)->pluck('name')->toArray();
                }

                if ($place->kindPlaceId == 3){
//                    if(isset($features->kind_id)) {
//                        array_push($feat, ['نوع اقامتگاه', $kid]);
//                    }
                }
                elseif ($place->kindPlaceId == 4){
                    if(isset($features->kind_id)) {
                        switch ($features->kind_id) {
                            case 1:
                                $kid = 'هتل';
                                break;
                            case 2:
                                $kid = 'هتل آپارتمان';
                                break;
                            case 3:
                                $kid = 'مهمان سرا';
                                break;
                            case 4:
                                $kid = 'ویلا';
                                break;
                            case 5:
                                $kid = 'متل';
                                break;
                            case 6:
                                $kid = 'مجتمع تفریحی';
                                break;
                            case 7:
                                $kid = 'پانسیون';
                                break;
                        }
                        array_push($feat, ['نوع اقامتگاه', $kid]);
                    }

                    if(isset($features->rate_int))
                        array_push($feat, ['درجه هتل', $features->rate_int]);
                }
                elseif ($place->kindPlaceId == 10){
                    if(isset($features->size))
                        switch ($features->size) {
                            case 1:
                                array_push($feat, ['ابعاد', 'کوچک']);
                                break;
                            case 2:
                                array_push($feat, ['ابعاد', 'متوسط']);
                                break;
                            case 3:
                                array_push($feat, ['ابعاد', 'بزرگ']);
                                break;
                        }

                    if(isset($features->weight))
                        switch ($features->weight){
                        case 1:
                            array_push($feat, ['وزن', 'سبک']);
                            break;
                        case 2:
                            array_push($feat, ['وزن', 'متوسط']);
                            break;
                        case 3:
                            array_push($feat, ['وزن', 'سنگین']);
                            break;
                    }

                    if(isset($features->price))
                        switch ($features->price){
                        case 1:
                            array_push($feat, ['کلاس قیمتی', 'ارزان']);
                            break;
                        case 2:
                            array_push($feat, ['کلاس قیمتی', 'متوسط']);
                            break;
                        case 3:
                            array_push($feat, ['کلاس قیمتی', 'گران']);
                            break;

                    }

                    if($features->eatable == 1){
                        $enFeat = ['torsh', 'shirin', 'talkh', 'malas', 'shor', 'tond'];
                        $faFeat = ['ترش', 'شیرین', 'بلخ', 'ملس', 'شور', 'تند'];
                        $imploaded = implode(',', $features->features);
                        $n = str_replace($enFeat, $faFeat, $imploaded);
                        array_push($feat, ['مزه', $n]);
                    }
                    else{
                        $enFeat = ['jewelry', 'cloth', 'decorative', 'applied', 'style_1', 'style_2', 'style_3', 'fragile'];
                        $faFeat = ['زیورآلات', 'پارچه و پوشیدنی', 'لوازم تزئینی', 'لوازم کاربردی منزل', 'سنتی', 'مدرن', 'تلفیقی', 'شکستنی'];
                        $imploaded = implode(',', $features->features);
                        $n = str_replace($enFeat, $faFeat, $imploaded);
                        array_push($feat, ['ویژگی ها', $n]);
                    }
                }
                elseif ($place->kindPlaceId == 11){
                    if(isset($features->kind))
                        switch ($features->kind){
                        case 1:
                            array_push($feat, ['کلاس غذا', 'چلوخورش']);
                            break;
                        case 2:
                            array_push($feat, ['کلاس غذا', 'خوراک']);
                            break;
                        case 3:
                            array_push($feat, ['کلاس غذا', 'سالاد و پیش غذا']);
                            break;
                        case 4:
                            array_push($feat, ['کلاس غذا', 'ساندویچ']);
                            break;
                        case 5:
                            array_push($feat, ['کلاس غذا', 'کباب']);
                            break;
                        case 6:
                            array_push($feat, ['کلاس غذا', 'دسر']);
                            break;
                        case 7:
                            array_push($feat, ['کلاس غذا', 'نوشیدنی']);
                            break;
                        case 8:
                            array_push($feat, ['کلاس غذا', 'سوپ و آش']);
                            break;
                    }

                    if(isset($features->hotFood)){
                        if($features->hotFood == 'hot')
                            array_push($feat, ['گرم یا سرد', 'گرم']);
                        else
                            array_push($feat, ['گرم یا سرد', 'سرد']);
                    }
                    if(isset($features->material)){
                        $material = json_decode($features->material);
                        $text = '<ul>';
                        for($i = 0; $i < count($material); $i++){
                            $text .= '<li>' . $material[$i][0] . ' : ' . $material[$i][1] . '</li>';
                        }
                        $text .= '</ul>';
                        array_push($feat, ['مواد لازم', $text]);
                    }

                    if(isset($features->recipes))
                        array_push($feat, ['دستور پخت', $features->recipes]);

                    $enFeat = ['vegetarian', 'vegan', 'diabet'];
                    $faFeat = ['گیاهخواران', 'وگان', 'دیابت'];
                    $imploaded = implode(',', $features->features);
                    $n = str_replace($enFeat, $faFeat, $imploaded);
                    array_push($feat, ['مناسب برای', $n]);

                }
                elseif ($place->kindPlaceId == 12){
                    if(isset($features->room_num))
                        array_push($feat, ['تعداد اتاق', $features->room_num]);
                }

                $show = [
                    [
                        'نام',
                        $place->name
                    ],
                    [
                        'نوع',
                        $kindPlace->name
                    ],
                    [
                        'نام کاربری',
                        $u->username
                    ],
                    [
                        'شماره تماس کاربر',
                        $u->phone
                    ],
                    [
                        'استان',
                        $place->state->name
                    ],
                    [
                        'شهر',
                        $place->city
                    ],
                    [
                        'آدرس',
                        $place->address
                    ],
                    [
                        'lat',
                        $place->lat
                    ],
                    [
                        'lng',
                        $place->lng
                    ],
                    [
                        'تماس ثابت',
                        $place->fixPhone
                    ],
                    [
                        'تماس همراه',
                        $place->phone
                    ],
                    [
                        'ایمیل',
                        $place->email
                    ],
                    [
                        'وب سایت',
                        $place->website
                    ],
                    [
                        'توضیحات',
                        $place->website
                    ],
                    [
                        'امکانات اصلی',
                        $tfeatures
                    ],
                    [
                        'امکانات',
                        $feat
                    ]
                ];
                array_push($showPlaces, $show);
            }
        }

//        foreach ($showPlaces as $place){
//            foreach ($place as $item){
//                echo $item[0] . '<br>';
//            }
//        }

        return view('userContent.addPlace.userAddPlaceList', compact(['showPlaces']));
    }

}
