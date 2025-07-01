<?php 

namespace Modules\Product\Services;

use Illuminate\Support\Facades\Log;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Transformers\ProductResource;

class ProductApiService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        try {
            Log::info('Fetching products from repository');
            $products = $this->repository->all();
            Log::info('Products fetched from repository: ' . json_encode($products));
            $productCollection = ProductResource::collection($products);
            return $productCollection->resolve();
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return [];
        }
    }

    public function get($id)
    {
        try {
            $product = $this->repository->find($id);
            return (new ProductResource($product))->resolve();
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return null;
        }
    }

    public function create(array $data)
    {
        try {
            $product = $this->repository->create($data);
            return (new ProductResource($product))->resolve();
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return null;
        }
    }

    public function update($id, array $data)
    {
        try {
            $product = $this->repository->update($id, $data);
            return (new ProductResource($product))->resolve();
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return null;
        }
    }

    public function delete($id)
    {
        try {
            $this->repository->delete($id);
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return false;
        }
    }
}