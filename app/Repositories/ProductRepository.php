<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    
    public function getAllPaginated()
    {
        return Product::paginate(10);
    }

    public function create(array $attributes)
    {
        return Product::create($attributes);
    }

    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Product $product,  array $attributes)
    {
        $product->update($attributes);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }

    public function filterPosts($postCategory)
    {
        $query = Product::query();

        if ($postCategory) {
            $query->where('post_category', '=', $postCategory);
        }

        return $query->get();
    }
}
