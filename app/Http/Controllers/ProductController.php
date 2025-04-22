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
        $products = Product::all();

        return view('catalog_form', ['products' => $products]);

    }
    public function index()
    {
        $products = Cache::remember('products_all', 3600, function () {
            return Product::all();
        });

        return view('products.index', compact('products'));
    }
    // Cache::remember() проверяет, есть ли кэш по ключу products_all, и если нет — вызывает переданную функцию и кэширует результат на 3600 секунд (1 час).
    //
    //Product::all() загружает все продукты из БД.
    //
    //view() возвращает Blade-шаблон с переменной $products.
    public function store(Request $request)
    {
        Product::create($request->all());
        Cache::forget('products_all');

        return redirect()->route('products.index')
            ->with('success', 'Продукт создан и кэш сброшен');
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        Cache::forget('products_all');

        return redirect()->route('products.index')
            ->with('success', 'Продукт обновлён и кэш сброшен');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Cache::forget('products_all');

        return redirect()->route('products.index')
            ->with('success', 'Продукт удалён и кэш сброшен');
    }

}
