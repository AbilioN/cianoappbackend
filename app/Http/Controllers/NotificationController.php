<?php

namespace App\Http\Controllers;

use App\Models\Aquarium;
use App\Models\AquariumNotification;
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

            $notification = Notification::where('slug', $request->notification_slug)->first();
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

            if (!$userAquariumNotification) {
                // return response()->json(['message' => 'failed' , 'message_code' => 'notification_already_activated' ], 404);
                $userAquariumNotification = AquariumNotification::firstOrCreate([
                    'aquarium_id' => $aquarium->id,
                    'notification_id' => $notification->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    // 'renew_date' => now()->addDays($notification->duration_value),
                ]);
                $isCreating = true;
            }

            if($isCreating){
                // $notification->is_active = true;
                // $notification->save();
                return response()->json(['message' => 'success' , 'message_code' => 'notification_activated_successfully' , 'notification' => $userAquariumNotification->toDto()]);
            }else{
                // return response()->json(['message' => 'failed' , 'message_code' => 'notification_already_activated' ], 404);
                return response()->json(['message' => 'success' , 'message_code' => 'notification_activated_successfully' , 'notification' => $userAquariumNotification->toDto()]);

            }

        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return response()->json(['message' => 'failed' , 'message_code' => 'notification_activated_failed' , 'errors' => $e->getMessage()], 500);
        }
    }
}