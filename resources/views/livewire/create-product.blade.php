<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-900">Create New Product</h2>
            <a href="{{ route('admin.products') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Back to Products
            </a>
        </div>

        <!-- Language Selector -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Language</label>
            <div class="flex gap-2">
                @foreach($languages as $lang)
                    <button 
                        wire:click="updateSelectedLanguage('{{ $lang }}')"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $selectedLanguage === $lang ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    >
                        {{ strtoupper($lang) }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Preview (PageBuilder) -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Preview</h3>
                <livewire:page-builder :details="$details" :key="'page-builder'" />
            </div>
        </div>

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input type="text" wire:model="product.name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('product.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select wire:model="product.product_category_id" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a category</option>
                        @foreach(\App\Models\ProductCategory::with('translations')->get() as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->translations->first()?->name ?? $category->slug }}
                            </option>
                        @endforeach
                    </select>
                    @error('product.product_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Image URL -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input type="text" wire:model="product.image" id="image" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('product.image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Product Details</h3>
                <button wire:click="addDetail" type="button" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                    Add Detail
                </button>
            </div>

            <div class="space-y-4">
                @foreach($details as $index => $detail)
                    <div class="flex gap-4 items-start">
                        <div class="flex-1">
                            <select wire:model="details.{{ $index }}.type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select type</option>
                                <option value="text">Text</option>
                                <option value="medium_text">Medium Text</option>
                                <option value="small_text">Small Text</option>
                                <option value="image">Image</option>
                                <option value="large_image">Large Image</option>
                                <option value="medium_image">Medium Image</option>
                                <option value="small_image">Small Image</option>
                                <option value="divider">Divider</option>
                                <option value="list">List</option>
                                <option value="ordered_list">Ordered List</option>
                                <option value="title">Title</option>
                                <option value="title_left">Title Left</option>
                                <option value="description">Description</option>
                                <option value="youtube">YouTube</option>
                                <option value="notification_button">Notification Button</option>
                                <option value="yes_or_no">Yes or No</option>
                                <option value="link_button">Link Button</option>
                            </select>
                            @error("details.{$index}.type") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex-1">
                            <input type="text" wire:model="details.{{ $index }}.value" placeholder="Value" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error("details.{$index}.value") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <button wire:click="removeDetail({{ $index }})" type="button" class="p-2 text-red-600 hover:text-red-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Save Button -->
        <div class="mt-8 flex justify-end">
            <button wire:click="save" type="button" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Create Product
            </button>
        </div>
    </div>
</div>
