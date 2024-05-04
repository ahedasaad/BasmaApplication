<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Models\User;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = $this->productService->getAllPaginated();
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllCategories()
    {
        try {
            $categories = $this->productService->getAllCategories();
            return response()->json(['data' => $categories], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'exists:categories,id',
                'name' => 'string',
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'state' => 'string',
            ]);

            $user = User::find(auth()->id());
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $productData = [
                'user_id' => $user->id,
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'state' => $request->input('state'),
                'demand_state' => 'pending',
                'price' => 0,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $imagePath = $image->storeAs('products', $imageName, 'public'); // Store image in 'public/products' directory

                $productData['image'] = $imagePath; // Save image path to product data
            }

            // Update user data with mobile_number and address
            // $userData = $request->only(['mobile_number', 'address']); // Assuming these fields are part of the request
            // $user->update($userData);

            $product = $this->productService->createProduct($productData);

            $productResource = new ProductResource($product);
            return response()->json(['data ' => $productResource], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = $this->productService->findProductById($id);
            $productResource = new ProductResource($product);
            return response()->json(['data ' => $productResource], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'price' => 'required|integer|min:0',
            ]);

            $product = $this->productService->findProductById($id);
            $updatedProduct = $this->productService->updateProduct($product, $request->all());

            return response()->json(['data' => new ProductResource($updatedProduct)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Filter products by category.
     */
    public function filter(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
            $state = $request->input('state');

            $products = $this->productService->filterProducts([
                'category_id' => $categoryId,
                'state' => $state,
            ]);

            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function acceptProduct($id)
    {
        try {
            $product = $this->productService->acceptProduct($id);
            return response()->json(['message' => 'Product accepted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unacceptProduct($id)
    {
        try {
            $product = $this->productService->unacceptProduct($id);
            return response()->json(['message' => 'Product rejected successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProductsByCategory($categoryId)
    {
        try {
            $products = $this->productService->getProductsByCategory($categoryId);
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
