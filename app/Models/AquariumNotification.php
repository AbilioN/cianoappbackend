<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AquariumNotification extends Model
{
    //
    protected $fillable = ['aquarium_id', 'notification_id', 'start_date', 'end_date', 'renew_date', 'is_read', 'is_active', 'read_at'];

    public function aquarium()
    {
        return $this->belongsTo(Aquarium::class);
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }



    public function toDto()
    {
        return [
            'id' => $this->id,
            'aquarium' => $this->aquarium,
            'notification' => $this->notification,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'renew_date' => $this->renew_date,
            'is_read' => $this->is_read,
            'is_active' => $this->is_active,
            'read_at' => $this->read_at,
        ];
    }
}
