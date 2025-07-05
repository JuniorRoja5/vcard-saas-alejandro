<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'buyer_email',
        'buyer_name',
        'amount',
        'stripe_session_id',
        'status'
    ];
}
