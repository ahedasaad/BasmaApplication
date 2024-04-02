<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BasketProduct extends Pivot
{
    use HasFactory;

    protected $table = 'basket_products';
}
