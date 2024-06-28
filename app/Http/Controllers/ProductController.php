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

        // $this->middleware(['permission:get_all_products'], ['only' => ['index', 'show']]);
        // $this->middleware(['permission:get_approved_products'], ['only' => ['getMarketPrdoucts', 'show']]);
        // $this->middleware(['permission:get_products_by_category'], ['only' => ['getProductsByCategory', 'show']]);
        // $this->middleware(['permission:get_all_categories'], ['only' => ['getAllCategories']]);
        // $this->middleware(['permission:create_product'], ['only' => ['store']]);
        // $this->middleware(['permission:update_product'], ['only' => ['update']]);
        // $this->middleware(['permission:delete_product'], ['only' => ['destroy']]);
        // $this->middleware(['permission:filter_product'], ['only' => ['filter']]);
        // $this->middleware(['permission:accept_product'], ['only' => ['acceptProduct']]);
        // $this->middleware(['permission:unaccept_product'], ['only' => ['unacceptProduct']]);
        // $this->middleware(['permission:get_user_products'], ['only' => ['getUserProducts']]);
        // $this->middleware(['permission:get_pending_products'], ['only' => ['getPendingProducts']]);
        // $this->middleware(['permission:get_rejected_products'], ['only' => ['getRejectedProducts']]);
        // $this->middleware(['permission:count_products'], ['only' => ['countProducts']]);
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

    public function getMarketPrdoucts()
    {
        try{
            $products = $this->productService->getProducts();

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
                'state' => 'in:used,new',
                'mobile_number' => 'string',
                'address' => 'string',
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
                $imagePath = $image->storeAs('products', $imageName, 'public');

                $productData['image'] = $imagePath; 
            }

            $product = $this->productService->createProduct($productData);

            // Update user data with mobile_number and address
            $userData = $request->only(['mobile_number', 'address']);
            $user->update($userData);

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

    public function getPendingProducts()
    {
        try {
            $products = $this->productService->pendingProduct();
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRejectedProducts()
    {
        try {
            $products = $this->productService->rejectedProduct();
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserProducts(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $products = $this->productService->myProducts($user);

            return ProductResource::collection($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countProducts()
    {
        try{
            $countProduct = $this->productService->getProductCount();
            return response()->json(['total_records = ' => $countProduct]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
