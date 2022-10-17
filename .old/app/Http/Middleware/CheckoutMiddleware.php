<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use function env;
use function redirect;

class CheckoutMiddleware
{
    /**
     * Handle an incoming request.
     * This function expect isAdmin session that was set when the adminUser login on the login page.
     * If isAdmin is set, the request will be redirected to the ticket authenticator page.
     * The unique parameter is for representing one particular seat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
        if ($request->session()->get('isAdmin') == true){
            $unique = $request->route()->parameter('unique');
            return redirect(env('APP_URL')."/authenticate/{$unique}");
        }
        else{
            return $next($request);
        }
    }
}
