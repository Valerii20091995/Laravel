<?php

namespace App\Services;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(private CartService $cartService)
    {}
    public function createOrder(User $user, array $data):Order
    {
        $orderProducts = $this->cartService->getUserCart($user);
        $total = $this->cartService->getCartSum($user);
        DB::beginTransaction();
        try {
            if ($total < 500) {
                throw new \Exception('Минимальная сумма заказа 500 рублей');
            }
            $order = Order::query()->create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'comment' => $data['comment'],
                'address' => $data['address'],
                'user_id' => $user->id,
            ]);

            foreach ($orderProducts as $item) {
                $order->products()->attach($item->product_id, [
                    'amount' => $item->amount
                ]);
            }
            $this->cartService->deleteOrder($user);
            DB::commit();
            return $order;


        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
    public function getAll():array
    {
        /** @var User $user */
        $user = Auth::user();
        $orders = Order::with('products')
            ->where('user_id', $user->id)
            ->get();
        $result = [];

        foreach ($orders as $order) {
            $orderProducts = [];
            $totalSum = 0;

            foreach ($order->products as $product) {
                $sum = $product->pivot->amount * $product->price;

                $orderProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'amount' => $product->pivot->amount,
                    'price' => $product->price,
                    'sum' => $sum,
                ];
                $totalSum += $sum;
            }

            $result[] = [
                'id' => $order->id,
                'name' => $order->name,
                'phone' => $order->phone,
                'comment' => $order->comment,
                'address' => $order->address,
                'created_at' => $order->created_at,
                'orderProducts' => $orderProducts,
                'total_sum' => $totalSum,
            ];
        }
        return $result;
    }

}
