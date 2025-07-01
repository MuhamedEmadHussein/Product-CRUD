<?php

namespace Modules\Product\App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Modules\Product\Services\ProductApiService;

class ProductList extends Component
{
    public $products = [];
    public $name = '';
    public $description = '';
    public $price = '';
    public $stock = '';
    public $image = '';
    public $is_active = true;
    public $selectedProductId = null;
    public $isEditing = false;
    public $showDeleteConfirmation = false;
    public $deleteProductId = null;

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
            Log::info('Fetching products from repository');
            $this->products = $this->productApiService->getAll();
            Log::info('Products fetched: ' . count($this->products));
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            $this->dispatch('error', ['text' => 'Failed to fetch products']);
        }
    }

    public function createProduct()
    {
        Log::info('Creating product: ' . $this->name);
        
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
            
            Log::info('Product created successfully');
            $this->fetchProducts();
            $this->resetInputFields();
            $this->dispatch('success', ['text' => 'Product created successfully']);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            $this->dispatch('error', ['text' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    public function editProduct($id)
    {
        Log::info('Editing product with ID: ' . $id);
        
        try {
            $product = $this->productApiService->get($id);
            Log::info('Product data fetched for editing: ' . json_encode($product));
            
            $this->selectedProductId = $product['id'];
            $this->name = $product['name'];
            $this->description = $product['description'];
            $this->price = $product['price'];
            $this->stock = $product['stock'];
            $this->image = $product['image'];
            $this->is_active = $product['is_active'];
            $this->isEditing = true;
            
            Log::info('Product loaded for editing: ' . $this->name);
        } catch (\Exception $e) {
            Log::error('Error fetching product for edit: ' . $e->getMessage());
            $this->dispatch('error', ['text' => 'Failed to load product: ' . $e->getMessage()]);
        }
    }

    public function updateProduct()
    {
        Log::info('Updating product ID: ' . $this->selectedProductId);
        
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
            
            Log::info('Product updated successfully');
            $this->fetchProducts();
            $this->resetInputFields();
            $this->dispatch('success', ['text' => 'Product updated successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            $this->dispatch('error', ['text' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        Log::info('Confirming delete for product ID: ' . $id);
        $this->deleteProductId = $id;
        $this->showDeleteConfirmation = true;
    }

    public function cancelDelete()
    {
        Log::info('Delete cancelled');
        $this->deleteProductId = null;
        $this->showDeleteConfirmation = false;
    }

    public function deleteProduct()
    {
        Log::info('Deleting product ID: ' . $this->deleteProductId);
        
        try {
            $this->productApiService->delete($this->deleteProductId);
            Log::info('Product deleted successfully');
            
            $this->fetchProducts();
            $this->showDeleteConfirmation = false;
            $this->deleteProductId = null;
            $this->dispatch('success', ['text' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            $this->dispatch('error', ['text' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }

    public function resetInputFields()
    {
        Log::info('Resetting input fields');
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
