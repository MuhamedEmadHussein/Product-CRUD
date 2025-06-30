<?php

namespace Modules\Product\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\App\Http\Requests\ProductRequest;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Transformers\ProductResource;
use Modules\Product\Http\Requests\ProductRequest as RequestsProductRequest;
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
        $products = $this->repository->all();
        return ProductResource::collection($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(RequestsProductRequest $request)
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
    public function update(RequestsProductRequest $request, $id)
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
