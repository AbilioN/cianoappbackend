<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuidePage extends Model
{
    protected $fillable = [
        'guide_id',
        'language',
        'order'
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(GuideComponent::class)->orderBy('order');
    }
}
