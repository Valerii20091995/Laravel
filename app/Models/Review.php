<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $product_id
 * @property int $user_id
 * @property int $rating
 * @property string $product_review
 */

class Review extends Model
{
    use Notifiable;
    //разрешает доабвлять занчние в таблицу

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'product_review'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public static function getAverageRating($productId)
    {
        return self::where('product_id', $productId)->avg('rating');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
