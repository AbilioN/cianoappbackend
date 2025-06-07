<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuideComponent extends Model
{
    protected $fillable = [
        'guide_page_id',
        'type',
        'content',
        'order'
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(GuidePage::class);
    }
}
