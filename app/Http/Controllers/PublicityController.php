<?php

namespace App\Http\Controllers;

use App\models\Company;
use App\models\Publicity;
use App\models\Section;
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

    public function addSection() {

        $err = '';
        if (isset($_POST['name'])) {
            $name = makeValidInput($_POST['name']);
            $section = new Section();
            $section->name = $name;

            try {
                $section->save();
                return Redirect::route('section');
            } catch (Exception $e) {
                $err = 'مسیر مورد نظر در سامانه موجود است';
            }
        }

        return view('config.publicity.section', ['section' => Section::all(), 'msg' => $err]);
    }

    public function deleteSection() {

        if (isset($_POST['deleteSection'])) {
            $sectionId = makeValidInput($_POST["deleteSection"]);
            Section::destroy($sectionId);
            return Redirect::route('section');
        }

        return view('section', ['section' => Section::all(), 'msg' => '']);
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
            isset($_FILES["pic"]) && isset($_POST["parts"])
        ) {

            $startDate = convertDateToString(makeValidInput($_POST["startDate"]));
            $endDate = convertDateToString(makeValidInput($_POST["endDate"]));

            if($startDate > $endDate)
                $msg = "تاریخ آغاز باید قبل از تاریخ اتمام باشد";
            else {

                $companyId = makeValidInput($_POST["companyId"]);
                $states = $_POST["states"];
                $sections = $_POST["sections"];
                $parts = $_POST["parts"];

                $url = makeValidInput($_POST["url"]);

                $file = $_FILES["pic"];
                $targetFile = __DIR__ . "/../../../../ads/" . $file["name"];

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

                        $counter = 0;

                        foreach ($sections as $section) {
                            $section = makeValidInput($section);
                            $tmp = new SectionPublicity();
                            $tmp->sectionId = $section;
                            $tmp->publicityId = $adv->id;
                            $tmp->part = makeValidInput($parts[$counter++]);
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
                if(file_exists(__DIR__ . '/../../../public/ads/' . $tmp->pic))
                    unlink(__DIR__ . '/../../../public/ads/' . $tmp->pic);
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
            isset($_POST["endDate"]) && isset($_POST["states"]) && isset($_POST["sections"]) && isset($_POST["url"]) &&
            isset($_POST["parts"])
        ) {

            $startDate = convertDateToString(makeValidInput($_POST["startDate"]));
            $endDate = convertDateToString(makeValidInput($_POST["endDate"]));

            if($startDate > $endDate)
                $msg = "تاریخ آغاز باید قبل از تاریخ اتمام باشد";
            else {

                $companyId = makeValidInput($_POST["companyId"]);
                $states = $_POST["states"];
                $sections = $_POST["sections"];
                $parts = $_POST["parts"];
                $url = makeValidInput($_POST["url"]);
                $err = "";

                if(isset($_FILES["pic"]) && $ad->pic != $_FILES["pic"]["name"]) {

                    $file = $_FILES["pic"];
                    $targetFile = __DIR__ . "/../../../public/ads/" . $file["name"];

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

                        $counter = 0;

                        foreach ($sections as $section) {
                            $section = makeValidInput($section);
                            $tmp = new SectionPublicity();
                            $tmp->publicityId = $ad->id;
                            $tmp->sectionId = $section;
                            $tmp->part = makeValidInput($parts[$counter++]);
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
            if($tmp != null) {
                $section->select = 1;
                $section->part = $tmp->part;
            }
            else
                $section->select = 0;
        }

        return view('config.publicity.ads', array('companies' => Company::all(), 'sections' => $sections, 'ad' => Publicity::whereId($adId),
            'states' => $states, 'mode' => 'edit', 'msg' => $msg));
    }
}
