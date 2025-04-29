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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\ProductController;

class AuthController extends Controller
{
    protected $guideController;
    protected $productController;

    public function __construct(GuideController $guideController, ProductController $productController)
    {
        $this->guideController = $guideController;
        $this->productController = $productController;
    }

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

        // Obtendo os guias
        $guidesResponse = $this->guideController->getGuides();
        $guides = $guidesResponse->original;

        $productsResponse = $this->productController->getProductsByLanguage('pt');
        // $productsResponse = $this->productController->index('pt');
        $products = $productsResponse->original;

        // contando login

        if($user->email != 'admin@ciano.pt' && $user->email != 'test@email.com') {
            LoginCounter::create([
                'user_id' => $user->id,
                'datetime' => Carbon::now('Europe/Lisbon'),
            ]);
        }

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'message_code' => 'login_success',
            'user' => $user,
            'token' => $token,
            'aquariums' => $aquariums,
            'guides' => $guides,
            'products' => $products,
        ], 200);
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
                ? response()->json(['message' => 'E-mail de recuperação enviado!', 'message_code' => 'email_sent'])
                : response()->json(['message' => 'Erro ao enviar o email', 'message_code' => 'email_not_sent', 'error' => $status], 400);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'validation error', 'message_code' => 'validation_error', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro interno no servidor.', 'message_code' => 'internal_error', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            
            // Delete all user's tokens
            $user->tokens()->delete();
            
            // Delete login counters
            LoginCounter::where('user_id', $user->id)->delete();
            
            // Get all user's aquariums
            $aquariums = $user->aquariums;
            
            // Delete related records for each aquarium
            foreach ($aquariums as $aquarium) {
                // Delete aquarium notifications
                DB::table('aquarium_notifications')->where('aquarium_id', $aquarium->id)->delete();
                
                // Delete any other related records here if needed
                // For example: aquarium_parameters, aquarium_events, etc.
            }
            
            // Now delete the aquariums
            $user->aquariums()->delete();
            
            // Finally delete the user
            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Conta deletada com sucesso',
                'message_code' => 'account_deleted_successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar conta',
                'message_code' => 'delete_account_error',
                'error' => $e->getMessage()
            ], 500);
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
