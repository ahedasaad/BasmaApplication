<?php

namespace App\Services;

use App\Http\Resources\SoldProductResource;
use App\Repositories\OrderRepository;
use App\Repositories\BasketRepository;

class OrderService
{
    protected $orderRepository;
    protected $basketRepository;

    public function __construct(OrderRepository $orderRepository, BasketRepository $basketRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->basketRepository = $basketRepository;
    }

    public function placeOrder($userId, $data)
    {
        $basket = $this->basketRepository->getUserBasket($userId);

        foreach ($basket->products as $product) {
            $this->orderRepository->createOrder($userId, $product->id, $data);
        }

        // Clear the basket after placing the order
        $this->basketRepository->clearBasket($userId);
    }

    public function getPendingOrders()
    {
        return $this->orderRepository->getPendingOrders();
    }

    public function getAcceptedOrders()
    {
        return $this->orderRepository->getAcceptedOrders();
    }

    public function getReceivedOrders()
    {
        return $this->orderRepository->getReceivedOrders();
    }

    public function getUnreceivedOrders()
    {
        return $this->orderRepository->getUnreceivedOrders();
    }

    public function getDoneOrders()
    {
        return $this->orderRepository->getDoneOrders();
    }

    public function acceptOrder($orderId,$userId)
    {
        return $this->orderRepository->updateOrderStateToAccept($orderId,$userId);
    }

    public function updateOrderState($orderId)
    {
        return $this->orderRepository->updateOrderStateToReceived($orderId);
    }

    public function updateOrderStateToDone($orderId)
    {
        return $this->orderRepository->updateOrderStateToDone($orderId);
    }

    public function updateOrderStateToUnreceived($orderId, $note = null)
    {
        return $this->orderRepository->updateOrderStateToUnreceived($orderId, $note);
    }

    public function getOrderById($orderId)
    {
        return $this->orderRepository->getOrderById($orderId);
    }

    public function getUserOrders($userId)
    {
        return $this->orderRepository->getUserOrders($userId);
    }

    public function getSoldProductsBetweenDates($startDate, $endDate)
    {
        $soldProducts = $this->orderRepository->getSoldProductsBetweenDates($startDate, $endDate);

        $totalPrice = $soldProducts->sum(function ($buyProduct) {
            return $buyProduct->product->price;
        });

        return [
            'soldProducts' => SoldProductResource::collection($soldProducts),
            'totalPrice' => $totalPrice,
        ];
    }
}
