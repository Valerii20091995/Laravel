<?php

namespace App\Http\Controllers;

class ReviewController
{
    public function getProduct(Product $product)
    {
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
        Review::query()->create([
            'product_id' => $product->id,
            'rating' => $request->rating,
            'author' => Auth::user()->name, // Берем имя авторизованного пользователя
            'product_review' => $request->product_review
        ]);

        return redirect()->route('product.show', ['product' => $product->id]);
    }
}
