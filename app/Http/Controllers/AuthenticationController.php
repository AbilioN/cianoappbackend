<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\TryCatch;

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

    // public function sendResetLink(Request $request)
    // {
    //     try {
    //         $request->validate(['email' => 'required|email']);
    
    //         $status = Password::sendResetLink($request->only('email'));
    
    //         return $status === Password::RESET_LINK_SENT
    //             ? redirect()->intended('/home')
    //             : throw new Exception('Erro no envio do E-mail.');

    //     } catch (Exception $e) {
    //         dd($e->getMessage());
    //     }
    // }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'email' => 'Email inválido.',
            'password' => 'Senha Inválida.',
            'password.confirmed' => 'A senha não corresponde.',
        ]);
        
        try {
            if (!$request->has('email')) {
                throw new Exception('O campo email não foi enviado na requisição.');
            }
            
            // Encontra o usuário pelo e-mail
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'Usuário não encontrado.']);
            }
            
            // Valida se o token existe e é válido
            if (!Password::tokenExists($user, $request->token)) {
                return redirect()->back()->withErrors(['error' => 'Token inválido ou expirado.']);
            }

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );

            if (!in_array($status, [
                Password::PASSWORD_RESET,
                Password::INVALID_USER,
                Password::INVALID_TOKEN
            ])) {
                throw new Exception('Status inesperado: ' . $status);
            }

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->back()->with('success', 'Senha redefinida com sucesso!');
            }

            return redirect()->back()->withErrors(['status' => __($status)]);

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro inesperado: ' . $e->getMessage()]);
        }
    }
}
