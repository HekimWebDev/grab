<?php

namespace Domains\Products\Models;

use Domains\Prices\Models\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'product_id',
        'product_code',
        'service_type',
        'category_name',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'product_id';
    public $incrementing = false;

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'product_id');
    }

    public function price(): HasOne
    {
        return $this->hasOne(Price::class, 'product_id')->ofMany()->orderByDesc('created_at');
    }

    public function getMagazine($number)
    {
        switch ($number) {
            case 1 :
                echo "ALTINYILDIZ classics";
                break;
            case 2 :
                echo "COTON";
                break;
        }

    }
}
