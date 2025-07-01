<?php

namespace Modules\Product\Repositories;

use Modules\Product\App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        try {
            Log::info('Repository: Retrieving all products');
            $products = $this->model->all();
            Log::info('Repository: Found ' . $products->count() . ' products');
            return $products;
        } catch (\Exception $e) {
            Log::error('Repository error in all method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('Repository error in find method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error('Repository error in create method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $product = $this->find($id);
            $product->update($data);
            return $product;
        } catch (\Exception $e) {
            Log::error('Repository error in update method: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $product = $this->find($id);
            $this->deleteImage($product);
            return $product->delete();
        } catch (\Exception $e) {
            Log::error('Repository error in delete method: ' . $e->getMessage());
            throw $e;
        }
    }

    private function deleteImage(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }
    }
}