<?php

namespace App\Http\Controllers;

use App\models\Cities;
use App\models\PlacePic;
use App\models\Question;
use App\models\Place;
use App\models\QuestionAns;
use App\models\QuestionSection;
use App\models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QuestionsController extends Controller
{
    public function index($kind = '')
    {
        $questions = Question::all();

        foreach ($questions as $item){

            if($item->ansType == 'multi') {
                $item->typeName = 'چند گزینه ای';
                $ans = QuestionAns::where('questionId', $item->id)->get();
                $item->ans = '<ul>';
                foreach ($ans as $item2){
                    $item->ans .= '<li>' . $item2->ans . '</li>';
                }
                $item->ans .= '</ul>';
            }
            else if($item->ansType == 'text')
                $item->typeName = 'متنی';
            else
                $item->typeName = 'درجه ای';

            $kindId = \DB::select('SELECT kindPlaceId FROM questionSections WHERE questionId = ' . $item->id . ' GROUP BY kindPlaceId');
            $item->kindName = '';

            if(count($kindId) == 0) {
                if($item->kindPlaceId == 0)
                    $item->kindName = 'تمامی مکان ها';
                else{
                    $kin = Place::find($item->kindPlaceId);

                    if ($kin != null)
                        $item->kindName = $kin->name;
                }
            }
            else {
                foreach ($kindId as $item2) {

                    if ($item2->kindPlaceId == 0) {
                        $item->kindName = 'تمامی مکان ها';
                        break;
                    } else {
                        $kin = Place::find($item2->kindPlaceId);

                        if ($kin != null)
                            $item->kindName .= ' - ' . $kin->name;
                    }
                }
            }

            $fullrecords = QuestionSection::where('questionId', $item->id)->get();
            $stateId = array();
            $stateIdIndex = array();
            $cityId = array();
            $fullState = true;

            for($i = 0; $i < count($fullrecords); $i++){
                if($fullrecords[$i]->stateId == 0){
                    break;
                }
                else{
                    $fullState = false;
                    if(in_array($fullrecords[$i]->stateId, $stateIdIndex)){
                        $index = array_search($fullrecords[$i]->stateId, $stateIdIndex);
                        if(!$stateId[$index]['full']){

                            if($fullrecords[$i]->cityId == 0)
                                $stateId[$index]['full'] = true;
                            elseif(!in_array($fullrecords[$i]->cityId, $stateId[$index]['cityId'])) {
                                array_push($stateId[$index]['cityId'], $fullrecords[$i]->cityId);
                                array_push($cityId, $fullrecords[$i]->cityId);
                            }

                        }
                    }
                    else {
                        $s = [
                            'stateId' => $fullrecords[$i]->stateId,
                            'cityId' => array(),
                            'full' => false,
                        ];
                        if($fullrecords[$i]->cityId == 0)
                            $s['full'] = true;
                        else {
                            array_push($s['cityId'], $fullrecords[$i]->cityId);
                            array_push($cityId, $fullrecords[$i]->cityId);
                        }

                        array_push($stateId, $s);
                        array_push($stateIdIndex, $fullrecords[$i]->stateId);
                    }
                }
            }

            if(!$fullState) {
                $stateName = \DB::select('SELECT name FROM state WHERE id IN (' . implode(',', $stateIdIndex) . ')');

                if($cityId != [])
                    $cityName = \DB::select('SELECT name, id FROM cities WHERE id IN (' . implode(',', $cityId) . ')');
                else
                    $cityName = array();

                for ($i = 0; $i < count($stateId); $i++) {
                    if ($stateId[$i]['full'])
                        $stateId[$i]['stateName'] = 'در تمام استان ' . $stateName[$i]->name;
                    else {
                        $stateId[$i]['stateName'] = 'استان ' . $stateName[$i]->name;
                        $stateId[$i]['city'] = '<ul>';
                        for ($k = 0; $k < count($stateId[$i]['cityId']); $k++) {
                            for ($j = 0; $j < count($cityName); $j++) {
                                if ($cityName[$j]->id == $stateId[$i]['cityId'][$k]) {
                                    $stateId[$i]['city'] .= '<li style="font-size: 15px;">' . $cityName[$j]->name . '</li>';
//                                    $stateId[$i]['cityId'][$k] = $cityName[$j]->name;
                                    break;
                                }
                            }
                        }
                        $stateId[$i]['city'] .= '</ul>';
                    }
                }

                $item->fullState = false;
                $item->states = $stateId;
            }
            else{
                $item->fullState = true;
                $item->stateName = 'در تمامی استان ها';
            }

        }
        $kindPlace = Place::all();
        return view('config.questions.index', compact(['questions', 'kindPlace']));
    }

    public function newPage()
    {
        $kindPlace = Place::all();
        $state = State::all();
        return view('config.questions.new', compact(['kindPlace', 'state']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'ansType' => 'required',
            'kindPlaceId' => 'required',
            'state' => 'required',
            'city' => 'required',
        ]);

        $newQuestion = new Question();
        $newQuestion->description = $request->question;
        $newQuestion->ansType = $request->ansType;
        $newQuestion->save();

        if($request->ansType == 'multi'){
            $ans = json_decode($request->ans);
            foreach ($ans as $item){
                $newAns = new QuestionAns();
                $newAns->questionId = $newQuestion->id;
                $newAns->ans = $item;
                $newAns->save();
            }
        }

        $kindPlaceId = json_decode($request->kindPlaceId);
        $stateId = json_decode($request->state);
        $cityId = json_decode($request->city);

        if ($kindPlaceId[0] != '0'){
            foreach ($kindPlaceId as $kindId){
                if($stateId[0] != '0'){
                    for($i = 0; $i < count($stateId); $i++){
                        if($cityId[$i] == '0'){
                            $newSection = new QuestionSection();
                            $newSection->questionId = $newQuestion->id;
                            $newSection->kindPlaceId = $kindId;
                            $newSection->stateId = $stateId[$i];
                            $newSection->cityId = 0;
                            $newSection->save();
                        }
                        else{
                            foreach ($cityId[$i] as $cId){
                                $newSection = new QuestionSection();
                                $newSection->questionId = $newQuestion->id;
                                $newSection->kindPlaceId = $kindId;
                                $newSection->stateId = $stateId[$i];
                                $newSection->cityId = $cId;
                                $newSection->save();
                            }
                        }
                    }
                }
                else{
                    $newSection = new QuestionSection();
                    $newSection->questionId = $newQuestion->id;
                    $newSection->kindPlaceId = $kindId;
                    $newSection->stateId = 0;
                    $newSection->cityId = 0;
                    $newSection->save();
                }
            }
        }
        else{
            if($stateId[0] != '0'){
                for($i = 0; $i < count($stateId); $i++){
                    if($cityId[$i] == '0'){
                        $newSection = new QuestionSection();
                        $newSection->questionId = $newQuestion->id;
                        $newSection->kindPlaceId = 0;
                        $newSection->stateId = $stateId[$i];
                        $newSection->cityId = 0;
                        $newSection->save();
                    }
                    else{
                        foreach ($cityId[$i] as $cId){
                            $newSection = new QuestionSection();
                            $newSection->questionId = $newQuestion->id;
                            $newSection->kindPlaceId = 0;
                            $newSection->stateId = $stateId[$i];
                            $newSection->cityId = $cId;
                            $newSection->save();
                        }
                    }
                }
            }
            else{
                $newSection = new QuestionSection();
                $newSection->questionId = $newQuestion->id;
                $newSection->kindPlaceId = 0;
                $newSection->stateId = 0;
                $newSection->cityId = 0;
                $newSection->save();
            }
        }

        return redirect(route('questions.index'));
    }

    public function editPage($id)
    {
        $question = Question::find($id);
        $kindPlace = Place::all();
        $state = State::all();

        if($question == null)
            return redirect()->back();
        else{
            if($question->ansType == 'multi') {
                $question->ans = QuestionAns::where('questionId', $id)->get();
            }
            else
                $question->ans = null;

            $question->kindPlaceId = QuestionSection::where('questionId', $question->id)->get()->groupBy('kindPlaceId');

            $question->state = QuestionSection::where('questionId', $question->id)->select(['id', 'stateId', 'cityId'])->get()->groupBy('stateId');

            $stateId = array();
            $cityId = array();

            foreach ($question->state as $item){

                if($item[0]->stateId != 0) {
                    $s = State::find($item[0]->stateId);
                    $item[0]->stateName = $s->name;

                    $co = array();
                    foreach ($item as $item2) {
                        $c = Cities::find($item2->cityId);
                        if($c != null) {
                            $item2->cityName = $c->name;
                            array_push($co, $c);
                        }
                    }

                    array_push($stateId, $s->id);
                    array_push($cityId, count($co));
                }
            }

            $stateId = json_encode($stateId);
            $cityCount = json_encode($cityId);

            $question->sections = QuestionSection::where('questionId', $id)->get();

            return view('config.questions.edit', compact(['state', 'kindPlace', 'question', 'stateId', 'cityCount']));
        }
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'ansType' => 'required',
            'kindPlaceId' => 'required',
            'state' => 'required',
            'id' => 'required',
            'city' => 'required',
        ]);

        $editQuestion = Question::find($request->id);
        if($editQuestion != null) {
            $editQuestion->description = $request->question;
            $editQuestion->kindPlaceId = 0;
            $editQuestion->ansType = $request->ansType;
            $editQuestion->save();
        }
        else
            return redirect()->back();

        QuestionAns::where('questionId', $editQuestion->id)->delete();
        if($request->ansType == 'multi'){
            $ans = json_decode($request->ans);
            foreach ($ans as $item){
                $newAns = new QuestionAns();
                $newAns->questionId = $editQuestion->id;
                $newAns->ans = $item;
                $newAns->save();
            }
        }

        QuestionSection::where('questionId', $editQuestion->id)->delete();
        $kindPlaceId = json_decode($request->kindPlaceId);
        $stateId = json_decode($request->state);
        $cityId = json_decode($request->city);

        if ($kindPlaceId[0] != '0'){
            foreach ($kindPlaceId as $kindId){
                if($stateId[0] != '0'){
                    for($i = 0; $i < count($stateId); $i++){
                        if($cityId[$i] == '0'){
                            $newSection = new QuestionSection();
                            $newSection->questionId = $editQuestion->id;
                            $newSection->kindPlaceId = $kindId;
                            $newSection->stateId = $stateId[$i];
                            $newSection->cityId = 0;
                            $newSection->save();
                        }
                        else{
                            foreach ($cityId[$i] as $cId){
                                $newSection = new QuestionSection();
                                $newSection->questionId = $editQuestion->id;
                                $newSection->kindPlaceId = $kindId;
                                $newSection->stateId = $stateId[$i];
                                $newSection->cityId = $cId;
                                $newSection->save();
                            }
                        }
                    }
                }
                else{
                    $newSection = new QuestionSection();
                    $newSection->questionId = $editQuestion->id;
                    $newSection->kindPlaceId = $kindId;
                    $newSection->stateId = 0;
                    $newSection->cityId = 0;
                    $newSection->save();
                }
            }
        }
        else{
            if($stateId[0] != '0'){
                for($i = 0; $i < count($stateId); $i++){
                    if($cityId[$i] == '0'){
                        $newSection = new QuestionSection();
                        $newSection->questionId = $editQuestion->id;
                        $newSection->kindPlaceId = 0;
                        $newSection->stateId = $stateId[$i];
                        $newSection->cityId = 0;
                        $newSection->save();
                    }
                    else{
                        foreach ($cityId[$i] as $cId){
                            $newSection = new QuestionSection();
                            $newSection->questionId = $editQuestion->id;
                            $newSection->kindPlaceId = 0;
                            $newSection->stateId = $stateId[$i];
                            $newSection->cityId = $cId;
                            $newSection->save();
                        }
                    }
                }
            }
            else{
                $newSection = new QuestionSection();
                $newSection->questionId = $editQuestion->id;
                $newSection->kindPlaceId = 0;
                $newSection->stateId = 0;
                $newSection->cityId = 0;
                $newSection->save();
            }
        }

        return redirect(route('questions.index'));
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        Question::find($request->id)->delete();
        QuestionSection::where('questionId', $request->id)->delete();
        QuestionAns::where('questionId', $request->id)->delete();

        return redirect()->back();
    }
}
