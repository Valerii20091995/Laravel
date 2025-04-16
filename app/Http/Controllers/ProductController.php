<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
class ProductController
{
    public function getCatalog()
    {
        /** @var  User $user */
        $user = Auth::user();
        $products = Product::all();

        return view('catalog_form', ['products' => $products]);

    }

}
