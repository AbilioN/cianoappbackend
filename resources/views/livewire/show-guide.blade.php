<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-900">Guide: {{ $guide->name }}</h2>
            <a href="{{ route('admin.guides') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Back to Guides
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
                @foreach($guide->pages->groupBy('order') as $pageOrder => $pages)
                    @php
                        $page = $pages->first();
                        $pageDetails = $page->components->map(function($component) {
                            $content = is_string($component->content) ? json_decode($component->content, true) : $component->content;
                            return [
                                'type' => $component->type,
                                ...$content
                            ];
                        })->toArray();
                    @endphp
                    
                    <div class="mb-8">
                        <div class="flex items-center mb-4">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">Page {{ $pageOrder + 1 }}</h4>
                            </div>
                        </div>
                        <livewire:page-builder :details="$pageDetails" :key="'page-builder-'.$pageOrder" />
                    </div>
                    
                    @if(!$loop->last)
                        <div class="my-8 border-t border-gray-200"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div> 