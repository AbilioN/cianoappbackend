<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guide extends Model
{
    protected $fillable = [
        'name',
        'category',
        'notification'
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(GuidePage::class)->orderBy('order');
    }

    public function pagesByLanguage(string $language): HasMany
    {
        return $this->hasMany(GuidePage::class)
            ->where('language', $language)
            ->orderBy('order');
    }
}
