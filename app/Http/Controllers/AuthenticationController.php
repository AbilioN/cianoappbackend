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
use App\Mail\PasswordChangedMail;

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
        $language = $request->input('language', 'en');

        $passwordRules = [
            'required' => [
                'pt' => 'A senha é obrigatória.',
                'en' => 'Password is required.',
                'es' => 'La contraseña es obligatoria.',
                'fr' => 'Le mot de passe est obligatoire.',
                'de' => 'Passwort ist erforderlich.',
                'it' => 'La password è obbligatoria.',
            ],
            'min' => [
                'pt' => 'A senha deve ter pelo menos 8 caracteres.',
                'en' => 'Password must be at least 8 characters.',
                'es' => 'La contraseña debe tener al menos 8 caracteres.',
                'fr' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'de' => 'Das Passwort muss mindestens 8 Zeichen lang sein.',
                'it' => 'La password deve essere di almeno 8 caratteri.',
            ],
            'confirmed' => [
                'pt' => 'A confirmação da senha não corresponde.',
                'en' => 'Password confirmation does not match.',
                'es' => 'La confirmación de la contraseña no coincide.',
                'fr' => 'La confirmation du mot de passe ne correspond pas.',
                'de' => 'Die Passwortbestätigung stimmt nicht überein.',
                'it' => 'La password di conferma non corrisponde.',
            ],
            'regex' => [
                'pt' => 'A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.',
                'en' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
                'es' => 'La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial.',
                'fr' => 'Le mot de passe doit contenir au moins une lettre majuscule, une minuscule, un chiffre et un caractère spécial.',
                'de' => 'Das Passwort muss mindestens einen Großbuchstaben, einen Kleinbuchstaben, eine Zahl und ein Sonderzeichen enthalten.',
                'it' => 'La password deve contenere almeno una lettera maiuscola, una lettera minuscola, un numero e un carattere speciale.',
            ]
        ];

        $request->validate([
            'token' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'password.required' => $passwordRules['required'][$language] ?? $passwordRules['required']['en'],
            'password.min' => $passwordRules['min'][$language] ?? $passwordRules['min']['en'],
            'password.confirmed' => $passwordRules['confirmed'][$language] ?? $passwordRules['confirmed']['en'],
            'password.regex' => $passwordRules['regex'][$language] ?? $passwordRules['regex']['en'],
        ]);

        try {
            // Primeiro encontramos o registro do reset
            $resetEntry = DB::table('password_reset_tokens')
                ->get()
                ->first(function($entry) use ($request) {
                    // O token no banco está hasheado, então precisamos verificar se o hash bate
                    return Hash::check($request->token, $entry->token);
                });

            $invalidTokenMessage = [
                'pt' => 'Token inválido ou expirado.',
                'en' => 'Invalid or expired token.',
                'es' => 'Token inválido o expirado.',
                'fr' => 'Token invalide ou expiré.',
                'de' => 'Token ungültig oder abgelaufen.',  
            ];

            if (!$resetEntry) {
                $errorMessage = $invalidTokenMessage[$language] ?? $invalidTokenMessage['en'];
                return redirect()->back()->withErrors(['error' => $errorMessage]);
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

            $successMessages = [
                'pt' => 'Senha redefinida com sucesso!',
                'en' => 'Password reset successfully!',
                'es' => '¡Contraseña restablecida con éxito!',
                'fr' => 'Mot de passe réinitialisé avec succès!',
                'de' => 'Passwort erfolgreich zurückgesetzt!',
                'it' => 'Password reimpostato con successo!',
            ];

            if ($status === Password::PASSWORD_RESET) {
                // Enviar email de confirmação
                $language = $request->input('language', 'en');
                $view = "components.emails.password-changed-{$language}";
                Mail::to($resetEntry->email)->send(new PasswordChangedMail($view));

                $successMessage = $successMessages[$language] ?? $successMessages['en'];

                return redirect()->back()->with('success', $successMessage);
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
