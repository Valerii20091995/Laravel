<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use App\Services\OrderService;
use App\Models\User;
class OrderController
{
    public function __construct(
        private OrderService $orderService,
        private CartService $cartService
    ) {}
    public function getCheckOutForm()
    {
        /** @var User $user */
        $cartItems = $this->cartService->getUserCart(Auth::user());
        $total = $this->cartService->getCartSum(Auth::user());
        if ($cartItems->isEmpty()) {
            return redirect()->route('catalog');
        }
        return view('order_form', ['cartItems'=>$cartItems, 'total'=>$total]);
    }
    public function handleCheckOut(OrderRequest $request)
    {
        /** @var User $user */
        try {
            $order = $this->orderService->createOrder(
                Auth::user(),
                $request->validated()
            );
            return redirect()->route('catalog');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    public function getAll()
    {
        $userOrders = $this->orderService->getAll();

        return view('order_detail', ['userOrders'=>$userOrders]);
    }


}
