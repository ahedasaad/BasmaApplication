<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'name',
        'image',
        'state',
        'demand_state',
        'price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buy_product()
    {
        return $this->hasOne(BuyProduct::class);
    }

    public function baskets()
    {
        return $this->belongsToMany(Basket::class, 'basket_products');
    }
}
