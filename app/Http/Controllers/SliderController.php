<?php

namespace App\Http\Controllers;

use App\models\MainSliderPic;
use DemeterChain\Main;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $pics = MainSliderPic::all();
        return view('config.mainSliderPic', compact(['pics']));
    }

    public function storePic(Request $request)
    {
        $location = __DIR__ . '/../../../../assets/_images/sliderPic';

        if(!file_exists($location))
            mkdir($location);

        if(isset($_FILES['pic'])){

            $valid_ext = array('image/jpeg','image/png');
            if(in_array($_FILES['pic']['type'], $valid_ext)){
                $filename = time() . '_' . pathinfo($_FILES['pic']['name'], PATHINFO_FILENAME) . '.jpg';
                $destinationMainPic = $location . '/' . $filename;
                compressImage($_FILES['pic']['tmp_name'], $destinationMainPic, 100);

                $newSlidePic = new MainSliderPic();
                $newSlidePic->pic = $filename;
                $newSlidePic->alt = '';
                $newSlidePic->text = '';
                $newSlidePic->save();

                echo json_encode(['ok', $newSlidePic->id]);
            }
            else{
                echo 'nok2';
            }
        }
        else
            echo 'nok3';

        return;
    }

    public function deletePic(Request $request)
    {
        if(isset($request->id)){
            $pic = MainSliderPic::find($request->id);
            if($pic != null){
                $location = __DIR__ . '/../../../../assets/_images/sliderPic/' . $pic->pic;
                if(file_exists($location))
                    unlink($location);
                $pic->delete();
                echo 'ok';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }

    public function changePic(Request $request)
    {
        $location = __DIR__ . '/../../../../assets/_images/sliderPic';

        if(!file_exists($location))
            mkdir($location);

        if(isset($_FILES['pic']) && isset($request->id)){
            $pic = MainSliderPic::find($request->id);
            if($pic != null){
                $valid_ext = array('image/jpeg','image/png');
                if(in_array($_FILES['pic']['type'], $valid_ext)){
                    $filename = time() . '_' . pathinfo($_FILES['pic']['name'], PATHINFO_FILENAME) . '.jpg';
                    $destinationMainPic = $location . '/' . $filename;
                    compressImage($_FILES['pic']['tmp_name'], $destinationMainPic, 100);

                    $location = __DIR__ . '/../../../../assets/_images/sliderPic/' . $pic->pic;
                    if(file_exists($location))
                        unlink($location);

                    $pic->pic = $filename;
                    $pic->save();

                    echo json_encode(['ok']);
                }
                else
                    echo 'nok3';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }

    public function changeAltPic(Request $request)
    {
        if(isset($request->id) && isset($request->alt)){
            $pic = MainSliderPic::find($request->id);
            if($pic != null){
                $pic->alt = $request->alt;
                $pic->save();

                echo 'ok';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }

    public function changeTextPic(Request $request)
    {
        if(isset($request->id)){
            $pic = MainSliderPic::find($request->id);
            if($pic != null){
                if($request->text == null)
                    $pic->text = '';
                else
                    $pic->text = $request->text;
                $pic->save();

                echo 'ok';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }

    public function changeColor(Request $request)
    {
        if(isset($request->id)){
            $pic = MainSliderPic::find($request->id);
            if($pic != null){
                $pic->textColor = $request->color;
                $pic->textBackground = $request->background;
                $pic->save();

                echo 'ok';
            }
            else
                echo 'nok2';
        }
        else
            echo 'nok1';

        return;
    }
}
