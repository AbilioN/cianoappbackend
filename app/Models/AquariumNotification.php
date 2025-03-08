<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AquariumNotification extends Model
{

    use HasFactory;

    protected $fillable = ['aquarium_id', 'notification_id', 'consumable_notification_id', 'start_date', 'end_date', 'renew_date', 'is_read', 'is_active', 'read_at'];

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

        $notification = $this->notification;

        $notificationBodies = $notification->bodies;
        // dd('aqui');
         $notificationBodies->map(function ($body) {
            // Verifica se o título existe antes de tentar substituí-lo
            if (isset($body['title'])) {
                $body['title'] = str_replace('{aquarium_name}', $this->aquarium->name, $body['title']);
            }
            return $body; // Retorna o corpo atualizado
        });

        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        $today = Carbon::now('Europe/Lisbon');
        $totalDays = ceil($startDate->diffInDays($endDate));
        $daysLeft = ceil(max(0, $today->diffInDays($endDate, false))); // false para obter negativo se já passou
        $elapsedDays = $totalDays - $daysLeft;
        $progress = $totalDays > 0 ? ($elapsedDays / $totalDays) * 100 : 0;
        $progress = floor(max(0, min(100, $progress)));

        $returnData = [
            'id' => $this->id,
            'aquarium' => $this->aquarium->toDto(),
            'notification' => $this->notification,
            'consumable' => $this->consumable_notification_id ? $this->consumableNotification->consumable : null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'renew_date' => $this->renew_date,
            'is_read' => $this->is_read,
            'is_active' => $this->is_active,
            'read_at' => $this->read_at,
            'progress' => $progress,
            'total_days' => $totalDays,
            'days_left' => $daysLeft,
            'duration_type' => $notification->duration_type,
            'duration_value' => $notification->duration_value,
        ];

        // if (isset($this->consumable_notification_id)) {
        //     $returnData['consumable'] = $this->consumableNotification->consumable;
        // }

        return $returnData;
    }

    public function toBasicDto()
    {
        $notificationBodies = $this->notification->bodies;
        $notificationBodies->map(function ($body) {
            // Verifica se o título existe antes de tentar substituí-lo
            if (isset($body['title'])) {
                $body['title'] = str_replace('{aquarium_name}', $this->aquarium->name, $body['title']);
            }
            return $body; // Retorna o corpo atualizado
        });

        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);
        $today = Carbon::now('Europe/Lisbon');

        
        $totalDays = ceil($startDate->diffInDays($endDate));
        $daysLeft = ceil(max(0, $today->diffInDays($endDate, false))); // false para obter negativo se já passou
        $elapsedDays = $totalDays - $daysLeft;
        $progress = $totalDays > 0 ? ($elapsedDays / $totalDays) * 100 : 0;
        $progress = floor(max(0, min(100, $progress)));
        
        return [
            'id' => $this->notification_id,
            // 'aquarium_id' => $this->aquarium_id,
            // 'notification_id' => $this->notification_id,
            // 'consumable_notification_id' => $this->consumable_notification_id,
            'name' => $this->notification->name,
            'slug' => $this->notification->slug,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'renew_date' => $this->renew_date,
            'is_read' => $this->is_read,
            'is_active' => $this->is_active,
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'bodies' => $notificationBodies,
            'days_left' => $daysLeft,
            'total_days' => $totalDays,
            'progress' => $progress,
            'duration_type' => $this->notification->duration_type,
            'duration_value' => $this->notification->duration_value,
        ];

    }


    public function consumableNotification()
    {
        return $this->belongsTo(ConsumableNotification::class);
    }

    public function consumable()
    {
        return $this->belongsTo(Consumable::class);
    }
}
