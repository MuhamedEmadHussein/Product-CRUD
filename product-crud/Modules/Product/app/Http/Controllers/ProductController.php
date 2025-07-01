<?php

namespace Modules\Product\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::info('ProductController: Retrieving all products');
            $products = $this->repository->all();
            Log::info('Products found: ' . $products->count());
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            Log::error('Error in index method: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(ProductRequest $request)
    {
        $product = $this->repository->create($request->validated());
        return new ProductResource($product);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $product = $this->repository->find($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {
        $product = $this->repository->update($id, $request->validated());
        return new ProductResource($product);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json(['message' => 'Product deleted successfully'],200);
    }
}
