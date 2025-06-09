<div class="container mx-auto px-4 py-8">
    <!-- Feedback Message -->
    @if($showFeedback)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="fixed top-4 right-4 z-50"
             x-init="setTimeout(() => { show = false; $wire.hideFeedback() }, 3000)">
            <div class="rounded-lg shadow-lg px-4 py-3 {{ $feedbackType === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $feedbackMessage }}
            </div>
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Product</h1>
        <div class="flex space-x-4">
            <a href="{{ route('admin.products') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                Back to Products
            </a>
            <button wire:click="toggleEditing" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                {{ $editing ? 'Cancel Editing' : 'Edit Product' }}
            </button>
            @if($editing)
                <button wire:click="$dispatch('save-all-details')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    Save Changes
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Product Content</h2>
            <div class="flex space-x-2">
                @foreach($languages as $lang)
                    <button wire:click="updateSelectedLanguage('{{ $lang }}')" 
                            class="px-3 py-1 rounded-lg {{ $selectedLanguage === $lang ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                        {{ strtoupper($lang) }}
                    </button>
                @endforeach
            </div>
        </div>

        @if($editing)
            <div class="space-y-4">
                @foreach($details as $index => $detail)
                    @if(in_array($detail['type'], ['text', 'large_text', 'medium_text', 'small_text', 'list', 'ordered_list', 'title', 'title_left']))
                        <livewire:detail-input 
                            :key="'detail-'.$index" 
                            :index="$index" 
                            :detail="$detail"
                            wire:key="detail-{{ $index }}"
                        />
                    @endif
                @endforeach
            </div>
        @else
            <livewire:page-builder :details="$details" :editing="$editing" />
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('hide-feedback-after-delay', () => {
            setTimeout(() => {
                @this.hideFeedback();
            }, 3000);
        });
    });
</script>
@endpush
