<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use function redirect;
use function response;

class AuthController extends Controller{

    public function register(RegisterRequest $request){ //todo fix request filtration
        $user = User::updateOrCreate($request->only('name') , ['password' => \Hash::make($request->input('password'))]);
        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request){
        if (!Auth::attempt($request->only('name', 'password'))) {
            return response("[
                'error' => 'invalid credentials'
            ]", Response::HTTP_UNAUTHORIZED);
        }
        $request->session()->regenerate();
        $request->session()->put('is_gmco', true);
        return response(['message' => 'success']);
    }

    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
