<?php

namespace App\Http\Controllers;


use App\DTO\AddProductDTO;
use App\DTO\DecreaseProductDTO;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\DecreaseProductRequest;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProduct;

class CartController
{
    public function __construct(
        private CartService $cartService
    ) {}
    public function getCart()
    {
        $userProducts = Auth::user()->userProducts()->with('product')->get();
        $total = $userProducts->sum(function($item) {
            return $item->amount * $item->product->price;
//            $userProducts->sum()	Метод коллекции Laravel для суммирования значений
//            function($item)	Анонимная функция, получающая каждый элемент коллекции
//            $item->amount	Количество конкретного товара в корзине
//            $item->product->price	Цена товара (через отношение к модели Product)
        });
        return view('cart_form', ['userProducts' => $userProducts, 'total' => $total]);
    }
    public function addProduct(AddProductRequest $request)
    {
        $dto = new AddProductDTO(
            productId :$request->get('product_id'),
        );
        $amount = $this->cartService->addProduct($dto);
        $result = ['amount' => $amount];
        echo json_encode($result);
    }
    public function decreaseProduct(DecreaseProductRequest $request)
    {
        $dto = new DecreaseProductDTO(
            productId :$request->get('product_id'),
        );
        $amount = $this->cartService->decreaseProduct($dto);
        $result = ['amount' => $amount];
        echo json_encode($result);
    }

}
