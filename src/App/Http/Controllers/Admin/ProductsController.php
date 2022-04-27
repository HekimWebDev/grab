<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\AltinyildizGrabJob;
use App\Models\User;
use Domains\Prices\Models\Price;
use Domains\ServiceManagers\AltinYildiz\AltinYildizManager;
use Domains\Products\Models\Product;
use Domains\ServiceManagers\Koton\KotonManager;
use Domains\ServiceManagers\Mavi\MaviManager;
use Domains\ServiceManagers\Ramsey\RamseyManager;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use Service\AltinYildiz\AltinYildizClient;
use Service\Koton\KotonClient;
use Service\Mavi\MaviClient;
use Service\Ramsey\RamseyClient;

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

        $message = 'Нет изменений в ценах.';

        switch ($product->service_type){
            case 1:
                $response = $this->altinyildizPrice($product->product_url);
                break;
            case 2:
                $response = $this->ramseyPrice($product->product_url);
                break;
            case 3:
                $response = $this->MaviPrice($product->product_url);
                break;
            case 4:
                $response = $this->KotonPrice($product->product_url);
                break;

        }

        $oldPrices = $product->price;

        if ($product->service_type < 4) {
            $originPrice = ayLiraFormatter($response['original_price']);

            $salePrice = ayLiraFormatter($response['sale_price']);
        } else {
            $originPrice = $response['original_price'];

            $salePrice = $response['sale_price'];
        }

        if (empty($oldPrices) || !($oldPrices->original_price == $originPrice && $oldPrices->sale_price == $salePrice)) {

            Price::create([
                'product_id'        => $product->id,
                'original_price'    => $originPrice,
                'sale_price'        => $salePrice,
                'internal_code'     => $product->internal_code
            ]);

            $message = 'Цена изменена!';
        }

        $product->touch();

        return redirect()->back()->with('message', $message);
    }

    private function KotonPrice(string $url): array
    {
        $manager = new KotonManager();

        return $manager->getOnePrice($url);


    }

    private function altinyildizPrice($url): array
    {
        $service = new AltinYildizClient();

        return $service->getOnePrice($url);
    }

    private function ramseyPrice($url): array
    {
        $service = new RamseyClient();

        return $service->getOnePrice($url);
    }

    private function maviPrice($url): array
    {
        $manager = new MaviClient();

        return $manager->getOnePrice($url);
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
            ->select(['id', 'product_id', 'name', 'product_code'])
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
            2 => 'https://www.ramsey.com.tr/',
            3 => 'https://www.mavi.com/',
            4 => 'https://www.koton.com/',
        ];

        return view('admin.altinyildiz.prices', compact('product','base_urls'));
    }

    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request), 'exported at ' . now() . '.xlsx');
    }

}
