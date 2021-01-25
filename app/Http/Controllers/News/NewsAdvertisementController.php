<?php

namespace App\Http\Controllers\News;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsAdvertisementController extends Controller
{
    public function advertisePage(){

        return view('newsAdvertisementPage');
    }
}
