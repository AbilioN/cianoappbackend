<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthenticationController extends Controller
{

    public function register(Request $request)
    {

        try {
            // dd($request->all());
            $validator = Validator::make(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $request->password,
                ],
                [
                    'name' => ['required', 'min:3'],
                    'email' => ['required', 'email', Rule::unique('users')],
                    'password' => ['required', 'min:8'],
                ]);

            if($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
            ]);

            return redirect()->route('auth-login');

        }catch(Exception $e) {

            return redirect()->back();
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);

            if (Auth::attempt($credentials)) {

                $role = Auth::user()->role()->first();
                $request->session()->regenerate();

                if($role->name == "Admin")
                {
                    return redirect()->intended('/admin');
                }

                return redirect()->intended('/home');
            }
            return back()->withErrors([
                'email' => 'Credenciais inválidas!',
                'password' => 'Credenciais inválidas!'
            ])->onlyInput('email');

        }catch(Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withErrors(['email' => 'Credenciais inválidas. Verifique seu e-mail e senha.']);
        }

    }

    public function logout(Request $request)
    {

        $userInLogout = Auth::user();
        if($userInLogout->role->name == 'Admin')
        {
            $redirectRoute = '/login';
        }else{
            $redirectRoute = '/';
        }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect($redirectRoute);
    }

}
