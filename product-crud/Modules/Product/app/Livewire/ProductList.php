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
    
    // Add messages for validation errors
    protected $messages = [
        'name.required' => 'The product name is required.',
        'description.required' => 'The product description is required.',
        'price.required' => 'The product price is required.',
        'price.numeric' => 'The product price must be a number.',
        'stock.required' => 'The product stock is required.',
        'stock.integer' => 'The product stock must be an integer.',
        'image.required' => 'The product image URL is required.',
    ];

    protected $productApiService;

    public function boot(ProductApiService $productApiService)
    {
        $this->productApiService = $productApiService;
        Log::info('ProductList component booted with namespace: ' . __NAMESPACE__);
        Log::info('ProductList class: ' . get_class($this));
        Log::info('ProductApiService class: ' . get_class($productApiService));
    }
    
    public function hydrate()
    {
        Log::info('ProductList component hydrated');
    }
    
    public function dehydrate()
    {
        Log::info('ProductList component dehydrated');
    }

    public function mount()
    {
        Log::info('ProductList component mounted');
        $this->fetchProducts();
    }

    public function fetchProducts()
    {
        try {
            Log::info('Fetching products from repository');
            $this->products = $this->productApiService->getAll();
            Log::info('Products fetched: ' . count($this->products));
            Log::info('Products data: ' . json_encode($this->products));
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            $this->dispatch('error', ['text' => 'Failed to fetch products: ' . $e->getMessage()]);
        }
    }

    public function createProduct()
    {
        Log::info('createProduct method called with request data: ' . json_encode(request()->all()));
        Log::info('Product data: ' . json_encode([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'image' => $this->image,
            'is_active' => $this->is_active,
        ]));
        
        try {
            // Validate the input fields
            Log::info('Validating input fields');
            $validatedData = $this->validate();
            Log::info('Validation passed: ' . json_encode($validatedData));
            
            // Create the product using direct repository call
            Log::info('Calling repository directly');
            
            // Get the repository from the service
            $repository = app(\Modules\Product\Repositories\ProductRepository::class);
            
            // Create the product
            $product = $repository->create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $this->image,
                'is_active' => $this->is_active,
            ]);
            
            Log::info('Product created successfully via repository: ' . json_encode($product));
            $this->fetchProducts();
            $this->resetInputFields();
            $this->dispatch('success', ['text' => 'Product created successfully']);
            Log::info('Success event dispatched');
            
            return $product;
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            $this->dispatch('error', ['text' => 'Failed to create product: ' . $e->getMessage()]);
            Log::error('Error event dispatched');
            
            return null;
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
        
        try {
            // Validate the input fields
            Log::info('Validating input fields for update');
            $validatedData = $this->validate();
            Log::info('Validation passed for update: ' . json_encode($validatedData));
            
            Log::info('Updating product with data: ' . json_encode([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'stock' => $this->stock,
                'image' => $this->image,
                'is_active' => $this->is_active,
            ]));
            
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
        $this->reset(['name', 'description', 'price', 'stock', 'image']);
        $this->is_active = true;
        $this->selectedProductId = null;
        $this->isEditing = false;
        Log::info('Input fields reset completed');
    }

    public function render()
    {
        Log::info('Rendering ProductList component');
        return view('product::livewire.product-list');
    }
}
