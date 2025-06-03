<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'product_category_id',
        'name',
        'description',
        'image',
        'price',
        'stock',
        'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ProductDetail::class);
    }

    public function translation(string $language): HasOne
    {
        return $this->hasOne(ProductTranslation::class)->where('language', $language);
    }
}
