<?php

namespace App\Http\Controllers;

use App\models\Company;
use App\models\Publicity;
use App\models\Section;
use App\models\SectionPage;
use App\models\SectionPublicity;
use App\models\State;
use App\models\StatePublicity;
use Exception;
use Illuminate\Support\Facades\Redirect;

class PublicityController extends Controller {

    public function addCompany() {

        $err = '';

        if (isset($_POST['name'])) {
            $name = makeValidInput($_POST['name']);
            $company = new Company();
            $company->name = $name;

            try {
                $company->save();
                return Redirect::route('company');
            } catch (Exception $e) {
                $err = 'شرکت مورد نظر در سامانه موجود است';
            }
        }

        return view('config.publicity.company', ['company' => Company::all(), 'msg' => $err]);
    }

    public function deleteCompany() {

        if (isset($_POST['deleteCompany'])) {
            $companyId = makeValidInput($_POST["deleteCompany"]);
            Company::destroy($companyId);
            return Redirect::route('company');
        }

        return view('company', ['company' => Company::all(), 'msg' => '']);
    }

    public function sectionStep2($sectionId) {
        
        $section = Section::whereId($sectionId);
        
        if($section == null)
            return Redirect::route('section');

        $pages = SectionPage::whereSectionId($sectionId)->select('page')->get();
        $out = [];
        $counter = 0;

        foreach ($pages as $page)
            $out[$counter++] = $page->page;
        
        return view('config.publicity.sectionStep2', ['section' => $section, 'pages' => $out]);
    }

    public function addPageToSection($sectionId) {

        if(isset($_POST["page"])) {

            $section = Section::whereId($sectionId);

            if($section == null)
                return Redirect::route('section');

            $page = $_POST["page"];
            SectionPage::whereSectionId($sectionId)->delete();
            
            foreach ($page as $itr) {
                $tmp = new SectionPage();
                $tmp->sectionId = $sectionId;
                $tmp->page = $itr;
                $tmp->save();
            }
        }

        return Redirect::route('section');
    }
    
