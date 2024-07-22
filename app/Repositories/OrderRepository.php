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
        $userId = auth()->id();

        if (is_null($userId)) {
            throw new \Exception('User not authenticated');
        }

        return BuyProduct::where('state', 'pending')
            ->where('representative_id', $userId)
            ->get();
    }

    public function getAcceptedOrders()
    {
        $userId = auth()->id();

        if (is_null($userId)) {
            throw new \Exception('User not authenticated');
        }

        return BuyProduct::where('state', 'accept')
            ->where('representative_id', $userId)
            ->get();
    }

    public function getReceivedOrders()
    {
        $userId = auth()->id();

        if (is_null($userId)) {
            throw new \Exception('User not authenticated');
        }

        return BuyProduct::where('state', 'received')
            ->where('representative_id', $userId)
            ->get();
    }

    public function getDoneOrders()
    {
        $userId = auth()->id();

        if (is_null($userId)) {
            throw new \Exception('User not authenticated');
        }
        return BuyProduct::where('state', 'done')
            ->where('representative_id', $userId)
            ->get();
    }

    public function getUnreceivedOrders()
    {
        return BuyProduct::where('state', 'unreceived')->get();
    }
    public function updateOrderStateToAccept($orderId, $userId)
    {
        $order = BuyProduct::findOrFail($orderId);
        $order->state = 'accept';
        $order->representative_id = $userId;

        $order->save();

        return $order;
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