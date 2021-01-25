<?php

namespace App\Http\Controllers\News;

use App\models\Advertisement\Advertisement;
use App\models\News\News;
use App\models\News\NewsCategory;
use App\models\News\NewsCategoryRelations;
use App\models\News\NewsLimboPics;
use App\models\News\NewsTags;
use App\models\News\NewsTagsRelation;
use App\models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class NewsController extends Controller
{

    public function __construct()
    {
        $location = __DIR__.'/../../../../../assets/_images/news/limbo';
        $limbos = NewsLimboPics::where("created_at", "<", Carbon::now()->subDay())->get();
        foreach ($limbos as $item){
            if(is_file($location.$item->pic))
                unlink($location.$item->pic);
            $item->delete();
        }
    }

    public function newsList(){
        $selectCols = ['id', 'title', 'userId', 'release', 'updated_at', 'confirm', 'date', 'time'];

        $news = News::where('confirm', 1)->select($selectCols)->orderBy('created_at', 'desc')->get();
        $noneConfirmNews = News::where('confirm', 0)->select($selectCols)->orderBy('updated_at', 'desc')->get();

        foreach ([$news, $noneConfirmNews] as $nItem) {
            foreach ($nItem as $item) {

                $item->user = User::find($item->userId);
                $item->categories = DB::table('newsCategoryRelations')->join('newsCategories', 'newsCategories.id', 'newsCategoryRelations.categoryId')
                                        ->where('newsCategoryRelations.newsId', $item->id)
                                        ->orderBy('newsCategoryRelations.isMain', 'desc')
                                        ->select(['newsCategoryRelations.newsId', 'newsCategoryRelations.isMain', 'newsCategories.name', 'newsCategories.id AS categoryId'])
                                        ->get()->toArray();

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

        return view('News.newsList', compact(['news', 'noneConfirmNews']));
    }

    public function newsNewPage()
    {
        $category = NewsCategory::where('parentId', 0)->get();
        foreach ($category as $item)
            $item->sub = NewsCategory::where('parentId', $item->id)->get();

        $code = rand(10000, 99999);
        return view('News.newNews', compact(['category', 'code']));
    }

    public function editNewsPage($id)
    {
        $code = rand(10000, 99999);
        $news = News::find($id);

        if($news == null)
            return Redirect::route('home');

        if($news->slug == null || $news->slug == '')
            $news->slug = makeSlug($news->title);

        if($news->seoTitle == null || $news->seoTitle == '')
            $news->seoTitle = $news->title;

        if($news->pic !=  null)
            $news->pic = URL::asset("_images/news/{$news->id}/{$news->pic}");
        if($news->time !=  null) {
            $time = $news->time[0];
            $time .= $news->time[1];
            $time .= ':';
            $time .= $news->time[2];
            $time .= $news->time[3];
            $news->time = $time;
        }

        $news->category = NewsCategoryRelations::where('newsId', $news->id)->get();
        $mainCategory = NewsCategoryRelations::where('newsId', $news->id)->where('isMain', 1)->first();
        $news->mainCategory = $mainCategory != null ? $mainCategory->categoryId : 0;

        $news->tags = $news->getTags->pluck('tag')->toArray();

        $category = NewsCategory::where('parentId', 0)->get();
        foreach ($category as $item)
            $item->sub = NewsCategory::where('parentId', $item->id)->get();

        return view('News.newNews', compact(['news', 'category', 'code']));
    }

    public function newsTagSearch(){
        if(isset($_GET['text'])){
            $tags = NewsTags::where('tag', 'LIKE', '%'.$_GET['text'].'%')->get()->pluck('tag')->toArray();
            return response()->json(['status' => 'ok', 'result' => $tags, 'value' => $_GET['text']]);
        }
        else
            return response()->json(['status' => 'nok']);
    }

    public function uploadNewsPic(Request $request)
    {
        $user = auth()->user();
        $data = json_decode($request->data);
        if(isset($data->code)){
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $mainFileName = $user->id.'_'.time().'.png';
                $nLocation = __DIR__ . '/../../../../../assets/_images/news/limbo';

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
                    $needToConvert = false;
                else if($fileType == 'jpg' || $fileType == 'jpeg')
                    $img = imagecreatefromjpeg($resizeLocation);
                else if($fileType == 'gif')
                    $img = imagecreatefromgif($resizeLocation);
                else if($fileType == 'webp')
                    $img = imagecreatefromwebp($resizeLocation);
                else{
                    if(is_file($resizeLocation))
                        unlink($resizeLocation);
                    $response = [ 'uploaded' => false, 'error' => [ 'message' => 'file type error'] ];
                    return response()->json($response);
                }

                if($needToConvert)
                    $image = imagepng($img, $destinationLocation, 9);
                if($image || !$needToConvert){
                    $limbo = NewsLimboPics::create([
                                'userId' => $user->id,
                                'code' => $data->code,
                                'pic' => $mainFileName,
                            ]);

                    if(is_file($resizeLocation) && $needToConvert)
                        unlink($resizeLocation);

                    $response = [ 'uploaded' => true,
                        'url' => URL::asset('_images/news/limbo/'.$mainFileName),
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

    public function storeNews(Request $request){

        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'releaseType' => 'required',
            'category' => 'required',
        ]);

        $news = null;

        if($request->id != 0)
            $news = News::find($request->id);

        if($news == null){
            $news = new News();
            $news->userId = \auth()->user()->id;
            $news->date = verta()->now()->format('Y/m/d');
        }

        $news->title = $request->title;
        $news->text = ' ';
        if($request->releaseType == 'future'){
            $time = str_replace(':', '', $request->time);
            $news->time = $time;
            $date = convertNumber('en', $request->date);
            $date = convertDateToString($date,'/');
            $news->date = $date;
        }
        else if($request->releaseType == 'release' && $news->release != 'release')
            $news->date = verta()->now()->format('Y/m/d');

        if($request->releaseType != 'future')
            $news->time = strftime('%H:%M');

        $news->meta = $request->meta;
        $news->keyword = $request->keyword;
        $news->seoTitle = $request->seoTitle;
        $news->slug = makeSlug($request->slug);

        if($request->slug != null)
            $news->slug = makeSlug($request->slug);
        else if($request->keword != null)
            $news->slug = makeSlug($request->keyword);

        $news->confirm = 1;
        $news->release = $request->releaseType;
        $news->save();

        $newsId = $news->id;

        $loca = __DIR__.'/../../../../../assets/_images/news';
        $limboDestination = $loca.'/limbo';
        $newDestination = $loca.'/'.$newsId;
        if(!is_dir($newDestination))
            mkdir($newDestination);

        $description = $request->description;
        $limbos = explode(',', $request->limboPicIds);
        $limboPics = NewsLimboPics::whereIn('id', $limbos)->where('userId', auth()->user()->id)->get();
        foreach ($limboPics as $item){
            if(is_file($limboDestination.'/'.$item->pic)) {
                rename($limboDestination . '/' . $item->pic, $newDestination . '/' . $item->pic);
                $url = URL::asset('_images/news/limbo/' . $item->pic);
                $newUrl = URL::asset('_images/news/' . $newsId . '/' . $item->pic);
                $description = str_replace($url, $newUrl, $description);
            }
            $item->delete();
        }
        $notUseLimboPics = NewsLimboPics::where('code', $request->code)->where('userId', auth()->user()->id)->get();
        foreach ($notUseLimboPics as $item){
            $locationss = __DIR__.'/../../../../../assets/_images/news/limbo/';
            if(is_file($locationss.$item->pic))
                unlink($locationss.$item->pic);
            $item->delete();
        }
        $news->text = $description;
        $news->save();

        $tagId = [];
        $tags = json_decode($request->tags);
        foreach ($tags as $item){
            $tt = NewsTags::firstOrCreate(['tag' => $item]);
            array_push($tagId, $tt->id);
        }
        NewsTagsRelation::where('newsId', $newsId)
                            ->whereNotIn('tagId', $tagId)
                            ->delete();
        foreach ($tagId as $id)
            NewsTagsRelation::firstOrCreate(['newsId' => $newsId, 'tagId' => $id]);

        $categories = json_decode($request->category);
        $categoryId = [];
        $mainCategoryId = 0;
        foreach ($categories as $item){
            array_push($categoryId, $item->id);
            if($item->thisIsMain == 1)
                $mainCategoryId = $item->id;
        }
        NewsCategoryRelations::where('newsId', $news->id)
                                ->whereNotIn('categoryId', $categoryId)
                                ->delete();
        foreach ($categoryId as $item){
            $safCatId = NewsCategoryRelations::firstOrCreate(['newsId' => $news->id,'categoryId' => $item]);
            $safCatId->update(['isMain' => 0]);
            if($safCatId->categoryId == $mainCategoryId)
                $safCatId->update(['isMain' => 1]);
        }

        if(isset($_FILES['pic']) && $_FILES['pic']['error'] == 0){
            $location = __DIR__ . '/../../../../../assets/_images/news';
            if(!file_exists($location))
                mkdir($location);

            $location .= '/' . $news->id;

            if(!file_exists($location))
                mkdir($location);

            if($news->pic != null && $news->pic != ''){
                $location .= '/' . $news->pic;
                if(file_exists($location))
                    unlink($location);
            }
            else{
                $news->pic = time() . '-mainPic.jpg';
                $news->save();
                $location .= '/' . $news->pic;
            }
            compressImage($_FILES['pic']['tmp_name'], $location, 80);
        }
        return response()->json(['status' => 'ok', 'result' => $news->id]);
    }

    public function deleteNews(Request $request)
    {
        if(isset($request->newsId)) {
            try {
                $news = News::find($request->newsId);
                if($news != null){
                    NewsTagsRelation::where('newsId', $news->id)->delete();
                    NewsCategoryRelations::where('newsId', $news->id)->delete();
//                    SafarnamehComments::where('newsId', $news->id)->delete();
//                    SafarnamehLike::where('newsId', $news->id)->delete();
                    $location = __DIR__ . '/../../../../../assets/_images/news/' . $news->id;
                    deleteFolder($location);

                    $news->delete();
                    return response()->json(["status" => "ok"]) ;
                }
                else
                    return response()->json(["status" => "nok2"]) ;
            }
            catch (\Exception $x) {
                return response()->json(["status" => "nok3"]);
            }
        }
        else
            return response()->json(["status" => "nok1"]);
    }

}
