<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginCounter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        
        try {

            DB::beginTransaction();
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
                $errors = $validator->errors();
                $messageCode = 'validation_failed';
                $statusCode = 422;
                $message = 'Validation failed';

                // Determine specific message code based on error
                if ($errors->has('name')) {
                    if ($errors->first('name') === 'The name field is required.') {
                        $messageCode = 'username_required';
                        $message = 'Name is required';
                    } else {
                        $messageCode = 'invalid_name';
                        $message = 'Invalid name format';
                    }
                } elseif ($errors->has('email')) {
                    if ($errors->first('email') === 'The email has already been taken.') {
                        $messageCode = 'email_already_exists';
                        $statusCode = 409; // Conflict status code
                        $message = 'Email already registered';
                    } else {
                        $messageCode = 'email_invalid';
                    }
                } elseif ($errors->has('password')) {
                    $messageCode = 'invalid_password';
                }
                
                return response()->json([
                    'message' => $message,
                    'message_code' => $messageCode,
                    'errors' => $errors
                ], $statusCode);
            }
            
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 2,
            ]);
            
            $token = $user->createToken('api-token')->plainTextToken;

            DB::commit();
            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'message_code' => 'user_created',
                'user' => $user,
                'token' => $token,
            ], 201);


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erro ao criar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
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
                'error_code' => 'invalid_credentials',
                'message_code' => 'invalid_credentials',
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('api-token')->plainTextToken;
        $aquariums = $user->aquariums->map(function ($aquarium) {
            return $aquarium->toDto();
        });

        // contando login
        LoginCounter::create([
            'user_id' => $user->id,
            'datetime' => Carbon::now('Europe/Lisbon'),
        ]);

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'message_code' => 'login_success',
            'user' => $user,
            'token' => $token,
            'aquariums' => $aquariums,
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
