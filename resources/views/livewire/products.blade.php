<div class="container mx-auto px-4 py-8">
    <!-- Search and Filter Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <!-- Search Input -->
            <div class="w-full md:w-1/3">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Search products..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <!-- Category Filter -->
            <div class="w-full md:w-1/3">
                <select 
                    wire:model.live="selectedCategory" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->translations->first()?->name ?? $category->slug }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Filters Button -->
            <button 
                wire:click="clearFilters"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($filteredProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <!-- Product Image -->
                <div class="relative h-48 w-full">
                    @if($product->image)
                        <img 
                            src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">No image</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>

                    <!-- Category Badge -->
                    <div class="mb-3">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">
                            {{ $product->category->translations->first()?->name ?? $product->category->slug }}
                        </span>
                    </div>

                    <!-- Edit Button -->
                    <a 
                        href="{{ route('admin.products.edit', $product->id) }}" 
                        class="block w-full text-center px-3 py-2 text-sm bg-blue-100 text-blue-600 rounded hover:bg-blue-200 transition-colors font-semibold"
                    >
                        Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No products found</p>
            </div>
        @endforelse
    </div>
</div>
