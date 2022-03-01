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
        'category_name',
        'product_url',
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

    public function getMagazine()
    {
        switch ($this->service_type) {
            case 1 :
                echo "ALTINYILDIZ classics";
                break;
            case 2 :
                echo "COTON";
                break;
        }
    }

    public function getDate(): string
    {
        return Carbon::parse($this->updated_at)->diffForHumans(null, \Carbon\CarbonInterface::DIFF_RELATIVE_AUTO, false, 2);
    }

    public function percent(): float|int
    {
        return intval(100 - (($this->price->sale_price * 100) / $this->price->original_price));
    }
}
