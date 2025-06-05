<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    //

    protected $fillable = [
        'product_id',
        'type',
        'order',
        'content',
        'language',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function translations()
    // {
    //     return $this->hasMany(ProductDetailTranslation::class);
    // }
}
