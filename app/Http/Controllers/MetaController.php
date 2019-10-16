<?php

namespace App\Http\Controllers;

use App\models\Adab;
use App\models\Amaken;
use App\models\Hotel;
use App\models\Majara;
use App\models\Restaurant;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    public function index($kind = '')
    {
        if ($kind != "") {
            $target = array();
            switch ($kind) {
                case "adab":
                    $name = 'اداب';
                    $target = Adab::select('id', 'name')->get();
                    break;
                case "majara":
                    $name = 'ماجرا';
                    $target = Majara::select('id', 'name')->get();
                    break;
                case "hotel":
                    $name = 'هتل';
                    $target = Hotel::select('id', 'name')->get();
                    break;
                case "restaurant":
                    $name = 'رستوران';
                    $target = Restaurant::select('id', 'name')->get();
                    break;
                case "amaken":
                    $name = 'اماکن';
                    $target = Amaken::select('id', 'name')->get();
                    break;
            }

            return view('meta.index', compact(['kind', 'target', 'name']));
        }

        return view('meta.index');
    }

    public function edit($kind, $id)
    {
        if($kind == '')
            return redirect(url('meta/index'));
        elseif($id == '')
            return redirect(url('meta/index/' . $kind));

        $result = array();

        switch ($kind) {
            case "adab":
                $result = Adab::whereId($id);
                break;
            case "majara":
                $result = Majara::whereId($id);
                break;
            case "hotel":
                $result = Hotel::whereId($id);
                break;
            case "restaurant":
                $result = Restaurant::whereId($id);
                break;
            case "amaken":
                $result = Amaken::whereId($id);
                break;
        }
        return view('meta.edit', compact(['kind', 'result']));
    }

    public function doEdit(Request $request)
    {
        $kind = $request->kind;
        $id = $request->id;

        if($kind == '')
            return redirect(url('meta/index'));
        elseif($id == '')
            return redirect(url('meta/index/' . $kind));

        $result = array();

        switch ($kind) {
            case "adab":
                $result = Adab::whereId($id);
                break;
            case "majara":
                $result = Majara::whereId($id);
                break;
            case "hotel":
                $result = Hotel::whereId($id);
                break;
            case "restaurant":
                $result = Restaurant::whereId($id);
                break;
            case "amaken":
                $result = Amaken::whereId($id);
                break;
        }
        $result->meta = $request->meta;
        $result->save();

        return redirect(url('meta/index/' . $kind));

    }

}
