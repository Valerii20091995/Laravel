<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\signUpRequest;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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

//        Mail::to('regaska0384@mail.ru')->send(new TestMail($data));
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'valera', 'qwerty');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage($user->id);
        $channel->basic_publish($msg, '', 'hello');

        $channel->close();
        $connection->close();

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
        return response()->redirectTo('/catalog');
    }
    public function getProfile()
    {
        $user = Auth::user();
        return view('profile_form', ['user' => $user]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
    public function getEditProfile()
    {
        $user = Auth::user();
        return view('editProfile_form', ['user' => $user]);
    }
    public function editProfile(EditProfileRequest $request)
    {
        /** @var  User $user */
        $user = Auth::user();
        $data = $request->validated();
        if(!empty($request['name']) && ($user->name !== $data['name'])){
            $user->name = $data['name'];
        };
        if(!empty($request['email']) && ($user->email !== $data['email'])){
            $user->email = $data['email'];
        };

        $user->save();

        return response()->redirectTo('/profile');

    }


}
