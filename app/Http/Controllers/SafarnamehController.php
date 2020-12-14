<?php

namespace App\Http\Controllers;

use App\models\ACL;
use App\models\Amaken;
use App\models\BannerPosts;
use App\models\Cities;
use App\models\FavoritePost;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\Restaurant;
use App\models\Safarnameh;
use App\models\SafarnamehCategories;
use App\models\SafarnamehCategoryRelations;
use App\models\SafarnamehCityRelations;
use App\models\SafarnamehComments;
use App\models\SafarnamehLike;
use App\models\SafarnamehLimboPics;
use App\models\SafarnamehPlaceRelations;
use App\models\SafarnamehTagRelations;
use App\models\SafarnamehTags;
use App\models\SogatSanaie;
use App\models\State;
use App\models\Tag;
use App\models\VideoCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class SafarnamehController extends Controller
{

    public function __construct()
    {
        $location = __DIR__.'/../../../../assets/_images/posts/limbo/';
        $limbos = SafarnamehLimboPics::where("created_at", "<", Carbon::now()->subDay())->get();
        foreach ($limbos as $item){
            if(is_file($location.$item->pic))
                unlink($location.$item->pic);
            $item->delete();
        }
    }

    public function addToFavoriteSafarnameh() {

        if(isset($_POST["safarnamehId"])) {

            $allFav = FavoritePost::all()->count();

            while($allFav >= 5){
                FavoritePost::orderBy('id')->first()->delete();
                $allFav = FavoritePost::all()->count();
            }
            $tmp = new FavoritePost();
            $tmp->postId = makeValidInput($_POST["safarnamehId"]);
            try {
                $tmp->save();
                echo "ok";
            }
            catch (\Exception $x) {}
        }

    }

    public function addToBannerSafarnameh() {

        if(isset($_POST["safarnamehId"])) {

            $tmp = new BannerPosts();
            $tmp->postId = makeValidInput($_POST["safarnamehId"]);
            try {
                $tmp->save();
                echo "ok";
            }
            catch (\Exception $x) {}
        }

    }

    public function deleteFromFavoriteSafarnameh() {

        if(isset($_POST["safarnamehId"])) {
            try {
                FavoritePost::wherePostId(makeValidInput($_POST["safarnamehId"]))->delete();
                echo "ok";
            }
            catch (\Exception $x) {}
        }
    }

    public function deleteFromBannerSafarnameh() {

        if(isset($_POST["safarnamehId"])) {

            try {
                BannerPosts::wherePostId(makeValidInput($_POST["safarnamehId"]))->delete();
                echo "ok";
            }
            catch (\Exception $x) {}
        }
    }

    public function safarnamehPage() {
        $safarnameh = Safarnameh::where('confirm', 1)->select('id', 'title', 'userId', 'release', 'updated_at', 'confirm', 'date', 'time')
                            ->orderBy('created_at', 'desc')
                            ->get();
        $noneConfirmSafar = Safarnameh::where('confirm', 0)->select('id', 'title', 'userId', 'release', 'updated_at', 'confirm', 'date', 'time')
                                        ->orderBy('updated_at', 'desc')
                                        ->get();
        foreach ([$safarnameh, $noneConfirmSafar] as $safarn) {
            foreach ($safarn as $item) {
                $item->favorited = (FavoritePost::where('postId', $item->id)->count() > 0);
                $item->bannered = (BannerPosts::where('postId', $item->id)->count() > 0);

                $item->user = User::find($item->userId);
                $category = SafarnamehCategoryRelations::where('safarnamehId', $item->id)
                    ->orderBy('isMain', 'desc')
                    ->pluck('categoryId')
                    ->toArray();
                if (count($category) != 0)
                    $item->categories = SafarnamehCategories::whereIn('id', $category)->get();
                else
                    $item->categories = array();

                $tags = SafarnamehTagRelations::where('safarnamehId', $item->id)
                    ->pluck('tagId')
                    ->toArray();
                if (count($tags) != 0)
                    $item->tags = SafarnamehTags::whereIn('id', $tags)->get();
                else
                    $item->tags = [];
                $lastUpdate = gregorianToJalali(Carbon::make($item->updated_at)->format('Y-m-d'), '-');
                $item->lastUpdate = $lastUpdate[0] . '/' . $lastUpdate[1] . '/' . $lastUpdate[2] . ' ' . Carbon::make($item->updated_at)->format('H:m');

                if ($item->confirm == 0)
                    $item->status = 'تایید نشده';
                else
                    switch ($item->release) {
                        case 'draft':
                            $item->status = 'پیش نویس';
                            break;
                        case 'released':
                            $item->status = 'منتشر شده';
                            break;
                        case 'future':
                            $item->status = 'در آینده منتشر می شود';
                            $item->futureDate = substr($item->date, 0, 4) . '/' . substr($item->date, 4, 2) . '/' . substr($item->date, 6, 1) . ' ' . substr($item->time, 0, 2) . ':' . substr($item->time, 2, 2);
                            break;
                    }
            }
        }

        $gardeshname = \DB::select('SELECT post_title, post_status, post_parent, ID FROM wp_posts WHERE post_parent = 0 AND post_status = "publish"');

        return view('content.safarnameh.safarnameh', compact(['safarnameh', 'noneConfirmSafar', 'gardeshname']));
    }

    public function deleteSafarnameh() {

        if(isset($_POST["safarnamehId"])) {

            try {
                $safarnameh = Safarnameh::find($_POST['safarnamehId']);
                if($safarnameh != null){
                    SafarnamehTagRelations::where('safarnamehId', $safarnameh->id)->delete();
                    SafarnamehCategoryRelations::where('safarnamehId', $safarnameh->id)->delete();
                    SafarnamehCityRelations::where('safarnamehId', $safarnameh->id)->delete();
                    SafarnamehComments::where('safarnamehId', $safarnameh->id)->delete();
                    SafarnamehLike::where('safarnamehId', $safarnameh->id)->delete();
                    SafarnamehPlaceRelations::where('safarnamehId', $safarnameh->id)->delete();
                    $location = __DIR__ . '/../../../../assets/_images/posts/' . $safarnameh->id;
                    deleteFolder($location);

                    $safarnameh->delete();
                    echo "ok";
                }
                else
                    echo 'nok2';
            }
            catch (\Exception $x) {}
        }
        else
            echo 'nok1';

        return;
    }

    public function newSafarnameh() {
        $category = SafarnamehCategories::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = SafarnamehCategories::where('parent', $item->id)->get();

        $ostan = State::all();

        $acl = ACL::where('userId', auth()->user()->id)->first();
        if($acl != null && $acl->adminAccess == 1)
            $acl = 1;
        else
            $acl = 0;

        $code = random_int(10000, 99999);
        return view('content.safarnameh.newSafarnameh', compact(['category', 'ostan', 'acl', 'code']));
    }

    public function editSafarnameh($id) {

        $code = random_int(10000, 99999);
        $safarnameh = Safarnameh::find($id);

        if($safarnameh == null)
            return Redirect::route('home');

        if($safarnameh->slug == null || $safarnameh->slug == '')
            $safarnameh->slug = makeSlug($safarnameh->title);

        if($safarnameh->seoTitle == null || $safarnameh->seoTitle == '')
            $safarnameh->seoTitle = $safarnameh->title;

        if($safarnameh->meta == null || $safarnameh->meta == '')
            $safarnameh->meta = $safarnameh->summery;

        if($safarnameh->pic !=  null)
            $safarnameh->pic = URL::asset('_images/posts/' . $safarnameh->id . '/' . $safarnameh->pic);
        if($safarnameh->time !=  null) {
            $time = $safarnameh->time[0];
            $time .= $safarnameh->time[1];
            $time .= ':';
            $time .= $safarnameh->time[2];
            $time .= $safarnameh->time[3];
            $safarnameh->time = $time;
        }

        $safarnameh->category = SafarnamehCategoryRelations::where('safarnamehId', $safarnameh->id)->get();
        $mainCategory = SafarnamehCategoryRelations::where('safarnamehId', $safarnameh->id)->where('isMain', 1)->first();
        $safarnameh->mainCategory = $mainCategory != null ? $mainCategory->categoryId : 0;

        $safarnameh->tags = $safarnameh->getTags->pluck('tag')->toArray();

        $category = SafarnamehCategories::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = SafarnamehCategories::where('parent', $item->id)->get();

        $safarPlaces = [];
        $citySafar = SafarnamehCityRelations::where('safarnamehId', $safarnameh->id)->get();
        foreach ($citySafar as $item){
            if($item->cityId == 0){
                $state = State::find($item->stateId);
                array_push($safarPlaces, ['id' => $state->id, 'name' => 'استان '.$state->name, 'kind' => 'state']);
            }
            else{
                $city = Cities::find($item->cityId);
                array_push($safarPlaces, ['id' => $city->id, 'name' => 'شهر '.$city->name, 'kind' => 'city']);
            }
        }

        $placeSafar = SafarnamehPlaceRelations::where('safarnamehId', $safarnameh->id)->get();
        foreach ($placeSafar as $item){
            $kindPlace = Place::find($item->kindPlaceId);
            $place = DB::table($kindPlace->tableName)->find($item->placeId);
            if($place == null)
                $item->delete();
            else
                array_push($safarPlaces, ['id' => $place->id, 'name' => $place->name, 'kind' => $kindPlace->id]);
        }

        $safarnameh->places = $safarPlaces;

        return view('content.safarnameh.newSafarnameh', compact(['safarnameh', 'category', 'code']));
    }

    public function uploadSafarnamehPic(Request $request)
    {
        $user = auth()->user();
        $data = json_decode($request->data);
        if(isset($data->code)){
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $mainFileName = $user->id.'_'.time().'.webp';
                $nLocation = __DIR__ . '/../../../../assets/_images/posts/limbo';

                $size = [[
                    'width' => 900,
                    'height' => null,
                    'name' => $user->id.'_',
                    'destination' => $nLocation
                ]];

                $image = $request->file('file');
                $nFileName = resizeImage($image, $size);
                if($nFileName == 'error'){
                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'error in resize image'] ];
                    return response()->json($response);
                }

                $nFileName = $user->id.'_'.$nFileName;
                $resizeLocation = $nLocation.'/'.$nFileName;
                $destinationLocation = $nLocation.'/'.$mainFileName;

                $fileType = explode('.', $nFileName);
                $fileType = end($fileType);
                $needToConvert = true;
                if($fileType == 'png')
                    $img = imagecreatefrompng($resizeLocation);
                else if($fileType == 'jpg' || $fileType == 'jpeg')
                    $img = imagecreatefromjpeg($resizeLocation);
                else if($fileType == 'gif')
                    $img = imagecreatefromgif($resizeLocation);
                else if($fileType == 'webp')
                    $needToConvert = false;
                else{
                    if(is_file($resizeLocation))
                        unlink($resizeLocation);
                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'file type error'] ];
                    return response()->json($response);
                }

                if($needToConvert)
                    $image = imagewebp($img, $destinationLocation, 80);
                if($image || !$needToConvert){
                    $limbo =SafarnamehLimboPics::create([
                        'userId' => $user->id,
                        'code' => $data->code,
                        'pic' => $mainFileName,
                    ]);

                    if(is_file($resizeLocation) && $needToConvert)
                        unlink($resizeLocation);

                    $response = [ 'uploaded' => true,
                                  'url' => URL::asset('_images/posts/limbo/'.$mainFileName),
                                  'limboId' => $limbo->id ];
                }
                else
                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'error in convert'] ];
            }
            else
                $response = [ 'uploaded' => false, 'error' => [ 'message' => 'could not upload this image1'] ];
        }
        else
            $response = [ 'uploaded' => false, 'error' => [ 'message' => 'less data'] ];

        return response()->json($response);
    }

    public function safarnamehTagSearch()
    {
        if(isset($_GET['text'])){
            $tags = SafarnamehTags::where('tag', 'LIKE', '%'.$_GET['text'].'%')->get()->pluck('tag')->toArray();
            return response()->json(['status' => 'ok', 'result' => $tags, 'value' => $_GET['text']]);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function newSafarnamehCategory(Request $request)
    {
        if(isset($request->value) && isset($request->parent)){
            $cat = SafarnamehCategories::where('name', $request->value)->first();

            if($cat == null){
                $category = new SafarnamehCategories();
                $category->name = $request->value;
                $category->parent = $request->parent;
                $category->save();

                echo json_encode(['ok', $category->id]);
            }
            else
                echo json_encode(['nok2']);
        }
        else
            echo json_encode(['nok1']);

        return;
    }

    public function storeSafarnameh(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'releaseType' => 'required',
            'category' => 'required',
        ]);
        $safarnamehId = $request->id;

        if($safarnamehId != 0){
            $safarnameh = Safarnameh::find($safarnamehId);
            if($safarnameh == null) {
                $safarnameh = new Safarnameh();
                if($request->gardeshName != 0){
                    $uAdmin = User::where('username', 'koochita')->first();
                    if($uAdmin != null)
                        $safarnameh->userId = $uAdmin->id;
                }
                else
                    $safarnameh->userId = \auth()->user()->id;
            }
        }
        else {
            $safarnameh = new Safarnameh();
            if($request->gardeshName != 0){
                $uAdmin = User::where('username', 'koochita')->first();
                if($uAdmin != null)
                    $safarnameh->userId = $uAdmin->id;
            }
            else
                $safarnameh->userId = \auth()->user()->id;
        }

        $safarnameh->title = $request->title;
        $safarnameh->description = ' ';
        $safarnameh->seoCheck = $request->warningCount;
        $safarnameh->release = $request->releaseType;
        $date = convertNumber('en', $request->date);
        $date = convertDateToString($date);
        $safarnameh->date = $date;
        if($request->releaseType == 'future'){
            $time = str_replace(':', '', $request->time);
            $safarnameh->time = $time;
        }
        if($request->releaseType != 'future')
            $safarnameh->time = '0000';

        if(isset($request->meta))
            $safarnameh->meta = $request->meta;
        if($request->keyword != null)
            $safarnameh->keyword = $request->keyword;
        if($request->seoTitle != null)
            $safarnameh->seoTitle = $request->seoTitle;
        if($request->slug != null)
            $safarnameh->slug = makeSlug($request->slug);
        else if($request->keword != null)
            $safarnameh->slug = makeSlug($request->keyword);

        $safarnameh->confirm = 1;
        $safarnameh->save();

        $safarnamehId = $safarnameh->id;

        $loca = __DIR__.'/../../../../assets/_images/posts';
        $limboDestination = $loca.'/limbo';
        $newDestination = $loca.'/'.$safarnamehId;
        if(!is_dir($newDestination))
            mkdir($newDestination);

        $description = $request->description;
        $limbos = explode(',', $request->limboPicIds);
        $limboPics = SafarnamehLimboPics::whereIn('id', $limbos)->where('userId', auth()->user()->id)->get();
        foreach ($limboPics as $item){
            rename($limboDestination.'/'.$item->pic, $newDestination.'/'.$item->pic);
            $url = URL::asset('_images/posts/limbo/'.$item->pic);
            $newUrl = URL::asset('_images/posts/'.$safarnamehId.'/'.$item->pic);
            $description = str_replace($url, $newUrl, $description);
            $item->delete();
        }
        $notUseLimboPics = SafarnamehLimboPics::where('code', $request->code)->where('userId', auth()->user()->id)->get();
        foreach ($notUseLimboPics as $item){
            $locationss = __DIR__.'/../../../../assets/_images/posts/limbo/';
            if(is_file($locationss.$item->pic))
                unlink($locationss.$item->pic);
            $item->delete();
        }
        $safarnameh->description = $description;
        $safarnameh->save();

        $tagId = [];
        $tags = json_decode($request->tags);
        foreach ($tags as $item){
            $tt = SafarnamehTags::firstOrCreate(['tag' => $item]);
            array_push($tagId, $tt->id);
        }
        SafarnamehTagRelations::where('safarnamehId', $safarnamehId)
                                ->whereNotIn('tagId', $tagId)
                                ->delete();
        foreach ($tagId as $id){
            SafarnamehTagRelations::firstOrCreate(['safarnamehId' => $safarnamehId, 'tagId' => $id]);
        }

        $categories = json_decode($request->category);
        $categoryId = [];
        $mainCategoryId = 0;
        foreach ($categories as $item){
            array_push($categoryId, $item->id);
            if($item->thisIsMain == 1)
                $mainCategoryId = $item->id;
        }
        SafarnamehCategoryRelations::where('safarnamehId', $safarnameh->id)
                                    ->whereNotIn('categoryId', $categoryId)
                                    ->delete();
        foreach ($categoryId as $item){
            $safCatId = SafarnamehCategoryRelations::firstOrCreate(['safarnamehId' => $safarnameh->id,'categoryId' => $item]);
            $safCatId->update(['isMain' => 0]);
            if($safCatId->categoryId == $mainCategoryId)
                $safCatId->update(['isMain' => 1]);
        }

        if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
            $location = __DIR__ . '/../../../../assets/_images/posts';
            if(!file_exists($location))
                mkdir($location);

            $location .= '/' . $safarnameh->id;

            if(!file_exists($location))
                mkdir($location);

            if($safarnameh->pic != null && $safarnameh->pic != ''){
                $location .= '/' . $safarnameh->pic;
                if(file_exists($location))
                    unlink($location);
            }
            else{
                $safarnameh->pic = time() . '-mainPic.jpg';
                $safarnameh->save();
                $location .= '/' . $safarnameh->pic;
            }
            compressImage($_FILES['pic']['tmp_name'], $location, 80);
        }

        $stateCityIds = [0];
        $placeSafarIds = [0];
        $places = json_decode($request->places);
        foreach ($places as $place){
            if($place->kind == 'city' || $place->kind == 'state'){
                if($place->kind == 'state'){
                    $city = 0;
                    $state = $place->id;
                }
                else{
                    $city = $place->id;
                    $state = Cities::find($city)->getState->id;
                }
                $tt = SafarnamehCityRelations::firstOrCreate(['safarnamehId' => $safarnamehId, 'stateId' => $state, 'cityId' => $city]);
                array_push($stateCityIds, $tt->id);
            }
            else{
                $tt = SafarnamehPlaceRelations::firstOrCreate(['safarnamehId' => $safarnamehId, 'kindPlaceId' => $place->kind, 'placeId' => $place->id]);
                array_push($placeSafarIds, $tt->id);
            }
        }
        SafarnamehCityRelations::whereNotIn('id', $stateCityIds)->where('safarnamehId', $safarnamehId)->delete();
        SafarnamehPlaceRelations::whereNotIn('id', $placeSafarIds)->where('safarnamehId', $safarnamehId)->delete();

        if($request->gardeshName != 0){
            \DB::select('DELETE FROM wp_posts WHERE ID = ' . $request->gardeshName);
            \DB::select('DELETE FROM wp_term_relationships WHERE object_id = ' . $request->gardeshName);

            $uAdmin = User::where('username', 'koochita')->first();
            if($uAdmin != null){
                $safarnameh->userId = $uAdmin->id;
                $safarnameh->save();
            }
        }

        return response()->json(['status' => 'ok', 'result' => $safarnameh->id]);
    }

    public function deleteSafarnamehCategory(Request $request)
    {
        if(isset($request->id)){
            $category = SafarnamehCategories::find($request->id);
            if($category != null) {
                if($category->parent == 0){
                    $subCategory = SafarnamehCategories::where('parent', $request->id)->count();
                    if($subCategory != 0){
                        echo 'hasSub';
                        return;
                    }
                }

                $posts = SafarnamehCategoryRelations::where('categoryId', $request->id)->count();
                if($posts != 0)
                    echo 'hasArticle';
                else{
                    $category->delete();
                    echo 'ok';
                }
            }
        }

        return;
    }

    public function gardeshNameEdit($id)
    {
        $safars = \DB::select('SELECT post_title, post_status, post_parent, ID, post_content FROM wp_posts WHERE ID ='. $id);
        $safarnameh = $safars[0];
        $confirmTag = [];
        $tags = \DB::select('SELECT * FROM wp_term_relationships WHERE object_id = ' . $id);
        foreach ($tags as $item){
            $item->taxonomy = \DB::select('SELECT * FROM wp_term_taxonomy WHERE taxonomy = "post_tag" AND term_taxonomy_id = ' . $item->term_taxonomy_id);
            $item->term = \DB::select('SELECT * FROM wp_terms WHERE term_id = ' . $item->term_taxonomy_id);
        }

        foreach ($tags as $item){
            if(count($item->term) != 0)
                array_push($confirmTag, $item->term[0]->name);
        }
        $safarnameh->tags = $confirmTag;
        $safarnameh->id = 0;
        $safarnameh->mainCategory = 0;
        $safarnameh->gardeshName = $safarnameh->ID;
        $safarnameh->date = null;
        $safarnameh->time = null;
        $safarnameh->pic = null;
        $safarnameh->keyword = null;
        $safarnameh->seoTitle = null;
        $safarnameh->slug = null;
        $safarnameh->meta = null;
        $safarnameh->title = $safarnameh->post_title;
        $safarnameh->description = str_replace("gardeshname.shazdemosafer.com/wp-content","static.koochita.com",$safarnameh->post_content);
        $safarnameh->release = 'draft';
        $safarnameh->place = [];
        $safarnameh->city = [];
        $safarnameh->category = [];

        $category = SafarnamehCategories::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = SafarnamehCategories::where('parent', $item->id)->get();

        $code = random_int(10000, 99999);

        return view('content.safarnameh.newSafarnameh', compact(['safarnameh', 'category', 'code']));
    }

    public function deleteGardesh(Request $request)
    {
        if(isset($request->id)){
            \DB::select('DELETE FROM wp_posts WHERE ID = ' . $request->id);
            \DB::select('DELETE FROM wp_term_relationships WHERE object_id = ' . $request->id);

            echo 'ok';
        }
        else
            echo 'nok1';

        return;
    }

    public function addGardeshNameTags()
    {
        $tags = \DB::select('SELECT wp2.name FROM wp_term_taxonomy as wp1, wp_terms as wp2  WHERE wp1.taxonomy = "post_tag" AND wp2.term_id = wp1.term_id');
        $text = '';
        for($i = 1; $i < count($tags); $i++)
            $text .= ' ,(null, "' . $tags[$i]->name . '")';
        DB::select("INSERT INTO `safarnamehTags` (`id`, `tag`) VALUES (NULL, '" . $tags[0]->name . "')" . $text );
        dd('done');
    }

    public function gardeshNameList()
    {
        $gardeshname = \DB::select('SELECT post_title, post_status, post_parent, ID FROM wp_posts WHERE post_parent = 0 AND post_status = "publish"');

        return view('content.safarnameh.gardeshNameList', ['gardeshname' => $gardeshname]);
    }
}
