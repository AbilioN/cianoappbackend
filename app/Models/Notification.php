<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'slug', 'duration_type', 'duration_value', 'type', 'start_date', 'end_date', 'renew_date', 'callback_key'];


    public function bodies()
    {
        return $this->hasMany(NotificationBody::class);
    }

    public function aquariumNotifications()
    {
        return $this->hasMany(AquariumNotification::class, 'notification_id', 'id');
    }

    public function toDto()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'duration_type' => $this->duration_type,
            'duration_value' => $this->duration_value,
            'type' => $this->type,
            'callback_key' => $this->callback_key,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'bodies' => $this->bodies,
        ];
    }

    public function calculateDates()
    {
        $durationValue = $this->duration_value;
        $durationType = $this->duration_type;
        $startDate = now();

        switch ($durationType) {
            case 'days':
                $endDate = now()->addDays($durationValue);
                break;
            case 'weeks':
                $endDate = now()->addWeeks($durationValue);
                break;
            case 'months':
                $endDate = now()->addMonths($durationValue);
                break;
            case 'minutes':
                $endDate = now()->addMinutes($durationValue);
                break;
            case 'hours':
                $endDate = now()->addHours($durationValue);
                break;
            default:
                $endDate = now();
                break;
        }

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

}
