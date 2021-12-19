<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $account_type = Auth::user()->account_type;
            if($account_type == 4 || $account_type == 5) {
                return $next($request);
            }
        }
        return redirect()->back();
    }
}
