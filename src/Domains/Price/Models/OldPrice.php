<?php

namespace Domains\Price\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class OldPrice extends Model
{
    protected $table = 'old_price';
    protected $fillable = [
        'brand',
        'product_id',
        'original_price',
        'sale_price',
        'discount',
    ];

    public function product()
    {
        $this->belongsTo(Product::class);
    }

}