    public function addSection() {

        $err = '';

        if (
            isset($_POST['name']) && isset($_POST['mobileHidden']) &&
            isset($_POST["width"]) && isset($_POST["height"]) && isset($_POST["backgroundSize"])
        ) {
            $name = makeValidInput($_POST['name']);
            $section = new Section();
            $section->name = $name;
            $section->top_ = (isset($_POST["top"])&& !empty($_POST["top"])) ? makeValidInput($_POST["top"]) : -1;
            $section->left_ = (isset($_POST["left"])&& !empty($_POST["left"])) ? makeValidInput($_POST["left"]) : -1;
            $section->right_ = (isset($_POST["right"])&& !empty($_POST["right"])) ? makeValidInput($_POST["right"]) : -1;
            $section->bottom_ = (isset($_POST["bottom"])&& !empty($_POST["bottom"])) ? makeValidInput($_POST["bottom"]) : -1;
            $section->width = makeValidInput($_POST["width"]);
            $section->height = makeValidInput($_POST["height"]);
            $section->backgroundSize = (makeValidInput($_POST["backgroundSize"]) == "1");

            $section->mobileHidden = (makeValidInput($_POST["mobileHidden"]) == "1");

            if(makeValidInput($_POST["mobileHidden"]) != "1") {
                $section->mobileTop = (isset($_POST["mobileTop"])&& !empty($_POST["mobileTop"])) ? makeValidInput($_POST["mobileTop"]) : -1;
                $section->mobileLeft = (isset($_POST["mobileLeft"])&& !empty($_POST["mobileLeft"])) ? makeValidInput($_POST["mobileLeft"]) : -1;
                $section->mobileRight = (isset($_POST["mobileRight"])&& !empty($_POST["mobileRight"])) ? makeValidInput($_POST["mobileRight"]) : -1;
                $section->mobileBottom = (isset($_POST["mobileBottom"])&& !empty($_POST["mobileBottom"])) ? makeValidInput($_POST["mobileBottom"]) : -1;
            }

            try {
                $section->save();
                return Redirect::route('sectionStep2', ['sectionId' => $section->id]);
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }

        $sections = Section::all();

        foreach ($sections as $section) {

            switch ($section->page) {
                case getValueInfo('hotel-detail'):
                    $section->page = 'hotel-detail';
                    break;
                case getValueInfo('adab-detail'):
                    $section->page = 'adab-detail';
                    break;
                case getValueInfo('majara-detail'):
                    $section->page = 'majara-detail';
                    break;
                case getValueInfo('amaken-detail'):
                    $section->page = 'amaken-detail';
                    break;
                case getValueInfo('restaurant-detail'):
                    $section->page = 'restarant-detail';
                    break;
                case getValueInfo('main_page'):
                    $section->page = 'main_page';
                    break;
                case getValueInfo('hotel-list'):
                    $section->page = 'hotel-list';
                    break;
                case getValueInfo('adab-list'):
                    $section->page = 'adab-list';
                    break;
                case getValueInfo('majara-list'):
                    $section->page = 'majara-list';
                    break;
                case getValueInfo('restaurant-list'):
                    $section->page = 'restaurant-list';
                    break;
                case getValueInfo('amaken-list'):
                    $section->page = 'amaken-list';
                    break;
            }
        }

        return view('config.publicity.section', ['section' => $sections, 'msg' => $err]);
    }

    public function deleteSection() {

        if (isset($_POST['deleteSection'])) {
            $sectionId = makeValidInput($_POST["deleteSection"]);
            Section::destroy($sectionId);
            return Redirect::route('section');
        }

        return view('section', ['section' => Section::all(), 'msg' => '']);
    }

    public function editSection() {

        if(isset($_POST["id"])) {

            $section = Section::whereId(makeValidInput($_POST["id"]));
            
            if ($section == null)
                return Redirect::route('home');
            
            $section->name = makeValidInput($_POST["name"]);
            $section->top_ = (isset($_POST["top"])&& !empty($_POST["top"])) ? makeValidInput($_POST["top"]) : -1;
            $section->left_ = (isset($_POST["left"])&& !empty($_POST["left"])) ? makeValidInput($_POST["left"]) : -1;
            $section->right_ = (isset($_POST["right"])&& !empty($_POST["right"])) ? makeValidInput($_POST["right"]) : -1;
            $section->bottom_ = (isset($_POST["bottom"])&& !empty($_POST["bottom"])) ? makeValidInput($_POST["bottom"]) : -1;
            $section->width = makeValidInput($_POST["width"]);
            $section->height = makeValidInput($_POST["height"]);
            $section->backgroundSize = (makeValidInput($_POST["backgroundSize"]) == "1");

            $section->mobileHidden = (makeValidInput($_POST["mobileHidden"]) == "1");

            if(makeValidInput($_POST["mobileHidden"]) != "1") {
                $section->mobileTop = (isset($_POST["mobileTop"])&& !empty($_POST["mobileTop"])) ? makeValidInput($_POST["mobileTop"]) : -1;
                $section->mobileLeft = (isset($_POST["mobileLeft"])&& !empty($_POST["mobileLeft"])) ? makeValidInput($_POST["mobileLeft"]) : -1;
                $section->mobileRight = (isset($_POST["mobileRight"])&& !empty($_POST["mobileRight"])) ? makeValidInput($_POST["mobileRight"]) : -1;
                $section->mobileBottom = (isset($_POST["mobileBottom"])&& !empty($_POST["mobileBottom"])) ? makeValidInput($_POST["mobileBottom"]) : -1;
            }
            
            $section->save();


            return Redirect::route('section');
        }
    }

    public function seeAds() {

        $ads = Publicity::all();

        foreach ($ads as $ad) {

            $tmp = SectionPublicity::wherePublicityId($ad->id)->get();
            $ad->sections = "";
            foreach ($tmp as $itr)
                $ad->sections .= Section::whereId($itr->sectionId)->name . ' - ';

            $ad->companyId = Company::whereId($ad->companyId)->name;
            $ad->from_ = convertStringToDate($ad->from_);
            $ad->to_ = convertStringToDate($ad->to_);
            $tmp = StatePublicity::wherePublicityId($ad->id)->get();
            $ad->states = "";
            foreach ($tmp as $itr)
                $ad->states .= State::whereId($itr->stateId)->name . ' - ';
        }

        return view('config.publicity.ads', ['ads' => $ads, 'mode' => 'see']);
    }

    public function addAds() {

        $msg = "";

        if(isset($_POST["addPublicity"]) && isset($_POST["startDate"]) && isset($_POST["companyId"]) &&
            isset($_POST["endDate"]) && isset($_POST["states"]) && isset($_POST["sections"]) && isset($_POST["url"]) &&
            isset($_FILES["pic"])
        ) {

            $startDate = convertDateToString(makeValidInput($_POST["startDate"]));
            $endDate = convertDateToString(makeValidInput($_POST["endDate"]));

            if($startDate > $endDate)
                $msg = "تاریخ آغاز باید قبل از تاریخ اتمام باشد";
            else {

                $companyId = makeValidInput($_POST["companyId"]);
                $states = $_POST["states"];
                $sections = $_POST["sections"];

                $url = makeValidInput($_POST["url"]);

                $file = $_FILES["pic"];
                $targetFile = __DIR__ . "/../../../../assets/ads/" . $file["name"];

                $err = "";

                if (!file_exists($targetFile)) {
                    $err = uploadCheck($targetFile, "pic", "ایجاد تبلیغ جدید", 300000000, -1);
                    if (empty($err)) {
                        $err = upload($targetFile, "pic", "ایجاد تبلیغ جدید");
                    }
                }

                if (empty($err)) {
                    try {

                        $adv = new Publicity();
                        $adv->companyId = $companyId;
                        $adv->url = $url;
                        $adv->from_ = $startDate;
                        $adv->to_ = $endDate;
                        $adv->pic = $file["name"];
                        $adv->save();

                        foreach ($sections as $section) {
                            $section = makeValidInput($section);
                            $tmp = new SectionPublicity();
                            $tmp->sectionId = $section;
                            $tmp->publicityId = $adv->id;
                            $tmp->save();
                        }

                        foreach ($states as $state) {
                            $adInState = new StatePublicity();
                            $adInState->stateId = makeValidInput($state);
                            $adInState->publicityId = $adv->id;
                            $adInState->save();
                        }

                        return Redirect::route('seeAds');

                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                    }
                } else
                    $msg = $err;
            }
        }

        return view('config.publicity.ads', array('companies' => Company::all(), 'sections' => Section::all(),
            'states' => State::all(), 'mode' => 'add', 'msg' => $msg));
    }

    public function deleteAd() {

        if (isset($_POST["adId"])) {

            $tmp = Publicity::whereId(makeValidInput($_POST["adId"]));

            if($tmp != null) {
                if(file_exists(__DIR__ . '/../../../../assets/ads/' . $tmp->pic))
                    unlink(__DIR__ . '/../../../../assets/ads/' . $tmp->pic);
                $tmp->delete();
            }
        }

        return Redirect::route('seeAds');

    }

    public function editAd($adId) {

        $msg = "";
        $ad = Publicity::whereId($adId);

        if($ad == null)
            return Redirect::route('seeAds');

        if(isset($_POST["addPublicity"]) && isset($_POST["startDate"]) && isset($_POST["companyId"]) &&
            isset($_POST["endDate"]) && isset($_POST["states"]) && isset($_POST["sections"]) && isset($_POST["url"])
        ) {

            $startDate = convertDateToString(makeValidInput($_POST["startDate"]));
            $endDate = convertDateToString(makeValidInput($_POST["endDate"]));

            if($startDate > $endDate)
                $msg = "تاریخ آغاز باید قبل از تاریخ اتمام باشد";
            else {

                $companyId = makeValidInput($_POST["companyId"]);
                $states = $_POST["states"];
                $sections = $_POST["sections"];
                $url = makeValidInput($_POST["url"]);
                $err = "";

                if(isset($_FILES["pic"]) && $ad->pic != $_FILES["pic"]["name"]) {

                    $file = $_FILES["pic"];
                    $targetFile = __DIR__ . "/../../../../assets/ads/" . $file["name"];

                    if (!file_exists($targetFile)) {
                        $err = uploadCheck($targetFile, "pic", "ایجاد تبلیغ جدید", 300000000, -1);
                        if (empty($err)) {
                            $err = upload($targetFile, "pic", "ایجاد تبلیغ جدید");
                            if(file_exists(__DIR__ . '/../../../../assets/ads/' . $ad->pic))
                                unlink(__DIR__ . '/../../../../assets/ads/' . $ad->pic);

                            $ad->pic = $file["name"];
                        }
                    }
                }

                if (empty($err)) {
                    try {

                        StatePublicity::wherePublicityId($adId)->delete();
                        SectionPublicity::wherePublicityId($adId)->delete();

                        $ad->companyId = $companyId;
                        $ad->url = $url;
                        $ad->from_ = $startDate;
                        $ad->to_ = $endDate;
                        $ad->save();

                        foreach ($sections as $section) {
                            $section = makeValidInput($section);
                            $tmp = new SectionPublicity();
                            $tmp->publicityId = $ad->id;
                            $tmp->sectionId = $section;
                            $tmp->save();
                        }

                        foreach ($states as $state) {
                            $adInState = new StatePublicity();
                            $adInState->stateId = makeValidInput($state);
                            $adInState->publicityId = $ad->id;
                            $adInState->save();
                        }

                        return Redirect::route('seeAds');
                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                    }
                } else
                    $msg = $err;
            }
        }

        $states = State::all();
        foreach ($states as $state) {
            if(StatePublicity::wherePublicityId($adId)->whereStateId($state->id)->count() > 0)
                $state->select = 1;
            else
                $state->select = 0;
        }

        $sections = Section::all();
        foreach ($sections as $section) {
            $tmp = SectionPublicity::wherePublicityId($adId)->whereSectionId($section->id)->first();
            if($tmp != null)
                $section->select = 1;
            else
                $section->select = 0;
        }

        return view('config.publicity.ads', array('companies' => Company::all(), 'sections' => $sections, 'ad' => Publicity::whereId($adId),
            'states' => $states, 'mode' => 'edit', 'msg' => $msg));
    }
}
