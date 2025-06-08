<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideDetail extends Model
{
    protected $fillable = [
        'guide_id',
        'type',
        'content',
        'order',
        'language'
    ];

    protected $casts = [
        'content' => 'array',
        'order' => 'integer'
    ];

    public function guide(): BelongsTo
    {
        return $this->belongsTo(Guide::class);
    }
} 