<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\Amaken;
use App\models\BannerPosts;
use App\models\Cities;
use App\models\FavoritePost;
use App\models\Hotel;
use App\models\MahaliFood;
use App\models\Majara;
use App\models\Place;
use App\models\Post;
use App\models\PostCategory;
use App\models\PostCategoryRelation;
use App\models\PostCityRelation;
use App\models\PostComment;
use App\models\PostLikes;
use App\models\PostPlaceRelation;
use App\models\PostTag;
use App\models\PostTagsRelation;
use App\models\Restaurant;
use App\models\SogatSanaie;
use App\models\State;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller {

    public function addToFavoritePosts() {

        if(isset($_POST["postId"])) {

            $allFav = FavoritePost::all()->count();

            while($allFav >= 5){
                FavoritePost::orderBy('id')->first()->delete();
                $allFav = FavoritePost::all()->count();
            }
            $tmp = new FavoritePost();
            $tmp->postId = makeValidInput($_POST["postId"]);
            try {
                $tmp->save();
                echo "ok";
            }
            catch (\Exception $x) {}
        }

    }

    public function deleteFromFavoritePosts() {

        if(isset($_POST["postId"])) {

            try {
                FavoritePost::wherePostId(makeValidInput($_POST["postId"]))->delete();
                echo "ok";
            }
            catch (\Exception $x) {}
        }
    }

    public function addToBannerPosts() {

        if(isset($_POST["postId"])) {

            $tmp = new BannerPosts();
            $tmp->postId = makeValidInput($_POST["postId"]);
            try {
                $tmp->save();
                echo "ok";
            }
            catch (\Exception $x) {}
        }

    }

    public function deleteFromBannerPosts() {

        if(isset($_POST["postId"])) {

            try {
                BannerPosts::wherePostId(makeValidInput($_POST["postId"]))->delete();
                echo "ok";
            }
            catch (\Exception $x) {}
        }
    }



    public function posts() {
        $posts = Post::select('id', 'title', 'creator', 'release', 'updated_at', 'date', 'time')->orderBy('updated_at', 'desc')->get();
        foreach ($posts as $post) {
            $post->favorited = (FavoritePost::wherePostId($post->id)->count() > 0);
            $post->bannered = (BannerPosts::wherePostId($post->id)->count() > 0);

            $post->user = User::find($post->creator);
            $category = PostCategoryRelation::where('postId', $post->id)->select('categoryId')->orderBy('isMain', 'desc')->get()->pluck('categoryId')->toArray();
            if(count($category) != 0)
                $post->categories = PostCategory::whereIn('id', $category)->get();
            else
                $post->categories = array();

            $tags = PostTagsRelation::where('postId', $post->id)->select('tagId')->get()->pluck('tagId')->toArray();
            if(count($tags) != 0)
                $post->tags = PostTag::whereIn('id', $tags)->get();
            else
                $post->tags = array();
            $lastUpdate = gregorianToJalali(Carbon::make($post->updated_at)->format('Y-m-d'), '-');
            $post->lastUpdate = $lastUpdate[0] . '/' . $lastUpdate[1] . '/' . $lastUpdate[2] . ' ' . Carbon::make($post->updated_at)->format('H:m');

            switch ($post->release){
                case 'draft':
                    $post->status = 'پیش نویس';
                    break;
                case 'released':
                    $post->status = 'منتشر شده';
                    break;
                case 'future':
                    $post->status = 'در آینده منتشر می شود';
                    $post->futureDate = substr($post->date,0, 4) . '/' . substr($post->date,4, 2) . '/' . substr($post->date,6, 1) . ' ' . substr($post->time, 0, 2) . ':' . substr($post->time, 2, 2);
                    break;

            }
        }

        $gardeshname = \DB::select('SELECT post_title, post_status, post_parent, ID FROM wp_posts WHERE post_parent = 0 AND post_status = "publish"');

        return view('content.post.posts', ['posts' => $posts, 'gardeshname' => $gardeshname]);
    }

    public function createPost() {
        $category = PostCategory::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = PostCategory::where('parent', $item->id)->get();

        $ostan = State::all();

        return view('content.post.createPost', compact(['category', 'ostan']));
    }

    public function editPost($id) {

        $post = Post::whereId($id);

        if($post == null)
            return Redirect::route('home');

        if($post->pic !=  null)
            $post->pic = URL::asset('_images/posts/' . $post->id . '/' . $post->pic);
        if($post->time !=  null) {
            $time = $post->time[0];
            $time .= $post->time[1];
            $time .= ':';
            $time .= $post->time[2];
            $time .= $post->time[3];
            $post->time = $time;
        }

        $post->category = PostCategoryRelation::where('postId', $post->id)->get();
        $mainCategory = PostCategoryRelation::where('postId', $post->id)->where('isMain', 1)->first();
        if($mainCategory != null)
            $post->mainCategory = $mainCategory->categoryId;
        else
            $post->mainCategory = 0;

        $post->tags = PostTagsRelation::where('postId', $post->id)->get();
        foreach($post->tags as $item)
            $item->tag = PostTag::find($item->tagId)->tag;

        $category = PostCategory::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = PostCategory::where('parent', $item->id)->get();

        $allCity = PostCityRelation::where('postId', $post->id)->where('cityId', 0)->where('stateId', 0)->first();
        if($allCity == null){
            $cityRelation = PostCityRelation::where('postId', $post->id)->get();
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
            $post->city = $cityRelation;
        }
        else
            $post->city = array();


        $allPlace = PostPlaceRelation::where('postId', $post->id)->where('placeId', 0)->where('kindPlaceId', 0)->first();
        if($allPlace == null){
            $placeRelation = PostPlaceRelation::where('postId', $post->id)->get();
            foreach ($placeRelation as $item){
                switch ($item->kindPlaceId){
                    case 1:
                        $place = Amaken::find($item->placeId);
                        break;
                    case 3:
                        $place = Restaurant::find($item->placeId);
                        break;
                    case 4:
                        $place = Hotel::find($item->placeId);
                        break;
                    case 6:
                        $place = Majara::find($item->placeId);
                        break;
                    case 10:
                        $place = SogatSanaie::find($item->placeId);
                        break;
                    case 11:
                        $place = MahaliFood::find($item->placeId);
                        break;
                }
                $item->name = $place->name;
                $item->validId = $item->placeId .'_'.$item->kindPlaceId;
            }
            $post->place = $placeRelation;
        }
        else
            $post->place = array();

        $ostan = State::all();
        $postJson = json_encode($post);

        return view('content.post.createPost', compact(['post', 'category', 'ostan', 'postJson']));
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'releaseType' => 'required',
            'mainCategory' => 'required',
            'category' => 'required',
        ]);
        $postId = $request->id;

        if($postId != 0){
            $post = Post::find($postId);
            if($post == null)
                $post = new Post();
        }
        else
            $post = new Post();

        $post->title = $request->title;
        $post->creator = \auth()->user()->id;
        $post->description = ' ';
        $post->seoCheck = $request->warningCount;
        $post->release = $request->releaseType;
        $date = convertNumber('en', $request->date);
        $date = convertDateToString($date);
        $post->date = $date;
        if($request->releaseType == 'future'){
            $time = str_replace(':', '', $request->time);
            $post->time = $time;
        }
        if(isset($request->meta))
            $post->meta = $request->meta;
        if($request->keyword != null)
            $post->keyword = $request->keyword;
        if($request->seoTitle != null)
            $post->seoTitle = $request->seoTitle;
        if($request->slug != null){
            $slug = makeSlug($request->slug);
            $post->slug = $slug;
        }
        else if($request->keword != null){
            $slug = makeSlug($request->keyword);
            $post->slug = $slug;
        }

        $post->save();
        $postId = $post->id;

        $tags = json_decode($request->tags);
        $tagRelation = PostTagsRelation::where('postId', $post->id)->get();
        $tagId = array();
        $existTag = array();
        foreach ($tags as $item){
            $tag = PostTag::where('tag', $item)->first();
            if($tag == null){
                $tag = new PostTag();
                $tag->tag = $item;
                $tag->save();
            }
            array_push($tagId, $tag->id);
        }
        foreach ($tagRelation as $item){
            if(in_array($item->tagId, $tagId))
                array_push($existTag, $item->tagId);
            else
                PostTagsRelation::find($item->id)->delete();
        }
        foreach ($tagId as $item){
            if(!in_array($item, $existTag)){
                $postTag = new PostTagsRelation();
                $postTag->postId = $post->id;
                $postTag->tagId = $item;
                $postTag->save();
            }
        }

        $category = json_decode($request->category);
        $categoryRelation = PostCategoryRelation::where('postId', $post->id)->get();
        $categoryId = array();
        for($i = 0; $i < count($category); $i += 2){
            if($category[$i] == 'true')
                array_push($categoryId, $category[$i+1]);
        }
        foreach ($categoryRelation as $item){
            if(in_array($item->categoryId, $categoryId))
                array_push($existTag, $item->categoryId);
            else
                PostCategoryRelation::find($item->id)->delete();
        }
        foreach ($categoryId as $item){
            if(!in_array($item, $existTag)){
                $postCategory = new PostCategoryRelation();
                $postCategory->postId = $post->id;
                $postCategory->categoryId = $item;
                $postCategory->isPlaceCategory = 0;
                $postCategory->isMain = 0;
                $postCategory->save();
            }
        }

        if($request->mainCategory != 0) {
            $mainCategory = $request->mainCategory;
            $condition = ['postId' => $post->id, 'categoryId' => $mainCategory];
            $main = PostCategoryRelation::where($condition)->first();
            $main->isMain = 1;
            $main->save();
        }

        if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
            $location = __DIR__ . '/../../../../assets/_images/posts';
            if(!file_exists($location))
                mkdir($location);

            $location .= '/' . $post->id;

            if(!file_exists($location))
                mkdir($location);

            if($post->pic != null && $post->pic != ''){
                $location .= '/' . $post->pic;
                if(file_exists($location))
                    unlink($location);
            }
            else{
                $post->pic = 'mainPic.jpg';
                $post->save();
                $location .= '/' . $post->pic;
            }
            compressImage($_FILES['pic']['tmp_name'], $location, 100);
        }

        $city = json_decode($request->cityId);
        if(count($city) != 0) {
            $cityRelations = PostCityRelation::where('postId', $postId)->get();
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

                        $newCity = new PostCityRelation();
                        $newCity->stateId = $stateId;
                        $newCity->cityId = $cityId;
                        $newCity->postId = $postId;
                        $newCity->save();
                    }
                    else
                        $count++;
                }
            }
            if($count == count($city)){
                $allCity = PostCityRelation::where('postId', $postId)->where('stateId', 0)->where('cityId', 0)->first();
                if($allCity == null){
                    $newCity = new PostCityRelation();
                    $newCity->stateId = 0;
                    $newCity->cityId = 0;
                    $newCity->postId = $postId;
                    $newCity->save();
                }
            }
        }
        else{
            $allCity = PostCityRelation::where('postId', $postId)->where('stateId', 0)->where('cityId', 0)->first();
            if($allCity == null){
                $newCity = new PostCityRelation();
                $newCity->stateId = 0;
                $newCity->cityId = 0;
                $newCity->postId = $postId;
                $newCity->save();
            }
        }

        $place = json_decode($request->placeId);
        if(count($place) != 0) {
            PostPlaceRelation::where('postId', $postId)->where('kindPlaceId', 0)->where('placeId', 0)->delete();
            $placeRelations = PostPlaceRelation::where('postId', $postId)->get();
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

                        $newPlace = new PostPlaceRelation();
                        $newPlace->kindPlaceId = $kindPlaceId;
                        $newPlace->placeId = $placeId;
                        $newPlace->postId = $postId;
                        $newPlace->save();
                    }
                    else
                        $count++;
                }
            }

            if(count($place) == $count){
                $allPlace = PostPlaceRelation::where('postId', $postId)->where('kindPlaceId', 0)->where('placeId', 0)->first();
                if($allPlace == null){
                    $newPlace = new PostPlaceRelation();
                    $newPlace->kindPlaceId = 0;
                    $newPlace->placeId = 0;
                    $newPlace->postId = $postId;
                    $newPlace->save();
                }
            }
        }
        else{
            $allPlace = PostPlaceRelation::where('postId', $postId)->where('kindPlaceId', 0)->where('placeId', 0)->first();
            if($allPlace == null){
                $newPlace = new PostPlaceRelation();
                $newPlace->kindPlaceId = 0;
                $newPlace->placeId = 0;
                $newPlace->postId = $postId;
                $newPlace->save();
            }
        }

        if($request->gardeshName != 0){
            \DB::select('DELETE FROM wp_posts WHERE ID = ' . $request->gardeshName);
            \DB::select('DELETE FROM wp_term_relationships WHERE object_id = ' . $request->gardeshName);
        }

        echo json_encode(['ok', $post->id]);
        return;
    }

    public function deletePost() {

        if(isset($_POST["postId"])) {

            try {
                $post = Post::find($_POST['postId']);
                if($post != null){
                    PostTagsRelation::where('postId', $post->id)->delete();
                    PostCategoryRelation::where('postId', $post->id)->delete();
                    PostCityRelation::where('postId', $post->id)->delete();
                    PostComment::where('postId', $post->id)->delete();
                    PostLikes::where('postId', $post->id)->delete();
                    PostPlaceRelation::where('postId', $post->id)->delete();
                    $location = __DIR__ . '/../../../../assets/_images/posts/' . $post->id;
                    deleteFolder($location);

                    $post->delete();
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


    public function postTagSearch(Request $request)
    {
        if(isset($request->text)){

            $tags = \DB::select('SELECT * FROM postTags WHERE tag LIKE "%' . $request->text . '%" ');

            echo json_encode(['ok', $tags]);
        }
        else
            echo json_encode(['nok']);

        return;
    }

    public function newPostCategory(Request $request)
    {
        if(isset($request->value) && isset($request->parent)){
            $cat = PostCategory::where('name', $request->value)->first();

            if($cat == null){
                $category = new PostCategory();
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

    public function imageUploadCKeditor4(Request $request)
    {
        if(isset($request->id)) {
            $post = Post::find($request->id);
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

    public function storeDescriptionPost(Request $request)
    {
        if(isset($request->id)){
            $post = Post::find($request->id);
            if($post != null){
                if($request->description == null)
                    $post->description = ' ';
                else
                    $post->description = $request->description;
                $post->save();

                echo 'ok';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }

    public function deleteCategory(Request $request)
    {
        if(isset($request->id)){
            $category = PostCategory::find($request->id);
            if($category != null) {
                if($category->parent == 0){
                    $subCategory = PostCategory::where('parent', $request->id)->count();
                    if($subCategory != 0){
                        echo 'hasSub';
                        return;
                    }
                }

                $posts = PostCategoryRelation::where('categoryId', $request->id)->count();
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
        $posts = \DB::select('SELECT post_title, post_status, post_parent, ID, post_content FROM wp_posts WHERE ID ='. $id);
        $post = $posts[0];
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
        $post->tags = $tags;
        $post->id = 0;
        $post->mainCategory = 0;
        $post->gardeshName = $post->ID;
        $post->date = null;
        $post->time = null;
        $post->pic = null;
        $post->keyword = null;
        $post->seoTitle = null;
        $post->slug = null;
        $post->meta = null;
        $post->title = $post->post_title;
        $post->description = $post->post_content;
        $post->release = 'draft';
        $post->place = array();
        $post->city = array();
        $post->category = array();

        $ostan = State::all();

        $category = PostCategory::where('parent', 0)->get();
        foreach ($category as $item)
            $item->sub = PostCategory::where('parent', $item->id)->get();

        $postJson = json_encode($post);

        return view('content.post.createPost', compact(['post', 'category', 'ostan', 'postJson']));

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
        for($i = 1; $i < count($tags); $i++) {
            $text .= ' ,(null, "' . $tags[$i]->name . '")';
        }
        DB::select("INSERT INTO `postTags` (`id`, `tag`) VALUES (NULL, '" . $tags[0]->name . "')" . $text );
        dd('done');
    }

    public function gardeshNameList()
    {
        $gardeshname = \DB::select('SELECT post_title, post_status, post_parent, ID FROM wp_posts WHERE post_parent = 0 AND post_status = "publish"');

        return view('content.post.gardeshNameList', ['gardeshname' => $gardeshname]);
    }
}


