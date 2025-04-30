<?php

namespace App\Services;
use App\Jobs\SendHttpRequest;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderService
{
    public function __construct(
        private CartService $cartService,
        private LoggerService $loggerService
    ) {}
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
//            $response = Http::get('https://yougile.com/api-v2/auth/keys');
//            $response->body();
//            print_r($response);
            // ниже предоставлен синхронный метод создание задача в проекте в юджайл
//            Http::withHeaders([
//                'Content-Type' => 'application/json',
//                'Authorization' => "Bearer FyfUN5DPJi-eRUuTHu7dGkXh41EYlXTGIfhcoYDnOcQkJemAsHdiFta9mZ-2bO9X"
//            ])->post("https://yougile.com/api-v2/tasks",[
//                'title' => "Заказ № $order->id",
//                "columnId" => "3972ae1f-e4d6-4963-b9c9-cd0fbba0b6ef",
//                "description" => json_encode($orderProducts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
//                "archived" => false,
//                "completed" => false,
//            ])->throw()->json();
            // ниже предоставлен ассинхронный метод через job
            SendHttpRequest::dispatch($order);
            DB::commit();
            return $order;


        } catch (\Throwable $exception) {
            DB::rollBack();
            $this->loggerService->logError($exception);
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
