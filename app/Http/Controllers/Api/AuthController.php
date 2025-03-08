<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\LoginCounter;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
        ],
        [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'role_id' => 2,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciais inválidas',
                'error_code' => 'invalid_credentials'
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('api-token')->plainTextToken;

        // contando login
        LoginCounter::create([
            'user_id' => $user->id,
            'datetime' => Carbon::now('Europe/Lisbon'),
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

        public function sendResetLink(Request $request)
        {
            $request->validate(['email' => 'required|email']);
    
            $status = Password::sendResetLink($request->only('email'));
    
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'E-mail de recuperação enviado!'])
                : response()->json(['error' => 'Erro ao enviar e-mail.'], 400);
        }
    
        public function resetPassword(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);
    
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );
    
            return $status === Password::PASSWORD_RESET
                ? response()->json(['message' => 'Senha redefinida com sucesso!'])
                : response()->json(['error' => 'Erro ao redefinir senha.'], 400);
        }

}
