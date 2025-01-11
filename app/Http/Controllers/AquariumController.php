<?php

namespace App\Http\Controllers;

use App\Models\Aquarium;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AquariumController extends Controller
{
    public function createAquarium(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'message_code' => 'validation_failed', 'errors' => $validator->errors()], 422);
            }
            
            $user = Auth::user();
            $slug = slugify($request->name);
            $slugExists = Aquarium::where('slug', $slug)->where('user_id', $user->id)->exists();
            
            if ($slugExists) {
                return response()->json(['message' => 'Aquarium created failed' , 'message_code' => 'aquarium_created_failed', 'errors' => 'Name already exists.'], 422);
            }
            
            DB::beginTransaction();
            $aquarium = Aquarium::create([
                'name' => $request->name,
                'slug' => $slug,
                'user_id' => $user->id,
            ]);
            // dd($aquarium);
            DB::commit();
            return response()->json(['message' => 'Aquarium created successfully', 'message_code' => 'aquarium_created_successfully', 'aquarium' => $aquarium]);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['message' => 'Aquarium created failed' , 'message_code' => 'aquarium_created_failed', 'errors' => $e->getMessage()], 500);
        }


        // return response()->json(['message' => 'Aquarium created successfully']);
    }

    public function getAquarium()
    {
        return response()->json(['message' => 'Aquarium retrieved successfully']);
    }
}

