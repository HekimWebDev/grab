<?php

namespace Domains\Products\Models;

use Carbon\Carbon;
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
        'category_url',
        'product_url',
        'in_stock'
    ];

    private $brands = [
        1   => 'Altinyildiz Classik',
        2   => 'Ramsey',
        3   => 'Mavi',
        4   => 'Koton',
    ];

    /**
     * @var mixed
     */
    private $price;

    public function serviceType()
    {
        return $this->brands[$this->service_type];
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class)
            ->orderBy('id', 'desc');
    }

    public function price(): HasOne
    {
        return $this->hasOne(Price::class, 'product_id', 'id')->ofMany()->orderByDesc('created_at');
    }

    public function getMagazine()
    {
        switch ($this->service_type) {
            case 1 :
                echo "ALTINYILDIZ classics";
                break;
            case 2 :
                echo "Ramsey";
                break;
        }
    }

    public function percent(): float|int
    {
        return intval(100 - (($this->price->sale_price * 100) / $this->price->original_price));
    }
}
