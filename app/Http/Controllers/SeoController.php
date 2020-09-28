<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\AdminLog;
use App\models\Amaken;
use App\models\Cities;
use App\models\ConfigModel;
use App\models\Hotel;
use App\models\Majara;
use App\models\Place;
use App\models\Safarnameh;
use App\models\SafarnamehTags;
use App\models\Restaurant;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use ReflectionException;
use SeoAnalyzer\Analyzer;
use SeoAnalyzer\Factor;
use SeoAnalyzer\HttpClient\Exception\HttpException;
use SeoAnalyzer\Page;

class SeoController extends Controller {

    public function changeSeo($city, $mode, $wantedKey = -1, $selectedMode = -1) {

        $out = [];
        $counter = 0;

        if($selectedMode == -1 || $selectedMode == getValueInfo('hotel')) {

            if($mode)
                $places = Hotel::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from hotels h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('hotel');
                $place->kindPlaceName = 'هتل';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('amaken')) {

            if($mode)
                $places = Amaken::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from amaken h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('amaken');
                $place->kindPlaceName = 'اماکن';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('restaurant')) {

            if($mode)
                $places = Restaurant::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from restaurant h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('restaurant');
                $place->kindPlaceName = 'رستوران';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('adab')) {

            $places = Adab::whereStateId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('adab');
                $place->kindPlaceName = 'آداب';
                $out[$counter++] = $place;
            }
        }

        if($selectedMode == -1 || $selectedMode == getValueInfo('majara')) {

            if($mode)
                $places = Majara::whereCityId($city)->select('id', 'name', 'meta', 'keyword', 'h1', 'tag1',
                'tag2', 'tag3', 'tag4', 'tag5', 'tag6', 'tag7', 'tag8', 'tag9', 'tag10', 'tag11', 'tag12',
                'tag13', 'tag14', 'tag15'
            )->get();
            else
                $places = DB::select('select h.id, h.name, h.meta, h.keyword, h.h1, h.tag1, h.tag2, ' .
                    'h.tag3, h.tag4, h.tag5, h.tag6, h.tag7, h.tag8, h.tag9, h.tag10, h.tag11, h.tag12, h.tag13, h.tag14, h.tag15, c.name as cityName ' .
                    'from majara h, cities c WHERE h.cityId = c.id and c.stateId = ' . $city);

            foreach ($places as $place) {
                $place->kindPlaceId = getValueInfo('majara');
                $place->kindPlaceName = 'ماجرا';
                $out[$counter++] = $place;
            }
        }

