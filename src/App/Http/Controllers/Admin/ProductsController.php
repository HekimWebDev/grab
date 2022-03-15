<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domains\AltinYildiz\Actions\AltinYildizManager;
use Domains\AltinYildiz\Actions\CreateAltinYildizActions;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Service\AltinYildiz\AltinYildizClient;

class ProductsController extends Controller
{

    public function index(): Factory|View|Application
    {
        $user = User::find(auth()->id());
        $product_count = Product::count();
        return view('admin.index', compact('user', 'product_count'));
    }

    /**
     * @throws GuzzleException
     */
    public function updatePrice($id): RedirectResponse
    {
        $product = Product::find($id);

        $checkMessage = 'Цена изменена';
        $oldPrice = $product->price;

//        dd($oldPrice);

        $client = new \Domains\ServiceManagers\AltinYildiz\AltinYildizManager();
        $client->updatePrices($id);

        $newPrice = $product->price;

//        dd($newPrice);

        if (($oldPrice->original_price == $newPrice->original_price) && ($oldPrice->sale_price == $newPrice->sale_price )){
            $checkMessage = 'Нет изменений в ценах';
        }
        return redirect()->back()->with('message', $checkMessage);
    }

    public function altinYildiz(Request $request): Factory|View|Application
    {
        $products = Product::when($request->service_type, function ($query, $v) {
                $query->where('service_type', $v);
            })
            ->when($request->id, function ($query, $v) {
                $query->where('product_id', $v);
            })
            ->when($request->name, function ($query, $v) {
                $query->where('name', "like", "%$v%");
            })
            ->when($request->code, function ($query, $v) {
                $query->where('product_code', "like", "%$v%");
            })
            ->with('price')
            ->select(['product_id', 'name', 'product_code'])
            ->latest()
            ->paginate(50);

            session()->flashInput($request->input());
//            dd($products->first()->price);
            return view('admin.altinyildiz.altinyildiz', compact('products'));
    }

    public function altinYildizSingle($id): Factory|View|Application
    {
        $product = Product::with('prices')
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
