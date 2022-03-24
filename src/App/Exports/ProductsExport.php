<?php

namespace App\Exports;

use Domains\Products\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, ShouldAutoSize, WithColumnFormatting, WithStyles, WithHeadings, WithMapping
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): Collection
    {
        $products = Product::when($this->request->service_type, function ($query, $v) {
                $query->where('service_type', $v);
            })
            ->when($this->request->id, function ($query, $v) {
                $query->where('product_id', $v);
            })
            ->when($this->request->name, function ($query, $v) {
                $query->where('name', "like", "%$v%");
            })
            ->when($this->request->code, function ($query, $v) {
                $query->where('product_code', "like", "%$v%");
            })
            ->whereInStock(1)
            ->with('price')
            ->select(['product_id', 'name', 'product_code', 'service_type', 'updated_at'])
            ->get();

        $products = $products->reject(fn($v) => !isset($v->price));

        return $products;
    }

    public function map($product): array
    {
        return [
            $product->product_id,
            $product->name,
            $product->product_code,
            $product->serviceType(),
            $product->price->original_price . ' TL',
            $product->price->sale_price . ' TL',
            $product->updated_at,
        ];
    }

    public function columnFormats(): array
    {
        return [
//            'F' => NumberFormat::FORMAT_DATE_DMYSLASH,
            'F' => NumberFormat::FORMAT_DATE_DMYSLASH,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1   => ['font' => ['size' => 13]],
            1   => ['font' => ['italic' => true]],
            1   => ['font' => ['bold' => true]],
            'A' => ['font' => ['bold' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            'Product_id',
            'Name',
            'Code',
            'Brand',
            'Original_Price',
            'Sale_Price',
            'Date',
        ];
    }

}
