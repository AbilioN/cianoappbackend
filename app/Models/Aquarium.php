<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aquarium extends Model
{
    //

    protected $fillable = ['name', 'slug', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->hasMany(AquariumNotification::class);
    }

public function allNotifications()
    {
        return $this->hasManyThrough(
            Notification::class,
            AquariumNotification::class,
            'aquarium_id',
            'id',
            'id',
            'notification_id'
        )->with('bodies');
    }

}
