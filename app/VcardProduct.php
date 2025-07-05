<?php

/*
 |--------------------------------------------------------------------------
 | GoBiz vCard SaaS
 |--------------------------------------------------------------------------
 | Developed by NativeCode © 2021 - https://nativecode.in
 | All rights reserved
 | Unauthorized distribution is prohibited
 |--------------------------------------------------------------------------
*/

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VcardProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge',
        'currency',
        'product_image',
        'product_name',
        'product_description',
        'regular_price',
        'sales_price',
        'product_status'
    ];
}
