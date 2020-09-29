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
use App\models\SafarnamehPlaceRelations;
use App\models\SafarnamehTagRelations;
use App\models\SafarnamehTags;
use App\models\SogatSanaie;
use App\models\State;
use App\models\VideoCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class SafarnamehController extends Controller
{

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

        return view('content.safarnameh.newSafarnameh', compact(['category', 'ostan', 'acl']));
    }

    public function editSafarnameh($id) {

        $safarnameh = Safarnameh::find($id);

        if($safarnameh == null)
            return Redirect::route('home');

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
        if($mainCategory != null)
            $safarnameh->mainCategory = $mainCategory->categoryId;
        else
            $safarnameh->mainCategory = 0;

        $safarnameh->tags = SafarnamehTagRelations::where('safarnamehId', $safarnameh->id)->get();
        foreach($safarnameh->tags as $item)
            $item->tag = SafarnamehTags::find($item->tagId)->tag;

        $category = SafarnamehCategories::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = SafarnamehCategories::where('parent', $item->id)->get();

        $allCity = SafarnamehCityRelations::where('safarnamehId', $safarnameh->id)->where('cityId', 0)->where('stateId', 0)->first();
        if($allCity == null){
            $cityRelation = SafarnamehCityRelations::where('safarnamehId', $safarnameh->id)->get();
            foreach ($cityRelation as $item){
                if($item->cityId == 0){
                    $name = 'استان ';
                    $state = State::find($item->stateId);
                    $item->name = $name . $state->name;
                }
                else{
                    $name = 'شهر ';
                    $Ctiy = Cities::find($item->cityId);
                    $item->name = $name . $Ctiy->name;
                }
                $item->validId = $item->stateId .'_'.$item->cityId;
            }
            $safarnameh->city = $cityRelation;
        }
        else
            $safarnameh->city = array();


        $allPlace = SafarnamehPlaceRelations::where('safarnamehId', $safarnameh->id)
                                            ->where('placeId', 0)
                                            ->where('kindPlaceId', 0)
                                            ->first();
        if($allPlace == null){
            $placeRelation = SafarnamehPlaceRelations::where('safarnamehId', $safarnameh->id)->get();
            foreach ($placeRelation as $item){
                $kindPlace = Place::find($item->kindPlaceId);
                $place = \DB::table($kindPlace->tableName)->find($item->placeId);

                $item->name = $place->name;
                $item->validId = $item->placeId .'_'.$item->kindPlaceId;
            }
            $safarnameh->place = $placeRelation;
        }
        else
            $safarnameh->place = [];

        $ostan = State::all();
        $safarnamehJson = json_encode($safarnameh);

        $acl = ACL::where('userId', auth()->user()->id)->first();
        if($acl != null && $acl->adminAccess == 1)
            $acl = 1;
        else
            $acl = 0;

        return view('content.safarnameh.newSafarnameh', compact(['safarnameh', 'category', 'ostan', 'safarnamehJson', 'acl']));
    }

    public function uploadSafarnamehPic(Request $request)
    {
        try {
            if ($this->request->hasFiles() == true) {
                $errors = []; // Store all foreseen and unforseen errors here
                $fileExtensions = ['jpeg','jpg','png','gif','svg'];
                $uploadDirectory = __DIR__ . '/../../Uploads/';
                $Y = date("Y");
                $M = date("m");

                foreach ($this->request->getUploadedFiles() as $file) {

                    if (in_array($file->getExtension(),$fileExtensions)) {

                        if($file->getSize() < 2000000)  {
                            if (!file_exists($uploadDirectory . $Y)) {
                                mkdir($uploadDirectory.$Y, 0777, true);
                            }
                            if (!file_exists($uploadDirectory.$Y.'/'.$M)) {
                                mkdir($uploadDirectory.$Y.'/'.$M, 0777, true);
                            }

                            $namenew = md5($file->getName().time()).'.'.$file->getExtension();
                            $uploadDirectory .= $Y.'/'.$M.'/';
                            $file->moveTo($uploadDirectory.$namenew);
                        }
                        else {
                            $errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
                        }
                    }
                    else{$errors[] = "This file extension is not allowed. Please upload a JPEG ,svg,gif,,jpg,PNG file";}

                    if(empty($errors))  {
                        echo '{
                        "uploaded": true,
                        "url": "http://localhost/cms/public/Uploads/'.$Y.'/'.$M.'/'.$namenew.'"}';
                    }
                    else{
                        echo '{
                        "uploaded": false,
                        "error": {
                            "message": "could not upload this image1"
                    }';
                    }
                }
            }
            else {
                echo '{
                "uploaded": false,
                "error": {
                    "message": "could not upload this image1"
                }';
            }
        }
        catch (\Exception $e) {
            echo '{
            "uploaded": false,
            "error": {
                "message": "could not upload this image0"
            }';
        }
    }

    public function safarnamehTagSearch(Request $request)
    {
        if(isset($request->text)){
            $tags = SafarnamehTags::where('tag', 'LIKE', '%'.$request->text.'%');
            echo json_encode(['ok', $tags]);
        }
        else
            echo json_encode(['nok']);

        return;
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
            'mainCategory' => 'required',
            'category' => 'required',
        ]);
        $safarnamehId = $request->id;

        if($safarnamehId != 0){
            $safarnameh = Safarnameh::find($safarnamehId);
            if($safarnameh == null) {
                $safarnameh = new Safarnameh();
                $safarnameh->userId = \auth()->user()->id;
            }
        }
        else {
            $safarnameh = new Safarnameh();
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
        if($request->slug != null){
            $slug = makeSlug($request->slug);
            $safarnameh->slug = $slug;
        }
        else if($request->keword != null){
            $slug = makeSlug($request->keyword);
            $safarnameh->slug = $slug;
        }

        $safarnameh->confirm = 1;
        $safarnameh->save();
        $safarnamehId = $safarnameh->id;

        $tags = json_decode($request->tags);
        $tagId = [];
        $existTag = [];
        foreach ($tags as $item){
            $tag = SafarnamehTags::where('tag', $item)->firstOrCreate(['tag' => $item]);
            $ex = SafarnamehTagRelations::firstOrCreate(['tagId' => $tag->id, 'safarnamehId' => $safarnamehId]);
            array_push($tagId, $tag->id);
            array_push($existTag, $ex->id);
        }
        SafarnamehTagRelations::whereNotIn('id', $ex)->where('safarnamehId', $safarnamehId)->delete();

        $category = json_decode($request->category);
        $categoryRelation = SafarnamehCategoryRelations::where('safarnamehId', $safarnameh->id)->get();
        $categoryId = [];
        for($i = 0; $i < count($category); $i += 2){
            if($category[$i] == 'true')
                array_push($categoryId, $category[$i+1]);
        }
        foreach ($categoryRelation as $item){
            if(in_array($item->categoryId, $categoryId))
                array_push($existTag, $item->categoryId);
            else
                SafarnamehCategoryRelations::find($item->id)->delete();
        }
        foreach ($categoryId as $item){
            if(!in_array($item, $existTag)){
                $safarnamehCategory = new SafarnamehCategoryRelations();
                $safarnamehCategory->safarnamehId = $safarnameh->id;
                $safarnamehCategory->categoryId = $item;
                $safarnamehCategory->isMain = 0;
                $safarnamehCategory->save();
            }
        }

        if($request->mainCategory != 0) {
            $mainCategory = $request->mainCategory;
            $condition = ['safarnamehId' => $safarnameh->id, 'categoryId' => $mainCategory];
            $main = SafarnamehCategoryRelations::where($condition)->first();
            $main->isMain = 1;
            $main->save();
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

        $city = json_decode($request->cityId);
        if(count($city) != 0) {
            $cityRelations = SafarnamehCityRelations::where('safarnamehId', $safarnamehId)->get();
            $existCity = array();

            for ($i = 0; $i < count($cityRelations); $i++) {
                $val = $cityRelations[$i]->stateId . '_' . $cityRelations[$i]->cityId;
                if (in_array($val, $city))
                    array_push($existCity, $val);
                else
                    $cityRelations[$i]->delete();
            }

            $count = 0;
            for ($i = 0; $i < count($city); $i++) {
                if (!in_array($city[$i], $existCity)) {
                    $ex = explode('_', $city[$i]);
                    if(count($ex) == 2) {
                        $stateId = $ex[0];
                        $cityId = $ex[1];

                        $newCity = new SafarnamehCityRelations();
                        $newCity->stateId = $stateId;
                        $newCity->cityId = $cityId;
                        $newCity->safarnamehId = $safarnamehId;
                        $newCity->save();
                    }
                    else
                        $count++;
                }
            }
            if($count == count($city))
                SafarnamehCityRelations::where('safarnamehId', $safarnamehId)
                                                    ->where('stateId', 0)
                                                    ->where('cityId', 0)
                                                    ->firstOrCreate(['safarnamehId' => $safarnamehId,
                                                                     'stateId' => 0, 'cityId' => 0]);
        }

        $place = json_decode($request->placeId);
        if(count($place) != 0) {
            SafarnamehPlaceRelations::where('safarnameId', $safarnamehId)
                                    ->where('kindPlaceId', 0)
                                    ->where('placeId', 0)
                                    ->delete();
            $placeRelations = SafarnamehPlaceRelations::where('safarnameId', $safarnamehId)->get();
            $existPlace = array();

            for ($i = 0; $i < count($placeRelations); $i++) {
                $val = $placeRelations[$i]->placeId . '_' . $placeRelations[$i]->kindPlaceId;
                if (in_array($val, $place))
                    array_push($existPlace, $val);
                else
                    $placeRelations[$i]->delete();
            }

            $count = 0;
            for ($i = 0; $i < count($place); $i++) {
                if (!in_array($place[$i], $existPlace)) {
                    $ex = explode('_', $place[$i]);
                    if(count($ex) == 2) {
                        $kindPlaceId = $ex[1];
                        $placeId = $ex[0];

                        $newPlace = new SafarnamehPlaceRelations();
                        $newPlace->kindPlaceId = $kindPlaceId;
                        $newPlace->placeId = $placeId;
                        $newPlace->safarnameId = $safarnamehId;
                        $newPlace->save();
                    }
                    else
                        $count++;
                }
            }

            if(count($place) == $count){
                $allPlace = SafarnamehPlaceRelations::where('safarnameId', $safarnamehId)
                                                    ->where('kindPlaceId', 0)
                                                    ->where('placeId', 0)
                                                    ->first();
                if($allPlace == null){
                    $newPlace = new SafarnamehPlaceRelations();
                    $newPlace->kindPlaceId = 0;
                    $newPlace->placeId = 0;
                    $newPlace->safarnameId = $safarnamehId;
                    $newPlace->save();
                }
            }
        }

        if($request->gardeshName != 0){
            \DB::select('DELETE FROM wp_posts WHERE ID = ' . $request->gardeshName);
            \DB::select('DELETE FROM wp_term_relationships WHERE object_id = ' . $request->gardeshName);
        }

        echo json_encode(['ok', $safarnameh->id]);
        return;
    }

    public function storeDescriptionSafarnameh(Request $request)
    {
        if(isset($request->id)){
            $safarnameh = Safarnameh::find($request->id);
            if($safarnameh != null){
                if($request->description == null)
                    $safarnameh->description = ' ';
                else
                    $safarnameh->description = $request->description;
                $safarnameh->save();

                echo 'ok';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
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

    public function imageUploadCKeditor4(Request $request)
    {
        if(isset($request->id)) {
            $post = Safarnameh::find($request->id);
            if($post != null){
                $img = $_POST['pic'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $location = __DIR__ . '/../../../../assets/_images/posts/'. $post->id ;

                while(true){
                    $randomNum = rand(10000, 99999);
                    $fileName = $randomNum . '.jpg';

                    $location .= '/' . $fileName;
                    if(!file_exists($location))
                        break;
                }

                file_put_contents($location, $data);

                $url = URL::asset('_images/posts/' . $post->id . '/' . $fileName);
                echo $url;
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }

    public function gardeshNameEdit($id)
    {
        $safars = \DB::select('SELECT post_title, post_status, post_parent, ID, post_content FROM wp_posts WHERE ID ='. $id);
        $safarnameh = $safars[0];
        $tags = \DB::select('SELECT * FROM wp_term_relationships WHERE object_id = ' . $id);
        foreach ($tags as $item){
            $item->taxonomy = \DB::select('SELECT * FROM wp_term_taxonomy WHERE taxonomy = "post_tag" AND term_taxonomy_id = ' . $item->term_taxonomy_id);
            $item->term = \DB::select('SELECT * FROM wp_terms WHERE term_id = ' . $item->term_taxonomy_id);
        }

        foreach ($tags as $item){
            if(count($item->taxonomy) != 0){
                $item->tag = $item->term[0]->name;
            }
        }
        $safarnameh->tags = $tags;
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
        $safarnameh->description = $safarnameh->post_content;
        $safarnameh->release = 'draft';
        $safarnameh->place = array();
        $safarnameh->city = array();
        $safarnameh->category = array();

        $ostan = State::all();

        $category = SafarnamehCategories::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = SafarnamehCategories::where('parent', $item->id)->get();

        $safarnamehJson = json_encode($safarnameh);

        return view('content.safarnameh.newSafarnameh', compact(['safarnameh', 'category', 'ostan', 'safarnamehJson']));

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

    public function transferCategories()
    {
        $categes = VideoCategory::where('parent', 0)->get();
        foreach ($categes as $item) {
            $subs = VideoCategory::where('parent', $item->id)->pluck('name')->toArray();
            $safarCateg = SafarnamehCategories::firstOrCreate(['name' => $item->name, 'parent' => 0]);
            foreach ($subs as $sub)
                SafarnamehCategories::firstOrCreate(['name' => $sub, 'parent' => $safarCateg->id]);
        }
        dd('done');
    }
}
