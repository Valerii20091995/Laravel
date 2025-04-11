<?php

namespace App\Http\Controllers;

class OrderController
{
    public function getAll():array
    {
        /** @var User $user */
        $user = Auth::user();
        $orders = Order::with('products')
            ->where('user_id', $user->id)
            ->get();
        $result = [];

        foreach ($orders as $order) {
            $orderProducts =[];
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
