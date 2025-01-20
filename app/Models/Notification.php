<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [ 'key', 'duration_type', 'duration_value', 'type', 'start_date', 'end_date', 'renew_date', 'callback_key'];


    public function bodies()
    {
        return $this->hasMany(NotificationBody::class);
    }

    public function aquariumNotifications()
    {
        return $this->hasMany(AquariumNotification::class, 'Notification_id', 'id');
    }
}
