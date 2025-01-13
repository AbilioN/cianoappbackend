<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    public function getNotification($slug)
    {
        $notification = Notification::with('bodies')->where('slug', $slug)->first();
        return response()->json($notification);
    }
}
