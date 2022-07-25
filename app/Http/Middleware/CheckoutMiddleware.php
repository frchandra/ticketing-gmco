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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
        if ($request->session()->get('is_gmco') === true){
            $unique = $request->route()->parameter('unique');
            return redirect(env('APP_URL')."/attend/{$unique}");
        }
        else{
            return $next($request);
        }
    }
}
