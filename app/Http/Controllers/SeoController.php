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
use App\models\Restaurant;
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

    private function myGetWords(string $text) {

        $text = html_entity_decode($text);
        $arr = explode(' ', $text);
        $words = [];


        foreach ($arr as $word) {

            if(strlen($word) > 4) {

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

}
