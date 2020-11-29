<?php

namespace App\Http\Controllers;

use App\models\localShops\LocalShops;
use Illuminate\Http\Request;

class LocalShopController extends Controller
{
    public function localshopList()
    {
        $lConfirmed = LocalShops::where('confirm', 1)->get();
        return view('userContent.addLocalShops.localShopList');
    }
}
