<?php

namespace App\Http\Controllers;

use App\models\AdminLog;
use App\models\Age;
use App\models\Message;
use App\models\OffCode;
use App\models\State;
use App\models\TripStyle;
use App\models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OffCodeController extends Controller {
    
    public function createOffer() {
        return view('offer.createOffer', ['tripStyles' => TripStyle::all(), 'ages' => Age::all(), 'states' => State::all()]);
    }
    
    public function doCreateOffer() {

        if(isset($_POST["amount"]) && isset($_POST["offerKind"]) && isset($_POST["query"]) && isset($_POST["expireTime"])) {

            $query = $_POST["query"];
            $amount = makeValidInput($_POST["amount"]);
            $offerKind = makeValidInput($_POST["offerKind"]);
            $expireTime = makeValidInput($_POST["expireTime"]);
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

            if($expireTime != "none")
                $expireTime = convertDateToString($expireTime);

            try {
                $users = DB::select($query);
                $senderId = Auth::user()->id;

                $log = new AdminLog();
                $log->uId = $senderId;
                $log->additional1 = count($users);
                $log->additional2 = $offerKind;
                $log->mode = getValueInfo('offCode');
                $log->save();

                foreach ($users as $user) {
                    $offer = new OffCode();
                    $offer->kind = ($offerKind == getValueInfo('staticOffer'));
                    $offer->amount = $amount;
                    $offer->creator = $senderId;
                    $offer->uId = $user->id;
                    $code = 'shazde_' . random_int(100000, 999999);
                    while (OffCode::whereCode($code)->count() > 0)
                        $code = 'shazde_' . random_int(100000, 999999);
                    $offer->code = $code;

                    if($expireTime != "none")
                        $offer->expire = convertDateToString($expireTime);
                    else
                        $offer->expire = null;

                    try {
                        $offer->save();

                        if($messageBox) {
                            $message = new Message();
                            $message->senderId = $senderId;
                            $message->message = "کاربر گرامی، کد تخفیف " . $code . ' به ارزش' . $amount .
                                (($offerKind == getValueInfo('staticOffer')) ? (' تومانی') : (' درصدی')) .
                                (($expireTime != "none") ? ("تا تاریخ " . $expireTime) : '') .
                                ' به شما تعلق گرفته است ';
                            $message->receiverId = $user->id;
                            $message->subject = "کد تخفیف";
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
                            sendSMS($user->phone, $code, "offCode", $amount, ($offerKind == getValueInfo('staticOffer')) ? 'تومان' : 'درصد');
                            $mailBox = new AdminLog();
                            $mailBox->uId = $senderId;
                            $mailBox->additional1 = $user->phone;
                            $mailBox->comment = "الگوی پیامکی offCode با پارامتر های " . $code . ', ' . $amount . ', ' . ($offerKind == getValueInfo('staticOffer')) ? 'تومان' : 'درصد';
                            $mailBox->mode = getValueInfo('phone');
                            $mailBox->save();
                        }

                        if($mail) {

                            if($user->email != null && !empty($user->email)) {

                                $text = "کاربر گرامی، کد تخفیف " . $code . ' به ارزش' . $amount .
                                    (($offerKind == getValueInfo('staticOffer')) ? (' تومانی') : (' درصدی')) .
                                    (($expireTime != "none") ? ("تا تاریخ " . $expireTime) : '') .
                                    ' به شما تعلق گرفته است ';

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

    public function deleteOffer() {

        if(isset($_POST["offers"])) {

            $offers = $_POST["offers"];

            foreach ($offers as $offer) {
                OffCode::destroy(makeValidInput($offer));
            }

            echo "ok";
        }
        
    }

    public function offers($mode = "all") {

        if($mode == 'all')
            $offers = DB::select('select username, offCode.* from offCode, users WHERE uId = users.id');
        else if($mode == 'used')
            $offers = DB::select('select username, offCode.* from offCode, users WHERE uId = users.id and used = true');
        else
            $offers = DB::select('select username, offCode.* from offCode, users WHERE uId = users.id and used = false');

        foreach ($offers as $offer) {
            $offer->creator = User::whereId($offer->creator)->username;
            $offer->date = convertDate($offer->created_at);
            $offer->expire = convertStringToDate($offer->expire);
        }

        return view('offer.offers', ['offers' => $offers, 'mode' => $mode]);
    }
    
}
