<?php

namespace App\Http\Controllers;

use App\models\ACL;
use App\models\Cities;
use App\models\DefaultPic;
use App\models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

    public function users() {

        $users = DB::select('select u.*, c.name as cityName, s.name as stateName from users u, cities c, state s WHERE u.cityId = c.id and s.id = c.stateId');

        foreach ($users as $user) {

            if($user->level == getValueInfo('userLevel'))
                $user->level = "کاربر عادی";
            else if($user->level == getValueInfo('adminLevel'))
                $user->level = "ادمین";
            else if($user->level == getValueInfo('superAdminLevel'))
                $user->level = "ادمین کل";

            if($user->uploadPhoto)
                $user->pic = URL::asset('userProfile/' . $user->picture);
            else {
                $tmpPic = DefaultPic::whereId($user->picture);
                if($tmpPic == null) {
                    $tmpPic = DefaultPic::first();
                    DB::update('update users set picture = ' . $tmpPic->id . ' where id = ' . $user->id);
                }
                $user->pic = URL::asset('defaultPic/' . $tmpPic->name);
            }

            $user->status = ($user->status) ? 'فعال' : 'غیر فعال';

        }

        return view('users.users', ['users' => $users]);
    }

    public function changePass() {
        
        if(isset($_POST["password"]) && isset($_POST["confirmPass"]) && isset($_POST["userId"])) {

            $pass = makeValidInput($_POST["password"]);
            $confirm = makeValidInput($_POST["confirmPass"]);

            if($pass == $confirm) {
                $userId = makeValidInput($_POST["userId"]);
                $user = User::whereId($userId);
                if($user != null) {
                    $user->password = Hash::make($pass);
                    $user->save();
                }
            }

        }
        
    }
    
    public function toggleStatusUser() {

        if(isset($_POST["userId"])) {
            $user = User::whereId(makeValidInput($_POST["userId"]));
            if($user != null) {
                $user->status = !$user->status;
                $user->save();
            }
        }

    }

    public function register($msg = "") {
        return view('users.register', ['msg' => $msg]);
    }

    public function addAdmin(Request $request) {

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'bail|required|min:5|unique:users',
            'username' => 'bail|required|min:8|unique:users',
            'password' => 'bail|required|min:8',
            'phone' => 'bail|required|min:11|max:11|unique:users',
            'confirm_password' => 'required',
        ], getMessages());

        $firstName = makeValidInput($_POST["firstName"]);
        $lastName = makeValidInput($_POST["lastName"]);
        $username = makeValidInput($_POST["username"]);
        $phone = makeValidInput($_POST["phone"]);
        $email = makeValidInput($_POST["email"]);
        $password = makeValidInput($_POST["password"]);
        $confirm_password = makeValidInput($_POST["confirm_password"]);

        if($password != $confirm_password) {
            return $this->register("رمز و تکرار آن یکی نیستند");
        }

        $user = new User();
        $user->first_name = $firstName;
        $user->picture = DefaultPic::first()->id;
        $user->last_name = $lastName;
        $user->cityId = Cities::first()->id;
        $user->level = getValueInfo('adminLevel');
        $user->username = $username;
        $user->email = $email;
        $user->phone = $phone;
        $user->password = Hash::make($password);

        $user->save();

        $tmp = new ACL();
        $tmp->userId = $user->id;
        $tmp->save();

        return Redirect::route('manageAccess', ['userId' => $user->id]);
        
    }

    public function manageAccess($userId) {

        $access = ACL::whereUserId($userId)->first();

        if($access == null)
            return Redirect::route('home');

        return view('users.manageAccess', ['access' => $access, 'userId' => $userId]);
    }

    public function changeAccess() {

        if(isset($_POST["userId"]) && isset($_POST["val"])) {
            
            $acl = ACL::whereUserId(makeValidInput($_POST["userId"]))->first();

            if($acl == null)
                return;

            switch (makeValidInput($_POST["val"])) {

                case "seo":
                    $acl->seo = !$acl->seo;
                    break;

                case "content":
                    $acl->content = !$acl->content;
                    break;

                case "offCode":
                    $acl->offCode = !$acl->offCode;
                    break;

                case "alt":
                    $acl->alt = !$acl->alt;
                    break;

                case "post":
                    $acl->post = !$acl->post;
                    break;

                case "comment":
                    $acl->comment = !$acl->comment;
                    break;

                case "config":
                    $acl->config = !$acl->config;
                    break;

                case "publicity":
                    $acl->publicity = !$acl->publicity;
                    break;
            }

            $acl->save();
        }
        
    }

}
