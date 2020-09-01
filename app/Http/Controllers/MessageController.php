<?php

namespace App\Http\Controllers;

use App\models\Alert;
use App\models\Message;
use App\models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function msgPage()
    {
        $uId = 0;

        $contactsId = [];
        $specUser = null;
        $contacts = [];

        $allMsg = Message::where('senderId', $uId)
                            ->orWhere('receiverId', $uId)
                            ->orderBy('date')
                            ->orderBy('time')
                            ->get();

        foreach ($allMsg as $msg){
            if($msg->senderId != $uId && array_search($msg->senderId, $contactsId) === false)
                array_push($contactsId, $msg->senderId);
            else if($msg->receiverId != $uId && array_search($msg->receiverId, $contactsId) === false)
                array_push($contactsId, $msg->receiverId);
        }


        $contacts = User::whereIn('id', $contactsId)->select(['id', 'username'])->get();
        foreach ($contacts as $item){
            $item->pic = getUserPic($item->id);
            $item->newMsg = Message::where('receiverId', $uId)->where('senderId', $item->id)->where('seen', 0)->count();
            $lastMsg = Message::whereRaw('senderId = ' . $uId . ' && receiverId = ' . $item->id)
                ->orWhereRaw('senderId = ' . $item->id . ' && receiverId = '. $uId)
                ->orderByDesc('date')
                ->orderByDesc('time')
                ->first();
            if($lastMsg != null) {
                $item->lastMsg = $lastMsg->message;
                $item->lastTime = $lastMsg->time;
                $item->lastDate = $lastMsg->date;
            }
            else{
                $item->lastMsg = '';
                $item->lastTime = '';
                $item->lastDate = 'max';
            }
        }

        for($i = 0; $i < count($contacts)-1; $i++){
            for($j = $i + 1; $j < count($contacts); $j++)
                if(($contacts[$j]->lastDate > $contacts[$i]->lastDate) ||
                    ($contacts[$j]->lastDate == $contacts[$i]->lastDate && $contacts[$j]->lastTime > $contacts[$i]->lastTime)){
                    $ll = $contacts[$j];
                    $contacts[$j] = $contacts[$i];
                    $contacts[$i] = $ll;
                }
        }

        if(isset(\Request::all()['user'])){
            $cont = User::where('username', \Request::all()['user'])->first();
            if(array_search($cont->id, $contactsId) === false && $cont != null){
                $cont->pic = getUserPic($cont->id);
                $cont->newMsg = Message::where('receiverId', $uId)->where('senderId', $cont->id)->where('seen', 0)->count();

                $cont->lastMsg = '';
                $cont->lastTime = '';
                if(count($contacts) == 0)
                    $contacts = [];
                else
                    $contacts = $contacts->toArray();

                array_unshift($contacts, $cont);
            }

            $specUser = $cont->id;
        }

        return view('msg.userMessages', compact(['contacts', 'specUser']));
    }

    public function getMessages(Request $request)
    {
        $contactId = $request->id;
        $uId = 0;
        $msgs = Message::whereRaw('senderId = ' . $uId . ' && receiverId = ' . $contactId)
            ->orWhereRaw('senderId = ' . $contactId . ' && receiverId = '. $uId)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        foreach ($msgs as $msg){
            if($msg->receiverId == $uId && $msg->seen == 0){
                $msg->seen = 1;
                $msg->save();

                $msg->newMsg = 1;
            }

            if($msg->date == verta()->format('Y-m-d'))
                $msg->date = 'امروز' ;
        }

        echo json_encode($msgs);
    }

    public function sendMessages(Request $request)
    {
        if(isset($request->userId) && isset($request->text)){
            $receiver = User::find($request->userId);
            if($receiver != null) {
                $newMsg = new Message();
                $newMsg->senderId = 0;
                $newMsg->receiverId = $request->userId;
                $newMsg->message = $request->text;
                $newMsg->date = verta()->format('Y-m-d');
                $newMsg->time = verta()->format('H:i');
                $newMsg->seen = 0;
                $newMsg->save();


                $alert = new Alert();
                $alert->userId = $request->userId;
                $alert->subject = 'newMessage';
                $alert->referenceTable = 'messages';
                $alert->referenceId = $newMsg->id;
                $alert->save();

                if($newMsg->date == verta()->format('Y-m-d'))
                    $newMsg->date = 'امروز' ;

                echo json_encode(['status' => 'ok', 'result' => $newMsg]);
            }
            else
                echo json_encode(['status' => 'userNotFound']);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }

    public function updateMessages(Request $request)
    {
        if(isset($request->lastId) && isset($request->userId)){
            $contactId = $request->userId;
            $uId = 0;

            $msgs = Message::whereRaw('id > ' . $request->lastId . ' AND ((senderId = ' . $uId . ' AND receiverId = ' . $contactId . ') OR (senderId = ' . $contactId . ' && receiverId = '. $uId.'))')
                ->orderBy('date')
                ->orderBy('time')
                ->get();

            foreach ($msgs as $msg){
                if($msg->date == verta()->format('Y-m-d'))
                    $msg->date = 'امروز' ;
            }

            echo json_encode(['status' => 'ok', 'result' => $msgs]);
        }
        else
            echo json_encode(['status' => 'nok']);

        return;
    }
}
