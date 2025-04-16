<?php

namespace App\Http\Controllers;
use App\Models\Review;
use App\Models\Product;
use App\Http\Requests\ReviewRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class ReviewController
{

    public function getProduct(Product $product)
    {
//        dd($product);
        $reviews = $product->reviews;
        $averageRating = Review::getAverageRating($product->id);

        return view('review_form', [
            'product' => $product,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ]);
    }

    public function addReview(ReviewRequest $request, Product $product)
    {
//        dd($product);
        /** @var  User $user */
        Review::query()->create([
            'product_id' => $product->id,
            'rating' => $request->rating,
            'user_id' => Auth::id(),
            'product_review' => $request->product_review
        ]);

        return redirect()->route('product.show', ['product' => $product->id]);
    }
}
