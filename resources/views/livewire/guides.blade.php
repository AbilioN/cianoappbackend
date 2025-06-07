<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Search and Filter Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-stretch sm:items-center">
            <!-- Search Input -->
            <div class="w-full sm:w-1/3">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="Search guides..." 
                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                >
            </div>

            <!-- Category Filter -->
            <div class="w-full sm:w-1/3">
                <select 
                    wire:model.live="selectedCategory" 
                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                >
                    <option value="">All Categories</option>
                    @foreach($filteredGuides->pluck('category')->unique() as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Filters Button -->
            <button 
                wire:click="clearFilters"
                class="w-full sm:w-auto px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm sm:text-base"
            >
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Guides Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @forelse($filteredGuides as $guide)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow flex flex-col h-full">
                <!-- Guide Info -->
                <div class="p-3 sm:p-4 flex flex-col flex-grow">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ $guide->name }}</h3>

                    <!-- Category Badge -->
                    <div class="mb-3">
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">
                            {{ $guide->category }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-auto flex gap-2">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.guides.show', $guide->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg">
                                View
                            </a>
                            <a href="{{ route('admin.guides.edit', $guide->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-lg">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-8 sm:py-12">
                <p class="text-gray-500 text-base sm:text-lg">No guides found</p>
            </div>
        @endforelse
    </div>
</div> 