<?php

namespace App\Http\Controllers;

use App\models\Cities;
use App\models\localShops\LocalShops;
use App\models\localShops\LocalShopsCategory;
use App\models\localShops\LocalShopsPictures;
use App\models\Place;
use App\models\PlaceTag;
use App\models\State;
use App\models\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use League\Flysystem\Adapter\Local;

class LocalShopController extends Controller
{
    public function localshopList()
    {
        return view('LocalShops.localShopList');
    }

    public function localShopEditPage($id = 0)
    {
        $localShop = LocalShops::find($id);
        if($localShop == null)
            return redirect(route('localShop.list'));

        $loclCateg = LocalShopsCategory::find($localShop->categoryId);
        $localShop->categoryName = $loclCateg == null ? '' : $loclCateg->name;
        $localShop->categoryParent = $loclCateg == null ? 0 : $loclCateg->parentId;

        $city = Cities::find($localShop->cityId);
        $localShop->cityName = $city != null ? $city->name : '';
        $localShop->stateId = $city != null ? $city->stateId : 0;

        $setKindPlace = false;
        $relKindPlace = Place::find($localShop->kindPlaceId);
        if($relKindPlace != null){
            $relPlace = \DB::table($relKindPlace->tableName)->find($localShop->placeId);
            if($relPlace != null){
                $localShop->placeName = $relPlace->name;
                $localShop->placeId = $relPlace->id;
                $localShop->kindPlaceId = $relKindPlace->id;
            }
            else
                $setKindPlace = true;
        }
        else
            $setKindPlace = true;

        if($setKindPlace){
            $localShop->placeId = 0;
            $localShop->placeName = '';
            $localShop->kindPlaceId = 0;
        }

        $localShop->tags = PlaceTag::where('placeTags.kindPlaceId', 13)
                                    ->where('placeTags.placeId', $localShop->id)
                                    ->join('tag', 'tag.id', 'placeTags.tagId')
                                    ->select(['placeTags.id', 'tag.name'])->get();

        $categories = LocalShopsCategory::where('parentId', 0)->get();
        foreach ($categories as $item)
            $item->sub = LocalShopsCategory::where('parentId', $item->id)->get();

        $owner = User::find($localShop->userId);
        $localShop->ownerUserName = $owner != null ? $owner->username : '';

        $kindPlaces = Place::whereNotNull('tableName')->get();
        $states = State::all();

        return view('LocalShops.localShopEdit', compact(['localShop', 'categories', 'kindPlaces', 'states']));
    }

    public function getAllLocalShops()
    {
        $localShops = LocalShops::leftJoin('users', 'users.id', 'localShops.userId')
                                ->leftJoin('localShopsCategories', 'localShopsCategories.id', 'localShops.categoryId')
                                ->leftJoin('cities', 'cities.id', 'localShops.cityId')
                                ->select(['localShops.*', 'users.username', 'localShopsCategories.name AS categoryName', 'cities.name AS cityName'])
                                ->get()->groupBy('confirm');

        if(!isset($localShops[0]))
            $localShops[0] = [];
        if(!isset($localShops[1]))
            $localShops[1] = [];

        return response()->json(['status' => 'ok', 'result' => $localShops]);
    }

    public function changeConfirmLocalShop(Request $request)
    {
        if(isset($request->id) && isset($request->kind)){
            $localShop = LocalShops::find($request->id);
            if($localShop != null){
                $localShop->confirm = $request->kind;
                $localShop->save();
                return response()->json(['status' => 'ok']);
            }
            else
                return response()->json(['status' => 'error2']);
        }
        else
            return response()->json(['status' => 'error1']);
    }

    public function doEditLocalShop(Request $request)
    {
        $data = $request->data;

        $localShop = LocalShops::find($data['id']);
        $localShop->name = $data['name'];
        $localShop->categoryId = $data['categoryId'];
        $localShop->address = $data['address'];
        $localShop->phone = $data['phone'];
        $localShop->cityId = $data['cityId'];
        $localShop->instagram = $data['instagram'];
        $localShop->instagram = $data['instagram'];
        $localShop->lat = $data['lat'];
        $localShop->lng = $data['lng'];
        $localShop->description = $data['description'];
        $localShop->seoTitle = $data['seoTitle'];
        $localShop->keyword = $data['keyword'];
        $localShop->meta = $data['meta'];
        $localShop->slug = $data['slug'];
        $localShop->inWeekCloseTime = $data['inWeekCloseTime'];
        $localShop->inWeekOpenTime = $data['inWeekOpenTime'];
        $localShop->afterClosedDayOpenTime = $data['afterClosedDayOpenTime'];
        $localShop->afterClosedDayCloseTime = $data['afterClosedDayCloseTime'];
        $localShop->closedDayOpenTime = $data['closedDayOpenTime'];
        $localShop->closedDayCloseTime = $data['closedDayCloseTime'];
        $localShop->closedDayIsOpen = $data['closedDayIsOpen'];
        $localShop->afterClosedDayIsOpen = $data['afterClosedDayIsOpen'];
        $localShop->isBoarding = $data['isBoarding'];
        $localShop->userId = $data['ownerId'];

        $changePlaceRel = false;
        $kindPlace = Place::find($data['kindPlaceId']);
        if($kindPlace != null){
            $place = \DB::table($kindPlace->tableName)->find($data['placeRelId']);
            if($place != null){
                $changePlaceRel = true;
                $localShop->placeId = $place->id;
                $localShop->kindPlaceId = $kindPlace->id;
            }
        }

        if(!$changePlaceRel){
            $localShop->placeId = 0;
            $localShop->kindPlaceId = 0;
        }
        $localShop->save();

        $tagTableId = [];
        $tags = isset($data['tags']) ? $data['tags'] : [];
        foreach ($tags as $item){
            $exTag = Tag::firstOrCreate(['name' => $item['name']]);
            $tagRel = PlaceTag::firstOrCreate([
                'kindPlaceId' => 13,
                'placeId' => $localShop->id,
                'tagId' => $exTag->id
            ]);
            array_push($tagTableId, $tagRel->id);
        }
        PlaceTag::where('kindPlaceId', 13)->where('placeId', $localShop->id)->whereNotIn('id', $tagTableId)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function localShopEditPics($id)
    {
        $kindPlaceId = 13;
        $kindPlace = Place::find($kindPlaceId);
        $place = LocalShops::find($id);

        $mainPic = LocalShopsPictures::where('localShopId', $place->id)->where('isMain', 1)->first();
        if($mainPic != null) {
            $place->mainPicF = \URL::asset('_images/' . $kindPlace->fileName . '/' . $place->file . '/f-' . $mainPic->pic);
            $place->mainPicL = \URL::asset('_images/' . $kindPlace->fileName . '/' . $place->file . '/l-' . $mainPic->pic);
        }
        else{
            $place->mainPicF = '';
            $place->mainPicL = '';
        }

        $place->pics = LocalShopsPictures::where('localShopId', $place->id)->where('isMain', 0)->get();
        foreach($place->pics as $pic){
            $pic->picF = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/f-'.$pic->pic);
            $pic->picL = \URL::asset('_images/'.$kindPlace->fileName.'/'.$place->file.'/l-'.$pic->pic);
        }

        $backUrl = route('localShop.list');

        return view('content.newContent.uploadImg', compact(['kindPlaceId', 'place', 'backUrl']));
    }

}
