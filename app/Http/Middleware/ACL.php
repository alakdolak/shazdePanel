<?php

namespace App\Http\Middleware;

use App\models\acl\AclList;
use App\models\acl\AclUserRelations;
use Closure;
use Illuminate\Support\Facades\Auth;

class ACL
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->user();
        if($user->level == getValueInfo('superAdminLevel')) {
            return $next($request);
        }
        else if($user->level == getValueInfo('adminLevel')){
            $acl = AclList::where('actualName', $role)->first();
            if($acl != null){
                $uAcl = AclUserRelations::where('userId', $user->id)
                                        ->where('aclId', $acl->id)
                                        ->first();
                if($uAcl != null)
                    return $next($request);
            }

            return redirect()->back();
        }
        else{
            Auth::logout();
            return redirect(route('login'));
        }
    }
}
