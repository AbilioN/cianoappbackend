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

    public function getAquarium(Request $request , $aquariumId)
    {

        $user = Auth::user();
        $aquarium = Aquarium::find($aquariumId);

        if(!$aquarium){
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }
        if($aquarium->user_id != $user->id){
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }
        return response()->json(['message' => 'success', 'message_code' => 'aquariums_retrieved_successfully', 'aquarium' => $aquarium->toDto()]);


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
                return response()->json(['message' => 'Consumable not found', 'message_code' => 'product_not_found'], 404);
            }

            $notification = $consumable->notification;
            $consumableNotification = ConsumableNotification::create([
                'consumable_id' => $consumable->id,
                'notification_id' => $notification->id,
            ]);

            $dates = $notification->calculateDates();
            $startDate = $dates['start_date'];
            $endDate = $dates['end_date'];

            $consumableExists = $consumableNotification->consumable->id;
            // $aquariumNotificationExists = AquariumNotification::where('aquarium_id', $aquarium->id)
            // ->where('consumable_notification_id', $consumableNotification->id)
            // ->where('notification_id', $notification->id)->exists();
            $aquariumNotificationExists = AquariumNotification::where('aquarium_id', $aquarium->id)
                ->whereHas('consumableNotification', function ($query) use ($consumable) {
                $query->where('consumable_id', $consumable->id);
            })->exists();
            if($aquariumNotificationExists) {
                DB::rollBack();
                return response()->json(['message' => 'Product already exists in this aquarium', 'message_code' => 'product_already_exists_in_this_aquarium'], 422);
                // Se o consumable ja existir, deve ir para a rota de activate notification
            }

            $aquariumNotification = AquariumNotification::create([
                'aquarium_id' => $aquarium->id,
                'notification_id' => $notification->id,
                'consumable_notification_id' => $consumableNotification->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            $aquarium = $aquarium->refresh();
            DB::commit();
            return response()->json(['message' => 'success', 'message_code' => 'product_added_successfully', 'aquarium' => $aquarium->toDto()]);
        }
        catch(\Exception $e){

            DB::rollBack();
            return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
        }

    }

    public function deleteAquarium(Request $request)
    {
        try {
            DB::beginTransaction();
    
            $user = Auth::user();
            $aquarium = Aquarium::find($request->aquarium_id);
    
            if(!$aquarium){
                return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
            }
            if($aquarium->user_id != $user->id){
                return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
            }
    
            $aquariumNotifications = $aquarium->aquariumNotifications();
    
            if($aquariumNotifications) {
                $aquariumNotifications->delete();
            }
            $aquarium->delete();
            
            DB::commit();
            return response()->json(['message' => 'success', 'message_code' => 'delete_aquarium_successfully']);
        }
        catch(Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'failed' , 'message_code' => 'delete_aquarium_failed' , 'errors' => $e->getMessage()], 500);
        }
    }

    public function deleteProduct(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'aquarium_id' => 'required|integer',
                'product_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'message_code' => 'validation_failed', 'errors' => $validator->errors()], 422);
            }

            $user = Auth::user();
            $aquarium = Aquarium::find($request->aquarium_id);

            if (!$aquarium) {
                return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
            }

            if ($aquarium->user_id != $user->id) {
                return response()->json(['message' => 'Aquarium not found', 'message_code' => 'aquarium_not_found'], 404);
            }

            // Find the aquarium notification that contains the product
            $aquariumNotification = AquariumNotification::where('aquarium_id', $aquarium->id)
                ->whereHas('consumableNotification', function ($query) use ($request) {
                    $query->where('consumable_id', $request->product_id);
                })->first();

            if (!$aquariumNotification) {
                return response()->json(['message' => 'Product not found in aquarium', 'message_code' => 'product_not_found_in_aquarium'], 404);
            }

            // Delete the aquarium notification (this will cascade delete related records due to foreign key constraints)
            $aquariumNotification->delete();
            $aquarium->refresh();
            DB::commit();

            // Get the last aquarium with its relationships
            $aquarium = Aquarium::with([
                'consumableNotifications.consumable',
                'aquariumNotifications.notification.bodies',
                'aquariumNotifications.aquarium'
            ])->where('user_id', $user->id)
              ->orderBy('created_at', 'desc')
              ->first();

            return response()->json([
                'message' => 'success', 
                'message_code' => 'product_deleted_successfully',
                'aquarium' => $aquarium->toDto()
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'failed', 'message_code' => 'delete_product_failed', 'errors' => $e->getMessage()], 500);
        }
    }
}

