<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $product_id
 * @property int $user_id
 * @property string $date
 * @property int $grade
 * @property string $comment
 * @property User $user
 */

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'rating',
        'author',
        'product_review',
        'created_at'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
//    public static function getReviewByProductId($productId)
//    {
//        return self::where('product_id', $productId)->get();
//    }
    public static function getAverageRating($productId)
    {
        return self::where('product_id', $productId)->avg('rating');
    }
}
