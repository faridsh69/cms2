<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UserRegistered;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $request->validate([
            // 'phone' => ['required', 'phone:AUTO,US,BE'],
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $authUser = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        // @TODO activity
        // activity('User Registered')->performedOn($authUser)
        //     ->causedBy($authUser)
        //     ->log('User Registered');
        $user_registered = new UserRegistered();
        $authUser->notify($user_registered);

        Auth::loginUsingId($authUser->id);

        return redirect()->route('admin.dashboard.profile');
    }
}
