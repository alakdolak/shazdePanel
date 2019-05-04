<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

include_once __DIR__ . '/../Controllers/Common.php';

class AdminLevel {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $level = Auth::user()->level;

        if($level == getValueInfo('adminLevel') || $level == getValueInfo('superAdminLevel')) {
            return $next($request);
        }

        return Redirect::route('home');
    }
}
