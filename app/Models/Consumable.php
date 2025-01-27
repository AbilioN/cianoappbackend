<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumableFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'image_url',
    ];

    public function consumableNotifications()
    {
        return $this->hasMany(ConsumableNotification::class);
    }

    public function notifications()
    {
        return $this->hasManyThrough(Notification::class, ConsumableNotification::class, 'consumable_id', 'id', 'id', 'notification_id');
    }

    public function notification()
    {
        return $this->hasOneThrough(Notification::class, ConsumableNotification::class, 'consumable_id', 'id', 'id', 'notification_id');
    }

    public function toDto()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'description' => $this->description,
            'image_url' => $this->image_url,
        ];
    }
}
