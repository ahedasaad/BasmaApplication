<?php

namespace App\Repositories;

use App\Models\Basket;
use App\Models\Product;
use App\Models\BuyProduct;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function createOrder($userId, $productId, $data)
    {
        return BuyProduct::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'state' => 'pending',
            'mobile_number' => $data['mobile_number'],
            'address' => $data['address'],
            'note' => $data['note'] ?? null,
        ]);
    }

    public function getPendingOrders()
    {
        return BuyProduct::where('state', 'pending')->get();
    }

    public function getReceivedOrders()
    {
        return BuyProduct::where('state', 'received')->get();
    }

    public function getUnreceivedOrders()
    {
        return BuyProduct::where('state', 'unreceived')->get();
    }

    public function getDoneOrders()
    {
        return BuyProduct::where('state', 'done')->get();
    }

    public function updateOrderStateToReceived($orderId)
    {
        $order = BuyProduct::findOrFail($orderId);
        $order->state = 'received';
        $order->save();

        return $order;
    }

    public function updateOrderStateToDone($orderId)
    {
        $order = BuyProduct::findOrFail($orderId);
        $order->state = 'done';
        $order->save();

        return $order;
    }

    public function updateOrderStateToUnreceived($orderId, $note)
    {
        $order = BuyProduct::findOrFail($orderId);
        $order->state = 'unreceived';
        $order->note = $note;
        $order->save();

        return $order;
    }

    public function getOrderById($orderId)
    {
        return BuyProduct::findOrFail($orderId);
    }

    public function getUserOrders($userId)
    {
        return BuyProduct::where('user_id', $userId)->get();
    }
}