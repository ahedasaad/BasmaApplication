<?php

namespace App\Repositories;

use App\Models\Basket;
use App\Models\Product;

class BasketRepository
{
    public function getUserBasket($userId)
    {
        return Basket::ensureUserBasket($userId);
    }

    public function addProductToBasket($basket, $productId)
    {
        $product = Product::findOrFail($productId);
        $basket->products()->attach($productId);

        return $basket;
    }

    public function removeProductFromBasket($basket, $productId)
    {
        $product = Product::findOrFail($productId);
        $basket->products()->detach($productId);

        return $basket;
    }

    public function clearBasket($userId)
    {
        $basket = Basket::where('user_id', $userId)->first();
        if ($basket) {
            $basket->products()->detach();
            $basket->total_price = 0;
            $basket->save();
        }
    }
}
