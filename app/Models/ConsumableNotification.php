<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumableNotification extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumableNotificationFactory> */
    use HasFactory;

    protected $fillable = ['consumable_id', 'notification_id'];

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }

    public function toDto()
    {
        // return [
        //     'id' => $this->id,
        //     'consumable_id' => $this->consumable_id,
        // ];
        return $this->consumable->toDto();
    }
}
