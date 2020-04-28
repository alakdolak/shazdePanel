<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class StreamController extends Controller {

    private $api = "Apikey 9c107ba7-451e-4640-89dc-0617a785aaea";

    public function manage() {

        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: ' . $this->api;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://napi.arvancloud.com/live/2.0/streams");
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($httpCode == 200) {
            $result = json_decode(substr($head, strpos($head, '{"data')));
            return view('stream.manage', ['streams' => $result->data]);
        }

        dd("مشکلی در گرفتن داده ها از ابرآروان به وجود آمده است.");
    }

    public function addStream() {
        return view('stream.addStream');
    }

    public function doAddStream() {

        if(isset($_POST["title"]) && isset($_POST["slug"]) && isset($_POST["mode"]) &&
            isset($_POST["type"]) &&
            isset($_POST["fps"]) && isset($_POST["archive_enabled"])
        ) {

            $mode = makeValidInput($_POST["mode"]);
            $type = makeValidInput($_POST["type"]);

            if($mode == -1)
                return view('stream.addStream', ['msg' => 'لطفا متد استریم خود را مشخص نمایید']);

            if($type == -1)
                return view('stream.addStream', ['msg' => 'لطفا نوع استریم خود را مشخص نمایید']);



            if($mode == "pull") {
                if (!isset($_POST["input_url"]))
                    return view('stream.addStream', ['msg' => 'لطفا لینک ورودی استریم خود را مشخص نمایید']);

                $input_url = makeValidInput($_POST["input_url"]);

                if (empty($input_url))
                    return view('stream.addStream', ['msg' => 'لطفا لینک ورودی استریم خود را مشخص نمایید']);
            }



            if($type == "normal") {
                if(!isset($_POST["timeshift"]))
                    return view('stream.addStream', ['msg' => 'لطفا وضعیت ماشین زمان استریم خود را مشخص نمایید']);

                $timeshift = makeValidInput($_POST["timeshift"]);

                if(empty($timeshift))
                    return view('stream.addStream', ['msg' => 'لطفا وضعیت ماشین زمان استریم خود را مشخص نمایید']);
            }



            $archive_enabled = makeValidInput($_POST["archive_enabled"]);

            if($archive_enabled == "on") {
                if(!isset($_POST["channel_id"]))
                    return view('stream.addStream', ['msg' => 'لطفا آی دی کانال خود را مشخص نمایید']);

                $channel_id = makeValidInput($_POST["channel_id"]);

                if(empty($channel_id))
                    return view('stream.addStream', ['msg' => 'لطفا آی دی کانال خود را مشخص نمایید']);
            }


            $title = makeValidInput($_POST["title"]);
            $slug = makeValidInput($_POST["slug"]);
            $fps = makeValidInput($_POST["fps"]);

            $desc = (isset($_POST["desc"])) ? makeValidInput($_POST["desc"]) : "";

            $converter = [];

            foreach ($_POST as $key => $value) {

                if(substr($key, 0, 12) == "converter_w_") {

                    $tmp = explode('_', $key);

                    $converter[count($converter)] = [
                        'audio_bitrate' => makeValidInput($_POST["converter_audio_res_" . $tmp[count($tmp) - 1]]),
                        'video_bitrate' => makeValidInput($_POST["converter_image_res_" . $tmp[count($tmp) - 1]]),
                        'resolution_height' => makeValidInput($_POST["converter_h_" . $tmp[count($tmp) - 1]]),
                        'resolution_width' => $value
                    ];
                }
            }

            if(count($converter) == 0)
                return view('stream.addStream', ['msg' => 'لطفا فرمت های تصویری را مشخص نمایید']);

            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: ' . $this->api;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://napi.arvancloud.com/live/2.0/streams");
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            $data = [
                "title" => $title,
                "slug" => $slug,
                "description" => $desc,
                "mode" => $mode,
                "type" => $type,
                "fps" => $fps,
                "archive_enabled" => ($archive_enabled == "on") ? true : false,
                "convert_info" => $converter
            ];

            if($mode == "pull")
                $data["input_url"] = $input_url;

            if($type == "normal")
                $data["timeshift"] = $timeshift;

            if($archive_enabled == "on")
                $data["channel_id"] = $channel_id;

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $head = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if($httpCode == 201)
                return Redirect::route('manageStreams');

            dd($head);

        }

        return view('stream.addStream', ['msg' => 'لطفا همه موارد لازم را پرنمایید']);
    }

    public function removeStream() {

        if(isset($_POST["streamId"])) {

            $streamId = makeValidInput($_POST["streamId"]);

            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: ' . $this->api;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://napi.arvancloud.com/live/2.0/streams/" . $streamId);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $head = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode == 200) {
                echo "ok";
            }
        }
    }

}
