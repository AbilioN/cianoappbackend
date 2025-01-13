<?php

namespace App\Http\Controllers;

use App\Models\Aquarium;
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

            DB::beginTransaction();
            $user = Auth::user();
            $aquarium = Aquarium::create([
                'name' => $request->name,
                'user_id' => $user->id,
            ]);
            // dd($aquarium);
            DB::commit();
            return response()->json(['message' => 'success', 'message_code' => 'aquarium_created_successfully', 'aquarium' => $aquarium]);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['message' => 'Aquarium created failed' , 'message_code' => 'aquarium_created_failed', 'errors' => $e->getMessage()], 500);
        }


        // return response()->json(['message' => 'Aquarium created successfully']);
    }

    public function getAquarium()
    {
        $user = Auth::user();
        $aquariums = $user->aquariums;
        if($aquariums->isEmpty()){
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }
        return response()->json(['message' => 'success', 'message_code' => 'aquariums_retrieved_successfully', 'aquariums' => $aquariums]);
    }
}

