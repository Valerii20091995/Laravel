<?php

namespace App\Services;

use App\DTO\AddProductDTO;
use App\DTO\DecreaseProductDTO;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProduct;
Use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function addProduct(AddProductDTO $dto):int
    {
        return DB::transaction(function () use ($dto) {
          $userProduct = UserProduct::query()->firstOrCreate(
              ['user_id'=> Auth::id(),'product_id'=> $dto->productId],
              ['amount'=>0]
          );
          return $userProduct->increment('amount');
        });
    }
    public function decreaseProduct(DecreaseProductDTO $dto):int
    {
        return DB::transaction(function () use ($dto) {
          /** @var User $user */
          $userProduct = UserProduct::query()->where([
              'user_id'=> Auth::id(),
              'product_id'=>$dto->productId],
          )->first();
          if (!$userProduct) return 0;

          if ($userProduct->amount > 1) {
              $userProduct->decrement('amount');
              return $userProduct->amount;
          }
          $userProduct->delete();
          return 0;
        });
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
