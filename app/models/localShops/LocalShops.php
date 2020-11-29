<?php

namespace App\models\localShops;

use App\models\Activity;
use App\models\LogModel;
use Illuminate\Database\Eloquent\Model;

class LocalShops extends Model
{
    protected $table = 'localShops';

    public function getCategory()
    {
        return $this->belongsTo(LocalShopsCategory::class, 'categoryId', 'id');
    }

    public function getPictures(){
        $pictures = $this->hasMany(LocalShopsPictures::class, 'localShopId', 'id')
                            ->get();
        foreach ($pictures as $pic)
            $pic->pic = [
                'main' => \URL::asset('_images/localShops/'.$this->id.'/'.$pic->pic),
                's' => \URL::asset('_images/localShops/'.$this->id.'/s-'.$pic->pic),
                'f' => \URL::asset('_images/localShops/'.$this->id.'/f-'.$pic->pic),
                'l' => \URL::asset('_images/localShops/'.$this->id.'/l-'.$pic->pic),
                't' => \URL::asset('_images/localShops/'.$this->id.'/t-'.$pic->pic),
            ];

        return $pictures;
    }

    public function getMainPicture()
    {
        $pictures = $this->hasMany(LocalShopsPictures::class, 'localShopId', 'id')
                        ->where('isMain', 1)
                        ->first();
        $pictures->pic = [
            'main' => \URL::asset('_images/localShops/'.$this->id.'/'.$pictures->pic),
            's' => \URL::asset('_images/localShops/'.$this->id.'/s-'.$pictures->pic),
            'f' => \URL::asset('_images/localShops/'.$this->id.'/f-'.$pictures->pic),
            'l' => \URL::asset('_images/localShops/'.$this->id.'/l-'.$pictures->pic),
            't' => \URL::asset('_images/localShops/'.$this->id.'/t-'.$pictures->pic),
        ];

        return $pictures;
    }

    public function getReviews(){
        $reviewAct = Activity::where('name', 'نظر')->first();
        $reviews = $this->hasMany(LogModel::class, 'placeId', 'id')
                        ->where('kindPlaceId', 13)
                        ->where('activityId', $reviewAct->id)
                        ->where(function ($query){
                            if(auth()->check())
                                $query->where('confirm', 1)->orWhere('visitorId', auth()->user()->id);
                            else
                                $query->where('confirm', 1);
                        })
                        ->orderByDesc('date')->get();

        foreach ($reviews as $item)
            $item = \reviewTrueType($item);

        return $reviews;
    }

}
