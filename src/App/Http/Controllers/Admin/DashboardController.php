<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Domains\Products\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        for ($i = 1; $i < 6; $i++){
            $counts[$i] = Product::where('service_type', $i)
                ->whereInStock(1)
                ->has('price')
                ->count();
        }

        return view('admin.altinyildiz.dashboard', compact('counts'));
    }
}
