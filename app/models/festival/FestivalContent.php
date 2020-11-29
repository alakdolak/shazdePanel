<?php

namespace App\models\festival;

use Illuminate\Database\Eloquent\Model;

class FestivalContent extends Model
{
    protected $table = 'festivalContents';

    public function festival(){
        return $this->belongsTo(Festival::class, 'festivalId', 'id');
    }

    public function festivalSurveysCount()
    {
        return $this->hasMany(festival\FestivalSurvey::class, 'contentId', 'id')->count();
    }
}
