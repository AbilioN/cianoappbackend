<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-900">Product: {{ $product->name }}</h2>
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
                        wire:click="changeLanguage('{{ $lang }}')"
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
                <div class="flex justify-end mt-4">
                    <button 
                        wire:click="$set('editing', true)"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Edit
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

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Image</label>
                        <div class="mt-1 flex items-center gap-4">
                            <div class="flex-1">
                                <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @if($tempImageUrl)
                                <div class="relative w-32 h-32">
                                    <img src="{{ $tempImageUrl }}" alt="Preview" class="w-full h-full object-cover rounded-lg">
                                    <button wire:click="removeImage" type="button" class="absolute -top-2 -right-2 p-1 bg-red-100 text-red-600 rounded-full hover:bg-red-200">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @elseif($product->image)
                                <div class="relative w-32 h-32">
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                    <button wire:click="removeImage" type="button" class="absolute -top-2 -right-2 p-1 bg-red-100 text-red-600 rounded-full hover:bg-red-200">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>

                    <!-- Image URL (fallback) -->
                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700">Or use Image URL</label>
                        <input type="text" wire:model="product.image" id="image_url" placeholder="https://..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('product.image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                            <livewire:detail-input 
                                :key="'detail-'.$index" 
                                :index="$index" 
                                :detail="$detail"
                                wire:key="detail-{{ $index }}"
                            />
                        @endforeach
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end gap-4">
                    <button 
                        wire:click="saveAsDraft" 
                        type="button" 
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium"
                    >
                        Save as Draft
                    </button>
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
