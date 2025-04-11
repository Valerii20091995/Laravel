<?php

namespace App\Services;

use App\DTO\AddProductDTO;
use App\DTO\DecreaseProductDTO;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProduct;
Use App\Models\User;

class CartService
{
    public function addProduct(AddProductDTO $dto):int
    {
        // нельзя через реквест делать в сервисах надо лии напрямую переменные либо дто создавать
        $userProduct = UserProduct::query()->firstOrCreate(
            ['user_id'=> Auth::id(),'product_id'=> $dto->productId],
            ['amount'=>0]
        );
        return $userProduct->increment('amount');
    }
    public function decreaseProduct(DecreaseProductDTO $dto):int
    {
        /** @var User $user */
        $userProduct = UserProduct::query()->where([
            'user_id'=> Auth::id(),
            'product_id'=>$dto->productId],
        )->first();
        if (!$userProduct) {
            return 0;
        }
        if ($userProduct->amount > 1) {
            $userProduct->decrement('amount');
            return $userProduct->amount;
        }
        $userProduct->delete();
        return 0;
    }
    public function getCartSum(User $user)
    {
        return UserProduct::query()->with('product')
            ->where('user_id',$user->id)
            ->get()->sum(function ($item) {
                return $item->amount * $item->product->price;
            });
    }
    public function getUserCart(User $user)
    {
        return UserProduct::query()->with('product')
            ->where('user_id', $user->id)
            ->get();
    }
    public function deleteOrder(User $user): void
    {
        UserProduct::query()->where('user_id',$user->id)->delete();
    }

}
