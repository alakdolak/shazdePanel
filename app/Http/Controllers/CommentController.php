<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Adab;
use App\models\AdminLog;
use App\models\Amaken;
use App\models\Comment;
use App\models\Hotel;
use App\models\LogModel;
use App\models\Majara;
use App\models\PicItem;
use App\models\Place;
use App\models\PlaceStyle;
use App\models\PostComment;
use App\models\Restaurant;
use App\models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CommentController extends Controller {

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
    
}
