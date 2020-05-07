<?php

namespace App\Http\Controllers;

use App\models\Video;
use App\models\VideoCategory;
use App\models\VideoComment;
use App\models\VideoFeedback;
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
        $category = VideoCategory::all();
        return view('vod.categoryIndex', compact(['category']));
    }

    public function videoCategoryStore(Request $request)
    {
        if(isset($request->id) && isset($request->name)){
            $check = VideoCategory::where('name', $request->name)->first();
            if($check != null){
                if($request->id != 0)
                    $name = VideoCategory::find($request->id)->name;
                else
                    $name = $request->name;

                echo json_encode(['status' => 'nok3', 'id' => $request->id, 'name' => $name]);
                return;
            }

            if($request->id == 0)
                $category = new VideoCategory();
            else{
                $category = VideoCategory::find($request->id);
                if($category == null){
                    echo json_encode(['status' => 'nok1', 'msg' => 'category id not found']);
                    return;
                }
            }

            $category->name = $request->name;
            $category->save();

            echo json_encode(['status' => 'ok', 'id' => $category->id]);
        }
        else
            echo json_encode(['status' => 'nok', 'msg' => 'Invalid Input']);

        return;
    }

    public function videoCategoryDelete(Request $request)
    {
        if(isset($request->id)){
            $category = VideoCategory::find($request->id);
            if($category != null) {
                $vid = Video::where('categoryId', $request->id)->first();
                if ($vid != null)
                    echo json_encode(['status' => 'nok2', 'msg' => 'cateError']);
                else {
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
}
