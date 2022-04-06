<?php

namespace Domains\Prices\Models;

use Domains\Prices\Casts\Money;
use Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'product_id',
        'original_price',
        'sale_price',
        'internal_code'
    ];

    public function product()
    {
        $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
