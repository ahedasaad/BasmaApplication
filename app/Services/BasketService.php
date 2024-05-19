<?php

namespace App\Services;

use App\Repositories\BasketRepository;

class BasketService
{
    protected $basketRepository;

    public function __construct(BasketRepository $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function getUserBasket($userId)
    {
        $basket = $this->basketRepository->getUserBasket($userId);
        $basket->load('products');
        return $basket;
    }

    public function addProductToBasket($userId, $productId)
    {
        $basket = $this->basketRepository->getUserBasket($userId);
        return $this->basketRepository->addProductToBasket($basket, $productId);
    }

    public function removeProductFromBasket($userId, $productId)
    {
        $basket = $this->basketRepository->getUserBasket($userId);
        return $this->basketRepository->removeProductFromBasket($basket, $productId);
    }
}