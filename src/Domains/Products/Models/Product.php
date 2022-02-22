<?php

namespace Domains\Products\Models;

use Domains\Price\Models\OldPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'product_url',
        'product_code',
        'brand',
        'category_name',
        'original_price',
        'sale_price',
        'discount',
        'created_at',
        'updated_at',
    ];

    protected $table = 'products';

    public function oldPrices(): HasMany
    {
        return $this->hasMany(OldPrice::class, 'product_id');
    }
}
