<?php

namespace App\Http\Controllers;

use App\Events\ActivityLogEvent;
use App\models\Activity;
use App\models\Alert;
use App\models\Amaken;
use App\models\Cities;
use App\models\Hotel;
use App\models\LogFeedBack;
use App\models\LogModel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\PhotographersLog;
use App\models\PhotographersPic;
use App\models\Place;
use App\models\Restaurant;
use App\models\SogatSanaie;
use App\models\State;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class UserContentController extends Controller
{
    public function photographerIndex()
    {
        $photo = PhotographersPic::where('status', 0)->get();
        foreach ($photo as $item){
            $kindPlace = Place::find($item->kindPlaceId);
            $place = \DB::table($kindPlace->tableName)->find($item->placeId);
            $file = $kindPlace->fileName;
            $item->kindPlace = $kindPlace->name;

            $item->placeName = $place->name;
            $item->placeId = $place->id;
            $item->url = 'https://koochita.com/place-details/' . $kindPlace->id. '/' . $place->id;

            $item->city = Cities::find($place->cityId);
            $item->state = State::find($item->city->stateId);

            $item->pics = [
                's' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/s-' . $item->pic),
                'f' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/f-' . $item->pic),
                'l' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/l-' . $item->pic),
                't' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/t-' . $item->pic),
                'mainPic' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/' . $item->pic),
            ];

            $item->uploadDate = convertDate($item->created_at);

            $user = User::find($item->userId);
            $item->userName = $user->username;
        }

        $oldPhoto = PhotographersPic::where('status', 1)->orderBy('created_at', 'DESC')->get();
        foreach ($oldPhoto as $item){

            $kindPlace = Place::find($item->kindPlaceId);
            $place = \DB::table($kindPlace->tableName)->find($item->placeId);
            $file = $kindPlace->fileName;
            $item->kindPlace = $kindPlace->name;

            $item->placeName = $place->name;
            $item->placeId = $place->id;
            $item->url = 'https://koochita.com/place-details/' . $kindPlace->id. '/' . $place->id;

            $item->city = Cities::find($place->cityId);
            $item->state = State::find($item->city->stateId);

            $item->pics = [
                's' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/s-' . $item->pic),
                'f' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/f-' . $item->pic),
                'l' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/l-' . $item->pic),
                't' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/t-' . $item->pic),
                'mainPic' => URL::asset('userPhoto/' . $file . '/' . $place->file . '/' . $item->pic),
            ];

            $item->uploadDate = convertDate($item->created_at);


            $user = User::find($item->userId);
            $item->userName = $user->username;
        }

        return view('userContent.photographer.photographer', compact(['photo', 'oldPhoto']));
    }

    public function photographerDelete(Request $request)
    {
        if(isset($request->id)){
            $photo = PhotographersPic::find($request->id);
            PhotographersPic::deleteWithPic($photo->id);
        }

        return redirect()->back();
    }

    public function photographerSubmit(Request $request)
    {
        if(isset($request->id)) {
            $photo = PhotographersPic::find($request->id);
            $photo->status = 1;
            $photo->save();

            event(new ActivityLogEvent($photo->userId, $photo->id, 'photographerPic', $photo->kindPlaceId));
        }

        return redirect()->back();
    }

    public function quesAnsIndex()
    {
        $questionAct = Activity::where('name', 'سوال')->first();
        $ansActivity = Activity::where('name', 'پاسخ')->first();

        $confirmQuestions = [];
        $newQuestions = [];
        $questionsLog = LogModel::where('activityId', $questionAct->id)->get();
        foreach ($questionsLog as $question) {
            $kindPlace = Place::find($question->kindPlaceId);
            if(isset($kindPlace->tableName)) {
                $place = \DB::table($kindPlace->tableName)->find($question->placeId);
                $uQ = User::find($question->visitorId);

                if ($place != null && $uQ != null) {
                    $question->kindPlaceName = $kindPlace->name;
                    $question->placeName = $place->name;
                    $question->username = $uQ->username;

                    $date = gregorianToJalali($question->date);
                    $question->date = $date[0] . '/' . $date[1] . '/' . $date[2];

                    if($question->confirm == 1)
                        array_push($confirmQuestions, $question);
                    else
                        array_push($newQuestions, $question);
                } else
                    $question->delete();
            }
            else
                $question->delete();
        }

        $confirmAnswer = [];
        $newAnswer = [];
        $answerLog = LogModel::where('activityId', $ansActivity->id)->get();
        foreach ($answerLog as $ans){
            $topAct = LogModel::find($ans->relatedTo);
            while($topAct != null && $topAct->activityId == $ansActivity->id)
                $topAct = LogModel::find($topAct->relatedTo);


            if($topAct == null)
                continue;

            if($topAct->activityId == $questionAct->id){
                $kindPlace = Place::find($ans->kindPlaceId);
                if(isset($kindPlace->tableName)) {
                    $place = \DB::table($kindPlace->tableName)->find($ans->placeId);
                    $uQ = User::find($ans->visitorId);

                    if ($place != null && $uQ != null) {
                        $ans->kindPlaceName = $kindPlace->name;
                        $ans->placeName = $place->name;
                        $ans->username = $uQ->username;

                        $date = gregorianToJalali($ans->date);
                        $ans->date = $date[0] . '/' . $date[1] . '/' . $date[2];

                        if($ans->confirm == 1)
                            array_push($confirmAnswer, $ans);
                        else
                            array_push($newAnswer, $ans);
                    }
                    else
                        $ans->delete();
                }
                else
                    $ans->delete();
            }
        }

        return view('userContent.quesAns', compact(['confirmQuestions', 'newQuestions', 'confirmAnswer', 'newAnswer']));
    }

    public function quesAnsSubmit(Request $request)
    {
        if(isset($request->id)){
            $log = LogModel::find($request->id);
            $log->confirm = 1;
            $log->save();

            $relatedLog = LogModel::find($log->relatedTo);

            $ansAct = Activity::where('name', 'پاسخ')->first();
            if($log->activityId == $ansAct->id) {
                $alert = new Alert();
                $alert->userId = $relatedLog->visitorId;
                $alert->subject = 'ansAns';
                $alert->referenceTable = 'log';
                $alert->referenceId = $log->id;
                $alert->save();
            }

            echo  json_encode(['status' => 'ok']);
        }
        else
            echo  json_encode(['status' => 'nok']);
    }

    public function quesAnsDelete(Request $request)
    {
        if(isset($request->id)){
            $log = LogModel::find($request->id);
            if($log != null) {
                $kindPlace = Place::find($log->kindPlaceId);

                $act = Activity::find($log->activityId);
                if($act->name == 'سوال')
                    $subject = 'deleteQues';
                else if($act->name == 'پاسخ')
                    $subject = 'deleteAns';

                $alert = new Alert();
                $alert->subject = $subject;
                $alert->referenceTable = $kindPlace->tableName;
                $alert->referenceId = $log->placeId;
                $alert->userId = $log->visitorId;
                $alert->save();

                $this->deleteRelated($request->id);
                echo  json_encode(['status' => 'ok']);
            }
            else
                echo  json_encode(['status' => 'nok1']);
        }
        else
            echo  json_encode(['status' => 'nok']);
    }

    private function deleteRelated($id){
        $log = LogModel::find($id);
        if($log != null){
            LogFeedBack::where('logId', $log->id)->delete();
            $logRelated = LogModel::where('relatedTo', $log->id)->get();
            foreach ($logRelated as $item)
                $this->deleteRelated($item->id);

            $log->delete();
        }

        return;
    }

}
