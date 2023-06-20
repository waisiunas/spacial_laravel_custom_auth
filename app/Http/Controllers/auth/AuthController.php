<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login_view() {
        return view('auth.login');
    }

    public function login_process(Request $request) {
        $request->validate([
            'email' => ['required',],
            'password' => ['required'],
        ]);

        if(Auth::attempt($request->except('_token'))) {
            return redirect()->route('home')->with(['success' => 'Successfully logged in!']);
        } else {
            return back()->with(['failure' => 'Invalid combination!']);
        }
    }

    public function reg_view() {
        return view('auth.reg');
    }

    public function reg_process(Request $request) {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $is_reged = User::create($data);

        if($is_reged) {
            return back()->with(['success' => 'Magic has been spelled!']);
        } else {
            return back()->with(['failure' => 'Magic has failed to spell!']);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login')->with(['success' => 'Successfully logged out!']);
    }
}
