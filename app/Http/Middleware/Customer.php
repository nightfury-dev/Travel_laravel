<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class Customer
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
            if($account_type == 1 || $account_type == 2) {
                return $next($request);
            }
        }
        return redirect()->back();
    }
}
