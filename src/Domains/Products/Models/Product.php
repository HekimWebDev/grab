<?php

namespace App\Models;

use Domains\Price\Models\OldPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'product_code',
        'product_url',
        'original_price',
        'sale_price',
        'discount',
    ];

    protected $table = 'products';

    public function oldPrices(): HasMany
    {
        return $this->hasMany(OldPrice::class, 'product_id');
    }
}
