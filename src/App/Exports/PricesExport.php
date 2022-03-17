<?php

namespace App\Exports;

use Domains\Products\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PricesExport implements FromCollection
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection():Collection
    {
        return $this->product->prices;

    }
}
