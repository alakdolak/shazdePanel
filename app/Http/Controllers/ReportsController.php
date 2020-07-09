<?php

namespace App\Http\Controllers;

use App\models\Activity;
use App\models\Alert;
use App\models\LogModel;
use App\models\Report;
use App\models\ReportsType;
use App\models\Place;
use App\models\ReviewPic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportsController extends Controller
{
    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('config.reports', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $reports = ReportsType::where('kindPlaceId', $kind->id)->get();
            return view('config.reports', compact(['kind', 'reports']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $reports= ReportsType::where('description', $request->description)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($reports == null){
            $reports =  new ReportsType();
            $reports->description = $request->description;
            $reports->kindPlaceId = $request->kindPlaceId;
            $reports->save();
        }
        else{
            Session::flash('error', 'این گزارش  قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'id' => 'required'
        ]);

        $reports = ReportsType::find($request->id);

        if($reports != null){
            $checkReportsType = ReportsType::where('description', $request->description)->first();
            if($checkReportsType == null) {
                $reports->description = $request->description;
                $reports->save();
            }
            else{
                Session::flash('error', 'این گزارش  موجود می باشد');
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

        ReportsType::find($request->id)->delete();

        return redirect()->back();
    }

    public function userReport()
    {
        $reports = [];
        $activityId = Activity::where('name', 'گزارش')->first()->id;
        $reviewActivityId = Activity::where('name', 'نظر')->first()->id;
        $logs = LogModel::where('activityId', $activityId)->where('confirm', 0)->get();
        foreach ($logs as $log){
            $refLog = LogModel::find($log->relatedTo);
            if($refLog == null){
                $log->delete();
                Report::where('logId', $log->id)->delete();
                continue;
            }

            $report = Report::where('logId', $log->id)->first();
            if($report != null){
                $reportType = ReportsType::find($report->reportsTypeId);
                if($reportType != null)
                    $log->text = $reportType->description;
                else
                    $log->text = 'متن گزارش از دیتابیس پاک شده است';
            }
            else if($log->text == '')
                $log->text = 'کاربر برای این پست سایر موارد را زده اما متنی وارد نکرده است.';

            if($refLog->activityId == $reviewActivityId){
                $log->ref = 'review';
                $log->refName = 'نقد';
                $log->logTxt = $refLog->text;
                $reviewPic = ReviewPic::where('logId', $refLog->id)->where('isVideo', 0)->get();
                $reviewVideo = ReviewPic::where('logId', $refLog->id)->where('isVideo', 1)->get();
                $reviewKindPlaceId = Place::find($refLog->kindPlaceId);
                $reviewPlace = \DB::table($reviewKindPlaceId->tableName)->find($refLog->placeId);
                $picss = [];
                $videoss = [];
                foreach ($reviewPic as $pic) {
                    $pic->pic = \URL::asset('userPhoto/' . $reviewKindPlaceId->tableName . '/' . $reviewPlace->file . '/' . $pic->pic);
                    array_push($picss, $pic->pic);
                }
                foreach ($reviewVideo as $video) {
                    $video->pic = \URL::asset('userPhoto/' . $reviewKindPlaceId->tableName . '/' . $reviewPlace->file . '/' . $video->pic);
                    array_push($videoss, $video->pic);
                }

                $log->pics = $picss;
                $log->Video = $videoss;
                $log->reviewUser = User::find($refLog->visitorId)->username;

                $log->placeName = $reviewPlace->name;
                $log->dateTime = gregorianToJalali($log->date)[0] .'/'. gregorianToJalali($log->date)[1] . '/' . gregorianToJalali($log->date)[2] . '  ' . substr($log->time, 0, 2) . ':' . substr($log->time, 2, 2);
            }

            $log->username = User::find($log->visitorId)->username;

            array_push($reports, $log);
        }
//dd($reports);
        return view('userContent.reports.userReports', compact(['reports']));
    }

    public function userConfirmReport(Request $request)
    {
        $log = LogModel::find($request->id);
        if($log != null){
            $log->confirm = 1;
            $log->save();

            $alert = new Alert();
            $alert->userId = $log->visitorId;
            $alert->subject = 'confirmReport';
            $alert->referenceTable = 'log';
            $alert->referenceId = $log->id;
            $alert->seen = 0;
            $alert->click = 0;
            $alert->save();

            echo 'ok';
        }
        else
            echo 'nok1';

        return;
    }
}
