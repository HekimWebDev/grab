<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AltinyildizGrabJob;
use App\Models\User;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Domains\Products\Models\Product;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;

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
    public function checkPrice($id, $serviceType): RedirectResponse
    {
        $product = Product::whereProductId($id)
            ->whereServiceType($serviceType)
            ->first();

        AltinyildizGrabJob::dispatch($product);

        return redirect()->back()->with('message', 'Цена проверяется');
    }

    public function products(Request $request): Factory|View|Application
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
            ->whereInStock(1)
            ->orderBy('service_type', 'desc')
            ->with('price')
            ->select(['product_id', 'name', 'product_code'])
            ->orderBy('product_id')
            ->latest()
            ->paginate(50);

        session()->put(['prevUrl' => url()->full()]);
        session()->flashInput($request->input());

        return view('admin.altinyildiz.altinyildiz', compact('products'));
    }

    public function productSingle($id): Factory|View|Application
    {
        $product = Product::with('prices')
            ->whereProductId($id)
            ->first();

        $base_urls = [
            1 => 'https://www.altinyildizclassics.com',
            2 => 'https://www.ramsey.com.tr/'
        ];

        return view('admin.altinyildiz.prices', compact('product','base_urls'));
    }

    public function export(Request $request)
    {
        // 29655
        return Excel::download(new ProductsExport($request), 'exported at ' . now() . '.xlsx');
//        return (new ProductsExport($request))->download('exported at ' . now() . '.xlsx');
    }

}
