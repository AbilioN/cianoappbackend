<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aquarium extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name', 'slug', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function aquariumNotifications()
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

    public function consumableNotifications()
    {
        return $this->hasManyThrough(
            ConsumableNotification::class,
            AquariumNotification::class,
            'aquarium_id',
            'id',
            'id',
            'consumable_notification_id'
        )->with('consumable');
    }

    public function toDto()
    {

        $consumableNotifications = $this->consumableNotifications;
        $consumableNotificationsDto = $consumableNotifications->map(function ($consumableNotification) {
            return $consumableNotification->toDto();

        });

        $aquariumNotifications = $this->aquariumNotifications;
        $aquariumNotificationsDto = $aquariumNotifications->map(function ($aquariumNotification) {
            return $aquariumNotification->toBasicDto();
        });
        $returnArray = [];
        $returnArray['id'] = $this->id;
        $returnArray['name'] = $this->name;
        $returnArray['slug'] = $this->slug;
        $returnArray['user_id'] = $this->user_id;
        $returnArray['created_at'] = $this->created_at;
        $returnArray['updated_at'] = $this->updated_at;

        // $returnArray['notifications'] = $this->notifications;

        if(count($consumableNotificationsDto) > 0){
            $returnArray['consumables'] = $consumableNotificationsDto;
        }
        if(count($aquariumNotificationsDto) > 0){
            $returnArray['notifications'] = $aquariumNotificationsDto;
        }
        return $returnArray;

    }

    public function toBasicDto()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }



}
