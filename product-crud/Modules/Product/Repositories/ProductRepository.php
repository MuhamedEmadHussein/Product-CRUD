<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Storage;
class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function all()
    {
        return $this->product->all();
    }

    public function find($id)
    {
        return $this->product->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = $this->find($id);
        $this->deleteImage($product);
        $product->delete();
    }

    private function deleteImage(Product $product)
    {
        if ($product->image) {
            Storage::delete($product->image);
        }
    }
}