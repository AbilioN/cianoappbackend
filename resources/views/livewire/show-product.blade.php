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
    </div>
</div>
