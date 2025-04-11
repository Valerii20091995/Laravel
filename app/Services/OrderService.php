<?php

namespace App\Services;

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
//            if ($total < 500) {
//                throw new \Exception('Минимальная сумма заказа 500 рублей');
//            }
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

}
