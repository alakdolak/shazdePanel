<?php

namespace App\Http\Controllers;

use App\models\ACL;
use App\models\acl\AclList;
use App\models\acl\AclUserRelations;
use App\models\AdminLog;
use App\models\Cities;
use App\models\DefaultPic;
use App\models\User;
use Illuminate\Support\Facades\Auth;
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
                    $tmp = new AdminLog();
                    $tmp->uId = Auth::user()->id;
                    $tmp->mode = getValueInfo('changePass');
                    $tmp->save();
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

        $accessList = AclList::all();
        foreach ($accessList as $item){
            $userAclRel = AclUserRelations::where('userId', $userId)
                                            ->where('aclId', $item->id)
                                            ->first();
            $item->userAccess = $userAclRel != null;
        }

        $user = User::select(['id', 'username'])->find($userId);
        return view('users.manageAccess', compact(['user', 'accessList']));
    }

    public function changeAccess(Request $request) {
        if(isset($request->userId) && isset($request->aclId)){
            $acl = AclList::find($request->aclId);
            if($acl != null){
                $aclRel = AclUserRelations::where('aclId', $acl->id)->where('userId', $request->userId)->first();
                if($aclRel == null){
                    $aclRel = new AclUserRelations();
                    $aclRel->userId = $request->userId;
                    $aclRel->aclId = $acl->id;
                    $aclRel->save();
                }
                else
                    $aclRel->delete();

                $userAclCount = AclUserRelations::where('userId', $request->userId)->count();
                if($userAclCount > 0)
                    User::where('id', $request->userId)->update(['level' => getValueInfo('adminLevel')]);
                else
                    User::where('id', $request->userId)->update(['level' => 0]);

                return response('ok');
            }
            else
                return response('error2');
        }
        else
            return response('error1');
    }

}
