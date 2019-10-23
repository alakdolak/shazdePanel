<?php

namespace App\Http\Controllers;

use App\models\Question;
use App\models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QuestionsController extends Controller
{
    public function index($kind = '')
    {
        if($kind == ''){
            $places = Place::all();
            return view('config.question', compact(['places']));
        }
        else{
            $kind = Place::where('name', $kind)->first();
            if($kind == null)
                return redirect()->back();

            $questions = Question::where('kindPlaceId', $kind->id)->get();
            return view('config.question', compact(['kind', 'questions']));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'kindPlaceId' => 'required'
        ]);

        $question= Question::where('description', $request->description)->where('kindPlaceId', $request->kindPlaceId)->first();

        if($question == null){
            $question =  new Question();
            $question->description = $request->description;
            $question->kindPlaceId = $request->kindPlaceId;
            $question->save();
        }
        else{
            Session::flash('error', 'این سوال قبلا تعریف شده است');
        }
        return redirect()->back();
    }

    public function doEdit(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'id' => 'required'
        ]);

        $question = Question::find($request->id);

        if($question != null){
            $checkQuestion = Question::where('description', $request->description)->first();
            if($checkQuestion == null) {
                $question->description = $request->description;
                $question->save();
            }
            else{
                Session::flash('error', 'این سوال موجود می باشد');
            }
        }
        else{
            Session::flash('error', 'مشکلی در ویرایش به وجود امد. لطفا دوباره تلاش کنید');
        }
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        Question::find($request->id)->delete();

        return redirect()->back();
    }
}