        return view('content.changeSeo', ['places' => $out, 'wantedKey' => $wantedKey,
            'selectedMode' => $selectedMode, 'modes' => Place::all(), 'showCity' => !$mode,
            'pageURL' => route('changeSeo', ['city' => $city, 'mode' => $mode, 'wantedKey' => $wantedKey])]);
    }

    public function doChangeSeo() {

        if(isset($_POST["id"]) && isset($_POST["kindPlaceId"]) && isset($_POST["val"]) &&
            isset($_POST["mode"])) {

            $kindPlaceId = makeValidInput($_POST["kindPlaceId"]);
            $id = makeValidInput($_POST["id"]);
            $mode = makeValidInput($_POST["mode"]);
            $val = makeValidInput($_POST["val"]);

            switch ($kindPlaceId) {
                case getValueInfo('hotel'):
                default:
                    try {
                        DB::update('update hotels set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                    break;
                case getValueInfo('adab'):
                    try {
                        DB::update('update adab set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                    break;
                case getValueInfo('amaken'):
                    try {
                        DB::update('update amaken set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                    break;
                case getValueInfo('restaurant'):
                    try {
                        DB::update('update restaurant set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                    break;
                case getValueInfo('majara'):
                    try {
                        DB::update('update majara set ' . $mode . ' = "' . $val . '" where id = ' . $id);
                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                    break;
            }

            $tmp = new AdminLog();
            $tmp->uId = Auth::user()->id;
            $tmp->mode = getValueInfo('changeSeo');
            $tmp->additional1 = $kindPlaceId;
            $tmp->additional2 = $id;
            $tmp->save();

        }

    }

    public function manageNoFollow() {
       return view('config.manageNoFollow', ['access' => ConfigModel::first()]);
    }

    public function changeNoFollow() {

        if(isset($_POST["val"])) {

            $config = ConfigModel::first();

            switch (makeValidInput($_POST["val"])) {

                case "nearby":
                    $config->nearbyNoFollow = !$config->nearbyNoFollow;
                    break;

                case "similar":
                    $config->similarNoFollow = !$config->similarNoFollow;
                    break;

                case "panel":
                    $config->panelNoFollow = !$config->panelNoFollow;
                    break;

                case "profile":
                    $config->profileNoFollow = !$config->profileNoFollow;
                    break;

                case "trip":
                    $config->myTripNoFollow = !$config->myTripNoFollow;
                    break;

                case "comment":
                    $config->writeCommentNoFollow = !$config->writeCommentNoFollow;
                    break;

                case "hotelList":
                    $config->hotelListNoFollow = !$config->hotelListNoFollow;
                    break;

                case "bookmark":
                    $config->bookmarkNoFollow = !$config->bookmarkNoFollow;
                    break;

                case "facebook":
                    $config->facebookNoFollow = !$config->facebookNoFollow;
                    break;

                case "telegram":
                    $config->telegramNoFollow = !$config->telegramNoFollow;
                    break;

                case "googlePlus":
                    $config->googlePlusNoFollow = !$config->googlePlusNoFollow;
                    break;

                case "policy":
                    $config->policyNoFollow = !$config->policyNoFollow;
                    break;

                case "site":
                    $config->externalSiteNoFollow = !$config->externalSiteNoFollow;
                    break;

                case "otherProfile":
                    $config->otherProfileNoFollow = !$config->otherProfileNoFollow;
                    break;

                case "allAns":
                    $config->allAnsNoFollow = !$config->allAnsNoFollow;
                    break;

                case "allComments":
                    $config->allCommentsNoFollow = !$config->allCommentsNoFollow;
                    break;

                case "twitter":
                    $config->twitterNoFollow = !$config->twitterNoFollow;
                    break;

                case "bogen":
                    $config->bogenNoFollow = !$config->bogenNoFollow;
                    break;

                case "gardeshname":
                    $config->gardeshnameNoFollow = !$config->gardeshnameNoFollow;
                    break;

                case "aparat":
                    $config->aparatNoFollow = !$config->aparatNoFollow;
                    break;

                case "instagram":
                    $config->instagramNoFollow = !$config->instagramNoFollow;
                    break;

                case "pinterest":
                    $config->pinterestNoFollow = !$config->pinterestNoFollow;
                    break;

                case "linkedin":
                    $config->linkedinNoFollow = !$config->linkedinNoFollow;
                    break;

                case "backToHotelList":
                    $config->backToHotelListNoFollow = !$config->backToHotelListNoFollow;
                    break;

                case "showReview":
                    $config->showReviewNoFollow = !$config->showReviewNoFollow;
                    break;
            }

            $config->save();
            $tmp  = new AdminLog();
            $tmp->uId = Auth::user()->id;
            $tmp->mode = getValueInfo('changeNoFollow');
            $tmp->save();
        }

    }

    public function myWordsCount($text){

        $text = html_entity_decode($text);
        $text = strip_tags($text);
        $text = trueShowForTextArea($text);

        $arr = explode(' ', $text);
        $words = [];
        $errorWords = [];
        $wordCount = count($arr);

        foreach ($arr as $word) {
            if (array_key_exists($word, $words)) {
                $words[$word] = $words[$word] + 1;
            } else {
                $words[$word] = 1;
            }
        }
        foreach ($words as $word => $count){
            $count = floor($count / $wordCount * 100);
            $words[$word] = $count;
            if($count > 10)
                array_push($errorWords, $word);
        }

        arsort($words);

        return [$words, $errorWords];
    }

    private function myGetWords(string $text) {
        $text = html_entity_decode($text);

        $arr = explode(' ', $text);
        $words = [];

        foreach ($arr as $word) {

            if(mb_strlen($word, 'utf8') > 4) {
                if (array_key_exists($word, $words)) {
                    $words[$word] = $words[$word] + 1;
                } else {
                    $words[$word] = 1;
                }
            }
        }

        arsort($words);

        return $words;
    }

    public function doSeoTest() {

        try {
            $page = new Page($_POST["url"]);

            $analyzer = new Analyzer($page);

            $analyzer->metrics = $page->setMetrics(
                [
                    [Factor::LENGTH => 'url.length'],
                    [Factor::CONTENT => 'content.size'],
                    Factor::KEYWORD,
                    Factor::KEYWORD_HEADERS,
                    Factor::META,
                    Factor::HEADERS,
                    Factor::LOAD_TIME,
                    Factor::SIZE,
                    Factor::ALTS,
                    Factor::SSL,
                    Factor::REDIRECT
                ]
            );
            $results = $analyzer->analyze();
        } catch (HttpException $e) {
            echo "Error loading page: " . $e->getMessage();
        } catch (ReflectionException $e) {
            echo "Error loading metric file: " . $e->getMessage();
        }

        $results["wordDensity"] = $this->myGetWords($page->getFactor('text'));

        return view('seoTester.seoTesterResult', ['results' => $results, 'totalWord' => count(explode(' ', $page->getFactor('text')))]);
    }



    public function seoTester() {
        return view('seoTester.seoTester');
    }



    public function seoTesterSafarnamehContent(Request $request)
    {
        $text = $request->desc;
        $meta = $request->meta;
        $key = $request->keyword;
        $seoTitle = $request->seoTitle;
        $title = $request->title;
        $slug= $request->slug;

        $goodResultCount = 0;
        $badResultCount = 0;
        $warningResultCount = 0;

        $warningResult = '';
        $goodResult = '';
        $badResult = '';

        $uniqueKey = true;
        $uniqueSlug = true;
        $uniqueTitle = true;
        $uniqueSeoTitle = true;

        if($key != null){
            $keyWordDensity = $this->keywordDensity($text, $key);
            if($keyWordDensity > 0.5 && $keyWordDensity < 3) {
                $goodResult .= '<div style="color: green;">تکرار عبارت کلیدی در متن مناسب است. : %'. $keyWordDensity .'</div>';
                $goodResultCount++;
            }
            else if($keyWordDensity >= 3){
                $badResultCount++;
                $badResult .= '<div style="color: red;">تکرار عبارت کلیدی در متن بیش از حد بالاست است. : %'. $keyWordDensity .'</div>';
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">تکرار عبارت کلیدی در متن بیش از حد پایین است. : %'. $keyWordDensity .'</div>';
            }

            $keyWordInFirstPragraph = $this->keywordInText($text, $key, 'firstP');
            if($keyWordInFirstPragraph > 0) {
                $goodResult .= '<div style="color: green;">پاراگراف اول شامل عبارت کلیدی می باشد</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی را در پاراگراف اول به کار ببرید.</div>';
            }

            $keywordInMeta = $this->keywordInText($meta, $key, 'common');
            if($keywordInMeta > 0) {
                $goodResult .= '<div style="color: green;">توضیحات  متا شامل عبارت کلیدی می باشد</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی را در توضیحات متا به کار ببرید.</div>';
            }

            $keywordInTitle = $this->keywordInText($title, $key, 'common');
            if($keywordInTitle > 0) {
                $goodResult .= '<div style="color: green;">عبارت کلیدی در تیتر اصلی یافت شد.</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی در تیتر اصلی یافت نشد.</div>';
            }

            if($slug != null){
                $keywordInSlugTitle = $this->keywordInText($slug, $key, 'slug');
                if($keywordInSlugTitle > 0) {
                    $goodResult .= '<div style="color: green;">نامک شامل عبارت کلیدی می باشد</div>';
                    $goodResultCount++;
                }
                else {
                    $badResultCount++;
                    $badResult .= '<div style="color: red;">عبارت کلیدی را در نامک به کار ببرید.</div>';
                }
            }

            $keywordInSeoTitle = $this->keywordInText($seoTitle, $key, 'common');
            if($keywordInSeoTitle > 0) {
                $goodResult .= '<div style="color: green;">عنوان سئو شامل عبارت کلیدی می باشد</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی را در عنوان سئو به کار ببرید.</div>';
            }

            $keywordDensityInTitle = $this->keywordDensityInTitle($text, $key);
            if($keywordDensityInTitle > 30) {
                $goodResult .= '<div style="color: green;">تیترهای فرعی شامل عبارت کلیدی می باشد. : %'. $keywordDensityInTitle .'</div>';
                $goodResultCount++;
            }
            else if($keywordDensityInTitle == 9999){
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی را در تیترهای فرعی به کار ببرید. : %'. $keywordDensityInTitle .'</div>';
            }

            $keywordNumWord = count(explode(' ', $key));
            if($keywordNumWord > 1 && $keywordNumWord <= 6){
                $goodResult .= '<div style="color: green;">طول عبارت کلیدی مناسب است</div>';
                $goodResultCount++;
            }
            else if($keywordNumWord > 6 && $keywordNumWord <= 10){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">طول عبارت کلیدی می تواند بهینه تر باشد</div>';
            }
            else{
                $badResultCount++;
                $badResult .= '<div style="color: red;">طول عبارت کلیدی نامناسب است </div>';
            }

            $keywordSimiralInDataBase = $this->keywordInDataBase($key, $request->id);
            if($keywordSimiralInDataBase[1] > 0){
                $uniqueKey = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی شما کاملا تکراری است. آن را تغییر دهید.</div>';
            }
            else if(count($keywordSimiralInDataBase[0]) > 0){
                $warningResultCount++;
                $li = '<ul style="padding-right: 20px;">';
                foreach ($keywordSimiralInDataBase[0] as $item)
                    $li .= '<li>' . $item . '</li>';
                $li .= '</ul>';
                $warningResult .= '<div style="color: #dec300;">عبارات کلیدی زیر به کلمه ی کلیدی شما شبیه هستند. اگر می توانید اصلاح کنید:' . $li . '</div>';
            }
            else{
                $goodResult .= '<div style="color: green;"> عبارت کلیدی شما کاملا جدید است.</div>';
                $goodResultCount++;
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">پرکردن کلمه‌کلیدی الزامی است</div>';
        }

        if($slug != null){
            $slugInDataBase = $this->slugInDataBase($slug, $request->id);
            if($slugInDataBase) {
                $goodResult .= '<div style="color: green;">نامک شما یکتاست.</div>';
                $goodResultCount++;
            }
            else {
                $uniqueSlug = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">نامک شما تکراری است. لطفا آن را تغییر دهید</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">پرکردن نامک الزامی است</div>';
        }

        if($seoTitle != null) {
            $seoTitleInDataBase = $this->seoInDataBase($seoTitle, $request->id);
            if ($seoTitleInDataBase) {
                $goodResult .= '<div style="color: green;">عنوان سئو شما یکتاست.</div>';
                $goodResultCount++;
            } else {
                $uniqueSeoTitle = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">عنوان سئو شما تکراری است. لطفا آن را تغییر دهید</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">پرکردن عنوان سئو الزامی است</div>';
        }

        if($text != null){
            $allWordCountInPTotal = $this->allWordCountInP($text);
            $allWordCountInP = $allWordCountInPTotal[0];
            $parError = $allWordCountInPTotal[1];

            if($allWordCountInP[0] > 300 && $allWordCountInP[0] <= 900){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;"> صفحه شما در حال حاضر  پست تلقی می شود اگر می خواهید مقاله محسوب شود طول آن را به بیش از 900 کلمه تغییر دهید:' . $allWordCountInP[0] . ' کلمه </div>';
            }
            else if($allWordCountInP[0] > 900){
                $goodResult .= '<div style="color: green;">صفحه شما متن تخصصی یا مقاله تلقی می شود و مناسب است.:' . $allWordCountInP[0] . ' کلمه</div>';
                $goodResultCount++;
            }
            else{
                $warningResultCount++;
                $warningResult .= '<div style="color: #ffb938;">متن شما کوتاه تر از 300 کلمه است و اصلا توصیه نمی شود. :' . $allWordCountInP[0] . 'کلمه</div>';
            }

            if(count($allWordCountInP) > 3) {
                $goodResult .= '<div style="color: green;">خوانایی متن شما مناسب است.</div>';
                $goodResultCount++;
            }
            else {
                $warningResultCount++;
                $warningResult .= '<div style="color: red;">تعداد پاراگراف متن شما بسیار کم است و ممکن است در خوانایی آن ایراد ایجاد کند</div>';
            }

            if($parError != 0){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">طول بعضی از پاراگراف ها بیش از 150 کلمه می باشد که پیشنهاد نمی گردد. پارگراف: ' . $parError . '</div>';
            }
            else{
                $goodResult .= '<div style="color: green;">طول پاراگراف ها مناسب است.</div>';
                $goodResultCount++;
            }

            $titles = $this->getAllTitles($text);
            if(count($titles[0]) < 1){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">احتمالا متن شما جامع نیست ، بهتر است از تیتر های فرعی استفاده کنید</div>';
            }
            else{
                $goodResult .= '<div style="color: green;">استفاده از تیترهای زیرمجموعه مناسب است</div>';
                $goodResultCount++;
            }
            if($titles[1] == true){
                $badResultCount++;
                $badResult .= '<div style="color: red;">توزیع تیترها در متن مناسب نیست. </div>';
            }
            else{
                $goodResult .= '<div style="color: green;">توزیع تیترها مناسب است</div>';
                $goodResultCount++;
            }

            $sentences = $this->sentencesCount($text);
            if($sentences > 30){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">بیش از سی درصد جملات دارای بیش از بیست کلمه می باشند  که پیشنهاد نمی گردد.: %' . $sentences . '</div>';
            }
            else{
                $goodResult .= '<div style="color: green;">تمامی جملات زیر 20 کلمه دارند.</div>';
                $goodResultCount++;
            }

            $img = $this->imgInText($text);
            if($img[0] > 0){
                $goodResult .= '<div style="color: green;">متن شما دارای عکس می باشد.</div>';
                $goodResultCount++;
            }
            else{
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">متن شما نیاز به عکس دارد. بدون عکس متن خوانایی مناسبی ندارد.</div>';
            }
            if($img[1] == $img[0]){
                $goodResult .= '<div style="color: green;">تمام عکس ها دارای عبارت جایگزین می باشند.</div>';
                $goodResultCount++;
            }
            else{
                $badResultCount++;
                $badResult .= '<div style="color: red;">بای تمام عکس ها عبارت جایگزین را تعریف کنید.</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">نوشتن متن مقاله الزامی است.</div>';
        }

        if($title != null){
            $titleInDataBase= $this->titleInDataBase($title, $request->id);
            if($titleInDataBase){
                $goodResultCount++;
                $goodResult .= '<div style="color: green;">عنوان مقاله شما یکتاست.</div>';
            }
            else{
                $uniqueTitle = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">عنوان مقاله شما تکراری است. لطفا آن را تغییر دهید</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">عنوان مقاله الزامی است.</div>';
        }


        if(mb_strlen($seoTitle, 'utf8') <= 85 && mb_strlen($seoTitle, 'utf8') > 60){
            $goodResultCount++;
            $goodResult .= '<div style="color: green;">طول عنوان سئو مناسب است.</div>';
        }
        else if(mb_strlen($seoTitle, 'utf8') > 85){
            $badResultCount++;
            $badResult .= '<div style="color: red;">عنوان سئو بیش از حد بلند است: ' . mb_strlen($seoTitle, 'utf8') . ' کاراکتر</div>';
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">عنوان سئو بیش از حد کوتاه است: ' . mb_strlen($seoTitle, 'utf8') . ' کاراکتر</div>';
        }

        if(mb_strlen($meta, 'utf8') <= 160 && mb_strlen($meta, 'utf8') > 120){
            $goodResultCount++;
            $goodResult .= '<div style="color: green;">طول توضیح متا مناسب است.</div>';
        }
        else if(mb_strlen($meta, 'utf8') > 160){
            $badResultCount++;
            $badResult .= '<div style="color: red;">توضیح متا بیش از حد بلند است: ' . mb_strlen($meta, 'utf8') . ' کاراکتر است</div>';
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">توضیح متا بیش از حد کوتاه است: ' . mb_strlen($meta, 'utf8') . ' کاراکتر است</div>';
        }

        echo json_encode([$warningResult, $goodResult, $badResult, $badResultCount, $warningResultCount, $uniqueKey, $uniqueSlug, $uniqueTitle, $uniqueSeoTitle]);
    }

    public function placeSeoTest(Request $request)
    {
        $text = $request->text;
        $meta = $request->meta;
        $key = $request->keyword;
        $seoTitle = $request->seoTitle;
        $slug= $request->slug;
        $name = $request->name;

        $goodResultCount = 0;
        $badResultCount = 0;
        $warningResultCount = 0;

        $warningResult = '';
        $goodResult = '';
        $badResult = '';

        $uniqueKey = true;
        $uniqueSlug = true;
        $uniqueTitle = true;
        $uniqueSeoTitle = true;


        if($key != null){
            $keyWordDensity = $this->keywordDensity($text, $key);
            if($keyWordDensity > 1.5 && $keyWordDensity < 3) {
                $goodResult .= '<div style="color: green;">تکرار عبارت کلیدی در متن مناسب است. : %'. $keyWordDensity .'</div>';
                $goodResultCount++;
            }
            else if($keyWordDensity >= 3){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">تکرار عبارت کلیدی در متن بیش از حد بالاست است. : %'. $keyWordDensity .'</div>';
            }
            else {
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">تکرار عبارت کلیدی در متن بیش از حد پایین است. : %'. $keyWordDensity .'</div>';
            }

            $keywordInMeta = $this->keywordInText($meta, $key, 'common');
            if($keywordInMeta > 0) {
                $goodResult .= '<div style="color: green;">توضیحات  متا شامل عبارت کلیدی می باشد</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی را در توضیحات متا به کار ببرید.</div>';
            }

            if($slug != null){
                $keywordInSlugTitle = $this->keywordInText($slug, $key, 'slug');
                if($keywordInSlugTitle > 0) {
                    $goodResult .= '<div style="color: green;">نامک شامل عبارت کلیدی می باشد</div>';
                    $goodResultCount++;
                }
                else {
                    $badResultCount++;
                    $badResult .= '<div style="color: red;">عبارت کلیدی را در نامک به کار ببرید.</div>';
                }
            }

            $keywordInSeoTitle = $this->keywordInText($seoTitle, $key, 'common');
            if($keywordInSeoTitle > 0) {
                $goodResult .= '<div style="color: green;">عنوان سئو شامل عبارت کلیدی می باشد</div>';
                $goodResultCount++;
            }
            else {
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی را در عنوان سئو به کار ببرید.</div>';
            }

            $keywordNumWord = count(explode(' ', $key));
            if($keywordNumWord > 0 && $keywordNumWord <= 6){
                $goodResult .= '<div style="color: green;">طول عبارت کلیدی مناسب است</div>';
                $goodResultCount++;
            }
            else if($keywordNumWord > 6 && $keywordNumWord <= 10){
                $warningResultCount++;
                $warningResult .= '<div style="color: #dec300;">طول عبارت کلیدی می تواند بهینه تر باشد</div>';
            }
            else{
                $badResultCount++;
                $badResult .= '<div style="color: red;">طول عبارت کلیدی نامناسب است </div>';
            }

            $keywordSimiralInDataBase = $this->keywordInPlaceDataBase($key, $request->id, $request->kindPlaceId);
            if(!$keywordSimiralInDataBase){
                $uniqueKey = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">عبارت کلیدی شما کاملا تکراری است. آن را تغییر دهید.</div>';
            }
            else{
                $goodResult .= '<div style="color: green;"> عبارت کلیدی شما کاملا جدید است.</div>';
                $goodResultCount++;
            }

            if($name != null && $name != ''){
                $keywordInName = $this->keywordInText($name, $key, 'common');
                if($keywordInSeoTitle > 0) {
                    $goodResult .= '<div style="color: green;">نام شامل عبارت کلیدی می باشد</div>';
                    $goodResultCount++;
                }
                else {
                    $badResultCount++;
                    $badResult .= '<div style="color: red;">عبارت کلیدی را در نام به کار ببرید.</div>';
                }
            }
            else{
                $badResultCount++;
                $badResult .= '<div style="color: red;">پرکردن نام الزامی است.</div>';
            }

        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">پرکردن کلمه‌کلیدی الزامی است</div>';
        }

        if($slug != null){
            $slugInDataBase = $this->slugInPlaceDataBase($slug, $request->id, $request->kindPlaceId);
            if($slugInDataBase) {
                $goodResult .= '<div style="color: green;">نامک شما یکتاست.</div>';
                $goodResultCount++;
            }
            else {
                $uniqueSlug = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">نامک شما تکراری است. لطفا آن را تغییر دهید</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">پرکردن نامک الزامی است</div>';
        }

        if($seoTitle != null) {
            $seoTitleInDataBase = $this->seoInPlaceDataBase($seoTitle, $request->id, $request->kindPlaceId);
            if ($seoTitleInDataBase) {
                $goodResult .= '<div style="color: green;">عنوان سئو شما یکتاست.</div>';
                $goodResultCount++;
            } else {
                $uniqueSeoTitle = false;
                $badResultCount++;
                $badResult .= '<div style="color: red;">عنوان سئو شما تکراری است. لطفا آن را تغییر دهید</div>';
            }
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">پرکردن عنوان سئو الزامی است</div>';
        }

        $countAllWord = $this->myWordsCount($text);
        $countError = $countAllWord[1];
        $allWord = $countAllWord[0];
        if(count($countError) > 0){
            $warningResultCount++;
            $warningResult .= '<div style="color: #dec300;">تعداد تکرار کلمات زیر بیش از 10 درصد می باشد:';
            $warningResult .= '<ul>';
            foreach ($countError as $item){
                $warningResult .= '<li>' . $item . '</li>';
            }
            $warningResult .= '</ul></div>';
        }

        if(mb_strlen($seoTitle, 'utf8') <= 85 && mb_strlen($seoTitle, 'utf8') > 60){
            $goodResultCount++;
            $goodResult .= '<div style="color: green;">طول عنوان سئو مناسب است.</div>';
        }
        else if(mb_strlen($seoTitle, 'utf8') > 85){
            $badResultCount++;
            $badResult .= '<div style="color: red;">عنوان سئو بیش از حد بلند است: ' . mb_strlen($seoTitle, 'utf8') . ' کاراکتر</div>';
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">عنوان سئو بیش از حد کوتاه است: ' . mb_strlen($seoTitle, 'utf8') . ' کاراکتر</div>';
        }

        if(mb_strlen($meta, 'utf8') <= 160 && mb_strlen($meta, 'utf8') > 120){
            $goodResultCount++;
            $goodResult .= '<div style="color: green;">طول توضیح متا مناسب است.</div>';
        }
        else if(mb_strlen($meta, 'utf8') > 160){
            $badResultCount++;
            $badResult .= '<div style="color: red;">توضیح متا بیش از حد بلند است: ' . mb_strlen($meta, 'utf8') . ' کاراکتر است</div>';
        }
        else{
            $badResultCount++;
            $badResult .= '<div style="color: red;">توضیح متا بیش از حد کوتاه است: ' . mb_strlen($meta, 'utf8') . ' کاراکتر است</div>';
        }

        echo json_encode([$warningResult, $goodResult, $badResult, $badResultCount, $warningResultCount, $uniqueKey, $uniqueSlug, $uniqueTitle, $uniqueSeoTitle]);
    }


    private function keywordDensity($text, $keyword){
        $text = html_entity_decode($text);
        $text = strip_tags($text);
        $text = trueShowForTextArea($text);


        $arr = explode(' ', $text);
        $countAllWords = count($arr);
        if(count(explode(' ', $keyword)) == 1) {
            $keyInText = 0;
            foreach ($arr as $item) {
                if ($item == $keyword)
                    $keyInText++;
            }
        }
        else
            $keyInText = substr_count($text, $keyword);

        $keywordCount = count(explode(' ', $keyword));

        $keyWordDensity = (( (int)$keywordCount * (int)$keyInText) / (int)$countAllWords) * 100;
        $keyWordDensity = floor($keyWordDensity * 100)/100;

        return $keyWordDensity;
    }

    private function keywordInText($text, $keyword, $textKind){
        if($textKind == 'firstP'){
            $startP = strpos($text, '<p');
            $start = strpos($text, '>', $startP);
            $end = strpos($text, '</p>', $start);
            $len = $end - $start;
            $p = substr($text, $start+1,  $len);
        }
        else if($textKind == 'common')
            $p = $text;
        else if($textKind == 'slug')
            $p = str_replace('_', ' ', $text);

        $p = html_entity_decode($p);
        $p = strip_tags($p);
        $p = trueShowForTextArea($p);

        if(count(explode(' ', $keyword)) == 1){
            $arr = explode(' ', $p);
            $keyInText = 0;
            foreach ($arr as $item){
                if($item == $keyword)
                    $keyInText++;
            }
        }
        else
            $keyInText = substr_count($p, $keyword);

        return $keyInText;
    }

    private function keywordDensityInTitle($text, $keyword){

        $totalCount = 0;
        $keyCount = 0;

        for($i = 1; $i < 6; $i++){
            $header = 'h'.$i;

            $s = 0;
            while(true){
                $st = strpos($text, '<'.$header, $s);

                if(!is_int($st))
                    break;

                $start = strpos($text, '>', $st);
                $end = strpos($text, '</' . $header . '>', $start);
                $len = $end - $start;
                $txt = substr($text, $start+1,  $len);

                $txt = html_entity_decode($txt);
                $txt = strip_tags($txt);
                $txt = trueShowForTextArea($txt);

                $arr = explode(' ', $txt);
                $totalCount++;
//                $totalCount += count($arr);

                if(count(explode(' ', $keyword)) == 1) {
                    $keyInText = 0;
                    foreach ($arr as $item) {
                        if ($item == $keyword)
                            $keyInText++;
                    }
                    $keyCount += $keyInText;
                }

                $keyCount += substr_count($txt, $keyword);

                $s = $end;
            }
        }

        $keywordCount = count(explode(' ', $keyword));

        $zeroTitle = false;
        if($totalCount == 0)
            $zeroTitle = true;

        if(!$zeroTitle) {
            $keyWordDensity = ((int)$keyCount / (int)$totalCount) * 100;
            $keyWordDensity = floor($keyWordDensity * 100) / 100;
        }
        else
            $keyWordDensity = 9999;

        return $keyWordDensity;
    }

    private function keywordInDataBase($keyword, $id)
    {
        $allKey = Safarnameh::select(['keyword', 'id'])->whereNotNull('keyword')->get();
        $same = 0;
        $similar = array();
        foreach ($allKey as $item){
            similar_text($item->keyword, $keyword, $percent);
            if($percent == 100 && $item->id != $id)
                $same++;
            else if($percent > 70 && $item->id != $id)
                array_push($similar, $item->keyword);
        }

        return [$similar, $same];
    }

    private function keywordInPlaceDataBase($keyword, $id, $kindPlaceId)
    {
        $kindPlace = Place::whereNotNull('tableName')->get();
        foreach ($kindPlace as $item){
            if($item->tableName != 'majara'){
                if($item->id == $kindPlaceId)
                    $place = DB::table($item->tableName)->where('keyword', $keyword)->where('id', '!=', $id)->first();
                else
                    $place = DB::table($item->tableName)->where('keyword', $keyword)->first();

                if($place != null)
                    return false;
            }
        }

        return true;
    }

    private function seoInDataBase($seoTitle, $id)
    {
        $seo = Safarnameh::where('seoTitle', $seoTitle)->where('id', '!=', $id)->first();

        if($seo == null)
            return true;
        else
            return false;
    }

    private function seoInPlaceDataBase($seoTitle, $id, $kindPlaceId)
    {
        $kindPlace = Place::where('tableName', '!=', null)->get();
        foreach ($kindPlace as $item){
            if($item->id == $kindPlaceId)
                $place = DB::table($item->tableName)->where('seoTitle', $seoTitle)->where('id', '!=', $id)->get();
            else
                $place = DB::table($item->tableName)->where('seoTitle', $seoTitle)->get();

            if(count($place) != 0)
                return false;
        }
        return true;
    }

    private function slugInDataBase($slug, $id)
    {
        $s = Safarnameh::where('slug', $slug)->where('id', '!=', $id)->first();

        if($s == null)
            return true;
        else
            return false;
    }

    private function slugInPlaceDataBase($slug, $id, $kindPlaceId)
    {
        $kindPlace = Place::where('tableName', '!=', null)->get();
        foreach ($kindPlace as $item){
            if($item->id == $kindPlaceId)
                $place = DB::table($item->tableName)->where('slug', $slug)->where('id', '!=', $id)->get();
            else
                $place = DB::table($item->tableName)->where('slug', $slug)->get();

            if(count($place) != 0)
                return false;
        }
        return true;
    }

    private function allWordCountInP($text){
        $countWordInP = [0];
        $s = 0;
        $parNumError = 0;
        while(true){
            $st = strpos($text, '<p', $s);

            if(!is_int($st) || $st == -1)
                break;

            $start = strpos($text, '>', $st);
            $end = strpos($text, '</p>', $start);
            $len = $end - $start;
            $p = substr($text, $start+1,  $len);

            $p = html_entity_decode($p);
            $p = strip_tags($p);

            $numWord = count(explode(' ', $p));
            $countWordInP[0] += $numWord;
            array_push($countWordInP, $numWord);

            if($numWord > 150)
                $parNumError = count($countWordInP)-1;
            $s = $end;
        }

        return [$countWordInP, $parNumError];
    }

    private function getAllTitles($text){
        $titels = array();

        $s = 0;
        while(true){
            $st = strpos($text, '</h', $s);
            if(!is_int($st) || $st == -1)
                break;

            $h = substr($text, $st+2, 2);
            array_push($titels, $h);
            $s = $st + 2;
        }

        $error = false;

        for($i = 0; $i < count($titels); $i++){
            if(($i + 1) < count($titels) && !$error) {
                switch ($titels[$i]){
                    case 'h1':
                        if(!($titels[$i+1] == 'h1' || $titels[$i+1] == 'h2'))
                            $error = true;
                        break;
                    case 'h2':
                        if(!($titels[$i+1] == 'h1' || $titels[$i+1] == 'h2' || $titels[$i+1] == 'h3'))
                            $error = true;
                        break;
                    case 'h3':
                        if(!($titels[$i+1] == 'h1' || $titels[$i+1] == 'h2' || $titels[$i+1] == 'h3' || $titels[$i+1] == 'h4'))
                            $error = true;
                        break;
                }
            }
            else
                break;
        }

        return [$titels, $error];
    }

    private function sentencesCount($text){
        $overCount = 0;
        $s = 0;
        $totalCount = 0;
        $i = 0;
        while(true){
            $st = strpos($text, '<p', $s);
            if(!is_int($st) || $st == -1)
                break;

            $start = strpos($text, '>', $st);
            $end = strpos($text, '</p>', $start);
            $len = $end - $start;

            $p = substr($text, $start+1,  $len);
            $p = html_entity_decode($p);
            $p = strip_tags($p);

            if($p != null){
                $ss = 0;
                $i++;
                while(true){
                    $stSenc1 = strpos($p, '.', $ss);
                    $stSenc2 = strpos($p, '؟', $ss);
                    $stSenc3 = strpos($p, '!', $ss);

                    if($stSenc1 == false && $stSenc2 == false && $stSenc3 == false)
                        break;

                    if($stSenc1 == false)
                        $stSenc1 = 99999999999;
                    if($stSenc2 == false)
                        $stSenc2 = 99999999999;
                    if($stSenc3 == false)
                        $stSenc3 = 99999999999;

                    if($stSenc2 > $stSenc3)
                        $stSenc2 = $stSenc3;
                    if($stSenc1 > $stSenc2)
                        $stSenc = $stSenc2;
                    else
                        $stSenc = $stSenc1;

                    if(!is_int($stSenc) || $stSenc == -1)
                        break;

                    $len = $stSenc - $ss;
                    $sentences = substr($p, $ss,  $len);
                    $ss = $stSenc + 1;

                    $exp = explode(' ', $sentences);
                    if(count($exp) > 20)
                        $overCount++;

                    $totalCount++;
                }
            }
            $s = $end;
        }

        if($totalCount > 0)
            $percent = ($overCount / $totalCount) * 100;
        else
            $percent = 0;

        $percent = floor($percent * 100)/100;

        return $percent;
    }

    private function imgInText($text){
        $imgCount = 0;
        $haveAlt = 0;
        $s = 0;
        while(true){
            $st = strpos($text, '<img', $s);
            if(!is_int($st) || $st == -1)
                break;

            $end = strpos($text, '>', $st);
            $len = $end - $st;
            $img = substr($text, $st,  $len);

            $alt =  strpos($img, 'alt=""');
            if(!is_int($alt) || $alt == -1)
                $haveAlt++;

            $imgCount++;

            $s = $end;
        }

        return [$imgCount, $haveAlt];
    }

    private function titleInDataBase($title, $id){
        $s = Safarnameh::where('title', $title)->where('id', '!=', $id)->first();

        if($s == null)
            return true;
        else
            return false;
    }

}
