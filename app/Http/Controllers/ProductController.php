<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
class ProductController
{
    public function getCatalog()
    {
        /** @var  User $user */
        $user = Auth::user();
        $products = Cache::remember('products_all', 3600, function () {
            return Product::all();
        });

        return view('catalog_form', ['products' => $products]);
    }

}
