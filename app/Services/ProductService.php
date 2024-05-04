<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllCategories()
    {
        return $this->productRepository->getAllCategory();
    }

    public function getAllPaginated()
    {
        return $this->productRepository->getAllPaginated();
    }

    public function getProductsByCategory($categoryId)
    {
        return $this->productRepository->getByCategory($categoryId);
    }

    public function createProduct(array $attributes)
    {
        return $this->productRepository->create($attributes);
    }

    public function findProductById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function updateProduct($product, array $attributes)
    {
        return $this->productRepository->update($product, $attributes);
    }

    public function deleteProduct($id)
    {
        return $this->productRepository->delete($id);
    }

    public function filterProducts(array $attributes)
    {
        return $this->productRepository->filterProducts($attributes);
    }

    public function acceptProduct($id)
    {
        return $this->productRepository->acceptProduct($id);
    }

    public function unacceptProduct($id)
    {
        return $this->productRepository->unacceptProduct($id);
    }
}
