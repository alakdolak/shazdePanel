<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\BannerPosts;
use App\models\Cities;
use App\models\FavoritePost;
use App\models\Hotel;
use App\models\Place;
use App\models\Post;
use App\models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller {

    private function createTmpPost($i) {

        $post = new Post();
        $tmp = Hotel::whereId(random_int(100, 800));

        if($tmp == null)
            return;

        $post->title = $tmp->name;
        $post->pic = $i . '.jpg';
        $post->color = "#" . random_int(100000, 999999);
        $post->backColor = "#" . random_int(100000, 999999);
        $post->categoryColor = "#" . random_int(100000, 999999);
        $post->creator = Auth::user()->id;
        $post->description = $tmp->description;
        $post->placeId = $tmp->id;
        $post->kindPlaceId = 4;
        $post->category = random_int(1, 15);
        $post->save();

    }

    private function createTmpPost2($i) {

        $post = new Post();
        $tmp = Hotel::whereId(random_int(100, 800));

        if($tmp == null)
            return;

        $post->title = $tmp->name;
        $post->pic = $i . '.jpg';
        $post->color = "#" . random_int(100000, 999999);
        $post->backColor = "#" . random_int(100000, 999999);
        $post->categoryColor = "#" . random_int(100000, 999999);
        $post->creator = Auth::user()->id;
        $post->description = $tmp->description;
        $post->C = $tmp->C;
        $post->D = $tmp->D;
        $post->category = random_int(1, 15);
        $post->save();

    }

    public function posts() {

        $posts = Post::select('id', 'pic', 'title')->get();

        foreach ($posts as $post) {
            $post->favorited = (FavoritePost::wherePostId($post->id)->count() > 0);
            $post->bannered = (BannerPosts::wherePostId($post->id)->count() > 0);
        }

        return view('content.post.posts', ['posts' => $posts]);
    }

    public function doAddPost(Request $request) {

        $request->validate([
            'description' => 'required',
            'title' => 'bail|required|max:300',
            'color' => 'bail|required|max:10',
            'backColor' => 'bail|required|max:10',
            'categoryColor' => 'required',
            'category' => 'required',
            'relatedMode' => 'required',
            'placeIdOrC' => 'required',
            'kindPlaceIdOrD' => 'required',
            'cityId' => 'required'
        ], getMessages());


        $post = new Post();

        $post->creator = Auth::user()->id;
        $post->description = $_POST["description"];
        $post->color = makeValidInput($_POST["color"]);
        $post->backColor = makeValidInput($_POST["backColor"]);
        $post->title = makeValidInput($_POST["title"]);
        $post->category = makeValidInput($_POST["category"]);
        $post->categoryColor = makeValidInput($_POST["categoryColor"]);
        $post->cityId = makeValidInput($_POST["cityId"]);
//        $post->date = '';
//        $post->time = '';

        $relatedMode = makeValidInput($_POST["relatedMode"]);

        if($relatedMode == 1) {
            $post->placeId = makeValidInput($_POST["placeIdOrC"]);
            $post->kindPlaceId = makeValidInput($_POST["kindPlaceIdOrD"]);
        }
        else {
            $post->C = makeValidInput($_POST["placeIdOrC"]);
            $post->D = makeValidInput($_POST["kindPlaceIdOrD"]);
        }

        try {
            $post->save();
            echo json_encode(["status" => "ok", "url" => route('createPostStep2', ['id' => $post->id])]);
        }
        catch (\Exception $x) {
            echo json_encode(["status" => "nok", "msg" => $x->getMessage()]);
        }
    }

    public function editPostTag() {

        if(isset($_POST["id"]) && isset($_POST["whichTag"]) && isset($_POST["val"])) {
            DB::update("update post set " . makeValidInput($_POST["whichTag"]) . " = '" . makeValidInput($_POST["val"]) . "' where id = " . makeValidInput($_POST["id"]));
        }

    }

    public function createPostStep2($id) {

        $post = Post::whereId($id);

        if($post == null)
            return Redirect::route('home');

        return view('content.post.createPostStep2', ['post' => $post]);
    }

    public function createPostStep3($id) {

        $post = Post::whereId($id);

        if($post == null)
            return Redirect::route('home');

        return view('content.post.createPostStep3', ['id' => $post->id, 'pic' => $post->pic]);
    }

    public function setPostPic($id) {

        if(isset($_FILES["pic"])) {

            $post = Post::whereId($id);

            if($post == null)
                return Redirect::route('home');

            if(empty($_FILES["pic"]["name"]))
                return Redirect::route('createPostStep4', ['id' => $post->id]);

            $name = time() . '_' . $_FILES["pic"]["name"];
            $pic = __DIR__ . '/../../../../assets/posts/' . $name;

            $err = uploadCheck($pic, "pic", "افزودن عکس جدید", 3000000, -1);
            if(empty($err)) {
                $err = upload($pic, "pic", "افزودن عکس جدید");
                if (empty($err)) {
                    $post->pic = $name;
                    $post->save();
                    return Redirect::route('createPostStep4', ['id' => $post->id]);
                }
                else {
                    dd($err);
                }
            }
            else {
                dd($err);
            }
        }

        return Redirect::route('home');
    }

    public function createPostStep4($id) {

        $post = Post::whereId($id);

        if($post == null)
            return Redirect::route('home');

        return view('content.post.createPostStep4', ['id' => $post->id, 'pic' => $post->pic]);
    }

    public function setPostInterval($id) {

        if(isset($_POST["date"]) && isset($_POST["time"])) {

            $post = Post::whereId($id);

            if($post == null)
                return Redirect::route('home');

            $post->date = convertDateToString(makeValidInput($_POST["date"]));
            $post->time = convertTimeToString(makeValidInput($_POST["time"]));

            $post->save();

            return Redirect::route('posts');
        }

        return Redirect::route('home');
    }

    public function doEditPost(Request $request) {

        $request->validate([
            'id' => 'required',
            'description' => 'required',
            'title' => 'bail|required|max:300',
            'color' => 'bail|required|max:10',
            'backColor' => 'bail|required|max:10',
            'categoryColor' => 'required',
            'category' => 'required',
            'relatedMode' => 'required',
            'placeIdOrC' => 'required',
            'kindPlaceIdOrD' => 'required',
            'cityId' => 'required'
        ], getMessages());

        $id = makeValidInput($_POST["id"]);
        $post = Post::whereId($id);

        if($post == null) {
            echo json_encode(["status" => "nok", "msg" => "post not exist"]);
            return;
        }

        $post->description = $_POST["description"];
        $post->color = makeValidInput($_POST["color"]);
        $post->backColor = makeValidInput($_POST["backColor"]);
        $post->title = makeValidInput($_POST["title"]);
        $post->category = makeValidInput($_POST["category"]);
        $post->categoryColor = makeValidInput($_POST["categoryColor"]);
        $post->cityId = makeValidInput($_POST["cityId"]);

        $relatedMode = makeValidInput($_POST["relatedMode"]);

        if($relatedMode == 1) {
            $post->placeId = makeValidInput($_POST["placeIdOrC"]);
            $post->kindPlaceId = makeValidInput($_POST["kindPlaceIdOrD"]);
        }
        else {
            $post->C = makeValidInput($_POST["placeIdOrC"]);
            $post->D = makeValidInput($_POST["kindPlaceIdOrD"]);
        }

        $post->save();

        $tmp = new AdminLog();
        $tmp->uId = Auth::user()->id;
        $tmp->mode = getValueInfo('editPost');
        $tmp->additional1 = $id;
        $tmp->save();

        echo json_encode(["status" => "ok", "url" => route('createPostStep2', ['id' => $post->id])]);
    }

    public function editPost($id) {

        $post = Post::whereId($id);

        if($post == null)
            return Redirect::route('home');

        $placeName = getPlaceAndFolderName($post->kindPlaceId, $post->placeId)[0]->name;

        if($post->cityId != null) {
            $city = Cities::find($post->cityId);
            $state = State::find($city->stateId);
            $post->city = $city->name . ' در ' . $state->name;
        }

        return view('content.post.createPost', ['categories' => getPostCategories(),
            'places' => Place::all(), 'post' => $post, 'placeName' => $placeName
        ]);
    }

    public function createPost() {

        return view('content.post.createPost', ['categories' => getPostCategories(),
            'places' => Place::all()
        ]);
    }

    public function addToFavoritePosts() {

        if(isset($_POST["postId"])) {

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

    public function deletePost() {

        if(isset($_POST["postId"])) {

            try {
                Post::destroy(makeValidInput($_POST["postId"]));
                echo "ok";
            }
            catch (\Exception $x) {}
        }

    }

}
