<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'basket_products');
    }
    
    public static function ensureUserBasket($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['total_price' => 0]
        );
    }
}
