<?php

namespace App\Listeners;

use App\Events\ActivityLogEvent;
use App\models\Activity;
use App\models\ActivityLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityLogListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActivityLogEvent  $event
     * @return void
     */
    public function handle(ActivityLogEvent $event)
    {
        $activity = Activity::where('actualName', $event->activityActualName)->first();
        if($activity != null){
            $activityLog = ActivityLogs::where('activityId', $activity->id)
                                        ->where('userId', $event->userId)
                                        ->where('kindPlaceId', $event->kindPlaceId)
                                        ->where('referenceId', $event->referenceId)
                                        ->first();

            if(($activity->oneTime == 1 && $activityLog == null) || $activity->oneTime == 0){
                $activityLog = new ActivityLogs();
                $activityLog->userId = $event->userId;
                $activityLog->referenceId = $event->referenceId;
                $activityLog->activityId = $activity->id;
                $activityLog->kindPlaceId = $event->kindPlaceId;
                $activityLog->save();
            }
        }
    }
}
