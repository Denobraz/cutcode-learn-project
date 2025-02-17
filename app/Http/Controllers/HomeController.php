<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $categories = Category::query()->homePage()->get();
        $brands = Brand::query()->homePage()->get();
        $products = Product::query()->homePage()->get();

        return view('index', compact(
            'categories',
            'brands',
            'products'
        ));
    }
}
