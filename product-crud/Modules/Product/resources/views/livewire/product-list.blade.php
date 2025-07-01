<div x-data="{ 
    toast: { 
        show: false, 
        message: '', 
        type: '' 
    }
}" 
     x-init="$wire.on('success', (message) => { 
                toast.show = true; 
                toast.message = message.text; 
                toast.type = 'success'; 
                setTimeout(() => toast.show = false, 3000); 
             });
             $wire.on('error', (message) => { 
                toast.show = true; 
                toast.message = message.text; 
                toast.type = 'error'; 
                setTimeout(() => toast.show = false, 3000); 
             });"
     class="container mx-auto p-6 bg-gray-100 min-h-screen">
    
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         :class="toast.type === 'success' ? 'bg-green-500' : 'bg-red-500'"
         class="fixed top-4 right-4 p-4 text-white rounded-lg shadow-lg transition-opacity duration-300 z-50">
        <span x-text="toast.message"></span>
    </div>

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Product Management</h1>
    
    <!-- Product Form -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4">{{ $isEditing ? 'Edit Product' : 'Add New Product' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input wire:model.defer="name" type="text" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Product Name">
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                <input wire:model.defer="price" type="number" step="0.01" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Price">
                @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                <input wire:model.defer="stock" type="number" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Stock">
                @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                <input wire:model.defer="image" type="text" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Image URL">
                @error('image') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea wire:model.defer="description" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Description" rows="4"></textarea>
                @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Active</label>
                <input wire:model.defer="is_active" type="checkbox" class="h-5 w-5 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
                @error('is_active') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="mt-6 flex space-x-4">
            @if($isEditing)
                <button type="button" wire:click.prevent="updateProduct" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">
                    <span wire:loading.remove wire:target="updateProduct">Update Product</span>
                    <span wire:loading wire:target="updateProduct">Processing...</span>
                </button>
                <button type="button" wire:click.prevent="resetInputFields" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
            @else
                <button type="button" wire:click.prevent="createProduct" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    <span wire:loading.remove wire:target="createProduct">Add Product</span>
                    <span wire:loading wire:target="createProduct">Processing...</span>
                </button>
                <button type="button" wire:click.prevent="resetInputFields" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Reset</button>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation -->
    @if($showDeleteConfirmation)
    <div class="bg-red-50 border border-red-200 p-4 rounded-lg shadow-md mb-8">
        <h3 class="text-lg font-semibold text-red-700 mb-2">Confirm Delete</h3>
        <p class="text-gray-700 mb-4">Are you sure you want to delete this product? This action cannot be undone.</p>
        <div class="flex space-x-4">
            <button type="button" wire:click="deleteProduct" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition">
                <span wire:loading.remove wire:target="deleteProduct">Delete</span>
                <span wire:loading wire:target="deleteProduct">Processing...</span>
            </button>
            <button type="button" wire:click="cancelDelete" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
        </div>
    </div>
    @endif

    <!-- Product List -->
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Products List</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
            <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition">
                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" 
                     class="w-full h-48 object-cover rounded-md mb-4"
                     onerror="this.onerror=null;this.src='https://via.placeholder.com/300x200?text=No+Image';">
                <h3 class="text-lg font-semibold text-gray-800">{{ $product['name'] }}</h3>
                <p class="text-gray-600 line-clamp-3">{{ $product['description'] }}</p>
                <p class="text-sm text-gray-500">Price: ${{ number_format($product['price'], 2) }}</p>
                <p class="text-sm text-gray-500">Stock: {{ $product['stock'] }}</p>
                <p class="text-sm text-gray-500">Status: 
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $product['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product['is_active'] ? 'Active' : 'Inactive' }}
                    </span>
                </p>
                <div class="mt-4 flex space-x-2">
                    <button type="button" wire:click="editProduct({{ $product['id'] }})" 
                            class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition">Edit</button>
                    <button type="button" wire:click="confirmDelete({{ $product['id'] }})" 
                            class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-full p-8 bg-white rounded-lg shadow-md text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-600 text-center">No products found.</p>
            </div>
        @endforelse
    </div>
</div> 
