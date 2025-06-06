<div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $detail['type'])) }}</span>
        </div>
        <button wire:click="$dispatch('detail-removed', { index: {{ $index }} })" class="text-red-500 hover:text-red-700">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>
    </div>

    @switch($detail['type'])
        @case('text')
        @case('large_text')
        @case('medium_text')
        @case('small_text')
            <div class="flex gap-2">
                <input type="text" wire:model="value" class="flex-1 form-input rounded-md shadow-sm" placeholder="Enter text">
                <button wire:click="saveDetail" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @break

        @case('list')
        @case('ordered_list')
            <div class="space-y-2">
                <div class="flex gap-2">
                    <input type="text" wire:model="newItem" class="flex-1 form-input rounded-md shadow-sm" placeholder="Add new item">
                    <button wire:click="addListItem" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($items as $index => $item)
                        <div class="flex gap-2">
                            <input type="text" wire:model="items.{{ $index }}" class="flex-1 form-input rounded-md shadow-sm">
                            <button wire:click="removeListItem({{ $index }})" class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end">
                    <button wire:click="saveDetail" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            @break

        @case('title')
        @case('title_left')
            <div class="flex gap-2">
                <input type="text" wire:model="text" class="flex-1 form-input rounded-md shadow-sm" placeholder="Enter title">
                <button wire:click="saveDetail" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            @break

        @default
            <div class="text-gray-500 italic">Unsupported detail type</div>
    @endswitch
</div> 