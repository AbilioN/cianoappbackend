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
use Illuminate\Validation\ValidationException;

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
        try {
            $request->validate(['email' => 'required|email']);
            $emailExists = User::where('email', $request->email)->exists();
            if (!$emailExists) {
                return response()->json(['message' => 'Email do not exists', 'message_code' => 'email_not_found'], 404);
            }
    
            $status = Password::sendResetLink($request->only('email'));
    
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['message' => 'E-mail de recuperação enviado!'])
                : response()->json(['message' => 'Erro ao enviar o email', 'message_code' => 'email_not_sent', 'error' => $status], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'validation error', 'message_code' => 'validation_error', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno no servidor.', 'message_code' => 'internal_error', 'error' => $e->getMessage()], 500);
        }
    }
    
    // public function resetPassword(Request $request)
    // {
    //     try {
    //         // Validação com retorno de erro JSON
    //         $validatedData = $request->validate([
    //             'email' => 'required|email',
    //             'token' => 'required',
    //             'password' => 'required|min:8|confirmed',
    //         ]);
    
    //         $status = Password::reset(
    //             $validatedData,
    //             function ($user, $password) {
    //                 $user->forceFill([
    //                     'password' => Hash::make($password),
    //                 ])->save();
    //             }
    //         );
    
    //         if ($status === Password::PASSWORD_RESET) {
    //             return response()->json(['message' => 'Senha redefinida com sucesso!'], 200);
    //         }
    
    //         return response()->json(['message' => 'Erro ao redefinir senha', 'message_code' => 'redefinition_failed', 'error' => $status], 400);
    //     } catch (ValidationException $e) {
    //         return response()->json(['message' => 'validation error', 'message_code' => 'validation_error', 'errors' => $e->errors()], 422);
    //     } catch (Exception $e) {
    //         return response()->json(['message' => 'Erro interno no servidor.', 'message_code' => 'internal_error', 'error' => $e->getMessage()], 500);
    //     }
    // }
}
