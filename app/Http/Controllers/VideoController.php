<?php

namespace App\Http\Controllers;

use App\models\Video;
use App\models\VideoCategory;
use App\models\VideoComment;
use App\models\VideoFeedback;
use App\models\VideoLive;
use App\models\VideoLiveGuest;
use App\models\VideoPlaceRelation;
use App\models\VideoTagRelation;
use App\User;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function vodIndex()
    {
        $videoCategory = VideoCategory::all();

        $videos = Video::orderByDesc('created_at')->get();
        $confirmedVideo = [];
        $nonConfirmVideo = [];

        foreach ($videos as $video){
            $video->username = User::find($video->userId)->username;
            $video->categoryName = VideoCategory::find($video->categoryId)->name;
            $video->video = \URL::asset('_images/video/' . $video->userId . '/' . $video->video);
            $video->thumbnail = \URL::asset('_images/video/' . $video->userId . '/' . $video->thumbnail);
            $video->date =  \verta($video->created_at)->format('%d %B %Y');
            $video->time =  \verta($video->created_at)->format('H:i');

            if($video->confirm == 1)
                array_push($confirmedVideo, $video);
            else
                array_push($nonConfirmVideo, $video);
        }

        return view('vod.vodIndex', compact(['nonConfirmVideo', 'confirmedVideo', 'videos', 'videoCategory']));
    }

    public function vodConfirm(Request $request)
    {
        if(isset($request->id)){
            $video = Video::find($request->id);
            if($video != null){
                if($video->confirm == 1)
                    $video->confirm = 0;
                else
                    $video->confirm = 1;
                $video->save();

                echo json_encode(['status' => 'ok', 'result' => $video->confirm]);
            }
            else
                echo json_encode(['status' => 'nok1', 'msg' => 'video id not found']);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);

        return;
    }

    public function doEditVideo(Request $request)
    {
        if(isset($request->id)){
            $video = Video::find($request->id);
            if ($video != null) {
                if(isset($request->title) && isset($request->categoryId)) {
                    $video->title = $request->title;
                    $video->categoryId = $request->categoryId;
                    $video->save();

                    $category = VideoCategory::find($video->categoryId)->name;
                    echo json_encode(['status' => 'ok', 'category' => $category]);
                }
                else if(isset($request->text)){
                    $video->description = $request->text;
                    $video->save();
                    echo json_encode(['status' => 'ok']);
                }
                else
                    echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);
            } else
                echo json_encode(['status' => 'nok1', 'msg' => 'video id not found']);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);
    }

    public function editThumbnail(Request $request)
    {
        if(isset($request->id) && isset($request->pic)){
            $video = Video::find($request->id);
            if($video != null){
                $location = __DIR__ . '/../../../../assets/_images/video/' . $video->userId;

                $img = $_POST['pic'];
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $thumbanil = time() . rand(100, 999) . '.jpg';

                $file = $location . '/' . $thumbanil;
                $success = file_put_contents($file, $data);

                $img = \Image::make($file);
                $img->resize(null, 160, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($location . '/min_' . $thumbanil);

                if($video->thumbnail != null){
                    if(is_file($location . '/' . $video->thumbnail))
                        unlink($location . '/' . $video->thumbnail);

                    if(is_file($location . '/min_' . $video->thumbnail))
                        unlink($location . '/min_' . $video->thumbnail);
                }

                $video->thumbnail = $thumbanil;
                $video->save();

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1', 'msg' => 'video id not found']);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);


        return;
    }

    function deleteVideo(Request $request){
        if(isset($request->id)){
            $video = Video::find($request->id);
            if($video != null){
                $loc = __DIR__ .'/../../../../assets/_images/video/' . $video->userId;

                if(is_file($loc .'/'.$video->video))
                    unlink($loc .'/'.$video->video);
                if(is_file($loc .'/'.$video->thumbnail))
                    unlink($loc .'/'.$video->thumbnail);
                if(is_file($loc .'/min_'.$video->thumbnail))
                    unlink($loc .'/min_'.$video->thumbnail);

                VideoPlaceRelation::where('videoId', $video->id)->delete();
                VideoComment::where('videoId', $video->id)->delete();
                VideoTagRelation::where('videoId', $video->id)->delete();
                VideoFeedback::where('videoId', $video->id)->delete();
                $video->delete();

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1', 'msg' => 'video id not found']);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);

        return;
    }

    public function videoCategoryIndex()
    {
        $category = VideoCategory::where('parent', 0)->get();
        $allCategory = VideoCategory::all();

        foreach ($category as $cat)
            $cat->sub = VideoCategory::where('parent', $cat->id)->get();
        foreach ($allCategory as $item){
            if($item->parent != 0) {
                $item->onIcon = \URL::asset('_images/video/category/' . $item->onIcon);
                $item->offIcon = \URL::asset('_images/video/category/' . $item->offIcon);
            }
            else{
                $item->banner = \URL::asset('_images/video/category/' . $item->banner);
                $item->mainIcon = \URL::asset('_images/video/category/' . $item->onIcon);
            }
        }

        return view('vod.category.categoryIndex', compact(['category', 'allCategory']));
    }

    public function videoCategoryStore(Request $request)
    {
        if(isset($request->id) && isset($request->name) && isset($request->parent)){
            $check = VideoCategory::where('name', $request->name)->where('id', '!=', $request->id)->first();
            if($check == null){
                if($request->id == 0)
                        $category = new VideoCategory();
                else{
                    $category = VideoCategory::find($request->id);
                    if($category == null){
                        echo json_encode(['status' => 'nok3']);
                        return;
                    }
                }

                $category->name = $request->name;
                $category->parent = $request->parent;

                $location = __DIR__ .'/../../../../assets/_images/video/category';
                if(!is_dir($location))
                    mkdir($location);
                $size = [
                    [
                        'width' => 150,
                        'height' => 150,
                        'name' => '',
                        'destination' => $location
                    ],
                ];

                if(isset($_FILES['onIcon']) && $_FILES['onIcon']['error'] == 0){

                    $image = $request->file('onIcon');
                    $fileName = resizeImage($image, $size);

                    if($category->onIcon != null && is_file($location .'/'.$category->onIcon))
                        unlink($location .'/'.$category->onIcon);

                    $category->onIcon = $fileName;
                }

                if(isset($_FILES['offIcon']) && $_FILES['offIcon']['error'] == 0){

                    $image = $request->file('offIcon');
                    $fileName = resizeImage($image, $size);

                    if($category->offIcon != null && is_file($location .'/'.$category->offIcon))
                        unlink($location .'/'.$category->offIcon);

                    $category->offIcon = $fileName;
                }

                if(isset($_FILES['banner']) && $_FILES['banner']['error'] == 0){
                    $fileName = time() . $_FILES['banner']['name'];
                    move_uploaded_file($_FILES['banner']['tmp_name'], $location.'/'.$fileName);

                    if($category->banner != null && is_file($location .'/'.$category->banner))
                        unlink($location .'/'.$category->banner);

                    $category->banner = $fileName;
                }

                if(isset($_FILES['mainIcon']) && $_FILES['mainIcon']['error'] == 0){
                    $fileName = time() . $_FILES['mainIcon']['name'];
                    move_uploaded_file($_FILES['mainIcon']['tmp_name'], $location.'/'.$fileName);

                    if($category->onIcon != null && is_file($location .'/'.$category->onIcon))
                        unlink($location .'/'.$category->onIcon);

                    $category->onIcon = $fileName;
                }

                $category->save();

                echo json_encode(['status' => 'ok']);
            }
            else
                echo json_encode(['status' => 'nok1']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function videoCategoryDelete(Request $request)
    {
        if(isset($request->id)){
            $category = VideoCategory::find($request->id);
            if($category != null) {
                if($category->parent == 0){
                    $vids = VideoCategory::where('parent', $category->id)->count();
                    if($vids > 0){
                        echo json_encode(['status' => 'nok3']);
                        return;
                    }
                }
                $vid = Video::where('categoryId', $request->id)->first();
                if ($vid != null)
                    echo json_encode(['status' => 'nok2', 'msg' => 'cateError']);
                else {
                    $location = __DIR__ .'/../../../../assets/_images/video/category';
                    if(is_file($location.'/'.$category->onIcon))
                        unlink($location.'/'.$category->onIcon);
                    if(is_file($location.'/'.$category->offIcon))
                        unlink($location.'/'.$category->offIcon);

                    $category->delete();
                    echo json_encode(['status' => 'ok']);
                }
            }
            else
                echo json_encode(['status' => 'nok1', 'msg' => 'category id not found']);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);

        return;
    }

    public function liveVideoList()
    {
        $videos = VideoLive::where('userId', auth()->user()->id)->get();
        foreach ($videos as $video) {
            $video->sDate = \verta($video->sDate)->format('Y-m-d');
            $video->guest = VideoLiveGuest::where('videoId', $video->id)->get();
            foreach ($video->guest as $guest)
                $guest->pic = asset('_images/live/' . $video->id . '/' . $guest->pic);
        }

        return view('vod.live.liveVideoIndex', compact(['videos']));
    }

    public function liveVideoStore(Request $request)
    {

        if(isset($request->id) && isset($request->title) && isset($request->time) && isset($request->date)){
            if($request->id == 0){
                $live = new VideoLive();
                $live->userId = auth()->user()->id;
                while(true){
                    $code = random_int(1000, 999999);
                    $check = VideoLive::where('code', $code)->first();
                    if($check == null)
                        break;
                }
                $live->code = $code;
            }
            else
                $live = VideoLive::find($request->id);

            $date = explode('-', convertNumber('en', $request->date));
            $date = Verta::getGregorian($date[0],$date[1],$date[2]);
            if($date[1] < 10)
                $date[1] = '0'.$date[1];
            if($date[2] < 10)
                $date[2] = '0'.$date[2];
            $date = implode('-', $date);

            $live->title = $request->title;
            $live->description = $request->desc;
            $live->sTime = convertNumber('en', $request->time);
            $live->sDate = $date;
            $live->save();

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function liveVideoIsLive(Request $request)
    {
        if(isset($request->id)){
            $video = VideoLive::find($request->id);
            if($video->isLive == 1)
                $video->isLive = 0;
            else
                $video->isLive = 1;
            $video->save();

            echo json_encode(['status' => 'ok']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }
}
