<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\signUpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function getSignUp()
    {
        return view('signUpForm');

    }
    public function signUp(signUpRequest $request)
    {
        $data = $request->all();
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return response()->redirectTo('login');
    }
    public function getLogin()
    {
        return view('login_form');
    }
    public function login(LoginRequest $request)
    {
        Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);
    }


}
