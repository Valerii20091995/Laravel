<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $order_id
 * @property int $product_id
 * @property int $amount
 */
class OrderProduct extends Model
{
    protected $fillable = ['order_id', 'product_id', 'amount'];

}
