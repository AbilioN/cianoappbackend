<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;

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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Password::createToken($user);

        $language = $request->input('language', 'en');
        $view = "components.emails.reset-password-{$language}";

        Mail::to($user->email)->send(new ResetPasswordMail($token, $view));

        return response()->json([
            'message' => 'Password reset link sent successfully',
            'reset_url' => route('auth.password.reset', ['token' => $token, 'language' => $language])
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'password' => 'Senha Inválida.',
            'password.confirmed' => 'A senha não corresponde.',
        ]);

        try {
            // Primeiro encontramos o registro do reset
            $resetEntry = DB::table('password_reset_tokens')
                ->get()
                ->first(function($entry) use ($request) {
                    // O token no banco está hasheado, então precisamos verificar se o hash bate
                    return Hash::check($request->token, $entry->token);
                });

            if (!$resetEntry) {
                return redirect()->back()->withErrors(['error' => 'Token inválido ou expirado.']);
            }

            // Agora temos o email, podemos fazer o reset
            $status = Password::reset(
                [
                    'email' => $resetEntry->email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'token' => $request->token,
                ],
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->back()->with('success', 'Senha redefinida com sucesso!');
            }

            return redirect()->back()->withErrors(['error' => __($status)]);

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro inesperado: ' . $e->getMessage()]);
        }
    }

    public function findUserByToken($token)
    {
        $resetEntry = DB::table('password_reset_tokens')
            ->get()
            ->first(function($entry) use ($token) {
                return hash_equals($entry->token, $token);
            });

        if (!$resetEntry) {
            throw new Exception('Token inválido ou expirado.');
        }

        return User::where('email', $resetEntry->email)->first();
    }
}
