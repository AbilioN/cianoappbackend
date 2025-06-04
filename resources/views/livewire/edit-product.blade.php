<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-900">Edit Product: {{ $product->name }}</h2>
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
                        wire:click="$set('selectedLanguage', '{{ $lang }}')"
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
                <livewire:page-builder :details="$details" />
                <div class="flex justify-end mt-4">
                    <button 
                        wire:click="$set('editing', true)"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Editar
                    </button>
                </div>
            </div>
        </div>

        @if($editing ?? false)
        <div class="bg-white rounded-lg shadow-sm">
            <form wire:submit="save" class="space-y-6 p-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            wire:model="product.name" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('product.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select 
                            id="category" 
                            wire:model="product.product_category_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            @foreach($product->category->translations as $translation)
                                <option value="{{ $product->category->id }}">
                                    {{ $translation->name }} ({{ strtoupper($translation->language) }})
                                </option>
                            @endforeach
                        </select>
                        @error('product.product_category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Image URL -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                        <input 
                            type="text" 
                            id="image" 
                            wire:model="product.image" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                        @error('product.image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Product Details -->
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Product Details</h3>
                        <button 
                            type="button" 
                            wire:click="addDetail" 
                            class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium"
                        >
                            Add Detail
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($details as $index => $detail)
                            <div class="flex gap-4 items-start p-4 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                    <input 
                                        type="text" 
                                        wire:model="details.{{ $index }}.type" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                    <input 
                                        type="text" 
                                        wire:model="details.{{ $index }}.value" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                </div>
                                <div class="flex-none pt-6">
                                    <button 
                                        type="button" 
                                        wire:click="removeDetail({{ $index }})" 
                                        class="text-red-600 hover:text-red-800"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
