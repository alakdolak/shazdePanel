<?php

namespace App\Http\Middleware;

use App\models\ACL;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ContentAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if(Auth::user()->level != getValueInfo('superAdminLevel')) {

            $acl = ACL::whereUserId(Auth::user()->id)->first();

            if ($acl == null || !$acl->content)
                return Redirect::route('home');
        }

        return $next($request);
    }
}
