<?php

namespace App\Http\Controllers;

use App\Models\Aquarium;
use App\Models\AquariumNotification;
use App\Models\Consumable;
use App\Models\ConsumableNotification;
use App\Models\Notification;
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
            return response()->json(['message' => 'success', 'message_code' => 'aquarium_created_successfully', 'aquarium' => $aquarium]);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['message' => 'Aquarium created failed' , 'message_code' => 'aquarium_created_failed', 'errors' => $e->getMessage()], 500);
        }


        // return response()->json(['message' => 'Aquarium created successfully']);
    }

    public function getAquariums()
    {
        $user = Auth::user();
        // Usando o método with corretamente na consulta
        $aquariums = $user->aquariums()->with('consumableNotifications')->get();
        $aquariumDtos = $aquariums->map(function ($aquarium) {
            return $aquarium->toDto(); // Chamando o método toDto em cada aquário
        });
        if($aquariums->isEmpty()){
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }
        return response()->json(['message' => 'success', 'message_code' => 'aquariums_retrieved_successfully', 'aquariums' => $aquariumDtos]);
    }

    public function getAquarium(Request $request)
    {

        $user = Auth::user();
        $aquariums = $user->aquariums;
        if($aquariums->isEmpty()){
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }
        return response()->json(['message' => 'success', 'message_code' => 'aquariums_retrieved_successfully', 'aquariums' => $aquariums]);


    //     $user = Auth::user();
    //     $aquarium = $user->aquariums()->where('id', $request->id)->get()->first();
    // dd($aquarium);
    //     if(!$aquarium){
    //         return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
    //     }
    //     if($aquarium->user_id != $user->id) {
    //         return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
    //     }
    //     return response()->json(['message' => 'success', 'message_code' => 'aquarium_retrieved_successfully', 'aquarium' => $aquarium]);
    }

    public function addConsumable(Request $request)
    {

        try{

            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'aquarium_id' => 'required|integer',
                'consumable_code' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'message_code' => 'validation_failed', 'errors' => $validator->errors()], 422);
            }
            $user = Auth::user();
            $aquarium = $user->aquariums()->where('id', $request->aquarium_id)->get()->first();
            if(!$aquarium){
                return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
            }
            if($user->id != $aquarium->user_id){
                return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
            }
            $consumable = Consumable::where('code', $request->consumable_code)->get()->first();

            if(!$consumable){
                return response()->json(['message' => 'Consumable not found', 'message_code' => 'consumable_not_found'], 404);
            }

            $notification = $consumable->notification;
            $consumableNotification = ConsumableNotification::create([
                'consumable_id' => $consumable->id,
                'notification_id' => $notification->id,
            ]);

            $dates = $notification->calculateDates();
            $startDate = $dates['start_date'];
            $endDate = $dates['end_date'];

            $aquariumNotification = AquariumNotification::create([
                'aquarium_id' => $aquarium->id,
                'notification_id' => $notification->id,
                'consumable_notification_id' => $consumableNotification->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            $aquarium = $aquarium->refresh();
            DB::commit();
            return response()->json(['message' => 'success', 'message_code' => 'consumable_added_successfully', 'aquarium' => $aquarium->toDto()]);
        }
        catch(\Exception $e){

            dd($e->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }

    }
}

