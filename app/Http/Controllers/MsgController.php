<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\Age;
use App\models\Message;
use App\models\State;
use App\models\TripStyle;
use App\models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MsgController extends Controller {

    public function sendMsg() {
        return view('msg.sendMsg', ['tripStyles' => TripStyle::all(), 'ages' => Age::all(), 'states' => State::all()]);
    }

    public function doSendMsg() {

        if(isset($_POST["msgText"]) && isset($_POST["query"])) {

            $query = $_POST["query"];
            $msgText = makeValidInput($_POST["msgText"]);
            $mail = $phone = $messageBox = false;

            if(isset($_POST["sendByMail"]) && $_POST["sendByMail"] == "1") {
                $mail = true;
            }
            else if(isset($_POST["sendByPhone"]) && $_POST["sendByPhone"] == "1") {
                $phone = true;
            }
            else if(isset($_POST["sendByMessageBox"]) && $_POST["sendByMessageBox"] == "1") {
                $messageBox = true;
            }

            if(!$mail && !$phone && !$messageBox) {
                echo "nok2";
                return;
            }

            if(empty($msgText)) {
                echo "nok3";
                return;
            }

            try {
                $users = DB::select($query);
                $senderId = Auth::user()->id;

                foreach ($users as $user) {

                    try {

                        if($messageBox) {
                            $message = new Message();
                            $message->senderId = $senderId;
                            $message->message = $msgText;
                            $message->receiverId = $user->id;
                            $message->subject = "noreply";
                            $message->date = getToday()["date"];
                            $message->save();

                            $mailBox = new AdminLog();
                            $mailBox->uId = $senderId;
                            $mailBox->additional1 = $user->id;
                            $mailBox->comment = $message->message;
                            $mailBox->mode = getValueInfo('messageBox');
                            $mailBox->save();
                        }

                        if($phone) {
//                            sendSMSWithoutTemplate($user->phone, $msgText);

                            $mailBox = new AdminLog();
                            $mailBox->uId = $senderId;
                            $mailBox->additional1 = $user->phone;
                            $mailBox->comment = $msgText;
                            $mailBox->mode = getValueInfo('phone');
                            $mailBox->save();
                        }

                        if($mail) {

                            if($user->email != null && !empty($user->email)) {

                                $text = $msgText;

                                if (sendMail($text, $user->email, 'کد تخفیف')) {
                                    $mailBox = new AdminLog();
                                    $mailBox->uId = $senderId;
                                    $mailBox->additional1 = $user->email;
                                    $mailBox->comment = $text;
                                    $mailBox->mode = getValueInfo('mail');
                                    $mailBox->save();
                                }
                            }
                        }

                    }
                    catch (\Exception $x) {
                        dd($x->getMessage());
                    }
                }
                echo "ok";
            }
            catch (\Exception $x) {
                echo "nok";
            }
        }

    }

    public function msgs() {

        $logs = DB::connection('mysql2')->table('adminLog')->join('pro.users', 'uId', '=', 'users.id')->where('mode', '=', getValueInfo('mail'))->orWhere('mode', '=', getValueInfo('messageBox'))->orWhere('mode', '=', getValueInfo('phone'))->select('mode', 'adminLog.id', 'adminLog.created_at', 'adminLog.additional1', 'adminLog.additional2', 'adminLog.comment', 'pro.users.username')->orderBy('adminLog.created_at', 'DESC')->paginate(30);

        foreach ($logs as $log) {

            $log->date = convertDate($log->created_at);

            switch ($log->mode) {

                case getValueInfo('mail'):
                    $log->mode = 'ارسال ایمیل';
                    break;

                case getValueInfo('messageBox'):
                    $log->mode = 'ارسال پیام به صندوق داخلی';
                    $log->additional1 = User::whereId($log->additional1)->username;
                    break;

                case getValueInfo('phone'):
                    $log->mode = 'ارسال پیام به شماره همراه';
                    break;

            }

        }

        return view('msg.msgs', ['logs' => $logs]);
    }
}
