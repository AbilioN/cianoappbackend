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
                    <label class="block text-sm font-medium text-gray-700">Product Name</label>
                    <div class="mt-1 text-gray-900">{{ $product->name }}</div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category</label>
                    <div class="mt-1 text-gray-900">
                        @foreach($product->category->translations as $translation)
                            {{ $translation->name }} ({{ strtoupper($translation->language) }})
                            @if(!$loop->last), @endif
                        @endforeach
                    </div>
                </div>

                <!-- Image URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Image URL</label>
                    <div class="mt-1">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg">
                        @else
                            <span class="text-gray-500">No image available</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
