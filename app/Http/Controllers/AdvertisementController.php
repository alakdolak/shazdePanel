<?php

namespace App\Http\Controllers;

use App\models\Advertisement\Advertisement;
use App\models\Advertisement\AdvertisementSizes;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function advertisePage($kind = ''){
        $advertisements = Advertisement::where('section', $kind)->get();
        foreach ($advertisements as $item)
            $item->kindName = AdvertisementSizes::find($item->kindId)->name;

        return view('Advertisement.advertisementPage', compact(['kind', 'advertisements']));
    }

    public function advertiseNewPage($kind = '')
    {
        $advKinds = AdvertisementSizes::all();

        return view('Advertisement.advertisementNewPage', compact(['kind', 'advKinds']));
    }

    public function advertiseEditPage($id)
    {
        $adv = Advertisement::find($id);
        if($adv == null)
            return redirect(route('advertisement'));

        $kind = $adv->section;
        $advKinds = AdvertisementSizes::all();

        $adv->pics = json_decode($adv->pics);
        $adv->pics->pc = \URL::asset('_images/esitrevda/'.$adv->pics->pc);
        $adv->pics->mobile = \URL::asset('_images/esitrevda/'.$adv->pics->mobile);

        return view('Advertisement.advertisementNewPage', compact(['kind', 'advKinds', 'adv']));
    }

    public function advertiseStore(Request $request)
    {
        $advDir = __DIR__.'/../../../../assets/_images/esitrevda';
        if(!is_dir($advDir))
            mkdir($advDir);

        $advId = $request->advId;
        $section = $request->section;
        $title = $request->title;
        $url = $request->url;
        $weight = $request->weight;
        $kind = $request->kind;

        $fileName = time().rand(100,999);

        $pcFileName = '';
        $mobileFileName = '';

        if(isset($_FILES['pcPic']) && $_FILES['pcPic']['error'] == 0) {
            $pcFileType = explode('.', $_FILES['pcPic']['name']);
            $pcFileType = end($pcFileType);
            $pcFileName = $fileName.'_pc.'.$pcFileType;
        }

        if(isset($_FILES['mobilePic']) && $_FILES['mobilePic']['error'] == 0) {
            $mobileFileType = explode('.', $_FILES['mobilePic']['name']);
            $mobileFileType = end($mobileFileType);
            $mobileFileName = $fileName.'_mobile.'.$mobileFileType;
        }

        $adv = null;
        if ($advId > 0)
            $adv = Advertisement::find($advId);

        if ($adv == null) {
            $adv = new Advertisement();
            $adv->userId = auth()->user()->id;
            $adv->section = $section;
        }

        if($advId == 0) {
            $pcCheck = move_uploaded_file($_FILES['pcPic']['tmp_name'], $advDir . '/' . $pcFileName);
            $mobileCheck = move_uploaded_file($_FILES['mobilePic']['tmp_name'], $advDir . '/' . $mobileFileName);

            $picsArr = ['pc' => $pcFileName, 'mobile' => $mobileFileName];
        }
        else if($adv != null){
            $picsArr = json_decode($adv->pics);
            if($mobileFileName != ''){
                $mobileCheck = move_uploaded_file($_FILES['mobilePic']['tmp_name'], $advDir . '/' . $mobileFileName);
                if(is_file($advDir.'/'.$picsArr->mobile) && $mobileCheck)
                    unlink($advDir.'/'.$picsArr->mobile);

                $picsArr->mobile = $mobileFileName;
            }

            if($pcFileName != ''){
                $pcCheck = move_uploaded_file($_FILES['pcPic']['tmp_name'], $advDir . '/' . $pcFileName);
                if(is_file($advDir.'/'.$picsArr->pc) && $pcCheck)
                    unlink($advDir.'/'.$picsArr->pc);

                $picsArr->pc = $pcFileName;
            }
        }

        $adv->title = $title;
        $adv->url = $url;
        $adv->pics = json_encode($picsArr);
        $adv->weight = $weight;
        $adv->kindId = $kind;
        $adv->save();

        return response()->json(['status' => 'ok', 'result' => $adv->id]);
    }

    public function advertiseDelete(Request $request){
        if(isset($request->advId)){
            $advDiv = __DIR__.'/../../../../assets/_images/esitrevda';

            $adv = Advertisement::find($request->advId);

            $pics = json_decode($adv->pics);
            if(is_file($advDiv.'/'.$pics->pc))
                unlink($advDiv.'/'.$pics->pc);
            if(is_file($advDiv.'/'.$pics->mobile))
                unlink($advDiv.'/'.$pics->mobile);

            $adv->delete();

            return response()->json(['status' => 'ok']);
        }
        else
            return response()->json(['status' => 'error']);
    }

}
