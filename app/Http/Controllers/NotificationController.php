<?php

namespace App\Http\Controllers;

use App\Models\Aquarium;
use App\Models\AquariumNotification;
use App\Models\ConsumableNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    //

    public function getNotification($slug)
    {
        $notification = Notification::with('bodies')->where('slug', $slug)->first();
        return response()->json($notification);
    }

    public function activateNotification(Request $request)
    {
        try{
            // dd($request->all());
            // return response()->json([
            //     'message' => 'success',
            //     'message_code' => 'notification_activated',
            //     'notification' => $request->all()
            // ]);

            DB::beginTransaction();
            $isCreating = false;
            $user = Auth::user();

            $notification = Notification::find($request->notification_id);
            if (!$notification) {
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_not_found' ], 404);
            }

            $durationValue = $notification->duration_value;
            $durationType = $notification->duration_type;
            $startDate = now();

            if ($durationType === 'days') {
                $endDate = now()->addDays($durationValue);
            } elseif ($durationType === 'weeks') {
                $endDate = now()->addWeeks($durationValue);
            } elseif ($durationType === 'months') {
                $endDate = now()->addMonths($durationValue);
            } elseif ($durationType === 'minutes') {
                $endDate = now()->addMinutes($durationValue);
            }else if($durationType === 'seconds'){
                $endDate = now()->addSeconds($durationValue);
            } elseif ($durationType === 'hours') {
                $endDate = now()->addHours($durationValue);
            } else {
                $endDate = now();
            }
            

            $aquarium = Aquarium::find($request->aquarium_id);
            if(!$aquarium){
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_aquarium_not_found' ], 404);
            }

            if($aquarium->user_id != $user->id  ){
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_aquarium_not_found' ], 404);
            }

                $userAquariumNotification = AquariumNotification::where('aquarium_id', $aquarium->id)->where('notification_id', $notification->id)->first();

            $consumableNotificationId = null;
            $consumableNotification = ConsumableNotification::where('notification_id', $notification->id)->first();
            if ($consumableNotification) {
                $consumableNotificationId = $consumableNotification->id;

                // criar aquariumConsumable
                // $aquarium->consumables()

            }

            if (!$userAquariumNotification) {
                // return response()->json(['message' => 'failed' , 'message_code' => 'notification_already_activated' ], 404);
                $userAquariumNotification = AquariumNotification::firstOrCreate([
                    'aquarium_id' => $aquarium->id,
                    'notification_id' => $notification->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'consumable_notification_id' => $consumableNotificationId,
                    // 'renew_date' => now()->addDays($notification->duration_value),
                ]);
                $isCreating = true;
            }
            $aquarium->refresh();

            if(!$isCreating){
                $userAquariumNotification->is_active = true;
                $userAquariumNotification->save();
                DB::commit();
                return response()->json(['message' => 'success' , 'message_code' => 'notification_activated_successfully' , 'data' => $userAquariumNotification->toDto()]);
            }else{
                // return response()->json(['message' => 'failed' , 'message_code' => 'notification_already_activated' ], 404);
                DB::commit();
                return response()->json(['message' => 'success' , 'message_code' => 'notification_activated_successfully' , 'data' => $userAquariumNotification->toDto()]);
            }

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage() , debug_backtrace()[20]);
            return response()->json(['message' => 'failed' , 'message_code' => 'notification_activated_failed' , 'errors' => $e->getMessage()], 500);
        }
    }

    public function getAquariumNotifications($aquariumSlug)
    {
        $user = Auth::user();
        $aquarium = Aquarium::where('slug', $aquariumSlug)->where('user_id', $user->id)->with('allNotifications')->first();
        if(!$aquarium){
            return response()->json(['message' => 'failed' , 'message_code' => 'aquarium_not_found' ], 404);
        }
        if($aquarium->user_id !== $user->id){
            return response()->json(['message' => 'failed' , 'message_code' => 'aquarium_not_found' ], 404);
        }
        $aquariumNotifications = AquariumNotification::where('aquarium_id', $aquarium->id)->get();
        $aquariumNotificationsDto = $aquariumNotifications->map(function ($notification) {
            return $notification->toDto();
        });
        return response()->json($aquariumNotificationsDto);
    }

    public function deactiveNotification(Request $request)
    {

        try{

            DB::beginTransaction();
            $user = Auth::user();

            $notification = Notification::find($request->notification_id);
            if (!$notification) {
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_not_found' ], 404);
            }

            $aquarium = Aquarium::find($request->aquarium_id);
            if(!$aquarium){
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_aquarium_not_found' ], 404);
            }

            if($aquarium->user_id != $user->id  ){
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_aquarium_not_found' ], 404);
            }

                $userAquariumNotification = AquariumNotification::where('aquarium_id', $aquarium->id)->where('notification_id', $notification->id)->first();

            if (!$userAquariumNotification) {
                return response()->json(['message' => 'failed' , 'message_code' => 'notification_not_found' ], 404);
            }

            if($userAquariumNotification === false) {
                // criar menssagem de sucesso no front-end
                return response()->json(['message' => 'success' , 'message_code' => 'notification_deactivated_successfully' , 'data' => $userAquariumNotification->toDto()]);
            }
            $userAquariumNotification->is_active = false;
            $userAquariumNotification->save();
            DB::commit();
            // Criar menssagem de sucesso no front-end
            return response()->json(['message' => 'success' , 'message_code' => 'notification_deactivated_successfully' , 'data' => $userAquariumNotification->toDto()]);

        }catch(\Exception $e){
            DB::rollBack();
            // criar menssagem de erro no front-end
            return response()->json(['message' => 'failed' , 'message_code' => 'notification_deactivated_failed' , 'errors' => $e->getMessage()], 500);
        }
    }

}
