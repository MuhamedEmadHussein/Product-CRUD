<?php

namespace Modules\Product\App\Livewire;

use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Modules\Product\Services\ProductApiService;

class ProductList extends Component
{
    public $products = [];
    public $name, $description, $price, $stock, $image, $is_active = true;
    public $selectedProductId = null;
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'required|string',
        'is_active' => 'boolean',
    ];

    protected $productApiService;

    public function boot(ProductApiService $productApiService)
    {
        $this->productApiService = $productApiService;
    }

    public function mount()
    {
        $this->fetchProducts();
    }

    public function fetchProducts()
    {
        try {
            $this->products = $this->productApiService->getAll();
            $this->dispatch('success', 'Products loaded successfully');
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            $this->dispatch('error', 'Failed to fetch products');
        }
    }

    public function createProduct()
    {
        $this->validate();

        try {
            $this->productApiService->create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $this->image,
                'is_active' => $this->is_active,
            ]);
            $this->fetchProducts();
            $this->resetInputFields();
            $this->dispatch('success', 'Product created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            $this->dispatch('error', 'Failed to create product');
        }
    }

    public function editProduct($id)
    {
        try {
            $product = $this->productApiService->get($id);
            $this->selectedProductId = $product['id'];
            $this->name = $product['name'];
            $this->description = $product['description'];
            $this->price = $product['price'];
            $this->stock = $product['stock'];
            $this->image = $product['image'];
            $this->is_active = $product['is_active'];
            $this->isEditing = true;
        } catch (\Exception $e) {
            Log::error('Error fetching product for edit: ' . $e->getMessage());
            $this->dispatch('error', 'Failed to load product');
        }
    }

    public function updateProduct()
    {
        $this->validate();

        try {
            $this->productApiService->update($this->selectedProductId, [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $this->image,
                'is_active' => $this->is_active,
            ]);
            $this->fetchProducts();
            $this->resetInputFields();
            $this->isEditing = false;
            $this->dispatch('success', 'Product updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            $this->dispatch('error', 'Failed to update product');
        }
    }

    public function deleteProduct($id)
    {
        try {
            $this->productApiService->delete($id);
            $this->fetchProducts();
            $this->dispatch('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            $this->dispatch('error', 'Failed to delete product');
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock = '';
        $this->image = '';
        $this->is_active = true;
        $this->selectedProductId = null;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('product::livewire.product-list');
    }
}
