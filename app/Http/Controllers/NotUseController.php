<?php

namespace App\Http\Controllers;

use App\models\Place;
use Illuminate\Http\Request;

class NotUseController extends Controller
{
    public function chooseCity($mode) {

        $url = route('root');

        switch ($mode) {
            case "seo":
                $url = $url . "/changeSeo/";
                break;
            case "content":
                $url = $url . "/changeContent/";
                return view('content.chooseCity', ['url' => $url, 'mode' => 2, 'places' => Place::all()]);
            case "content2":
                $url = $url . "/newChangeContent/";
                return view('content.chooseCity', ['url' => $url, 'mode' => 2, 'places' => Place::all()]);
        }

        return view('content.chooseCity', ['url' => $url, 'mode' => 1]);
    }

}
