<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'aquarium_id', 'key', 'duration_type', 'duration_value', 'type', 'start_date', 'end_date', 'renew_date', 'callback_key'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aquarium()
    {
        return $this->belongsTo(Aquarium::class);
    }

    public function bodies()
    {
        return $this->hasMany(NotificationBody::class);
    }

    public function aquariumNotifications()
    {
        return $this->hasMany(AquariumNotification::class, 'Notification_id', 'id');
    }
}
