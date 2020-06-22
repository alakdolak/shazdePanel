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
        $places = UserAddPlace::where('archive', 0)->get();

        foreach ($places as $place){
            $u = User::find($place->userId);
            if($u == null)
                $u->delete;
            else{
                $kindPlace = Place::find($place->kindPlaceId);
                $place->user = $u;
                $place->kindPlace = $kindPlace;

                if($place->stateId != 0){
                    $place->state = State::find($place->stateId)->name;
                    if(is_numeric($place->city))
                        $place->city = Cities::find($place->city)->name;
                    else
                        $place->newCity = true;
                }

                $place->addPic = false;
                $pics = json_decode($place->pics);

                if($pics != null){
                    foreach ($pics as $pic){
                        if($pic != null) {
                            $place->addPic = true;
                            break;
                        }
                    }
                }
            }
        }

        return view('userContent.addPlace.userAddPlaceList', compact(['places']));
    }

    public function userAddPlaceEdit($id)
    {
        $place = UserAddPlace::find($id);
        $user = User::find($place->userId);

        if($user == null || $place == null)
            return \redirect(route('userAddPlace.list'));

        $kindPlace = Place::find($place->kindPlaceId);

        $allState = State::all();
        $state = State::find($place->stateId);
        $cities = Cities::where('stateId', $place->stateId)->get();
        if(is_numeric($place->city)) {
            $cit = Cities::find($place->city);
            $place->city = $cit->name;
            $place->cityId = $cit->id;
        }
        else {
            $cit = Cities::where('stateId', $place->stateId)->where('name', $place->city)->first();
            if($cit != null) {
                $place->city = $cit->name;
                $place->cityId = $cit->id;
            }
            else{
                $place->newCity = $place->city;
                $place->city = null;
            }
        }

        $place->site = $place->website;
        $place->mobile = $place->phone;
        $place->phone = $place->fixPhone;
        $place->C = $place->lat;
        $place->D = $place->lng;

        $placeFeatures = [];
        $place->feat = json_decode($place->features);
        if(isset($place->feat->featuresId) && count($place->feat->featuresId) > 0)
            $placeFeatures = $place->feat->featuresId;

        $features = PlaceFeatures::where('kindPlaceId', $kindPlace->id)->where('parent', 0)->get();
        foreach ($features as $item) {
            $item->subFeat = PlaceFeatures::where('parent', $item->id)->get();
            $sf = PlaceFeatures::where('parent', $item->id)->pluck('id')->toArray();
        }

        $place->tags = [];

        $mode = $kindPlace->id;

        $place->addPlaceByUser = 1;

        if($kindPlace->id == 1)
            return view('content.editContent.editAmaken', compact(['place', 'mode', 'state', 'cities', 'allState', 'features', 'placeFeatures']));
        elseif($kindPlace->id == 3) {
            if($place->feat->kind == 'fastfood')
                $place->kind_id = 2;
            else
                $place->kind_id = 1;

            return view('content.editContent.editRestaurant', compact(['place', 'mode', 'state', 'cities', 'allState', 'features', 'placeFeatures']));
        }
        elseif($kindPlace->id == 4) {
            $place->kind_id = $place->feat->kind_id;
            $place->rate_int = $place->feat->rate_int;
            return view('content.editContent.editHotels', compact(['place', 'mode', 'state', 'cities', 'allState', 'features', 'placeFeatures']));
        }
        elseif($kindPlace->id == 10) {
            $place->size = $place->feat->size;
            $place->weight = $place->feat->weight;
            $place->price = $place->feat->price;
            $place->eatable = $place->feat->eatable;

            if($place->eatable) {
                $place->torsh = 0;
                $place->shirin = 0;
                $place->talkh = 0;
                $place->shor = 0;
                $place->malas = 0;
                $place->tond = 0;
                foreach ($place->feat->features as $ff){
                    if($ff == 'torsh')
                        $place->torsh = 1;
                    elseif($ff == 'shirin')
                        $place->shirin = 1;
                    elseif($ff == 'talkh')
                        $place->talkh = 1;
                    elseif($ff == 'malas')
                        $place->malas = 1;
                    elseif($ff == 'shor')
                        $place->shor = 1;
                    elseif($ff == 'tond')
                        $place->tond = 1;
                }
            }
            else{
                $place->jewelry = 0;
                $place->cloth = 0;
                $place->decorative = 0;
                $place->applied = 0;
                $place->style = 0;
                $place->fragile = 0;

                foreach ($place->feat->features as $ff){
                    if($ff == 'jewelry')
                        $place->jewelry = 1;
                    elseif($ff == 'cloth')
                        $place->cloth = 1;
                    elseif($ff == 'decorative')
                        $place->decorative = 1;
                    elseif($ff == 'applied')
                        $place->applied = 1;
                    elseif($ff == 'fragile')
                        $place->fragile = 1;

                    elseif(explode('_', $ff)[0] == 'style')
                        $place->style = explode('_', $ff)[1];
                }
            }
//            dd($place);
            return view('content.editContent.editSogatSanaie', compact(['place', 'mode', 'state', 'cities', 'allState', 'features', 'placeFeatures']));
        }
        elseif($kindPlace->id == 11){
            $place->kind = $place->feat->kind;
            $place->recipes = $place->feat->recipes;
            if(isset($place->feat->hotFood) && $place->feat->hotFood == 'hot')
                $place->hotOrCold = 1;
            else
                $place->hotOrCold = 0;

            $place->vegetarian = 0;
            $place->vegan = 0;
            $place->diabet = 0;
            foreach ($place->feat->features as $item){
                if($item == 'vegetarian')
                    $place->vegetarian = 1;
                elseif($item == 'vegan')
                    $place->vegan = 1;
                elseif($item == 'diabet')
                    $place->diabet = 1;
            }

            $place->material = json_decode($place->feat->material);

            return view('content.editContent.editMahaliFood', compact(['place', 'mode', 'state', 'cities', 'allState', 'features', 'placeFeatures']));
        }
        elseif($kindPlace->id == 12) {
            if(isset($place->feat->room_num))
                $place->room_num = $place->feat->room_num;
            return view('content.editContent.editBoomgardy', compact(['place', 'mode', 'state', 'cities', 'allState', 'features', 'placeFeatures']));
        }
    }

    public function userAddPlacePics($id)
    {
        $place = UserAddPlace::find($id);

        $newPic = [];
        $pics = json_decode($place->pics);
        if($pics != null){
            foreach ($pics as $pic){
                if($pic != null) {
                    $picUrl = URL::asset('_images/addPlaceByUser/' . $pic);
                    $t = [
                        'url' => $picUrl,
                        'name' => $pic
                    ];
                    array_push($newPic, $t);
                }
            }
        }

        $place->pics = $newPic;

        return view('userContent.addPlace.userAddPlacePics', compact(['place']));
    }

    public function userAddPlaceDeletePics(Request $request)
    {
        if(isset($request->name) && isset($request->placeId)){
            $place = UserAddPlace::find($request->placeId);
            $pics = json_decode($place->pics);
            $nPic = [];
            foreach ($pics as $pic){
                if($pic != $request->name)
                    array_push($nPic, $pic);
            }
            $place->pics = json_encode($nPic);
            $place->save();

            $location = __DIR__.'/../../../../assets/_images/addPlaceByUser/'.$request->name;
            if(is_file($location))
                unlink($location);

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function userAddPlaceDelete(Request $request)
    {
        if(isset($request->id)){
            $place = UserAddPlace::find($request->id);

            if($place->pics != null) {
                $pics = json_decode($place->pics);
                $location = __DIR__ . '/../../../../assets/_images/addPlaceByUser/';
                foreach ($pics as $item) {
                    if (is_file($location . $item))
                        unlink($location . $item);
                }
            }

            $place->delete();

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

}
