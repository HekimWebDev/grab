<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * @throws GuzzleException
     */
    public function altinYildizCheck($id){

        $client = new CreateAltinYildizActions();
        $client->checkDailyPrices($id);

        return redirect()->back();
    }

    public function index(): Factory|View|Application
    {
        return view('admin.index');
    }

    public function altinYildiz(Request $request): Factory|View|Application
    {
        $products = Product::with('price')
//            ->when($request->id, fn($q, $v) => $q->whereId($v))
//            ->when($request->name, fn($q, $v) => $q->where("name", 'like'. "%$v%"))
//            ->whereInStock(1)
            ->whereServiceType(1)
            ->select(['product_id', 'name', 'category_name', 'product_code', 'old_prices'])
            ->latest()
            ->paginate(50);
//        $count = Product::count();

        return view('admin.altinyildiz.altinyildiz', compact('products'));
    }

    public function altinYildizSingle($id): Factory|View|Application
    {
        $product = Product::with('prices', 'price')
            ->whereProductId($id)
            ->whereServiceType(1)
            ->first();
        return view('admin.altinyildiz.prices', compact('product'));
    }

    /* public function getProductsAjax(Request $request)
     {
         if ($request->ajax()) {
             $data = Product::latest()->get();
             return \DataTables::of($data)
                 ->addIndexColumn()
                 ->addColumn('action', function ($row) {
                     $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Смотреть</a>';
                     return $actionBtn;
                 })
                 ->rawColumns(['action'])
                 ->make(true);
         }
         return null;
     }*/
}
