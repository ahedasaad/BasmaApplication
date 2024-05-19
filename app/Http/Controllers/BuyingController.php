<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BasketService;
use App\Services\OrderService;
use App\Models\User;
use App\Http\Resources\ProductResource;
use App\Http\Resources\BuyingResource;

class BuyingController extends Controller
{
    protected $basketService;
    protected $orderService;

    public function __construct(BasketService $basketService, OrderService $orderService)
    {
        $this->basketService = $basketService;
        $this->orderService = $orderService;
    }

    public function showUserBasket(Request $request)
    {
        try {

            $user = User::find(auth()->id());
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $basket = $this->basketService->getUserBasket($user->id);

            return response()->json([
                'basket' => $basket,
                'products' => ProductResource::collection($basket->products),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addToBasket(Request $request, $productId)
    {
        try {
            $user = User::find(auth()->id());

            $basket = $this->basketService->addProductToBasket($user->id, $productId);

            return response()->json([
                'message' => 'Product added to basket'
                // 'basket' => $basket,
                // 'products' => ProductResource::collection($basket->products),
                //,200
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removeFromBasket(Request $request, $productId)
    {
        try {
            $user = User::find(auth()->id());
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $basket = $this->basketService->removeProductFromBasket($user->id, $productId);

            return response()->json([
                'message' => 'Product removed from basket'
                // 'basket' => $basket,
                // 'products' => ProductResource::collection($basket->products),
                ,
                //200
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'mobile_number' => 'string',
            'address' => 'string',
        ]);

        try {
            $user = User::find(auth()->id());
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $this->orderService->placeOrder($user->id, $request->all());

            return response()->json(['message' => 'Order created successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showOrder($orderId)
    {
        try {
            $order = $this->orderService->getOrderById($orderId);

            return new BuyingResource($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPendingOrders()
    {
        try {
            $pendingOrders = $this->orderService->getPendingOrders();

            return BuyingResource::collection($pendingOrders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getReceivedOrders()
    {
        try {
            $receivedOrders = $this->orderService->getReceivedOrders();

            return BuyingResource::collection($receivedOrders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUnreceivedOrders()
    {
        try {
            $unreceivedOrders = $this->orderService->getUnreceivedOrders();

            return BuyingResource::collection($unreceivedOrders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDoneOrders()
    {
        try {
            $doneOrders = $this->orderService->getDoneOrders();

            return BuyingResource::collection($doneOrders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateOrderState($orderId)
    {
        try {
            $order = $this->orderService->updateOrderState($orderId);

            return response()->json(['message' => 'Order Received successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateOrderStateToDone($orderId)
    {
        try {
            $order = $this->orderService->updateOrderStateToDone($orderId);

            return response()->json(['message' => 'Order Done successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateOrderStateToUnreceived(Request $request, $orderId)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        try {
            $note = $request->input('note');
            $order = $this->orderService->updateOrderStateToUnreceived($orderId, $note);

            return new BuyingResource($order);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserOrders()
    {
        try {
            $user = User::find(auth()->id());

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $orders = $this->orderService->getUserOrders($user->id);

            return BuyingResource::collection($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
