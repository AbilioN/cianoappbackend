<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //

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

}
