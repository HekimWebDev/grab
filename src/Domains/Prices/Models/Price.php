<?php

namespace Domains\Prices\Models;

use App\Casts\Money;
use Domains\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $casts = [
        'original_price' => Money::class,
        'sale_price' => Money::class
    ];

    protected $fillable = [
        'product_id',
        'original_price',
        'sale_price',
    ];

//    protected $primaryKey = 'product_id';
//    public $incrementing = false;

    public function product()
    {
        $this->belongsTo(Product::class, 'product_product_id', 'product_id');
    }


}
