<?php

namespace App\Repositories;

use App\Models\BuyProduct;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class ProductRepository
{
    public function getAllCategory()
    {
        return Category::get();
    }

    public function getAllPaginated()
    {
        return Product::paginate(10);
    }

    public function getByCategory($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->where('demand_state', 'approved')
            ->paginate(10);
    }

    public function getProducts()
    {
        return Product::where('demand_state', 'approved')
            ->paginate(10);
    }

    public function create(array $attributes)
    {
        return Product::create($attributes);
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Product $product, array $attributes)
    {
        $product->update($attributes);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function filterProducts(array $attributes)
    {
        $categoryId = $attributes['category_id'] ?? null;
        $state = $attributes['state'] ?? null;

        $query = Product::query();

        if ($categoryId != null) {
            $query->where('category_id', '=', $categoryId);
        }

        if ($state != null) {
            $query->where('state', '=', $state);
        }

        return $query->get();
    }

    public function acceptProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->demand_state = 'approved';
        $product->save();
        return $product;
    }

    public function unacceptProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->demand_state = 'rejected';
        $product->save();
        return $product;
    }

    public function getPendingProduct()
    {
        return Product::where('demand_state', 'pending')->get();
    }

    public function getRejectedProduct()
    {
        return Product::where('demand_state', 'rejected')->get();
    }

    public function getMyProducts($user)
    {
        return $user->products()->paginate(10);
    }

    public function countProduct()
    {
        $productCount = Product::where('demand_state', 'approved')->count();
        return $productCount;
    }

    public function countPendingProduct()
    {
        $productCount = Product::where('demand_state', 'pending')->count();
        return $productCount;
    }
}
