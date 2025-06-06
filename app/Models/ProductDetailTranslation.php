<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetailTranslation extends Model
{
    //

    protected $fillable = [
        'product_detail_id',
        'language',
        'content',
    ];

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class);
    }
    
}
