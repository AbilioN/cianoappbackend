<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductCategory extends Model
{
    protected $fillable = ['slug'];

    public function translations(): HasMany
    {
        return $this->hasMany(ProductCategoryTranslation::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function translation(string $language): HasOne
    {
        return $this->hasOne(ProductCategoryTranslation::class)->where('language', $language);
    }
}
