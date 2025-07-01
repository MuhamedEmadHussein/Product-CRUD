<div x-data="{ showModal: false, showDeleteConfirm: false, deleteId: null, toast: { show: false, message: '', type: '' }, isLoading: false }" 
     x-init="$wire.on('success', (message) => { 
                toast.show = true; 
                toast.message = message; 
                toast.type = 'success'; 
                setTimeout(() => toast.show = false, 3000); 
             });
             $wire.on('error', (message) => { 
                toast.show = true; 
                toast.message = message; 
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
    
    <!-- Create Button -->
    <div class="mb-6 text-right">
        <button @click="showModal = true" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Add Product</button>
    </div>

    <!-- Modal for Create/Edit -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">{{ $isEditing ? 'Edit Product' : 'Add Product' }}</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input wire:model="name" type="text" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Product Name">
                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input wire:model="price" type="number" step="0.01" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Price">
                    @error('price') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input wire:model="stock" type="number" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Stock">
                    @error('stock') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Description" rows="4"></textarea>
                    @error('description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input wire:model="image" type="text" class="block w-full border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500" placeholder="Image URL">
                    @error('image') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Active</label>
                    <input wire:model="is_active" type="checkbox" class="h-5 w-5 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
                    @error('is_active') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex space-x-4">
                    <button wire:loading.attr="disabled" wire:click="{{ $isEditing ? 'updateProduct' : 'createProduct' }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition disabled:bg-blue-300">
                        <span x-show="!isLoading">{{ $isEditing ? 'Update Product' : 'Add Product' }}</span>
                        <span x-show="isLoading">Processing...</span>
                    </button>
                    <button @click="showModal = false" wire:click="resetInputFields" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteConfirm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Confirm Delete</h2>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this product?</p>
            <div class="flex space-x-4">
                <button wire:loading.attr="disabled" wire:click="deleteProduct(deleteId)" @click="showDeleteConfirm = false" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition disabled:bg-red-300">
                    <span x-show="!isLoading">Delete</span>
                    <span x-show="isLoading">Processing...</span>
                </button>
                <button @click="showDeleteConfirm = false" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Product List -->
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
                    <button wire:click="editProduct({{ $product['id'] }})" @click="showModal = true" 
                            class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition">Edit</button>
                    <button @click="showDeleteConfirm = true; deleteId = {{ $product['id'] }}" 
                            class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-full p-8 bg-white rounded-lg shadow-md text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-600 text-center">No products found.</p>
                <button @click="showModal = true" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    Add Your First Product
                </button>
            </div>
        @endforelse
    </div>
</div> 
